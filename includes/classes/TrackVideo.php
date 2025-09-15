<?php
namespace Fopsco\Classes;
use Fopsco\Traits\Singleton;

class TrackVideo {
    use Singleton;

    private $pmes_url; 
    private $pmes_username; 
    private $pmes_password; 

    protected function __construct() {
        $this->pmes_url      = defined( 'DAREWP_PMES_URL' )  ? DAREWP_PMES_URL  : ''; 
        $this->pmes_username = defined( 'DAREWP_PMES_USER' ) ? DAREWP_PMES_USER : ''; 
        $this->pmes_password = defined( 'DAREWP_PMES_PASS' ) ? DAREWP_PMES_PASS : ''; 

        add_action( 'wp_ajax_video_played',    [ $this, 'handle_video_played' ] );
        add_action( 'wp_ajax_video_paused',    [ $this, 'handle_video_paused' ] );
        add_action( 'wp_ajax_video_skipped',   [ $this, 'handle_video_skipped' ] );
        add_action( 'wp_ajax_video_completed', [ $this, 'handle_video_completed' ] );

        add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_scripts' ] ); 

        //USER PROFILE
        add_action('show_user_profile', [$this, 'display_video_progress']);
        add_action('edit_user_profile', [$this, 'display_video_progress']);

        add_action('wp_ajax_pmes_reset_progress', [$this, 'ajax_reset_progress']);

        error_log("PMES Log Fallback initialized");
    }

    public function enqueue_scripts() {
        if ( is_user_logged_in() && is_page( 'pre-membership-education-seminar' ) ) {
            wp_enqueue_script(
                'video-tracker',
                get_template_directory_uri() . '/assets/js/pmes-tracker.js',
                [],
                filemtime( get_template_directory() . '/assets/js/pmes-tracker.js' ),
                true
            );

            wp_localize_script(
                'video-tracker',
                'VideoTracker',
                [
                    'ajaxurl' => admin_url( 'admin-ajax.php' ),
                    'nonce'   => wp_create_nonce( 'video_tracker_nonce' ),
                    'user_id' => get_current_user_id(),
                ]
            );
        }
    }  

    public function handle_video_played() {
        $this->verify_nonce();
        $this->increment_counter( 'played' );
    }

    public function handle_video_paused() {
        $this->verify_nonce();
        $this->increment_counter( 'paused' );
    }

    public function handle_video_skipped() {
        $this->verify_nonce();       
        $this->increment_counter( 'skipped' );
    }

    public function handle_video_completed() {
        $this->verify_nonce();
        error_log('PMES: completed');        

        $user_id = get_current_user_id();
        $progress = get_user_meta( $user_id, 'pmes_video_progress', true );

        // Only trigger webhook once
        if ( empty( $progress['completed'] ) || intval( $progress['completed'] ) === 0 ) {
            $this->trigger_n8n_workflow();
        } else {
            error_log("PMES: User {$user_id} already completed, skipping webhook.");
        }

        $this->increment_counter( 'completed' );
    }

    private function increment_counter( $type ) {
        $user_id = get_current_user_id();
        if ( ! $user_id ) {
            wp_send_json_error( [ 'message' => 'Invalid request' ] );
        }

        $data = get_user_meta( $user_id, 'pmes_video_progress', true );

        if ( ! is_array( $data ) ) {
            $data = [
                'played'    => 0,
                'paused'    => 0,
                'skipped'   => 0,
                'completed' => 0,
            ];
        }

        if ( isset( $data[ $type ] ) ) {
            $data[ $type ]++;
        }

        $data['updated'] = current_time( 'mysql' );

        update_user_meta( $user_id, 'pmes_video_progress', $data );

        wp_send_json_success( [
            'message' => "Video {$type} count updated",
            'data'    => $data,
        ] );
    }
    
    private function trigger_n8n_workflow() {
        $user_id = get_current_user_id();
        $user    = get_userdata( $user_id );

        if ( ! $user || empty( $this->pmes_url ) ) {
            return;
        }

        error_log('PMES Trigger: ' . $user->user_email);

        $phone_number = get_user_meta( $user_id, 'phone_number', true );

        $body = [
            'user_id'      => $user_id,
            'user_email'   => $user->user_email,
            'first_name'   => $user->first_name,
            'last_name'    => $user->last_name,
            'phone_number' => $phone_number ? $phone_number : '',
            'status'       => 'completed',
            'timestamp'    => current_time( 'mysql' ),
        ];

        $response = wp_remote_post( $this->pmes_url, [
            'method'  => 'POST',
            'headers' => [
                'Authorization' => 'Basic ' . base64_encode( $this->pmes_username . ':' . $this->pmes_password ),
                'Content-Type'  => 'application/json',
            ],
            'body'    => wp_json_encode( $body ),
            'timeout' => 15,
        ] );

        if ( is_wp_error( $response ) ) {
            error_log( 'PMES n8n webhook failed: ' . $response->get_error_message() );
        }
    }

    private function verify_nonce() {
        if (
            ! isset( $_POST['_ajax_nonce'] )
            || ! wp_verify_nonce( $_POST['_ajax_nonce'], 'video_tracker_nonce' )
        ) {
            wp_send_json_error( [ 'message' => 'Invalid nonce' ] );
        }
    }

    public function display_video_progress($user): void {
        $user_id = $user->ID;
        $progress = get_user_meta($user_id, 'pmes_video_progress', true);
        $progress = is_array($progress) ? $progress : [
            'played' => 0,
            'paused' => 0,
            'skipped' => 0,
            'completed' => 0,
        ];

        $percent = !empty($progress['completed']) ? 100 : 0;
        ?>
        <h2>PMES Video Progress</h2>
        <table class="form-table" id="pmes-progress-table-<?php echo esc_attr($user_id); ?>">
            <tr>
                <th>Progress</th>
                <td><?php echo esc_html($percent); ?>%</td>
            </tr>
            <tr>
                <th>Times Played</th>
                <td><?php echo esc_html($progress['played']); ?></td>
            </tr>
            <tr>
                <th>Times Paused</th>
                <td><?php echo esc_html($progress['paused']); ?></td>
            </tr>
            <tr>
                <th>Times Skipped</th>
                <td><?php echo esc_html($progress['skipped']); ?></td>
            </tr>
            <tr>
                <th>Status</th>
                <td><?php echo !empty($progress['completed']) ? 'Completed ✅' : 'Not Completed ❌'; ?></td>
            </tr>
            <tr>
                <th>Reset Progress</th>
                <td>
                    <button 
                        class="button button-secondary pmes-reset-btn" 
                        data-user-id="<?php echo esc_attr($user_id); ?>"
                    >
                        Reset Progress
                    </button>
                    <span class="pmes-reset-msg"></span>
                </td>
            </tr>
        </table>
        <?php
    }

    public function ajax_reset_progress(): void {
        check_ajax_referer('pmes_reset_nonce');

        $user_id = isset($_POST['user_id']) ? intval($_POST['user_id']) : 0;
        if (!$user_id) {
            wp_send_json_error(['message' => 'Invalid user ID']);
        }

        delete_user_meta($user_id, 'pmes_video_progress');
        wp_send_json_success(['message' => 'Progress reset']);
    }    
}
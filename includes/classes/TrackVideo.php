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
        error_log("PMES Log Fallback: ");
    }

    private function log($message) {
        $upload_dir = wp_get_upload_dir(); // ensures root /uploads
        $log_file   = trailingslashit($upload_dir['basedir']) . 'flog.txt';

        if (is_array($message) || is_object($message)) {
            $message = print_r($message, true);
        }

        $entry = '[' . date('Y-m-d H:i:s') . '] ' . $message . PHP_EOL;

        if (@file_put_contents($log_file, $entry, FILE_APPEND | LOCK_EX) === false) {
            error_log("PMES Log Fallback: " . $message);
        }
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
        $this->trigger_n8n_workflow();
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

        $this->log('PMES: '. $user->user_email);

        if ( ! $user || empty( $this->pmes_url ) ) {
            return;
        }

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
            $this->log( 'PMES n8n webhook failed: ' . $response->get_error_message() );
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
}
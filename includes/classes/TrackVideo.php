<?php
namespace Fopsco\Classes;
use Fopsco\Traits\Singleton;

class VideoTracker {
    use Singleton;

    protected function __construct() {
        add_action( 'wp_ajax_video_played',    [ $this, 'handle_video_played' ] );
        add_action( 'wp_ajax_video_paused',    [ $this, 'handle_video_paused' ] );
        add_action( 'wp_ajax_video_skipped',   [ $this, 'handle_video_skipped' ] );
        add_action( 'wp_ajax_video_completed', [ $this, 'handle_video_completed' ] );

        add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_scripts' ] );
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

    private function verify_nonce() {
        if (
            ! isset( $_POST['_ajax_nonce'] )
            || ! wp_verify_nonce( $_POST['_ajax_nonce'], 'video_tracker_nonce' )
        ) {
            wp_send_json_error( [ 'message' => 'Invalid nonce' ] );
        }
    }
}
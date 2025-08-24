<?php
namespace Fopsco\Classes;
use Fopsco\Traits\Singleton;

class DareWPAuto {
    use Singleton;

    private $n8n_url;
    private $username;
    private $password;

    public function __construct() {
        $this->log('DareWPAuto class loaded');
        // DEFINED IN WPCONFIG
        $this->n8n_url  = defined( 'DAREWP_N8N_URL' ) ? DAREWP_N8N_URL : '';
        $this->username = defined( 'DAREWP_N8N_USER' ) ? DAREWP_N8N_USER : '';
        $this->password = defined( 'DAREWP_N8N_PASS' ) ? DAREWP_N8N_PASS : '';

        add_action( 'user_register', [ $this, 'send_registration_data' ], 10, 1 );
    }

    /**
     * Custom logger to force writing into wp-content/debug.log
     *
     * @param mixed $message
     */
    private function log( $message ) {
        $log_file = WP_CONTENT_DIR . '/debug.log';

        if ( is_array( $message ) || is_object( $message ) ) {
            $message = print_r( $message, true );
        }

        file_put_contents(
            $log_file,
            '[' . date( 'Y-m-d H:i:s' ) . '] ' . $message . PHP_EOL,
            FILE_APPEND
        );
    }

    /**
     * Fires when a new user is registered.
     *
     * @param int $user_id
     */
    public function send_registration_data( $user_id ) {
        if ( empty( $this->n8n_url ) || empty( $this->username ) || empty( $this->password ) ) {
            $this->log( 'DareWPAuto error: n8n credentials not set.' );
            return;
        }

        $user_info = get_userdata( $user_id );

        // PENDING USER ROLE ONLY
        if ( ! in_array( 'pending', (array) $user_info->roles, true ) ) {
            return;
        }

        // WP USER META
        $first_name = get_user_meta( $user_id, 'first_name', true );
        $last_name  = get_user_meta( $user_id, 'last_name', true );
        $email      = $user_info->user_email;

        // CUSTOM FIELDS
        $contact     = get_user_meta( $user_id, 'contact', true );
        $member_type = get_user_meta( $user_id, 'member_type', true );

        $body = [
            'first_name'  => $first_name,
            'last_name'   => $last_name,
            'email'       => $email,
            'contact'     => $contact,
            'member_type' => $member_type,
            'registered'  => current_time( 'mysql' ),
        ];

        // HEADER
        $auth = base64_encode( $this->username . ':' . $this->password );

        $args = [
            'method'  => 'POST',
            'headers' => [
                'Content-Type'  => 'application/json',
                'Authorization' => 'Basic ' . $auth,
            ],
            'body'    => wp_json_encode( $body ),
            'timeout' => 15,
        ];

        $response = wp_remote_post( $this->n8n_url, $args );

        $this->log($response);
        if ( is_wp_error( $response ) ) {
            $this->log( 'DareWPAuto n8n error: ' . $response->get_error_message() );
        } else {
            $status_code = wp_remote_retrieve_response_code( $response );
            $resp_body   = wp_remote_retrieve_body( $response );

            if ( $status_code >= 200 && $status_code < 300 ) {
                $this->log( 'DareWPAuto success: Data sent successfully. Response: ' . $resp_body );
            } else {
                $this->log( 'DareWPAuto n8n error: Unexpected status ' . $status_code . ' - ' . $resp_body );
            }
        }
    }
}

<?php
namespace Fopsco\Classes;
use Fopsco\Traits\Singleton;

class JoinMember {
    use Singleton;

    public function __construct() {
        add_action('rest_api_init', [$this, 'register_routes']);
    }

    public function register_routes() {
        register_rest_route('lodge/v1', '/join', [
            'methods'             => 'POST',
            'callback'            => [$this, 'join_fopsco'],
            'permission_callback' => [$this, 'verify_nonce_and_rate_limit'],
        ]);
    }

    public function verify_nonce_and_rate_limit($request) {
        // custom nonce header
        $nonce = $request->get_header('X-Lodge-Nonce');
        if (!$nonce || !wp_verify_nonce($nonce, 'lodge_join_form')) {
            return new \WP_Error('invalid_nonce', 'Security check failed', ['status' => 403]);
        }

        // simple honeypot
        if (!empty($request['website'])) {
            return new \WP_Error('spam_detected', 'Bots not allowed.', ['status' => 400]);
        }

        // rate limiting
        $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
        $transient_key = 'join_rate_' . md5($ip);
        $attempts = (int) get_transient($transient_key);

        if ($attempts >= 20) {
            return new \WP_Error('rate_limit', 'Too many attempts. Please try again later.', ['status' => 429]);
        }

        set_transient($transient_key, $attempts + 1, 5 * MINUTE_IN_SECONDS);
        return true;
    }

    public function join_fopsco($request) {
        $first_name  = sanitize_text_field(trim($request['first_name'] ?? ''));
        $last_name   = sanitize_text_field(trim($request['last_name'] ?? ''));
        $contact     = sanitize_text_field(trim($request['contact'] ?? ''));
        $member_type = strtolower(sanitize_text_field(trim($request['member_type'] ?? '')));
        $email       = sanitize_email(trim($request['email'] ?? ''));
        $password    = $request['password'] ?? '';

        $allowed_member_types = ['regular', 'associate'];
        if (!in_array($member_type, $allowed_member_types)) {
            return new \WP_Error('invalid_member_type', 'Invalid member type.', ['status' => 400]);
        }

        if (!is_email($email)) {
            return new \WP_Error('invalid_email', 'Invalid email format.', ['status' => 400]);
        }

        if (email_exists($email) || username_exists($email)) {
            return new \WP_Error('email_exists', 'Email already registered.', ['status' => 400]);
        }

        if (strlen($password) < 8) {
            return new \WP_Error('weak_password', 'Password must be at least 8 characters.', ['status' => 400]);
        }

        $username = sanitize_user($email, true);

        $user_id = wp_insert_user([
            'user_login' => $username,
            'user_pass'  => $password,
            'user_email' => $email,
            'first_name' => $first_name,
            'last_name'  => $last_name,
            'role'       => 'pending',
            'meta_input' => [
                'contact_number' => $contact,
                'member_type'    => $member_type,
            ],
        ]);

        if (is_wp_error($user_id)) {
            return $user_id;
        }

        return [
            'success' => true,
            'message' => 'You have now Joined FOPSCo!',
            'user_id' => $user_id,
        ];
    }
}

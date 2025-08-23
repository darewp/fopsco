<?php
namespace Fopsco\Classes;
use Fopsco\Traits\Singleton;

class JoinMember {
    use Singleton;

    public function __construct() {
        add_action('wp_enqueue_scripts', [$this, 'join_member']);
    }

    public function join_member() {
        register_rest_route('custom/v1', '/join', [
            'methods' => 'POST',
            'callback' => [$this, 'join_fopsco'],
            'permission_callback' => '__return_true',
        ]);
    }

    public function join_fopsco($request) {
        $first_name  = sanitize_text_field($request['first_name']);
        $last_name   = sanitize_text_field($request['last_name']);
        $contact     = sanitize_text_field($request['contact']);
        $member_type = sanitize_text_field($request['member_type']);
        $email       = sanitize_email($request['email']);
        $password    = $request['password'];


        $allowed_member_types = ['regular', 'associate'];
        if (!in_array(strtolower($member_type), $allowed_member_types)) {
            return new WP_Error('invalid_member_type', 'Invalid member type.', ['status' => 400]);
        }

        if (!is_email($email)) {
            return new WP_Error('invalid_email', 'Invalid email format.', ['status' => 400]);
        }

        if (email_exists($email) || username_exists($email)) {
            return new WP_Error('email_exists', 'Email already registered.', ['status' => 400]);
        }

        if (strlen($password) < 8) {
            return new WP_Error('weak_password', 'Password must be at least 8 characters.', ['status' => 400]);
        }


        $username = sanitize_user($email, true);

        $user_id = wp_create_user($username, $password, $email);
        if (is_wp_error($user_id)) {
            return $user_id;
        }

        wp_update_user([
            'ID'         => $user_id,
            'first_name' => $first_name,
            'last_name'  => $last_name,
        ]);

        update_user_meta($user_id, 'contact_number', $contact);
        update_user_meta($user_id, 'member_type', strtolower($member_type));

        return [
            'success' => true,
            'message' => 'You have now Joined FOPSCo!',
            'user_id' => $user_id,
        ];
    }
}

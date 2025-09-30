<?php
namespace Fopsco\Classes;
use Fopsco\Traits\Singleton;

class HooksManager {
    use Singleton;

    private $redirect_url = '/profile/';

    public function __construct() {
        // Register hooks here
        add_filter('login_redirect', [$this, 'handle_login_redirect'], 10, 3);
        add_action('admin_init', [$this, 'restrict_admin_access']);
    }

    /**
     * Redirect non-admins after login
     */
    public function handle_login_redirect($redirect_to, $request, $user) {
        if (isset($user->roles) && is_array($user->roles)) {
            if (!in_array('administrator', $user->roles, true)) {
                return home_url($this->redirect_url);
            }
        }
        return $redirect_to;
    }

    /**
     * Restrict non-admins from accessing /wp-admin/
     */
    public function restrict_admin_access() {
        if (is_admin() && !current_user_can('administrator') && !(defined('DOING_AJAX') && DOING_AJAX)) {
            wp_redirect(home_url($this->redirect_url));
            exit;
        }
    }
}

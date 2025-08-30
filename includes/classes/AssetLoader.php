<?php
namespace Fopsco\Classes;
use Fopsco\Traits\Singleton;

class AssetLoader {
    use Singleton;

    public function __construct() {
        add_action('wp_enqueue_scripts', [$this, 'register_assets']);
    }

    public function register_assets() {
        wp_enqueue_style(
            'fopsco-tailwind',
            get_template_directory_uri() . '/assets/css/output.css',
            [],
            filemtime(get_template_directory() . '/assets/css/output.css')
        );

        wp_enqueue_script(
            'fopsco',
            get_template_directory_uri() . '/assets/js/main.js',
            [],
            filemtime(get_template_directory() . '/assets/js/main.js'),
            true
        );

        wp_enqueue_script(
            'validator',
            '//cdn.jsdelivr.net/npm/validator@13.9.0/validator.min.js',
            [],
            '13.9.0',
            true
        );

        if( is_page('join') ){
            wp_enqueue_script(
                'fopsco-join',
                get_template_directory_uri() . '/assets/js/join.js',
                ['validator'],
                filemtime(get_template_directory() . '/assets/js/join.js'),
                true
            );
        }

        // custom nonce for public form
        wp_localize_script('fopsco-join', 'lodgeSettings', [
            'nonce' => wp_create_nonce('lodge_join_form'),
            'restUrl' => esc_url_raw(rest_url('lodge/v1/join'))
        ]);
    }
}

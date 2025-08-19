<?php
namespace Fopsco\Classes;
use Fopsco\Traits\Singleton;

class AssetLoader {
    use Singleton;

    public function __construct() {
        add_action('wp_enqueue_scripts', [$this, 'register_assets']);
    }

    public function register_assets() {
        wp_enqueue_style('fopsco', get_stylesheet_uri());
        wp_enqueue_script('fopsco', get_template_directory_uri() . '/assets/js/main.js', [], false, true);
    }
}

// Initialize
AssetLoader::get_instance();
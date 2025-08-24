<?php
/* 
Template file: Functions.php
This file contains the core functions and classes for the theme.
Testing deployment and functionality.
*/
require get_template_directory() . '/includes/traits/Singleton.php';
require get_template_directory() . '/includes/classes/AssetLoader.php';
require get_template_directory() . '/includes/classes/SVGSupport.php';
require get_template_directory() . '/includes/classes/JoinMember.php';
require get_template_directory() . '/includes/classes/DareWPAuto.php';

add_action('after_setup_theme', function() {
    // Initialize classes
    \Fopsco\Classes\AssetLoader::get_instance();
    \Fopsco\Classes\SVGSupport::get_instance();
    \Fopsco\Classes\JoinMember::get_instance();
    $darewp = \Fopsco\Classes\DareWPAuto::get_instance();

    // Debug: check if constants are available
    if ( defined('DAREWP_N8N_URL') ) {
        $darewp->log('DAREWP_N8N_URL = ' . DAREWP_N8N_URL);
    } else {
        $darewp->log('DAREWP_N8N_URL NOT DEFINED!');
    }
});
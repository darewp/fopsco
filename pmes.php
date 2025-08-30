<?php
/* Template Name: PMES */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! is_user_logged_in() ) {
    // REDIRECT
    wp_redirect( wp_login_url( get_permalink() ) );
    exit;
}
?>

<video 
    id="lessonVideo" 
    controls 
    width="640">
    <source src="https://portal.smrmnt.com/wp-content/uploads/2025/08/Pre-membership-Education-Seminar.mp4" type="video/mp4">
</video>

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
wp_head();
?>

<div class="max-w-7xl mx-auto px-4">
    <div class="flex flex-wrap justify-center mt-4">
    <div class="relative pb-[56.25%] h-0 overflow-hidden rounded-2xl shadow-lg">
      <video 
        id="lessonVideo" 
        controls 
        class="absolute top-0 left-0 w-full h-full rounded-2xl"
      >
        <source src="https://portal.smrmnt.com/wp-content/uploads/2025/08/Pre-membership-Education-Seminar.mp4" type="video/mp4">
        Your browser does not support the video tag.
      </video>
    </div>
  </div>
</div>

<?php
wp_footer();
?>
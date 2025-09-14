<?php
/* Template Name: PMES */

if ( ! defined( 'ABSPATH' ) ) {
      exit;
}

if ( ! is_user_logged_in() ) {
      wp_redirect( wp_login_url( get_permalink() ) );
      exit;
      }

$user_id = get_current_user_id();
$required_fields = [ 'current_address', 'province', 'municipality', 'barangay', 'government_id' ];
$missing = false;

foreach ( $required_fields as $field ) {
      $value = get_user_meta( $user_id, $field, true );
      if ( empty( $value ) ) {
            $missing = true;
            break;
      }
}

if ( $missing && ! is_page( 'profile' ) ) {
    wp_redirect( site_url( '/profile' ) );
    exit;
}

wp_head();
?>
<div class="max-w-7xl mx-auto px-4 flex justify-center items-center">
      <div class="min-h-screen flex flex-wrap justify-center align-center mt-4 max-w-5xl w-full">
            <div class="relative w-full pb-[56.25%] h-0 overflow-hidden rounded-2xl shadow-lg">
                  <video 
                  id="lessonVideo" 
                  controls 
                  class="absolute top-0 left-0 w-full h-full rounded-2xl"
                  >
                  <source src="https://portal.smrmnt.com/wp-content/uploads/2025/08/Pre-membership-Education-Seminar-Video.mp4" type="video/mp4">
                  Your browser does not support the video tag.
                  </video>
            </div>
      </div>
</div>
<?php
wp_footer();
?>

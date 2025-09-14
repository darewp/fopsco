<?php
/* Template Name: PMES */

if ( ! defined( 'ABSPATH' ) ) {
      exit;
}
wp_head();
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
?>
<div class="max-w-7xl mx-auto px-4 flex justify-center items-center">
      <div class="min-h-screen flex flex-wrap justify-center align-center mt-4 max-w-5xl w-full">
            
            <?php
            if ( $missing && ! is_page( 'profile' ) ) {
                  //     wp_redirect( site_url( '/profile' ) );
                  //     exit;
                  ?>
                  <div class="w-full h-full bg-gray-100 flex flex-col justify-center items-center rounded-2xl p-4 text-center">
                        <h3 class="text-2xl font-bold mb-4">Complete Your Profile</h3>
                        <p class="mb-4">Please complete your profile to access the Pre-membership Education Seminar Video.</p>
                        <a href="<?php echo esc_url( site_url( '/profile' ) ); ?>" class="text-[#F85E00] px-6 py-3">Go to Profile</a>
                  <?php
            }else{
            ?>
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
            <?php
            }
            ?>
      </div>
</div>
<?php
wp_footer();
?>

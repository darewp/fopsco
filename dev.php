<?php
/* Template Name: DEV */
wp_head();
$user_id = get_current_user_id();
$user = get_userdata($user_id);

$current_address = get_user_meta($user_id, 'province_name', true);
var_dump($current_address);


wp_footer();
?>
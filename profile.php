<?php
/* Template Name: PROFILE */
wp_head();
?>

<div class="max-w-7xl mx-auto px-4">
    <div class="flex flex-wrap justify-center mt-4">
        <div class="w-full lg:w-3/4 bg-white shadow-md rounded-2xl p-6">
            <?php
            echo do_shortcode('[fopsco_profile_form]');
            ?>
        </div>
    </div>
</div>
<?php
wp_footer();
?>
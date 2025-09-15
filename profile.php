<?php
/* Template Name: Profile Page */
if (!is_user_logged_in()) {
    wp_redirect(wp_login_url());
    exit;
}

$user_id = get_current_user_id();
$user    = get_userdata($user_id);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['fopsco_profile_nonce'])) {
    if (wp_verify_nonce($_POST['fopsco_profile_nonce'], 'fopsco_update_profile')) {
        // Save user meta
        $fields = [
            'phone_number','birthdate','gender','civil_status',
            'province','municipality','barangay',
            'facebook_url','linkedin_url','portfolio_url','current_address'
        ];
        foreach ($fields as $field) {
            if (isset($_POST[$field])) {
                update_user_meta($user_id, $field, sanitize_text_field($_POST[$field]));
            }
        }

        // Handle file upload
        if (!empty($_FILES['government_id']['name'])) {
            require_once ABSPATH . 'wp-admin/includes/file.php';
            require_once ABSPATH . 'wp-admin/includes/image.php';
            require_once ABSPATH . 'wp-admin/includes/media.php';

            $attach_id = media_handle_upload('government_id', 0);
            if (!is_wp_error($attach_id)) {
                update_user_meta($user_id, 'government_id', $attach_id);
                update_user_meta($user_id, 'government_id_url', esc_url_raw(wp_get_attachment_url($attach_id)));
            }
        }

        $message = "Profile updated successfully.";
    } else {
        $message = "Security check failed.";
    }
}
?>

<?php get_header(); ?>
<div class="max-w-7xl mx-auto px-4">
    <div class="flex flex-wrap justify-center">
        <div class="w-full md:max-w-lg mx-auto py-10">
            <h1 class="text-2xl font-bold mb-6">My Profile</h1>

            <?php if (!empty($message)) : ?>
                <div class="mb-4 p-3 bg-green-100 text-green-800 rounded">
                    <?php echo esc_html($message); ?>
                </div>
            <?php endif; ?>

            <form method="post" enctype="multipart/form-data" class="space-y-6">
                <?php wp_nonce_field('fopsco_update_profile', 'fopsco_profile_nonce'); ?>

                <!-- First Name -->
                <div>
                    <label class="block text-sm font-medium">First Name</label>
                    <input type="text" class="mt-1 block w-full border rounded p-2"
                        value="<?php echo esc_attr($user->first_name); ?>" disabled>
                </div>

                <!-- Last Name -->
                <div>
                    <label class="block text-sm font-medium">Last Name</label>
                    <input type="text" class="mt-1 block w-full border rounded p-2"
                        value="<?php echo esc_attr($user->last_name); ?>" disabled>
                </div>

                <!-- Phone -->
                <div>
                    <label class="block text-sm font-medium">Mobile Number</label>
                    <input type="tel" name="phone_number"
                        value="<?php echo esc_attr(get_user_meta($user_id, 'phone_number', true)); ?>"
                        class="mt-1 block w-full border rounded p-2" placeholder="09123456789">
                </div>

                <!-- Birthdate -->
                <div>
                    <label class="block text-sm font-medium">Birthdate</label>
                    <input type="date" name="birthdate"
                        value="<?php echo esc_attr(get_user_meta($user_id, 'birthdate', true)); ?>"
                        class="mt-1 block w-full border rounded p-2">
                </div>

                <!-- Gender -->
                <div>
                    <label class="block text-sm font-medium">Gender</label>
                    <select name="gender" class="mt-1 block w-full border rounded p-2">
                        <option value="">Select Gender</option>
                        <option value="Male" <?php selected(get_user_meta($user_id, 'gender', true), 'Male'); ?>>Male</option>
                        <option value="Female" <?php selected(get_user_meta($user_id, 'gender', true), 'Female'); ?>>Female</option>
                    </select>
                </div>

                <!-- Civil Status -->
                <div>
                    <label class="block text-sm font-medium">Civil Status</label>
                    <select name="civil_status" class="mt-1 block w-full border rounded p-2">
                        <option value="">Select Status</option>
                        <option value="Single" <?php selected(get_user_meta($user_id, 'civil_status', true), 'Single'); ?>>Single</option>
                        <option value="Married" <?php selected(get_user_meta($user_id, 'civil_status', true), 'Married'); ?>>Married</option>
                        <option value="Widow" <?php selected(get_user_meta($user_id, 'civil_status', true), 'Widow'); ?>>Widow</option>
                    </select>
                </div>

                <!-- Province -->
                <div>
                    <label class="block text-sm font-medium">Province</label>
                    <input type="text" name="province"
                        value="<?php echo esc_attr(get_user_meta($user_id, 'province', true)); ?>"
                        class="mt-1 block w-full border rounded p-2">
                </div>

                <!-- Municipality -->
                <div>
                    <label class="block text-sm font-medium">Municipality</label>
                    <input type="text" name="municipality"
                        value="<?php echo esc_attr(get_user_meta($user_id, 'municipality', true)); ?>"
                        class="mt-1 block w-full border rounded p-2">
                </div>

                <!-- Barangay -->
                <div>
                    <label class="block text-sm font-medium">Barangay</label>
                    <input type="text" name="barangay"
                        value="<?php echo esc_attr(get_user_meta($user_id, 'barangay', true)); ?>"
                        class="mt-1 block w-full border rounded p-2">
                </div>

                <!-- Current Address -->
                <div>
                    <label class="block text-sm font-medium">Current Address</label>
                    <input type="text" name="current_address"
                        value="<?php echo esc_attr(get_user_meta($user_id, 'current_address', true)); ?>"
                        class="mt-1 block w-full border rounded p-2">
                </div>

                <!-- Facebook -->
                <div>
                    <label class="block text-sm font-medium">Facebook URL</label>
                    <input type="url" name="facebook_url"
                        value="<?php echo esc_attr(get_user_meta($user_id, 'facebook_url', true)); ?>"
                        class="mt-1 block w-full border rounded p-2" placeholder="https://facebook.com/...">
                </div>

                <!-- LinkedIn -->
                <div>
                    <label class="block text-sm font-medium">LinkedIn URL</label>
                    <input type="url" name="linkedin_url"
                        value="<?php echo esc_attr(get_user_meta($user_id, 'linkedin_url', true)); ?>"
                        class="mt-1 block w-full border rounded p-2" placeholder="https://linkedin.com/in/...">
                </div>

                <!-- Portfolio -->
                <div>
                    <label class="block text-sm font-medium">Portfolio URL</label>
                    <input type="url" name="portfolio_url"
                        value="<?php echo esc_attr(get_user_meta($user_id, 'portfolio_url', true)); ?>"
                        class="mt-1 block w-full border rounded p-2" placeholder="https://example.com">
                </div>

                <!-- Government ID -->
                <div>
                    <label class="block text-sm font-medium">Government ID</label>
                    <input type="file" name="government_id" class="mt-1 block w-full border rounded p-2">
                    <?php if ($gov_id = get_user_meta($user_id, 'government_id', true)) : ?>
                        <p class="mt-2 text-sm">Current: 
                            <a href="<?php echo esc_url(wp_get_attachment_url($gov_id)); ?>" target="_blank" class="text-blue-600 underline">View File</a>
                        </p>
                    <?php endif; ?>
                </div>

                <div>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">Update Profile</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php get_footer(); ?>

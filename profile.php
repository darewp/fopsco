<?php
/* Template Name: Profile Page */
if (!is_user_logged_in()) {
    wp_redirect(wp_login_url());
    exit;
}

$user_id = get_current_user_id();
$user    = get_userdata($user_id);

$n8n_url      = defined('DAREWP_N8N_ID_URL')  ? DAREWP_N8N_ID_URL  : '';
$n8n_username = defined('DAREWP_N8N_ID_USER') ? DAREWP_N8N_ID_USER : '';
$n8n_password = defined('DAREWP_N8N_ID_PASS') ? DAREWP_N8N_ID_PASS : '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['fopsco_profile_nonce'])) {
    if (wp_verify_nonce($_POST['fopsco_profile_nonce'], 'fopsco_update_profile')) {
        // Save user meta (codes + names)
        $fields = [
            'phone_number','birthdate','gender','civil_status',
            'province','province_name',
            'municipality','municipality_name',
            'barangay','barangay_name',
            'facebook_url','linkedin_url','portfolio_url','current_address'
        ];
        foreach ($fields as $field) {
            if (isset($_POST[$field])) {
                update_user_meta($user_id, $field, sanitize_text_field($_POST[$field]));
            }
        }

        // Handle file upload
        $valid_id = get_user_meta($user_id, 'government_id_url', true);

        if (!empty($_FILES['government_id']['name'])) {
            require_once ABSPATH . 'wp-admin/includes/file.php';
            require_once ABSPATH . 'wp-admin/includes/image.php';
            require_once ABSPATH . 'wp-admin/includes/media.php';

            $attach_id = media_handle_upload('government_id', 0);
            if (!is_wp_error($attach_id)) {
                update_user_meta($user_id, 'government_id', $attach_id);
                update_user_meta($user_id, 'government_id_url', esc_url_raw(wp_get_attachment_url($attach_id)));
                $valid_id = wp_get_attachment_url($attach_id);
            }
        }

        // Check required fields for webhook
        $current_address = get_user_meta($user_id, 'current_address', true);
        $province        = get_user_meta($user_id, 'province_name', true);
        $municipality    = get_user_meta($user_id, 'municipality_name', true);
        $barangay        = get_user_meta($user_id, 'barangay_name', true);
        $progress        = get_user_meta($user_id, 'pmes_video_progress', true);

        if (
            !empty($current_address) &&
            !empty($province) &&
            !empty($municipality) &&
            !empty($barangay) &&
            !empty($valid_id) &&
            (empty(is_array($progress) ? $progress['completed'] : $progress))
        ) {
            if (empty($n8n_url)) {
                error_log('n8n webhook skipped: DAREWP_N8N_URL not defined or empty');
            } else {
                $payload = [
                    'user_id'         => $user_id,
                    'email'           => $user->user_email,
                    'first_name'      => get_user_meta($user_id, 'first_name', true),
                    'last_name'       => get_user_meta($user_id, 'last_name', true),
                    'current_address' => $current_address,
                    'province'        => $province,
                    'municipality'    => $municipality,
                    'barangay'        => $barangay,
                    'valid_id'        => $valid_id,
                ];

                $args = [
                    'method'  => 'POST',
                    'headers' => [
                        'Content-Type' => 'application/json; charset=utf-8',
                    ],
                    'body'    => wp_json_encode($payload),
                    'timeout' => 15,
                ];

                if (!empty($n8n_username) || !empty($n8n_password)) {
                    $args['headers']['Authorization'] = 'Basic ' . base64_encode($n8n_username . ':' . $n8n_password);
                }

                $response = wp_remote_post($n8n_url, $args);

                if (is_wp_error($response)) {
                    error_log('n8n webhook error: ' . $response->get_error_message());
                } else {
                    error_log('n8n webhook response code: ' . wp_remote_retrieve_response_code($response));
                    error_log('n8n webhook response body: ' . wp_remote_retrieve_body($response));
                }
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
        <div class="w-full md:max-w-6xl py-10">
            <h3 class="text-2xl font-bold mb-6">Personal Information</h3>

            <?php if (!empty($message)) : ?>
                <div class="mb-4 p-3 bg-green-100 text-green-800 rounded">
                    <?php echo esc_html($message); ?>
                </div>
            <?php endif; ?>

            <form method="post" enctype="multipart/form-data" class="space-y-6">
                <?php wp_nonce_field('fopsco_update_profile', 'fopsco_profile_nonce'); ?>

                <?php
                $province     = get_user_meta($user_id, 'province', true);
                $provinceName = get_user_meta($user_id, 'province_name', true);
                $municipality = get_user_meta($user_id, 'municipality', true);
                $muniName     = get_user_meta($user_id, 'municipality_name', true);
                $barangay     = get_user_meta($user_id, 'barangay', true);
                $brgyName     = get_user_meta($user_id, 'barangay_name', true);
                ?>

                <!-- Province / Municipality / Barangay -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5">
                    <!-- Province -->
                    <div>
                        <label class="block text-sm font-medium">Province</label>
                        <select id="province" name="province" class="mt-1 block w-full border rounded p-2">
                            <?php if ($province): ?>
                                <option value="<?php echo esc_attr($province); ?>" selected>
                                    <?php echo esc_html($provinceName ?: $province); ?>
                                </option>
                            <?php else: ?>
                                <option value="">Loading provinces...</option>
                            <?php endif; ?>
                        </select>
                        <input type="hidden" id="province_name" name="province_name" value="<?php echo esc_attr($provinceName); ?>">
                    </div>

                    <!-- Municipality -->
                    <div>
                        <label class="block text-sm font-medium">Municipality/City</label>
                        <select id="municipality" name="municipality" class="mt-1 block w-full border rounded p-2">
                            <?php if ($municipality): ?>
                                <option value="<?php echo esc_attr($municipality); ?>" selected>
                                    <?php echo esc_html($muniName ?: $municipality); ?>
                                </option>
                            <?php else: ?>
                                <option value="">Select Municipality/City</option>
                            <?php endif; ?>
                        </select>
                        <input type="hidden" id="municipality_name" name="municipality_name" value="<?php echo esc_attr($muniName); ?>">
                    </div>

                    <!-- Barangay -->
                    <div>
                        <label class="block text-sm font-medium">Barangay</label>
                        <select id="barangay" name="barangay" class="mt-1 block w-full border rounded p-2">
                            <?php if ($barangay): ?>
                                <option value="<?php echo esc_attr($barangay); ?>" selected>
                                    <?php echo esc_html($brgyName ?: $barangay); ?>
                                </option>
                            <?php else: ?>
                                <option value="">Select Barangay</option>
                            <?php endif; ?>
                        </select>
                        <input type="hidden" id="barangay_name" name="barangay_name" value="<?php echo esc_attr($brgyName); ?>">
                    </div>
                </div>

                <script>
                    window.phLocationsData = {
                        selected: {
                            province: "<?php echo esc_js($province); ?>",
                            municipality: "<?php echo esc_js($municipality); ?>",
                            barangay: "<?php echo esc_js($barangay); ?>"
                        }
                    };
                </script>
                <!-- <script src="<?php //echo esc_url(get_template_directory_uri() . '/js/ph-locations.js'); ?>" defer></script> -->
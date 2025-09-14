<?php
/* Template Name: PROFILE */
wp_head();
?>
<?php
if ( ! is_user_logged_in() ) {
    wp_redirect( wp_login_url() );
    exit;
}

$current_user = wp_get_current_user();
$user_id = $current_user->ID;

// Handle form submission
if ( isset( $_POST['save_profile'] ) ) {
    $fields = [
        'member_type', 'membership_date', 'phone_number', 'birthdate', 'gender',
        'civil_status', 'current_address', 'province', 'municipality', 'barangay',
        'facebook_url', 'linkedin_url', 'portfolio_url', 'affiliation',
        'freelancing_status', 'freelancing_skills', 'monthly_earnings'
    ];

    foreach ( $fields as $field ) {
        if ( isset( $_POST[$field] ) && ! empty( $_POST[$field] ) ) {
            update_user_meta( $user_id, $field, sanitize_text_field( $_POST[$field] ) );
        }
    }

    // Handle government_id file upload
    if ( ! empty( $_FILES['government_id']['name'] ) ) {
        require_once ABSPATH . 'wp-admin/includes/file.php';
        $uploaded = media_handle_upload( 'government_id', 0 );
        if ( ! is_wp_error( $uploaded ) ) {
            update_user_meta( $user_id, 'government_id', $uploaded );
        }
    }

    echo '<div class="bg-green-100 text-green-700 p-3 rounded-lg mb-4">Profile updated successfully.</div>';
}

// Helper function for input attributes
function field_attr( $user_id, $meta_key ) {
    $value = get_user_meta( $user_id, $meta_key, true );
    if ( ! empty( $value ) ) {
        return 'placeholder="' . esc_attr( $value ) . '" disabled';
    }
    return '';
}
?>

<div class="max-w-7xl mx-auto px-4">
    <div class="flex flex-wrap justify-center mt-4">
        <div class="w-full lg:w-3/4 bg-white shadow-md rounded-2xl p-6">
            <h2 class="text-2xl font-bold mb-6 text-center">Complete Your Profile</h2>

            <form method="post" enctype="multipart/form-data" class="space-y-6">

                <!-- Member Type -->
                <div>
                    <label for="member_type" class="block text-sm font-medium text-gray-700 mb-1">Member Type</label>
                    <select name="member_type" id="member_type" class="w-full border rounded-lg px-3 py-2"
                        <?php echo ! empty( get_user_meta( $user_id, 'member_type', true ) ) ? 'disabled' : ''; ?>>
                        <option value="">Select</option>
                        <option value="regular" <?php selected(get_user_meta($user_id, 'member_type', true), 'regular'); ?>>Online Professional</option>
                        <option value="associate" <?php selected(get_user_meta($user_id, 'member_type', true), 'associate'); ?>>Associate</option>
                    </select>
                </div>

                <!-- Membership Date -->
                <div>
                    <label for="membership_date" class="block text-sm font-medium text-gray-700 mb-1">Membership Date</label>
                    <input type="date" name="membership_date" id="membership_date"
                        class="w-full border rounded-lg px-3 py-2"
                        <?php echo field_attr( $user_id, 'membership_date' ); ?>>
                </div>

                <!-- Phone Number -->
                <div>
                    <label for="phone_number" class="block text-sm font-medium text-gray-700 mb-1">Mobile Number</label>
                    <input type="text" name="phone_number" id="phone_number"
                        class="w-full border rounded-lg px-3 py-2"
                        <?php echo field_attr( $user_id, 'phone_number' ); ?>>
                </div>

                <!-- Birthdate -->
                <div>
                    <label for="birthdate" class="block text-sm font-medium text-gray-700 mb-1">Birthdate</label>
                    <input type="date" name="birthdate" id="birthdate"
                        class="w-full border rounded-lg px-3 py-2"
                        <?php echo field_attr( $user_id, 'birthdate' ); ?>>
                </div>

                <!-- Gender -->
                <div>
                    <label for="gender" class="block text-sm font-medium text-gray-700 mb-1">Gender</label>
                    <select name="gender" id="gender" class="w-full border rounded-lg px-3 py-2"
                        <?php echo ! empty( get_user_meta( $user_id, 'gender', true ) ) ? 'disabled' : ''; ?>>
                        <option value="">Select</option>
                        <option value="male" <?php selected(get_user_meta($user_id, 'gender', true), 'male'); ?>>Male</option>
                        <option value="female" <?php selected(get_user_meta($user_id, 'gender', true), 'female'); ?>>Female</option>
                    </select>
                </div>

                <!-- Civil Status -->
                <div>
                    <label for="civil_status" class="block text-sm font-medium text-gray-700 mb-1">Civil Status</label>
                    <select name="civil_status" id="civil_status" class="w-full border rounded-lg px-3 py-2"
                        <?php echo ! empty( get_user_meta( $user_id, 'civil_status', true ) ) ? 'disabled' : ''; ?>>
                        <option value="">Select</option>
                        <option value="single" <?php selected(get_user_meta($user_id, 'civil_status', true), 'single'); ?>>Single</option>
                        <option value="married" <?php selected(get_user_meta($user_id, 'civil_status', true), 'married'); ?>>Married</option>
                        <option value="widowed" <?php selected(get_user_meta($user_id, 'civil_status', true), 'widowed'); ?>>Widowed</option>
                        <option value="separated" <?php selected(get_user_meta($user_id, 'civil_status', true), 'separated'); ?>>Separated</option>
                    </select>
                </div>

                <!-- Current Address -->
                <div>
                    <label for="current_address" class="block text-sm font-medium text-gray-700 mb-1">Street/House Number/Village/Subdivision</label>
                    <input type="text" name="current_address" id="current_address"
                        class="w-full border rounded-lg px-3 py-2"
                        <?php echo field_attr( $user_id, 'current_address' ); ?>>
                </div>

                <!-- Province -->
                <div>
                    <label for="province" class="block text-sm font-medium text-gray-700 mb-1">Province</label>
                    <input type="text" name="province" id="province"
                        class="w-full border rounded-lg px-3 py-2"
                        <?php echo field_attr( $user_id, 'province' ); ?>>
                </div>

                <!-- Municipality -->
                <div>
                    <label for="municipality" class="block text-sm font-medium text-gray-700 mb-1">Municipality</label>
                    <input type="text" name="municipality" id="municipality"
                        class="w-full border rounded-lg px-3 py-2"
                        <?php echo field_attr( $user_id, 'municipality' ); ?>>
                </div>

                <!-- Barangay -->
                <div>
                    <label for="barangay" class="block text-sm font-medium text-gray-700 mb-1">Barangay</label>
                    <input type="text" name="barangay" id="barangay"
                        class="w-full border rounded-lg px-3 py-2"
                        <?php echo field_attr( $user_id, 'barangay' ); ?>>
                </div>

                <!-- Government ID -->
                <div>
                    <label for="government_id" class="block text-sm font-medium text-gray-700 mb-1">Government ID</label>
                    <input type="file" name="government_id" id="government_id"
                        class="w-full border rounded-lg px-3 py-2"
                        <?php echo ! empty( get_user_meta( $user_id, 'government_id', true ) ) ? 'disabled' : ''; ?>>
                </div>

                <!-- Social URLs -->
                <div>
                    <label for="facebook_url" class="block text-sm font-medium text-gray-700 mb-1">Facebook URL</label>
                    <input type="url" name="facebook_url" id="facebook_url"
                        class="w-full border rounded-lg px-3 py-2"
                        <?php echo field_attr( $user_id, 'facebook_url' ); ?>>
                </div>

                <div>
                    <label for="linkedin_url" class="block text-sm font-medium text-gray-700 mb-1">LinkedIn URL</label>
                    <input type="url" name="linkedin_url" id="linkedin_url"
                        class="w-full border rounded-lg px-3 py-2"
                        <?php echo field_attr( $user_id, 'linkedin_url' ); ?>>
                </div>

                <div>
                    <label for="portfolio_url" class="block text-sm font-medium text-gray-700 mb-1">Portfolio URL</label>
                    <input type="url" name="portfolio_url" id="portfolio_url"
                        class="w-full border rounded-lg px-3 py-2"
                        <?php echo field_attr( $user_id, 'portfolio_url' ); ?>>
                </div>
                <!-- Submit -->
                <div class="text-center">
                    <button type="submit" name="save_profile" class="bg-blue-600 text-white px-6 py-2 rounded-lg shadow hover:bg-blue-700 transition">
                        Save Changes
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>
<?php
wp_footer();
?>
<?php
/* Template Name: Create account */
get_header();

if( !is_user_logged_in() ){
?>

<div class="max-w-7xl mx-auto px-4">
    <div class="flex flex-wrap justify-center mt-4">
        <div class="bg-white shadow-lg rounded-2xl p-8 w-full max-w-md">
            <div class="flex justify-center">
                <?php echo file_get_contents(get_template_directory() . '/assets/img/FOPSCo-2025-logo.svg'); ?>
            </div>
            
            <form id="joinForm" class="gap-4">
                <?php wp_nonce_field('lodge_join_form', 'lodge_join_nonce'); ?>
                <div class="flex flex-col sm:flex-row gap-3 sm:gap-2 mb-3">
                    <div class="w-full sm:w-1/2">
                        <input type="text" id="first_name" placeholder="First Name"
                            class="w-full px-4 py-2 border rounded-lg focus:ring-1 focus:ring-[#F85E00] focus:border-[#F85E00] focus:outline-none"
                            autocomplete="off" required>
                    </div>
                    <div class="w-full sm:w-1/2">
                        <input type="text" id="last_name" placeholder="Last Name"
                            class="w-full px-4 py-2 border rounded-lg focus:ring-1 focus:ring-[#F85E00] focus:border-[#F85E00] focus:outline-none"
                            autocomplete="off" required>
                    </div>
                </div>
                <div class="flex flex-col sm:flex-row gap-3 sm:gap-2 mb-3">
                    <div class="w-full sm:w-1/2">
                        <input type="email" id="email" placeholder="Email Address"
                            class="w-full px-4 py-2 border rounded-lg focus:ring-1 focus:ring-[#F85E00] focus:border-[#F85E00] focus:outline-none"
                            autocomplete="off" required>
                    </div>
                    <div class="w-full sm:w-1/2">
                        <input type="text" id="phone_number" placeholder="Mobile Number"
                            class="w-full px-4 py-2 border rounded-lg focus:ring-1 focus:ring-[#F85E00] focus:border-[#F85E00] focus:outline-none"
                            autocomplete="off" required>
                    </div>
                </div>
                <div class="mb-3">
                    <select id="member_type"
                        class="w-full h-[42px] px-4 py-2 border border-[#000000] rounded-lg focus:ring-1 focus:ring-[#F85E00] focus:border-[#F85E00] focus:outline-none"
                        required>
                        <option value="" disabled selected>Select Member Type</option>
                        <option value="regular">Online Professional</option>
                        <option value="associate">MSME</option>
                    </select>
                </div>
                <div class="mb-3">
                    <input type="password" id="password" placeholder="Password"
                        class="w-full px-4 py-2 border rounded-lg focus:ring-1 focus:ring-[#F85E00] focus:border-[#F85E00] focus:outline-none"
                        autocomplete="off" required>
                </div>
                <div class="mb-3">
                    <input type="password" id="confirm" placeholder="Confirm Password"
                        class="w-full px-4 py-2 border rounded-lg focus:ring-1 focus:ring-[#F85E00] focus:border-[#F85E00] focus:outline-none"
                        autocomplete="off" required>
                </div>

                <input type="hidden" name="website" id="website" value="">
                <div id="errors" class="text-red-600 text-sm"></div>

                <button type="submit"
                    class="w-full bg-[#182955] text-white py-3 px-4 rounded-lg hover:bg-[#F85E00] transition">
                    Create an Account
                </button>
            </form>

        </div>
        
    </div>
</div>
<?php
}else{
?>
<div class="max-w-7xl mx-auto px-4">
    <div class="flex flex-wrap justify-center mt-4">
        <div class="bg-white shadow-lg rounded-2xl p-8 w-full max-w-md">
            <div class="flex justify-center">
                <?php echo file_get_contents(get_template_directory() . '/assets/img/FOPSCo-2025-logo.svg'); ?>
            </div>
            <p class="text-center text-gray-600 mt-3 mb-4">You are already have an account.</p>
        </div>
    </div>
</div>
<?php
} 

get_footer(); ?>

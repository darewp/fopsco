<?php
/* Template Name: Join Fopsco */
get_header();
?>

<div class="max-w-7xl mx-auto px-4">
    <div class="flex flex-wrap justify-center">
        <div class="bg-white shadow-lg rounded-2xl p-8 w-full max-w-md">
            <h2 class="text-2xl text-[#182955] font-bold text-center">Join</h2>
            <img src="/fopsco/wp-content/themes/fopsco/assets/img/FOPSCo-2025.svg" alt="FOPSCo" class="w-34 mx-auto mb-4">
            <?php echo file_get_contents(get_template_directory() . '/assets/img/FOPSCo-2025.svg'); ?>

            
            <form id="registerForm" class="space-y-4">
                <div>
                    <input type="text" id="first_name" placeholder="First Name" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none" autocomplete="off" required>
                </div>
                <div>
                    <input type="text" id="last_name" placeholder="Last Name" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none" required>
                </div>
                <div>
                    <input type="text" id="contact" placeholder="Contact Number" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none" required>
                </div>
                <div>
                    <input type="email" id="email" placeholder="Email Address" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none" required>
                </div>
                <div>
                    <input type="password" id="password" placeholder="Password" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none" required>
                </div>
                <div>
                    <input type="password" id="confirm" placeholder="Confirm Password" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none" required>
                </div>

                <div id="errors" class="text-red-600 text-sm"></div>

                <button type="submit" class="w-full bg-[#182955] text-white py-2 px-4 rounded-lg hover:bg-blue-700 transition">Register</button>
            </form>
        </div>
        
    </div>
</div>
<?php get_footer(); ?>

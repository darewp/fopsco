<?php
/* Template Name: Join Fopsco */
get_header();
?>

<div class="max-w-7xl mx-auto px-4">
    <div class="flex flex-wrap justify-center mt-4">
        <div class="bg-white shadow-lg rounded-2xl p-8 w-full max-w-md">
            <h2 class="text-2xl text-[#182955] font-bold text-center">Join</h2>
            <div class="flex justify-center">
                <?php echo file_get_contents(get_template_directory() . '/assets/img/FOPSCo-2025-logo.svg'); ?>
            </div>
            
            <form id="joinForm" class="space-y-4">
                <?php wp_nonce_field('lodge_join_form', 'lodge_join_nonce'); ?>

                <div>
                    <input type="text" id="first_name" placeholder="First Name" class="w-full px-4 py-2 border rounded-lg focus:ring-1 focus:ring-[#F85E00] focus:border-[#F85E00] focus:outline-none" autocomplete="off" required>
                </div>
                <div>
                    <input type="text" id="last_name" placeholder="Last Name" class="w-full px-4 py-2 border rounded-lg focus:ring-1 focus:ring-[#F85E00] focus:border-[#F85E00] focus:outline-none" autocomplete="off" required>
                </div>
                <div>
                    <input type="email" id="email" placeholder="Email Address" class="w-full px-4 py-2 border rounded-lg focus:ring-1 focus:ring-[#F85E00] focus:border-[#F85E00] focus:outline-none" autocomplete="off" required>
                </div>                
                <div>
                    <input type="text" id="phone_number" placeholder="Mobile Number" class="w-full px-4 py-2 border rounded-lg focus:ring-1 focus:ring-[#F85E00] focus:border-[#F85E00] focus:outline-none" autocomplete="off" required>
                </div>
                <div>
                    <select id="member_type" class="w-full px-4 py-2 border rounded-lg focus:ring-1 focus:ring-[#F85E00] focus:border-[#F85E00] focus:outline-none" required>
                        <option value="" disabled selected>Select Member Type</option>
                        <option value="regular">Online Professional</option>
                        <option value="associate">MSME</option>
                    </select>
                </div>                
                <div>
                    <input type="password" id="password" placeholder="Password" class="w-full px-4 py-2 border rounded-lg focus:ring-1 focus:ring-[#F85E00] focus:border-[#F85E00] focus:outline-none" autocomplete="off" required>
                </div>
                <div>
                    <input type="password" id="confirm" placeholder="Confirm Password" class="w-full px-4 py-2 border rounded-lg focus:ring-1 focus:ring-[#F85E00] focus:border-[#F85E00] focus:outline-none" autocomplete="off" required>
                </div>
                <input type="hidden" name="website" id="website" value="">
                <div id="errors" class="text-red-600 text-sm"></div>

                <button type="submit" class="w-full bg-[#182955] text-white py-3 px-4 rounded-lg hover:bg-[#F85E00] transition">Join Now</button>
            </form>
        </div>
        
    </div>
</div>
<?php get_footer(); ?>

<?php
/* Template Name: Join Fopsco */
get_header();
?>
<form id="joinForm" class="space-y-4" method="POST" action="">
    <?php wp_nonce_field( 'lodge_join_action', 'lodge_join_nonce' ); ?>
    
    <div>
        <input type="text" id="first_name" placeholder="First Name" 
            class="w-full px-4 py-2 border rounded-lg focus:ring-1 focus:ring-[#F85E00] 
            focus:border-[#F85E00] focus:outline-none" autocomplete="off" required>
    </div>
    <div>
        <input type="text" id="last_name" placeholder="Last Name"
            class="w-full px-4 py-2 border rounded-lg focus:ring-1 focus:ring-[#F85E00] 
            focus:border-[#F85E00] focus:outline-none" autocomplete="off" required>
    </div>
    <div>
        <input type="text" id="contact" placeholder="Contact Number"
            class="w-full px-4 py-2 border rounded-lg focus:ring-1 focus:ring-[#F85E00] 
            focus:border-[#F85E00] focus:outline-none" autocomplete="off" required>
    </div>
    <div>
        <input type="email" id="email" placeholder="Email Address"
            class="w-full px-4 py-2 border rounded-lg focus:ring-1 focus:ring-[#F85E00] 
            focus:border-[#F85E00] focus:outline-none" autocomplete="off" required>
    </div>
    <div>
        <select id="member_type"
            class="w-full px-4 py-2 border rounded-lg focus:ring-1 focus:ring-[#F85E00] 
            focus:border-[#F85E00] focus:outline-none" required>
            <option value="" disabled selected>Select Member Type</option>
            <option value="regular">Online Professional</option>
            <option value="associate">MSME</option>
        </select>
    </div>
    <div>
        <input type="password" id="password" placeholder="Password"
            class="w-full px-4 py-2 border rounded-lg focus:ring-1 focus:ring-[#F85E00] 
            focus:border-[#F85E00] focus:outline-none" autocomplete="off" required>
    </div>
    <div>
        <input type="password" id="confirm" placeholder="Confirm Password"
            class="w-full px-4 py-2 border rounded-lg focus:ring-1 focus:ring-[#F85E00] 
            focus:border-[#F85E00] focus:outline-none" autocomplete="off" required>
    </div>

    <!-- Hidden honeypot -->
    <input type="hidden" name="website" id="website" value="">

    <!-- Error container -->
    <div id="errors" class="text-red-600 text-sm"></div>

    <button type="submit"
        class="w-full bg-[#182955] text-white py-3 px-4 rounded-lg hover:bg-[#F85E00] transition">
        Join Now
    </button>
</form>
<?php get_footer(); ?>

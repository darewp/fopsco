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
      <div class="min-h-screen flex flex-wrap justify-center align-center mt-4 max-w-4xl w-full">
            
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
            <section class="mx-auto px-6 pt-6">
            <h1 class="text-3xl font-bold tracking-tight mb-8 text-gray-900">10 Quick Q&A About Joining FOPSCo (For the Lazy But Curious 😄)</h1>

            <div class="space-y-4">
                  <details class="group rounded-2xl border border-gray-200 bg-white p-5 shadow-sm open:shadow transition-all">
                        <summary class="flex cursor-pointer list-none items-center justify-between gap-4">
                              <span class="text-left font-semibold">1️⃣ What is FOPSCo?</span>
                              <span class="ml-4 grid h-6 w-6 place-items-center rounded-full border border-gray-300 text-xs transition group-open:rotate-45">+</span>
                        </summary>
                        <div class="mt-3 text-gray-700">
                              FOPSCo (Filipino Online Professionals Service Cooperative) is a community of freelancers, online professionals, and MSMEs helping each other grow through training, projects, and shared opportunities.
                        </div>
                  </details>

                  <details class="group rounded-2xl border border-gray-200 bg-white p-5 shadow-sm open:shadow transition-all">
                        <summary class="flex cursor-pointer list-none items-center justify-between gap-4">
                              <span class="text-left font-semibold">2️⃣ What’s FOPSCo’s main purpose?</span>
                              <span class="ml-4 grid h-6 w-6 place-items-center rounded-full border border-gray-300 text-xs transition group-open:rotate-45">+</span>
                        </summary>
                        <div class="mt-3  text-gray-700">
                              To empower Filipino online professionals and MSMEs with training, job opportunities, and cooperative benefits for a sustainable online career.
                        </div>
                  </details>

                  <details class="group rounded-2xl border border-gray-200 bg-white p-5 shadow-sm open:shadow transition-all">
                        <summary class="flex cursor-pointer list-none items-center justify-between gap-4">
                              <span class="text-left font-semibold">3️⃣ How is a cooperative different from a corporation?</span>
                              <span class="ml-4 grid h-6 w-6 place-items-center rounded-full border border-gray-300 text-xs transition group-open:rotate-45">+</span>
                        </summary>
                        <div class="mt-3 text-gray-700 space-y-2">
                              <p><span class="font-medium">Corporation:</span> Owned by investors who seek profit.</p>
                              <p><span class="font-medium">Cooperative:</span> Owned by members who share in the benefits.</p>
                        </div>
                  </details>

                  <details class="group rounded-2xl border border-gray-200 bg-white p-5 shadow-sm open:shadow transition-all">
                        <summary class="flex cursor-pointer list-none items-center justify-between gap-4">
                              <span class="text-left font-semibold">4️⃣ Is FOPSCo legal and registered?</span>
                              <span class="ml-4 grid h-6 w-6 place-items-center rounded-full border border-gray-300 text-xs transition group-open:rotate-45">+</span>
                        </summary>
                        <div class="mt-3 text-gray-700">
                              ✅ Yes! We’re officially registered with the Cooperative Development Authority (CDA).
                        </div>
                  </details>

                  <details class="group rounded-2xl border border-gray-200 bg-white p-5 shadow-sm open:shadow transition-all">
                        <summary class="flex cursor-pointer list-none items-center justify-between gap-4">
                              <span class="text-left font-semibold">5️⃣ What are the 7 principles of a cooperative?</span>
                              <span class="ml-4 grid h-6 w-6 place-items-center rounded-full border border-gray-300 text-xs transition group-open:rotate-45">+</span>
                        </summary>
                        <div class="mt-3 text-gray-700">
                              <ul class="list-disc pl-6 space-y-1">
                              <li>Voluntary and Open Membership</li>
                              <li>Democratic Member Control</li>
                              <li>Members’ Economic Participation</li>
                              <li>Autonomy and Independence</li>
                              <li>Education, Training, and Information</li>
                              <li>Cooperation Among Cooperatives</li>
                              <li>Concern for the Community</li>
                              </ul>
                        </div>
                  </details>

                  <details class="group rounded-2xl border border-gray-200 bg-white p-5 shadow-sm open:shadow transition-all">
                        <summary class="flex cursor-pointer list-none items-center justify-between gap-4">
                              <span class="text-left font-semibold">6️⃣ How can I earn or benefit financially as a member?</span>
                              <span class="ml-4 grid h-6 w-6 place-items-center rounded-full border border-gray-300 text-xs transition group-open:rotate-45">+</span>
                        </summary>
                        <div class="mt-3 text-gray-700">
                              <ul class="grid grid-cols-1 gap-2 sm:grid-cols-2">
                              <li class="flex items-center gap-2"><span class="inline-flex h-6 w-6 items-center justify-center rounded-full bg-green-100 text-green-700 text-xs">💰</span>Dividends</li>
                              <li class="flex items-center gap-2"><span class="inline-flex h-6 w-6 items-center justify-center rounded-full bg-blue-100 text-blue-700 text-xs">📚</span>Trainings and upskilling</li>
                              <li class="flex items-center gap-2"><span class="inline-flex h-6 w-6 items-center justify-center rounded-full bg-amber-100 text-amber-700 text-xs">💼</span>Work with FOPSCo’s clients</li>
                              <li class="flex items-center gap-2"><span class="inline-flex h-6 w-6 items-center justify-center rounded-full bg-purple-100 text-purple-700 text-xs">🤝</span>Referral incentives</li>
                              <li class="flex items-center gap-2 sm:col-span-2"><span class="inline-flex h-6 w-6 items-center justify-center rounded-full bg-gray-100 text-gray-700 text-xs">💬</span>Support and collaboration with other members</li>
                              </ul>
                        </div>
                  </details>

                  <details class="group rounded-2xl border border-gray-200 bg-white p-5 shadow-sm open:shadow transition-all">
                        <summary class="flex cursor-pointer list-none items-center justify-between gap-4">
                              <span class="text-left font-semibold">7️⃣ How much is the membership fee?</span>
                              <span class="ml-4 grid h-6 w-6 place-items-center rounded-full border border-gray-300 text-xs transition group-open:rotate-45">+</span>
                        </summary>
                        <div class="mt-3 text-gray-700">
                              Only ₱1,500 — lifetime membership!
                        </div>
                  </details>

                  <details class="group rounded-2xl border border-gray-200 bg-white p-5 shadow-sm open:shadow transition-all">
                        <summary class="flex cursor-pointer list-none items-center justify-between gap-4">
                              <span class="text-left font-semibold">8️⃣ How much is one share?</span>
                              <span class="ml-4 grid h-6 w-6 place-items-center rounded-full border border-gray-300 text-xs transition group-open:rotate-45">+</span>
                        </summary>
                        <div class="mt-3 text-gray-700">
                              Each share is ₱100.
                        </div>
                  </details>

                  <details class="group rounded-2xl border border-gray-200 bg-white p-5 shadow-sm open:shadow transition-all">
                        <summary class="flex cursor-pointer list-none items-center justify-between gap-4">
                              <span class="text-left font-semibold">9️⃣ What’s the minimum amount to become a member?</span>
                              <span class="ml-4 grid h-6 w-6 place-items-center rounded-full border border-gray-300 text-xs transition group-open:rotate-45">+</span>
                        </summary>
                        <div class="mt-3 text-gray-700">
                              You can start with ₱3,000 total:
                              <ul class="mt-2 list-disc pl-6 space-y-1">
                              <li>₱1,500 (membership fee)</li>
                              <li>₱1,500 (15 shares)</li>
                              </ul>
                        </div>
                  </details>

                  <details class="group rounded-2xl border border-gray-200 bg-white p-5 shadow-sm open:shadow transition-all">
                        <summary class="flex cursor-pointer list-none items-center justify-between gap-4">
                              <span class="text-left font-semibold">🔟 How long can I pay the balance for the remaining 35 shares?</span>
                              <span class="ml-4 grid h-6 w-6 place-items-center rounded-full border border-gray-300 text-xs transition group-open:rotate-45">+</span>
                        </summary>
                        <div class="mt-3 text-gray-700">
                              You have up to 24 months (2 years) to complete your payment of ₱3,500.
                        </div>
                  </details>
            </div>
      </section>

      </div>
</div>
<?php
wp_footer();
?>

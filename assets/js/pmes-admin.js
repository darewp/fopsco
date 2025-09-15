document.addEventListener("DOMContentLoaded", () => {
    const buttons = document.querySelectorAll(".pmes-reset-btn");

    buttons.forEach(button => {
        button.addEventListener("click", e => {
            e.preventDefault();

            const userId = button.dataset.userId;
            const msg = button.nextElementSibling;

            const formData = new FormData();
            formData.append("action", "pmes_reset_progress");
            formData.append("user_id", userId);
            formData.append("_ajax_nonce", PMESAdmin.nonce);

            fetch(PMESAdmin.ajaxurl, { method: "POST", body: formData, credentials: "same-origin" })
            .then(res => res.json())
            .then(response => {
                if (response.success) {
                    msg.textContent = "Progress reset ✅";

                    // Update table
                    const table = document.getElementById(`pmes-progress-table-${userId}`);
                    if (table) {
                        table.querySelectorAll("td").forEach(td => {
                            if (!td.querySelector("button")) {
                                if (td.textContent.includes("%")) td.textContent = "0%";
                                else if (td.textContent.includes("Completed") || td.textContent.includes("Not Completed")) td.textContent = "Not Completed ❌";
                                else td.textContent = "0";
                            }
                        });
                    }
                } else {
                    msg.textContent = "Reset failed ❌";
                }
            })
            .catch(() => { msg.textContent = "Reset failed ❌"; });
        });
    });
});
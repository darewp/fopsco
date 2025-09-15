document.addEventListener("DOMContentLoaded", () => {
    const resetButtons = document.querySelectorAll(".pmes-reset-btn");

    resetButtons.forEach((button) => {
        button.addEventListener("click", (e) => {
            e.preventDefault();

            const userId = button.dataset.userId;
            const msg = button.nextElementSibling; // span.pmes-reset-msg

            const formData = new FormData();
            formData.append("action", "pmes_reset_progress");
            formData.append("user_id", userId);
            formData.append("_ajax_nonce", PMESAdmin.nonce);

            fetch(PMESAdmin.ajaxurl, {
                method: "POST",
                credentials: "same-origin",
                body: formData
            })
            .then((res) => res.json())
            .then((response) => {
                if (response.success) {
                    msg.textContent = "Progress reset ✅";

                    // Update table cells
                    const table = document.getElementById(`pmes-progress-table-${userId}`);
                    if (table) {
                        const tdList = table.querySelectorAll("td");
                        tdList.forEach((td) => {
                            // Skip button cell
                            if (!td.querySelector("button")) {
                                if (td.textContent.includes("Completed") || td.textContent.includes("Not Completed")) {
                                    td.textContent = "Not Completed ❌";
                                } else if (td.textContent.includes("%")) {
                                    td.textContent = "0%";
                                } else {
                                    td.textContent = "0";
                                }
                            }
                        });
                    }
                } else {
                    msg.textContent = "Reset failed ❌";
                }
            })
            .catch(() => {
                msg.textContent = "Reset failed ❌";
            });
        });
    });
});

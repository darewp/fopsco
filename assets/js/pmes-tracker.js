document.addEventListener("DOMContentLoaded", () => {
    // Existing lessonVideo tracking
    const video = document.getElementById("lessonVideo");

    if (video && typeof VideoTracker !== "undefined") {
        const sendVideoEvent = (action, extraData = {}) => {
            const formData = new FormData();
            formData.append("action", action);
            formData.append("_ajax_nonce", VideoTracker.nonce);
            formData.append("user_id", VideoTracker.user_id);

            for (const [key, value] of Object.entries(extraData)) {
                formData.append(key, value);
            }

            fetch(VideoTracker.ajaxurl, {
                method: "POST",
                credentials: "same-origin",
                body: formData
            })
            .then(res => res.json())
            .then(response => {
                if (!response.success) {
                    console.error("Video Tracker Error:", response.data.message);
                } else {
                    console.log("Video Tracker:", response.data.message);
                }
            })
            .catch(err => console.error("AJAX Request Failed:", err));
        };

        let lastTime = 0;

        video.addEventListener("play", () => sendVideoEvent("video_played"));
        video.addEventListener("pause", () => sendVideoEvent("video_paused"));
        video.addEventListener("ended", () => sendVideoEvent("video_completed"));

        video.addEventListener("seeking", () => {
            const from = Math.floor(lastTime);
            const to = Math.floor(video.currentTime);

            if (Math.abs(to - from) > 1) {
                sendVideoEvent("video_skipped", { fromTime: from, toTime: to });
            }
        });

        video.addEventListener("timeupdate", () => {
            lastTime = video.currentTime;
        });
    }

    // --- Vanilla JS Reset Progress ---
    const resetButtons = document.querySelectorAll(".pmes-reset-btn");

    resetButtons.forEach(button => {
        button.addEventListener("click", (e) => {
            e.preventDefault();

            const userId = button.dataset.userId;
            const msg = button.nextElementSibling; // span.pmes-reset-msg

            const formData = new FormData();
            formData.append("action", "pmes_reset_progress");
            formData.append("user_id", userId);
            formData.append("_ajax_nonce", VideoTracker.nonce);

            fetch(VideoTracker.ajaxurl, {
                method: "POST",
                credentials: "same-origin",
                body: formData
            })
            .then(res => res.json())
            .then(response => {
                if (response.success) {
                    msg.textContent = "Progress reset ✅";

                    // Update table cells
                    const table = document.getElementById(`pmes-progress-table-${userId}`);
                    if (table) {
                        const tdList = table.querySelectorAll("td");
                        tdList.forEach(td => {
                            // Skip button cell
                            if (!td.querySelector("button")) {
                                td.textContent = td.textContent.includes("Completed") ? "Not Completed ❌" : "0";
                            }
                        });

                        // Reset progress %
                        const progressTd = table.querySelector("td:first-child");
                        if (progressTd) progressTd.textContent = "0%";
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
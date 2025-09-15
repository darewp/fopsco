document.addEventListener("DOMContentLoaded", () => {
    const video = document.getElementById("lessonVideo");
    if (!video || typeof VideoTracker === "undefined") return;

    let lastTime = 0;
    let accumulatedTime = 0;

    const sendVideoEvent = (action, extraData = {}) => {
        console.log(`[VideoTracker] Sending event: ${action}`, extraData);

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
                console.error("[VideoTracker] Error:", response.data.message);
            } else {
                console.log("[VideoTracker] Success:", response.data.message);
            }
        })
        .catch(err => console.error("[VideoTracker] AJAX Request Failed:", err));
    };

    video.addEventListener("play", () => {
        lastTime = video.currentTime;
        console.log("Play at", lastTime, "seconds");
        sendVideoEvent("video_played");
    });

    video.addEventListener("pause", () => {
        accumulatedTime += Math.floor(video.currentTime - lastTime);
        console.log("Pause at", video.currentTime, "seconds, accumulated:", accumulatedTime);
        sendVideoEvent("video_paused", {
            watched_seconds: accumulatedTime,
            video_duration: Math.floor(video.duration)
        });
        lastTime = video.currentTime;
    });

    video.addEventListener("ended", () => {
        accumulatedTime += Math.floor(video.currentTime - lastTime);
        console.log("Video ended, total watched:", accumulatedTime, "seconds");
        sendVideoEvent("video_completed", {
            watched_seconds: accumulatedTime,
            video_duration: Math.floor(video.duration)
        });
        accumulatedTime = 0;
        lastTime = 0;
    });

    video.addEventListener("seeking", () => {
        const from = lastTime;
        const to = video.currentTime;
        console.log("Seeking from", from, "to", to);

        if (Math.abs(to - from) > 1) {
            accumulatedTime += Math.floor(Math.min(from, to) - Math.min(from, lastTime));
            console.log("Skipped from", from, "to", to, "accumulated:", accumulatedTime);
            sendVideoEvent("video_skipped", {
                fromTime: Math.floor(from),
                toTime: Math.floor(to),
                watched_seconds: accumulatedTime,
                video_duration: Math.floor(video.duration)
            });
        }

        lastTime = video.currentTime;
    });

    video.addEventListener("timeupdate", () => {
        const current = video.currentTime;
        const delta = current - lastTime;
        if (delta > 0 && delta < 5) {
            accumulatedTime += Math.floor(delta);
            console.log("Timeupdate - current:", current, "delta:", delta, "accumulated:", accumulatedTime);
        }
        lastTime = current;
    });

});
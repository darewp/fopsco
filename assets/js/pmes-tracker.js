document.addEventListener("DOMContentLoaded", () => {
    const video = document.getElementById("lessonVideo");
    if (!video || typeof VideoTracker === "undefined") return;

    let lastTime = 0;
    let accumulatedTime = 0;

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
            }
        })
        .catch(err => console.error("AJAX Request Failed:", err));
    };

    video.addEventListener("play", () => {
        lastTime = video.currentTime;
        sendVideoEvent("video_played");
    });

    video.addEventListener("pause", () => {
        accumulatedTime += Math.floor(video.currentTime - lastTime);
        sendVideoEvent("video_paused", {
            watched_seconds: accumulatedTime,
            video_duration: Math.floor(video.duration)
        });
        lastTime = video.currentTime;
    });

    video.addEventListener("ended", () => {
        accumulatedTime += Math.floor(video.currentTime - lastTime);
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
        if (Math.abs(to - from) > 1) {
            accumulatedTime += Math.floor(Math.min(from, to) - lastTime);
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
        const delta = video.currentTime - lastTime;
        if (delta > 0 && delta < 5) {
            accumulatedTime += Math.floor(delta);
        }
        lastTime = video.currentTime;
    });
});
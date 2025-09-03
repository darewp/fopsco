document.addEventListener("DOMContentLoaded", () => {
  const video = document.getElementById("lessonVideo");

  if (!video || typeof VideoTracker === "undefined") return;

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
      body: formData,
    })
      .then((res) => res.json())
      .then((response) => {
        if (!response.success) {
          console.error("Video Tracker Error:", response.data.message);
        } else {
          console.log("Video Tracker:", response.data.message);
        }
      })
      .catch((err) => console.error("AJAX Request Failed:", err));
  };

  video.addEventListener("play", () => {
    sendVideoEvent("video_played");
  });

  video.addEventListener("pause", () => {
    sendVideoEvent("video_paused");
  });

  video.addEventListener("ended", () => {
    sendVideoEvent("video_completed");
  });

  video.addEventListener("seeking", () => {
    const from = Math.floor(video._lastTime || 0);
    const to = Math.floor(video.currentTime);

    if (Math.abs(to - from) > 2) {
      sendVideoEvent("video_skipped", { fromTime: from, toTime: to });
    }
  });

  video.addEventListener("timeupdate", () => {
    video._lastTime = Math.floor(video.currentTime);
  });
});

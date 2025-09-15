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

  let lastTime = 0;

  video.addEventListener("play", () => sendVideoEvent("video_played"));
  video.addEventListener("pause", () => sendVideoEvent("video_paused"));
  video.addEventListener("ended", () => sendVideoEvent("video_completed"));

  video.addEventListener("seeking", () => {
    const from = Math.floor(lastTime);
    const to = Math.floor(video.currentTime);

    if (Math.abs(to - from) > 1) { // reduce threshold to 1 second
      sendVideoEvent("video_skipped", { fromTime: from, toTime: to });
    }
  });

  video.addEventListener("timeupdate", () => {
    lastTime = video.currentTime;
  });
});

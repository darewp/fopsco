document.addEventListener("DOMContentLoaded", () => {
    const form = document.getElementById("joinForm");
    const errorsDiv = document.getElementById("errors");
    const submitBtn = form.querySelector("button[type='submit']");

    form.addEventListener("submit", async (e) => {
        e.preventDefault();
        errorsDiv.textContent = "";

        const payload = {
            first_name: document.getElementById("first_name").value,
            last_name: document.getElementById("last_name").value,
            contact: document.getElementById("contact").value,
            email: document.getElementById("email").value,
            member_type: document.getElementById("member_type").value,
            password: document.getElementById("password").value,
            confirm: document.getElementById("confirm").value,
            website: document.getElementById("website").value,
        };

        // 🔄 set loading state
        submitBtn.disabled = true;
        submitBtn.dataset.originalText = submitBtn.textContent;
        submitBtn.textContent = "Joining...";

        try {
            const res = await fetch(lodgeSettings.restUrl, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-Lodge-Nonce": lodgeSettings.nonce,
                },
                body: JSON.stringify(payload),
            });

            const data = await res.json();

            if (!res.ok) {
                errorsDiv.classList.remove("text-green-600");
                errorsDiv.classList.add("text-red-600");
                errorsDiv.textContent = data.message || "Something went wrong.";
                return;
            }

            // ✅ success flow
            errorsDiv.classList.remove("text-red-600");
            errorsDiv.classList.add("text-green-600", "font-medium");
            errorsDiv.textContent = "🎉 Registration successful! Redirecting...";

            setTimeout(() => {
                window.location.href = "/welcome";
            }, 2000);

        } catch (err) {
            errorsDiv.classList.remove("text-green-600");
            errorsDiv.classList.add("text-red-600");
            errorsDiv.textContent = "Server error. Please try again.";
        } finally {
            // 🔄 reset button if not redirected yet
            if (!form.classList.contains("redirecting")) {
                submitBtn.disabled = false;
                submitBtn.textContent = submitBtn.dataset.originalText;
            }
        }
    });
});

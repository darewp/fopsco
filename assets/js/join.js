document.addEventListener("DOMContentLoaded", () => {
    const form = document.getElementById("joinForm");
    const errorsDiv = document.getElementById("errors");

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

        try {
            const res = await fetch("/wp-json/lodge/v1/join", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-WP-Nonce": lodgeSettings.nonce,
                },
                body: JSON.stringify(payload),
            });

            const data = await res.json();

            if (!res.ok) {
                errorsDiv.textContent = data.message || "Something went wrong.";
                return;
            }

            // ✅ Success flow
            errorsDiv.classList.remove("text-red-600");
            errorsDiv.classList.add("text-green-600", "font-medium");
            errorsDiv.textContent = "You just joined FOPSCo, Redirecting...";

            setTimeout(() => {
                window.location.href = "/welcome";
            }, 2000);

        } catch (err) {
            errorsDiv.textContent = "Server error. Please try again.";
        }
    });
});

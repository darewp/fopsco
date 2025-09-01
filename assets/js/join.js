document.addEventListener("DOMContentLoaded", function () {
    const form = document.getElementById("joinForm");
    const errorsEl = document.getElementById("errors");

    // Create a success message element
    const successEl = document.createElement("div");
    successEl.className = "text-green-600 text-sm mt-2";
    form.appendChild(successEl);

    form.addEventListener("submit", async function (e) {
        e.preventDefault();
        errorsEl.textContent = "";
        successEl.textContent = "";

        const formData = {
            first_name: document.getElementById("first_name").value.trim(),
            last_name: document.getElementById("last_name").value.trim(),
            email: document.getElementById("email").value.trim(),
            phone_number: document.getElementById("phone_number").value.trim(),
            member_type: document.getElementById("member_type").value,
            password: document.getElementById("password").value,
            confirm: document.getElementById("confirm").value,
            website: document.getElementById("website").value
        };

        if (formData.password !== formData.confirm) {
            errorsEl.textContent = "Passwords do not match.";
            return;
        }

        const nonce = document.getElementById("lodge_join_nonce").value;

        try {
            const res = await fetch("/wp-json/lodge/v1/join", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-Lodge-Nonce": nonce
                },
                body: JSON.stringify(formData)
            });

            const data = await res.json();

            if (!res.ok || data.success === false) {
                errorsEl.textContent = data.message || "Something went wrong.";
                return;
            }

            successEl.textContent = "You have successfully joined FOPSCo!";
            form.reset();

            // Uncomment this if you want to redirect after success
            // setTimeout(() => {
            //     window.location.href = "/welcome";
            // }, 2000);

        } catch (err) {
            errorsEl.textContent = "Network error. Please try again.";
        }
    });
});
document.getElementById("registerForm").addEventListener("submit", async (e) => {
    e.preventDefault();

    const first_name = document.getElementById("first_name").value.trim();
    const last_name  = document.getElementById("last_name").value.trim();
    const contact    = document.getElementById("contact").value.trim();
    const email      = document.getElementById("email").value.trim();
    const password   = document.getElementById("password").value;
    const confirm    = document.getElementById("confirm").value;

    const errorsEl = document.getElementById("errors");
    errorsEl.innerHTML = "";
    let errors = [];

    if (validator.isEmpty(first_name)) errors.push("First name is required.");
    if (validator.isEmpty(last_name)) errors.push("Last name is required.");
    if (!validator.isMobilePhone(contact, 'any')) errors.push("Enter a valid contact number.");
    if (!validator.isEmail(email)) errors.push("Enter a valid email.");
    if (!validator.isLength(password, { min: 8 })) errors.push("Password must be at least 8 characters.");
    if (password !== confirm) errors.push("Passwords do not match.");

    if (errors.length > 0) {
        errorsEl.innerHTML = errors.map(e => `<p>${e}</p>`).join("");
        return;
    }

    try {
        const res = await fetch("<?php echo site_url('/wp-json/custom/v1/register'); ?>", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ first_name, last_name, contact, email, password })
        });

        const data = await res.json();
        if (!res.ok) {
        errorsEl.innerHTML = `<p>${data.message || "Registration failed."}</p>`;
        } else {
        alert("Registration successful!");
        window.location.href = "<?php echo site_url('/welcome'); ?>";
        }
    } catch (err) {
        errorsEl.innerHTML = `<p>Something went wrong. Try again later.</p>`;
    }
});
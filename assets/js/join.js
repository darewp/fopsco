document.getElementById("joinForm").addEventListener("submit", async (e) => {
    e.preventDefault();

    const first_name  = document.getElementById("first_name").value.trim();
    const last_name   = document.getElementById("last_name").value.trim();
    const contact     = document.getElementById("contact").value.trim();
    const email       = document.getElementById("email").value.trim();
    const password    = document.getElementById("password").value;
    const confirm     = document.getElementById("confirm").value;
    const member_type = document.getElementById("member_type").value;
    const website     = document.getElementById("website").value.trim();

    const errorsEl = document.getElementById("errors");
    errorsEl.innerHTML = "";
    let errors = [];

    if (validator.isEmpty(first_name)) errors.push("First name is required.");
    if (validator.isEmpty(last_name)) errors.push("Last name is required.");
    if (!validator.isMobilePhone(contact, 'any')) errors.push("Enter a valid contact number.");
    if (!validator.isEmail(email)) errors.push("Enter a valid email.");
    if (validator.isEmpty(member_type)) errors.push("Member type is required."); 
    if (!validator.isLength(password, { min: 8 })) errors.push("Password must be at least 8 characters.");
    if (password !== confirm) errors.push("Passwords do not match.");
    if (website) errors.push("bots");

    if (errors.length > 0) {
        errorsEl.innerHTML = errors.map(e => `<p>${e}</p>`).join("");
        return;
    }

    try {
        const res = await fetch('/wp-json/lodge/v1/join', {
            method: "POST",
            headers: { "Content-Type": "application/json",  "X-WP-Nonce": wpApiSettings.nonce },
            body: JSON.stringify({ first_name, last_name, contact, email, password, member_type, website })
        });

        const data = await res.json();
        if (!res.ok) {
            errorsEl.innerHTML = `<p>${data.message || "Lodge failed."}</p>`;
        } else {
            window.location.href = '/welcome';
        }
    } catch (err) {
        errorsEl.innerHTML = `<p>Something went wrong. Try again later.</p>`;
    }
});

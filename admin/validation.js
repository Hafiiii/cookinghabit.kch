function validateName(name) {
    // Only allow alphabets with optional spaces
    const regex = /^[a-zA-Z\s]+$/;
    return regex.test(name);
}

function validateEmail(email) {
    // Simple email validation (requires @)
    const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return regex.test(email);
}

function validatePassword(password) {
    // Password should be at least 8 characters, with at least one uppercase letter, one lowercase letter, one digit, and one special character
    const regex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*])[a-zA-Z\d!@#$%^&*]{8,}$/;
    return regex.test(password);
}

function validateForm() {
    const name = document.getElementById("name").value;
    const email = document.getElementById("email").value;
    const password = document.getElementById("password").value;
    const retypePassword = document.getElementById("retype_password").value;

    if (!validateName(name)) {
        alert("Invalid name. Only alphabets and spaces are allowed.");
        return false;
    }

    if (!validateEmail(email)) {
        alert("Invalid email address.");
        return false;
    }

    if (!validatePassword(password)) {
        alert("Invalid password. It should be at least 8 characters with a mix of alphanumeric and special characters.");
        return false;
    }

    if (password !== retypePassword) {
        alert("Password and Retype Password do not match.");
        return false;
    }

    return true;
}

function validateLoginForm() {
    const email = document.getElementById("email").value;
    const password = document.getElementById("password").value;

    if (!validateEmail(email)) {
        alert("Invalid email address.");
        return false;
    }

    if (!validatePassword(password)) {
        alert("Invalid password. It should be at least 8 characters with a mix of alphanumeric and special characters.");
        return false;
    }

    return true;
}

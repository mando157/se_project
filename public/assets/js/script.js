const signupForm = document.getElementById('signup-form');
const fullName = document.getElementById('fullName');
const password = document.getElementById('password');
const confirmPassword = document.getElementById('confirm-password');
const email = document.getElementById('email');
const roleRadios = document.getElementsByName('role');

signupForm.addEventListener('submit', (e) => {

    let hasError = false;

    document.querySelectorAll('.error-js').forEach(el => el.innerText = '');

    // role
    let selectedRole = '';
    roleRadios.forEach(r => {
        if (r.checked)
            selectedRole = r.value;
    });

    // Full Name
    if (!fullName.value.trim()) {
        showError(fullName, "Name is required");
        hasError = true;
    } else if (fullName.value.trim().length < 3) {
        showError(fullName, "At least 3 characters");
        hasError = true;
    }

    // Email
    const emailPattern = /^[^ ]+@[^ ]+\.[a-z]{2,3}$/;
    if (!email.value.trim()) {
        showError(email, "Email is required");
        hasError = true;
    } else if (!email.value.match(emailPattern)) {
        showError(email, "Invalid email");
        hasError = true;
    }

    // Password
    if (!password.value.trim()) {
        showError(password, "Password required");
        hasError = true;
    } else if (password.value.length < 8) {
        showError(password, "Min 8 characters");
        hasError = true;
    }

    // Confirm Password
    if (password.value !== confirmPassword.value) {
        showError(confirmPassword, "Passwords do not match");
        hasError = true;
    }

    // Role
    if (!selectedRole) {
        alert("Please select a role");
        hasError = true;
    }

    if (hasError) {
        e.preventDefault();
    }
});

function showError(input, message) {
    const parent = input.parentElement;
    let error = parent.querySelector('.error-js');

    if (!error) {
        error = document.createElement('div');
        error.classList.add('error-js');
        error.style.color = 'red';
        error.style.fontSize = '12px';
        parent.appendChild(error);
    }

    error.innerText = message;
}
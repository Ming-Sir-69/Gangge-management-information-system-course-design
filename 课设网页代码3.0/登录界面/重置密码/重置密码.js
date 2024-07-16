// 验证密码格式
function validatePassword() {
    const password = document.getElementById('password').value;
    const validityIndicator = document.getElementById('password-validity');
    const regex = /^[a-zA-Z0-9@&#]{6,15}$/;
    if (regex.test(password)) {
        validityIndicator.className = 'valid';
        validityIndicator.textContent = '✔️';
    } else {
        validityIndicator.className = 'invalid';
        validityIndicator.textContent = '❌';
    }
}

// 验证密码匹配
function confirmPasswordMatch() {
    const password = document.getElementById('password').value;
    const confirmPassword = document.getElementById('confirm_password').value;
    const validityIndicator = document.getElementById('confirm-password-validity');
    if (password === confirmPassword) {
        validityIndicator.className = 'valid';
        validityIndicator.textContent = '✔️';
    } else {
        validityIndicator.className = 'invalid';
        validityIndicator.textContent = '❌';
    }
}

function showHint() {
    document.getElementById('password-hint').style.display = 'block';
}

function hideHint() {
    document.getElementById('password-hint').style.display = 'none';
}

function togglePassword(fieldId) {
    const field = document.getElementById(fieldId);
    if (field.type === 'password') {
        field.type = 'text';
    } else {
        field.type = 'password';
    }
}

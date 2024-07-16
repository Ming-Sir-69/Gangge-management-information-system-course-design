// 验证密码格式
function validatePassword() {
    const password = document.getElementById('password').value; // 获取密码输入框的值
    const validityIndicator = document.getElementById('password-validity'); // 获取密码有效性指示器元素
    const regex = /^[a-zA-Z0-9@&#]{6,15}$/; // 密码格式的正则表达式
    if (regex.test(password)) { // 检查密码是否符合格式
        validityIndicator.className = 'valid'; // 设置有效性指示器的类名为'valid'
        validityIndicator.textContent = '✔️'; // 设置指示器内容为勾
    } else {
        validityIndicator.className = 'invalid'; // 设置有效性指示器的类名为'invalid'
        validityIndicator.textContent = '❌'; // 设置指示器内容为叉
    }
}

// 显示密码或隐藏
function togglePasswordVisibility() {
    var passwordField = document.getElementById("password");
    var passwordToggle = document.querySelector(".password-toggle");
    if (passwordField.type === "password") {
        passwordField.type = "text";
        passwordToggle.textContent = "👁️";
    } else {
        passwordField.type = "password";
        passwordToggle.textContent = "🙈";
    }
}

// 验证手机号格式
function validatePhone() {
    const phone = document.getElementById('phone').value; // 获取手机号输入框的值
    const phoneIndicator = document.getElementById('phone-validity'); // 获取手机号有效性指示器元素
    const regex = /^\d{11}$/; // 手机号格式的正则表达式
    if (regex.test(phone)) { // 检查手机号是否符合格式
        phoneIndicator.className = 'valid'; // 设置有效性指示器的类名为'valid'
        phoneIndicator.textContent = '✔️'; // 设置指示器内容为勾
    } else {
        phoneIndicator.className = 'invalid'; // 设置有效性指示器的类名为'invalid'
        phoneIndicator.textContent = '❌'; // 设置指示器内容为叉
    }
}

// 显示密码提示
function showHint() {
    document.getElementById('password-hint').style.display = 'block'; // 显示密码提示信息
}

// 隐藏密码提示
function hideHint() {
    document.getElementById('password-hint').style.display = 'none'; // 隐藏密码提示信息
}
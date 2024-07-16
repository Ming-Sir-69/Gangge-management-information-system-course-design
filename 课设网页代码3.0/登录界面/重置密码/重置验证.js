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

// 显示错误提示信息
function showPopup(message) {
    var popup = document.getElementById("popup");
    popup.textContent = message;
    popup.style.display = "block";
    setTimeout(function() {
        popup.style.display = "none";
    }, 3000); // 3秒后自动消失
}


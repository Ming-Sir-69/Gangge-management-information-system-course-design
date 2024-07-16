// 账户添加.js

function showAlert(errors) {
    let errorString = errors.join("\n");
    alert("发生以下错误：\n" + errorString);
}

function validateForm(event) {
    let errors = [];

    let cardNumber = document.getElementById('cardNumber').value.replace(/\s+/g, '');
    let cardExpiry = document.getElementById('cardExpiry').value;
    let securityCode = document.getElementById('security_code').value;

    // 验证卡号
    if (!/^\d{11,16}$/.test(cardNumber)) {
        errors.push("卡号必须为11到16位数字。");
    }
    
    // 验证有效期
    if (!/^\d{2}\/\d{2}$/.test(cardExpiry)) {
        errors.push("有效期格式不正确，必须为MM/YY。");
    } else {
        let parts = cardExpiry.split('/');
        let month = parts[0];
        let year = parts[1];
        if (!checkdate(month, 1, "20" + year)) {
            errors.push("有效期无效。");
        }
    }

    // 验证安全码
    if (!/^\d{3}$/.test(securityCode)) {
        errors.push("安全码必须为3位数字。");
    }

    if (errors.length > 0) {
        event.preventDefault();
        showAlert(errors);
    }
}

function formatCardNumber(event) {
    let input = event.target;
    let value = input.value.replace(/\s+/g, '').replace(/(\d{4})(?=\d)/g, '$1 ').trim();
    input.value = value;
}

function checkdate(month, day, year) {
    var date = new Date(year, month - 1, day);
    return date.getFullYear() === parseInt(year) && date.getMonth() + 1 === parseInt(month) && date.getDate() === parseInt(day);
}

document.getElementById('accountForm').addEventListener('submit', validateForm);
document.getElementById('cardNumber').addEventListener('input', function(event) {
    let input = event.target;
    let value = input.value.replace(/\s+/g, '').replace(/(\d{4})(?=\d)/g, '$1 ').trim();
    input.value = value;
});

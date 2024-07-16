function fetchBankAccounts(userId) {
    if (!userId) {
        document.getElementById('recipientAccount').innerHTML = '<option value="">请选择账号</option>';
        return;
    }
    var xhr = new XMLHttpRequest();
    xhr.open('POST', '获取账号.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
            document.getElementById('recipientAccount').innerHTML = xhr.responseText;
        }
    };
    xhr.send('userId=' + userId);
}

function fetchBankName(accountNumber) {
    if (!accountNumber) {
        document.getElementById('bank').value = '';
        return;
    }
    var xhr = new XMLHttpRequest();
    xhr.open('POST', '获取银行.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
            document.getElementById('bank').value = xhr.responseText;
        }
    };
    xhr.send('accountNumber=' + accountNumber);
}

document.getElementById('recipientAccount').addEventListener('change', function() {
    fetchBankName(this.value);
});

function fetchBalance(accountNumber) {
    if (!accountNumber) {
        document.getElementById('availableBalance').textContent = '可用余额 ￥0.00';
        return;
    }
    var xhr = new XMLHttpRequest();
    xhr.open('POST', '获取余额.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
            document.getElementById('availableBalance').textContent = '可用余额 ￥' + xhr.responseText;
        }
    };
    xhr.send('accountNumber=' + accountNumber);
}

document.getElementById('paymentCard').addEventListener('change', function() {
    fetchBalance(this.value);
});
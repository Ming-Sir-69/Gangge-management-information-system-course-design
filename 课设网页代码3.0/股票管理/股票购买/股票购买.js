function fetchBalance(accountNumber) {
    if (!accountNumber) {
        document.getElementById('availableBalance').textContent = '￥0.00';
        return;
    }
    var xhr = new XMLHttpRequest();
    xhr.open('POST', '获取余额.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
            document.getElementById('availableBalance').textContent = '￥' + xhr.responseText;
        }
    };
    xhr.send('accountNumber=' + accountNumber);
}

function calculateTotal() {
    var quantity = document.getElementById('quantity').value;
    var pricePerShare = document.querySelector('input[name="pricePerShare"]').value;
    var totalPrice = quantity * pricePerShare;
    document.getElementById('totalPrice').textContent = '￥' + totalPrice.toFixed(2);
}

function confirmPurchase() {
    document.getElementById('confirmModal').style.display = 'block';
}

function closeModal() {
    document.getElementById('confirmModal').style.display = 'none';
}

function submitPurchase() {
    var form = document.getElementById('purchaseForm');
    form.submit();
}

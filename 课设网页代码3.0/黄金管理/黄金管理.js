document.addEventListener('DOMContentLoaded', function() {
    const paymentCardSelect = document.getElementById('paymentCard');
    const availableBalanceSpan = document.getElementById('availableBalance');
    const quantityInput = document.getElementById('quantity');
    const totalPriceSpan = document.getElementById('totalPrice');

    // 获取余额
    paymentCardSelect.addEventListener('change', function() {
        const accountNumber = this.value;
        if (accountNumber) {
            fetchBalance(accountNumber);
        } else {
            availableBalanceSpan.textContent = '￥0.00';
        }
    });

    // 计算总价
    quantityInput.addEventListener('input', calculateTotal);

    // 获取余额的函数
    function fetchBalance(accountNumber) {
        const xhr = new XMLHttpRequest();
        xhr.open('POST', '获取余额.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4 && xhr.status === 200) {
                availableBalanceSpan.textContent = '￥' + xhr.responseText;
            }
        };
        xhr.send('accountNumber=' + encodeURIComponent(accountNumber));
    }

    // 计算总价的函数
    function calculateTotal() {
        const quantity = parseFloat(quantityInput.value);
        const pricePerGram = parseFloat(document.querySelector('input[name="pricePerGram"]').value);
        if (!isNaN(quantity) && !isNaN(pricePerGram)) {
            const totalPrice = (quantity * pricePerGram).toFixed(2);
            totalPriceSpan.textContent = '￥' + totalPrice;
        } else {
            totalPriceSpan.textContent = '￥0.00';
        }
    }
});

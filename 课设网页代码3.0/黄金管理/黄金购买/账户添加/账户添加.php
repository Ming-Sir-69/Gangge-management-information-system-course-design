<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <title>添加银行卡</title>
    <link rel="stylesheet" type="text/css" href="账户添加样式.css">
    <script src="账户添加.js"></script> 
</head>
<body>
    <div class="form-container">
        <h2>添加银行卡</h2>
        <form id="accountForm" action="账户添加处理.php" method="POST">
            <label for="bank_name">银行名称:</label>
            <input type="text" id="bank_name" name="bank_name" required>
            <br>
            <label for="cardNumber">卡号 (请输入您11-16位的卡号):</label>
            <input class="CheckoutInput CheckoutInput--tabularnums Input Input--empty" autocomplete="cc-number" autocorrect="off" spellcheck="false" id="cardNumber" name="cardNumber" type="text" inputmode="numeric" aria-label="卡号" placeholder="1234 1234 1234 1234" aria-invalid="false" data-1p-ignore="false" minlength="11" maxlength="19" required>
            <br>
            <label for="cardExpiry">有效期 (月/年) 最多4位:</label>
            <input class="CheckoutInput CheckoutInput--invalid CheckoutInput--tabularnums Input Input--empty" pattern="\d{4}" maxlength="4" autocomplete="cc-exp" autocorrect="off" spellcheck="false" id="cardExpiry" name="cardExpiry" type="text" inputmode="numeric" aria-label="有效期" placeholder="月份/年份" aria-invalid="true" data-1p-ignore="false" required>
            <br>
            <label for="security_code">安全码 (3位数字):</label>
            <input type="text" id="security_code" name="security_code" pattern="\d{3}" maxlength="3" required>
            <br>
            <label for="initial_balance">初始余额:</label>
            <input type="number" id="initial_balance" name="initial_balance" min="0.01" step="0.01" required>
            <br>
            <button type="submit">添加</button>
            <button type="button" onclick="window.location.href='../../黄金详情.php'">返回</button>
            </form>
    </div>
</body>
</html>

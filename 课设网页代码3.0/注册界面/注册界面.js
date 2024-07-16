// 切换密码显示/隐藏
function togglePassword(id) {
    const field = document.getElementById(id); // 获取密码输入框元素
    const type = field.getAttribute('type') === 'password' ? 'text' : 'password'; // 切换输入框类型
    field.setAttribute('type', type); // 设置输入框类型
}

// 显示密码提示
function showHint() {
    document.getElementById('password-hint').style.display = 'block'; // 显示密码提示信息
}

// 隐藏密码提示
function hideHint() {
    document.getElementById('password-hint').style.display = 'none'; // 隐藏密码提示信息
}

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

// 验证密码匹配
function confirmPasswordMatch() {
    const password = document.getElementById('password').value; // 获取密码输入框的值
    const confirmPassword = document.getElementById('confirm_password').value; // 获取确认密码输入框的值
    const validityIndicator = document.getElementById('confirm-password-validity'); // 获取确认密码有效性指示器元素
    if (password === confirmPassword) { // 检查两个密码是否匹配
        validityIndicator.className = 'valid'; // 设置有效性指示器的类名为'valid'
        validityIndicator.textContent = '✔️'; // 设置指示器内容为勾
    } else {
        validityIndicator.className = 'invalid'; // 设置有效性指示器的类名为'invalid'
        validityIndicator.textContent = '❌'; // 设置指示器内容为叉
    }
}

// 添加输入事件监听器以实时验证密码和确认密码
document.getElementById('password').addEventListener('input', validatePassword); // 密码输入框添加输入事件监听器
document.getElementById('confirm_password').addEventListener('input', confirmPasswordMatch); // 确认密码输入框添加输入事件监听器

// 城市选项
const cityOptions = {
    "天津": ["天津"],
    "日本": ["大阪"]
};

// 区县选项
const districtOptions = {
    "天津": ["滨海新区", "河西区", "南开区"],
    "大阪": ["中央区", "北区", "南区"]
};

// 更新城市选项
function updateCity() {
    const province = document.getElementById('province').value; // 获取省份选项的值
    const citySelect = document.getElementById('city'); // 获取城市选择元素
    citySelect.innerHTML = '<option value="" selected disabled>选择城市</option>'; // 重置城市选择框内容
    if (province in cityOptions) { // 检查省份是否在城市选项中
        cityOptions[province].forEach(city => { // 遍历城市选项
            const option = document.createElement('option'); // 创建新的选项元素
            option.value = city; // 设置选项值
            option.textContent = city; // 设置选项文本
            citySelect.appendChild(option); // 将选项添加到城市选择框中
        });
    }
    updateDistrict(); // 更新区县选项
}

// 更新区县选项
function updateDistrict() {
    const city = document.getElementById('city').value; // 获取城市选项的值
    const districtSelect = document.getElementById('district'); // 获取区县选择元素
    districtSelect.innerHTML = '<option value="" selected disabled>选择区县</option>'; // 重置区县选择框内容
    if (city in districtOptions) { // 检查城市是否在区县选项中
        districtOptions[city].forEach(district => { // 遍历区县选项
            const option = document.createElement('option'); // 创建新的选项元素
            option.value = district; // 设置选项值
            option.textContent = district; // 设置选项文本
            districtSelect.appendChild(option); // 将选项添加到区县选择框中
        });
    }
}

// 选择头像
function selectAvatar(avatarId) {
    console.log("Selecting avatar ID: " + avatarId); // 调试日志
    document.getElementById('avatar').value = avatarId; // 设置隐藏输入框的头像ID值
    const images = document.querySelectorAll('.avatar-container img'); // 获取所有头像图片元素
    images.forEach(img => img.classList.remove('selected')); // 移除所有头像的选中样式
    images[avatarId - 1].classList.add('selected'); // 为选中的头像添加选中样式
    document.getElementById('preview').innerHTML = ''; // 清空预览区
}

// 触发文件上传
function triggerFileUpload() {
    document.getElementById('custom_avatar').click(); // 模拟点击文件输入框以触发文件选择窗口
}

// 预览上传的图片
function previewImage(event) {
    if (event.target.files.length > 0) { // 检查是否有文件被选择
        var file = event.target.files[0]; // 获取选中的文件
        var reader = new FileReader(); // 创建FileReader对象
        reader.onload = function(e) { // 定义文件加载完成时的回调函数
            var img = document.createElement('img'); // 创建新的图像元素
            img.src = e.target.result; // 设置图像源为文件的内容
            document.getElementById('preview').innerHTML = ''; // 清空预览区
            document.getElementById('preview').appendChild(img); // 将图像添加到预览区
            document.getElementById('avatar').value = 'custom'; // 设置隐藏输入框的值为'custom'
            var images = document.querySelectorAll('.avatar-container img'); // 获取所有头像图片元素
            images.forEach(img => img.classList.remove('selected')); // 移除所有头像的选中样式
        };
        reader.readAsDataURL(file); // 读取上传的文件
    }
}


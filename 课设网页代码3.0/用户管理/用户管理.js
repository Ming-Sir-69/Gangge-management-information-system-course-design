function viewFullImage(src) {
    // 查看全屏图片
    var overlay = document.getElementById('fullscreen-overlay'); // 获取全屏覆盖层
    var fullImage = document.getElementById('fullscreen-image'); // 获取全屏显示的图片元素
    fullImage.src = src; // 设置全屏图片的来源
    overlay.style.display = 'flex'; // 显示全屏覆盖层
}

function closeFullImage() {
    // 关闭全屏图片
    var overlay = document.getElementById('fullscreen-overlay'); // 获取全屏覆盖层
    overlay.style.display = 'none'; // 隐藏全屏覆盖层
}

function logout() {
    // 注销并重定向到登录界面
    window.location.href = '../登录界面/登录/登录界面.php'; // 重定向到登录界面
}

document.addEventListener('DOMContentLoaded', function() {
    // 初始化页面，设置为文本显示模式
    initPage();

    // 监听编辑按钮的点击事件
    var editButton = document.getElementById('editButton');
    if (editButton) {
        editButton.addEventListener('click', function() {
            switchToEditMode();
        });
    }

    // 监听保存按钮的点击事件
    var saveButton = document.getElementById('saveButton');
    if (saveButton) {
        saveButton.addEventListener('click', function() {
            if (validateForm()) {
                saveChanges();
            } else {
                showError('请检查所有必填项是否填写正确');
            }
        });
    }

    // 监听文件选择按钮的变化事件
    var avatarInput = document.getElementById('avatarInput');
    if (avatarInput) {
        avatarInput.addEventListener('change', function() {
            previewAvatar(this.files[0]);
        });
    }

    // 为地区输入框添加事件监听器
    var locationInput = document.getElementById('location');
    if (locationInput) {
        locationInput.addEventListener('focus', showLocationHint);
        locationInput.addEventListener('blur', hideLocationHint);
    }
});

function initPage() {
    // 初始化页面内容为文本显示模式
    var userDetails = document.querySelectorAll('.user-details');
    userDetails.forEach(function(detail) {
        detail.setAttribute('data-original', detail.textContent); // 保存原始文本内容
    });
}

function switchToEditMode() {
    var userDetails = document.querySelectorAll('.user-details');
    userDetails.forEach(function(detail) {
        var inputType = detail.getAttribute('data-input-type') || 'text';
        var input = document.createElement('input');
        input.type = inputType;
        input.value = detail.getAttribute('data-original');
        detail.textContent = '';
        detail.appendChild(input);
    });
    var editButton = document.getElementById('editButton');
    var saveButton = document.getElementById('saveButton');
    if (editButton) editButton.style.display = 'none';
    if (saveButton) saveButton.style.display = 'block';
}

function validateForm() {
    var valid = true;
    var inputs = document.querySelectorAll('.user-details input');
    inputs.forEach(function(input) {
        if (input.value.trim() === '') {
            valid = false;
        }
    });
    return valid;
}

function saveChanges() {
    var userDetails = document.querySelectorAll('.user-details');
    userDetails.forEach(function(detail) {
        var input = detail.querySelector('input');
        if (input) {
            detail.setAttribute('data-original', input.value);
            detail.textContent = input.value;
        }
    });
    var editButton = document.getElementById('editButton');
    var saveButton = document.getElementById('saveButton');
    if (editButton) editButton.style.display = 'block';
    if (saveButton) saveButton.style.display = 'none';
}

function showError(message) {
    alert(message);
}

function triggerFileUpload() {
    document.getElementById('avatarInput').click();
}

function previewAvatar(file) {
    var reader = new FileReader();
    reader.onload = function(e) {
        var avatarImg = document.querySelector('.user-info img.avatar');
        avatarImg.src = e.target.result;
    };
    reader.readAsDataURL(file);
}

function showLocationHint() {
    document.getElementById('location-hint').style.display = 'block'; // 显示地区提示信息
}

function hideLocationHint() {
    document.getElementById('location-hint').style.display = 'none'; // 隐藏地区提示信息
}

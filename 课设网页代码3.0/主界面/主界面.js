function logout() {
    window.location.href = '退出处理.php';
}

function viewFullImage(imageSrc) {
    const overlay = document.getElementById('fullscreen-overlay');
    const fullImage = document.getElementById('fullscreen-image');
    fullImage.src = imageSrc;
    overlay.style.display = 'flex';
}

function closeFullImage() {
    const overlay = document.getElementById('fullscreen-overlay');
    overlay.style.display = 'none';
}

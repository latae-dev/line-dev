function getFontSize(size) {
    const sizes = {
        'xs': '12px',
        'sm': '14px',
        'md': '16px',
        'lg': '18px',
        'xl': '20px',
        'xxl': '24px'
    };
    return sizes[size] || '16px';
}

// Event Listeners สำหรับการควบคุม Preview
document.addEventListener('DOMContentLoaded', function() {
    const preview = document.getElementById('livePreview');
    const toggleBtn = document.getElementById('togglePreview');
    const showPreviewBtn = document.getElementById('showPreviewBtn');
    const hideText = toggleBtn.querySelector('.hide-preview-text');
    const showText = toggleBtn.querySelector('.show-preview-text');

    // โหลดสถานะจาก localStorage
    const isPreviewHidden = localStorage.getItem('previewHidden') === 'true';
    
    // ตั้งค่าสถานะเริ่มต้น
    if (isPreviewHidden) {
        preview.classList.add('hidden-preview');
        hideText.style.display = 'none';
        showText.style.display = 'block';
        showPreviewBtn.style.display = 'block';
    }

    // จัดการการคลิกปุ่มซ่อน
    toggleBtn.addEventListener('click', function() {
        preview.classList.add('hidden-preview');
        hideText.style.display = 'none';
        showText.style.display = 'block';
        showPreviewBtn.style.display = 'block';
        localStorage.setItem('previewHidden', 'true');
    });

    // จัดการการคลิกปุ่มแสดง
    showPreviewBtn.addEventListener('click', function() {
        preview.classList.remove('hidden-preview');
        hideText.style.display = 'block';
        showText.style.display = 'none';
        showPreviewBtn.style.display = 'none';
        localStorage.setItem('previewHidden', 'false');
        updatePreview();
    });
});
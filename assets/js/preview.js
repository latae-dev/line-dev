function updatePreview() {
    // ตรวจสอบว่า preview กำลังถูกซ่อนอยู่หรือไม่
    const preview = document.getElementById('livePreview');
    if (preview.classList.contains('hidden-preview')) {
        return;
    }

    showLoading();
    setTimeout(() => {
        const form = document.getElementById('reportForm');
        const formData = new FormData(form);
        const preview = document.getElementById('livePreview');

        // อัพเดทขนาดฟอร์ม
        preview.className = '';
        preview.id = 'livePreview';
        preview.classList.add(formData.get('form_size'));

        // อัพเดทส่วนหัว
        const previewHeader = document.querySelector('.preview-header');
        const previewHeaderTitle = document.getElementById('previewHeaderTitle');
        const previewLogo = document.getElementById('previewLogo');
        
        previewHeader.style.backgroundColor = formData.get('header_bg_color');
        previewHeaderTitle.textContent = formData.get('header_title') || 'สำหรับผู้บริหารระดับสูง';
        previewHeaderTitle.style.fontSize = getFontSize(formData.get('header_title_size'));
        previewHeaderTitle.style.color = formData.get('header_title_color') || '#FFFFFF';
        previewLogo.src = formData.get('header_logo') || 'https://img5.pic.in.th/file/secure-sv1/JIB_white_logo.png';

        // อัพเดทวันที่
        const previewDateTime = document.getElementById('previewDateTime');
        previewDateTime.textContent = formData.get('datetime') || new Date().toLocaleString('th-TH');

        // อัพเดทส่วนเนื้อหา
        const previewItems = document.getElementById('previewItems');
        previewItems.innerHTML = '';
        
        const labels = formData.getAll('data_labels[]');
        const values = formData.getAll('data_values[]');
        const units = formData.getAll('data_units[]');
        const textColor = formData.get('body_text_color') || '#262262';
        const labelSize = formData.get('body_label_size') || 'sm';
        const valueSize = formData.get('body_value_size') || 'lg';

        if (labels.length === 0 || (labels.length === 1 && labels[0] === '')) {
            const sampleData = [
                { label: 'ยอดขายทั้งหมด', value: '0', unit: 'บาท' },
                { label: 'ยอดขายออนไลน์', value: '0', unit: 'THB' },
                { label: 'จำนวนบิลทั้งหมด', value: '0', unit: 'Bills' },
                { label: 'จำนวนสาขาทั้งหมด', value: '0', unit: 'Branches' }
            ];

            sampleData.forEach(item => {
                const div = document.createElement('div');
                div.className = 'preview-item';
                div.innerHTML = `
                    <div class="preview-item-label" style="font-size: ${getFontSize(labelSize)}; color: ${textColor}">${item.label}</div>
                    <div class="preview-item-value" style="font-size: ${getFontSize(valueSize)}; color: ${textColor}">${item.value} ${item.unit}</div>
                `;
                previewItems.appendChild(div);
            });
        } else {
            labels.forEach((label, index) => {
                if (label || values[index]) {
                    const formattedValue = values[index] ? Number(values[index]).toLocaleString() : '0';
                    const unit = units[index] || '';
                    const div = document.createElement('div');
                    div.className = 'preview-item';
                    div.innerHTML = `
                        <div class="preview-item-label" style="font-size: ${getFontSize(labelSize)}; color: ${textColor}">${label}</div>
                        <div class="preview-item-value" style="font-size: ${getFontSize(valueSize)}; color: ${textColor}">${formattedValue} ${unit}</div>
                    `;
                    previewItems.appendChild(div);
                }
            });
        }

        // อัพเดทปุ่ม
        const previewButton = document.getElementById('previewButton');
        previewButton.textContent = formData.get('footer_button_label') || 'ดูรายละเอียดเพิ่ม';
        previewButton.style.backgroundColor = formData.get('footer_button_color');
        previewButton.href = formData.get('footer_button_url') || '#';

        // อัพเดทชื่อรายงาน สี และขนาด
        const previewBodyTitle = document.querySelector('.preview-body-title');
        previewBodyTitle.textContent = formData.get('body_title') || 'รายงานยอดประจำวัน';
        previewBodyTitle.style.color = formData.get('body_title_color') || '#262262';
        previewBodyTitle.style.fontSize = getFontSize(formData.get('body_title_size'));
        hideLoading();
    }, 300);
}

function showLoading() {
    const loader = document.createElement('div');
    loader.className = 'loading-indicator';
    document.body.appendChild(loader);
}

function hideLoading() {
    const loader = document.querySelector('.loading-indicator');
    if (loader) {
        loader.remove();
    }
}
function addDataInput() {
    var container = document.getElementById('dataInputs');
    var newInput = document.createElement('div');
    newInput.className = 'grid grid-cols-1 md:grid-cols-2 gap-4 border p-4 rounded';
    newInput.innerHTML = `
        <div class="mb-4">
            <label class="form-label">ชื่อรายการ:</label>
            <input type="text" name="data_labels[]" class="form-control shadow-sm" required>
        </div>
        <div class="mb-4">
            <label class="form-label">ค่า:</label>
            <div class="input-group">
                <input type="text" name="data_values[]" class="form-control shadow-sm" required>
                <input type="text" name="data_units[]" class="form-control shadow-sm" placeholder="หน่วย">
                <button type="button" class="btn btn-danger shadow-sm" onclick="this.closest('.grid').remove(); updatePreview();">ลบ</button>
            </div>
        </div>
    `;
    container.appendChild(newInput);
    updatePreview();
}

function removeDataInput(element) {
    element.closest('.grid').remove();
    updatePreview();
}

function addUserIdInput() {
    var container = document.getElementById('userIdInputs');
    var newInput = document.createElement('div');
    newInput.className = 'input-group mb-2';
    newInput.innerHTML = `
        <input type="text" name="user_ids[]" class="form-control shadow-sm" placeholder="กรอก User ID">
        <button type="button" class="btn btn-danger shadow-sm" onclick="this.parentElement.remove()">-</button>
    `;
    container.appendChild(newInput);
    updatePreview();
}

// Event Listeners สำหรับฟอร์ม
document.getElementById('templateSelect').addEventListener('change', function() {
    var userIdSection = document.getElementById('userIdSection');
    var template1Preview = document.getElementById('template1Preview');
    var template2Preview = document.getElementById('template2Preview');
    
    if(this.value === 'form_userid.php') {
        userIdSection.style.display = 'block';
        if(template1Preview && template2Preview) {
            template1Preview.classList.remove('active');
            template2Preview.classList.add('active');
        }
    } else {
        userIdSection.style.display = 'none';
        if(template1Preview && template2Preview) {
            template1Preview.classList.add('active');
            template2Preview.classList.remove('active');
        }
    }
    updatePreview();
});

// เพิ่ม Event Listeners สำหรับการอัพเดท Preview แบบ Real-time
document.querySelectorAll('input, select').forEach(element => {
    element.addEventListener('input', updatePreview);
    element.addEventListener('change', updatePreview);
});

// โหลดข้อมูลเริ่มต้นเมื่อโหลดหน้า
window.onload = function() {
    // เพิ่มข้อมูลยอดขายทั้งหมด
    addDataInput();
    var inputs = document.getElementById('dataInputs').lastElementChild;
    inputs.querySelector('[name="data_labels[]"]').value = 'ยอดขายทั้งหมด';
    inputs.querySelector('[name="data_values[]"]').value = '';
    inputs.querySelector('[name="data_units[]"]').value = 'บาท';

    // เพิ่มข้อมูลยอดขายออนไลน์
    addDataInput();
    inputs = document.getElementById('dataInputs').lastElementChild;
    inputs.querySelector('[name="data_labels[]"]').value = 'ยอดขายออนไลน์';
    inputs.querySelector('[name="data_units[]"]').value = 'THB';

    // เพิ่มข้อมูลจำนวนบิลทั้งหมด
    addDataInput();
    inputs = document.getElementById('dataInputs').lastElementChild;
    inputs.querySelector('[name="data_labels[]"]').value = 'จำนวนบิลทั้งหมด';
    inputs.querySelector('[name="data_units[]"]').value = 'Bills';

    // เพิ่มข้อมูลจำนวนสาขาทั้งหมด
    addDataInput();
    inputs = document.getElementById('dataInputs').lastElementChild;
    inputs.querySelector('[name="data_labels[]"]').value = 'จำนวนสาขาทั้งหมด';
    inputs.querySelector('[name="data_units[]"]').value = 'Branches';

    // อัพเดท Preview ครั้งแรก
    updatePreview();
};
<?php
require_once 'config/config.php';
require_once 'handlers/form-handler.php';
require_once 'includes/header.php';
?>

<div class="container max-w-5xl mx-auto mt-12 p-6">
    <div class="bg-white rounded-xl shadow-2xl p-8 border border-gray-100">
        <!-- Header with gradient background -->
        <div class="relative mb-8 p-6 bg-gradient-to-r from-blue-600 to-blue-800 rounded-lg">
            <h2 class="text-3xl font-bold text-white mb-2 text-center">กรอกข้อมูลรายงานยอดประจำวัน</h2>
            <p class="text-blue-100 text-center">ระบบจัดการรายงานอัจฉริยะ สำหรับองค์กรชั้นนำ</p>
        </div>
        
        <form method="POST" action="" class="space-y-8" id="reportForm">
            <!-- Template Selection with icon -->
            <div class="bg-gray-50 rounded-lg p-6 shadow-sm border border-gray-200 hover:border-blue-300 transition-all">
                <div class="flex items-center mb-3">
                    <svg class="w-5 h-5 text-blue-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <label class="form-label font-semibold text-gray-700">เลือกรูปแบบการส่ง:</label>
                </div>
                <select name="template" class="form-select shadow-sm w-full focus:ring-2 focus:ring-blue-300 transition-all" id="templateSelect">
                    <option value="form_group.php">📊 ส่งแบบทั้งกรุ๊ป</option>
                    <option value="form_userid.php">👤 ส่งแบบรายบุคคล</option>
                </select>
            </div>

            <!-- Section headers with better styling -->
            <div class="bg-gray-50 rounded-lg p-6 shadow-sm border border-gray-200 hover:border-blue-300 transition-all">
                <div class="flex items-center mb-6">
                    <div class="bg-blue-100 p-2 rounded-lg mr-3">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-blue-600">ตั้งค่าส่วนหัว</h3>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="bg-white p-5 rounded-lg shadow-sm hover:shadow-md transition-all">
                        <label class="form-label font-medium text-gray-700 block mb-2">หัวข้อส่วนหัว:</label>
                        <input type="text" name="header_title" class="form-control focus:ring-2 focus:ring-blue-300" value="<?php echo DEFAULT_HEADER_TITLE; ?>">
                    </div>
                    <div>
                        <label class="form-label">URL โลโก้:</label>
                        <input type="text" name="header_logo" class="form-control" value="<?php echo DEFAULT_LOGO_URL; ?>">
                    </div>
                    <div>
                        <label class="form-label">สีพื้นหลังส่วนหัว:</label>
                        <input type="color" name="header_bg_color" class="form-control w-48 h-8 p-1 cursor-pointer" value="<?php echo DEFAULT_BG_COLOR; ?>">
                    </div>
                    <div>
                        <label class="form-label">สีตัวอักษรส่วนหัว:</label>
                        <input type="color" name="header_title_color" class="form-control w-48 h-8 p-1 cursor-pointer" value="<?php echo DEFAULT_TEXT_COLOR; ?>">
                    </div>
                    <div>
                        <label class="form-label">ขนาดตัวอักษรส่วนหัว:</label>
                        <select name="header_title_size" class="form-select">
                            <option value="xs">XS</option>
                            <option value="sm">SM</option>
                            <option value="md" selected>MD</option>
                            <option value="lg">LG</option>
                            <option value="xl">XL</option>
                            <option value="xxl">XXL</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- ส่วน User IDs สำหรับ Template 2 -->
            <div id="userIdSection" style="display:none;" class="bg-gray-50 rounded-lg p-6 shadow-sm border border-gray-200">
                <h3 class="text-xl font-semibold mb-4 text-blue-600">ตั้งค่า User IDs</h3>
                <div id="userIdInputs" class="space-y-2">
                    <div class="input-group">
                        <input type="text" name="user_ids[]" class="form-control shadow-sm" placeholder="กรอก User ID">
                        <button type="button" class="btn btn-success shadow-sm" onclick="addUserIdInput()">+</button>
                    </div>
                </div>
            </div>

            <!-- ส่วนขนาด Form -->
            <div class="bg-gray-50 rounded-lg p-6 shadow-sm border border-gray-200">
                <h3 class="text-xl font-semibold mb-4 text-blue-600">ขนาด Form</h3>
                <div>
                    <label class="form-label">เลือกขนาด Form:</label>
                    <select name="form_size" class="form-select">
                        <option value="default">Default</option>
                        <option value="nano">Nano</option>
                        <option value="micro">Micro</option>
                        <option value="kilo">Kilo</option>
                        <option value="mega">Mega</option>
                        <option value="giga" selected>Giga</option>
                    </select>
                </div>
            </div>

            <!-- ส่วนข้อมูลหลัก -->
            <div class="bg-gray-50 rounded-lg p-6 shadow-sm border border-gray-200">
                <h3 class="text-xl font-semibold mb-4 text-blue-600">ข้อมูลหลัก</h3>
                <div class="space-y-6">
                    <div class="bg-white p-4 rounded-lg shadow-sm">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="form-label">ชื่อรายงาน:</label>
                                <input type="text" name="body_title" class="form-control" value="<?php echo DEFAULT_BODY_TITLE; ?>">
                            </div>
                            <div>
                                <label class="form-label">สีชื่อรายงาน:</label>
                                <input type="color" name="body_title_color" class="form-control w-48 h-8 p-1 cursor-pointer" value="<?php echo DEFAULT_BODY_TITLE_COLOR; ?>">
                            </div>
                            <div>
                                <label class="form-label">ขนาดชื่อรายงาน:</label>
                                <select name="body_title_size" class="form-select">
                                    <option value="lg">LG</option>
                                    <option value="xl">XL</option>
                                    <option value="xxl" selected>XXL</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div id="dataInputs" class="space-y-4">
                        <!-- ข้อมูลจะถูกเพิ่มที่นี่ด้วย JavaScript -->
                    </div>

                    <button type="button" class="btn btn-success mt-4" onclick="addDataInput()">
                        เพิ่มข้อมูล
                    </button>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
                        <div>
                            <label class="form-label">สีตัวอักษรเนื้อหา:</label>
                            <input type="color" name="body_text_color" class="form-control w-48 h-8 p-1 cursor-pointer" value="<?php echo DEFAULT_BODY_TEXT_COLOR; ?>">
                        </div>

                        <div>
                            <label class="form-label">ขนาดป้ายกำกับ:</label>
                            <select name="body_label_size" class="form-select">
                                <option value="xs">XS</option>
                                <option value="sm" selected>SM</option>
                                <option value="md">MD</option>
                                <option value="lg">LG</option>
                            </select>
                        </div>

                        <div>
                            <label class="form-label">ขนาดค่าตัวเลข:</label>
                            <select name="body_value_size" class="form-select">
                                <option value="sm">SM</option>
                                <option value="md">MD</option>
                                <option value="lg" selected>LG</option>
                                <option value="xl">XL</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ส่วนการตั้งค่า Footer -->
            <div class="bg-gray-50 rounded-lg p-6 shadow-sm border border-gray-200">
                <h3 class="text-xl font-semibold mb-4 text-blue-600">ตั้งค่าส่วนท้าย</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="form-label">ข้อความปุ่ม:</label>
                        <input type="text" name="footer_button_label" class="form-control" value="<?php echo DEFAULT_FOOTER_BUTTON_LABEL; ?>">
                    </div>
                    <div>
                        <label class="form-label">URL ปุ่ม:</label>
                        <input type="text" name="footer_button_url" class="form-control" value="<?php echo DEFAULT_FOOTER_BUTTON_URL; ?>">
                    </div>
                    <div>
                        <label class="form-label">สีปุ่ม:</label>
                        <input type="color" name="footer_button_color" class="form-control w-48 h-8 p-1 cursor-pointer" value="<?php echo DEFAULT_FOOTER_BUTTON_COLOR; ?>">
                    </div>
                </div>
            </div>

            <!-- ส่วนวันที่และเวลา -->
            <div class="bg-gray-50 rounded-lg p-6 shadow-sm border border-gray-200">
                <div class="mb-4">
                    <label class="form-label font-medium text-gray-700 block mb-2">วันที่และเวลา:</label>
                    <input type="text" name="datetime" class="form-control focus:ring-2 focus:ring-blue-300" 
                           value="<?php echo date('j M y H:i', strtotime('+543 years')); ?> น." required>
                </div>
            </div>

            <!-- Enhanced submit button -->
            <button type="submit" class="btn w-full py-4 text-lg font-semibold shadow-lg transition duration-300 ease-in-out transform hover:scale-102 hover:shadow-xl bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-lg flex items-center justify-center space-x-2" onclick="return confirmSubmit(event)">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
                <span>บันทึกและสร้างรายงาน</span>
            </button>
        </form>
    </div>
    
    <!-- Add professional footer -->
    <div class="text-center mt-8 text-gray-600">
        <p>© 2024 ระบบรายงานอัจฉริยะ. สงวนลิขสิทธิ์.</p>
    </div>
</div>

<script>
function confirmSubmit(event) {
    event.preventDefault();
    Swal.fire({
        title: 'ยืนยันการส่งข้อมูล',
        text: 'คุณต้องการบันทึกและสร้างรายงานใช่หรือไม่?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'ยืนยัน',
        cancelButtonText: 'ยกเลิก',
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('reportForm').submit();
        }
    });
    return false;
}
</script>

<?php 
require_once 'includes/preview-template.php';
require_once 'includes/footer.php';
?>
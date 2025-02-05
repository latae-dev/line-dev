<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $template = $_POST['template'];
    
    // เก็บข้อมูลส่วนหัว
    $header = [
        'title' => $_POST['header_title'] ?? 'สำหรับผู้บริหารระดับสูง', 
        'logo_url' => $_POST['header_logo'] ?? 'https://img5.pic.in.th/file/secure-sv1/JIB_white_logo.png',
        'bg_color' => $_POST['header_bg_color'] ?? '#262262',
        'title_size' => $_POST['header_title_size'] ?? 'md',
        'title_color' => $_POST['header_title_color'] ?? '#FFFFFF'
    ];

    // เก็บข้อมูลส่วนเนื้อหา
    $body = [
        'title' => $_POST['body_title'] ?? 'รายงานยอดประจำวัน',
        'title_size' => $_POST['body_title_size'] ?? 'xxl',
        'title_color' => $_POST['body_title_color'] ?? '#262262',
        'datetime' => $_POST['datetime'],
        'text_color' => $_POST['body_text_color'] ?? '#262262',
        'label_size' => $_POST['body_label_size'] ?? 'sm',
        'value_size' => $_POST['body_value_size'] ?? 'lg',
        'size' => $_POST['form_size'] ?? 'giga'
    ];

    // เก็บข้อมูลหลักและข้อมูลเพิ่มเติม
    if(isset($_POST['data_labels']) && isset($_POST['data_values']) && isset($_POST['data_units'])) {
        $body['items'] = [];
        for($i = 0; $i < count($_POST['data_labels']); $i++) {
            if(!empty($_POST['data_labels'][$i]) && !empty($_POST['data_values'][$i])) {
                $formatted_value = number_format($_POST['data_values'][$i], 0, '.', ',');
                $body['items'][] = [
                    'label' => $_POST['data_labels'][$i],
                    'value' => $formatted_value . ' ' . $_POST['data_units'][$i]
                ];
            }
        }
    }

    // เก็บข้อมูลส่วน footer
    $footer = [
        'button_label' => $_POST['footer_button_label'] ?? 'ดูรายละเอียดเพิ่ม',
        'button_url' => $_POST['footer_button_url'] ?? 'https://linecorp.com',
        'button_color' => $_POST['footer_button_color'] ?? '#262262',
        'button_text_size' => $_POST['footer_button_text_size'] ?? 'md'
    ];
    
    $data = [
        'header' => $header,
        'body' => $body,
        'footer' => $footer
    ];

    if($template == 'form_userid.php' && isset($_POST['user_ids'])) {
        $data['user_ids'] = $_POST['user_ids'];
    }
    
    $_SESSION['line_data'] = $data;
    header("Location: " . $template);
    exit();
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>กรอกข้อมูลรายงาน</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            background-color: #f8f9fa;
        }
        .template-preview {
            border: 1px solid #e9ecef;
            padding: 15px;
            margin-top: 15px;
            border-radius: 8px;
            display: none;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
            background: white;
        }
        .template-preview img {
            max-width: 100%;
            height: auto;
            border-radius: 6px;
        }
        .template-preview.active {
            display: block;
            animation: fadeIn 0.3s ease-in;
        }
        .form-control:focus {
            border-color: #4f46e5;
            box-shadow: 0 0 0 0.2rem rgba(79, 70, 229, 0.25);
        }
        .btn-primary {
            background-color: #4f46e5;
            border-color: #4f46e5;
        }
        .btn-primary:hover {
            background-color: #4338ca;
            border-color: #4338ca;
        }
        .btn-success {
            background-color: #10b981;
            border-color: #10b981;
        }
        .btn-success:hover {
            background-color: #059669;
            border-color: #059669;
        }
        .btn-danger {
            background-color: #ef4444;
            border-color: #ef4444;
        }
        .btn-danger:hover {
            background-color: #dc2626;
            border-color: #dc2626;
        }
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        /* อัพเดทส่วน Live Preview */
        #livePreview {
            position: fixed;
            top: 20px;
            right: 20px;
            width: 340px;
            background: white;
            border-radius: 16px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            padding: 0;
            z-index: 1000;
            overflow: hidden;
        }

        .preview-header {
            background-color: #262262;
            padding: 16px 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .preview-header img {
            height: 24px;
            object-fit: contain;
        }

        .preview-header h3 {
            margin: 0;
            color: white;
            font-size: 16px;
            font-weight: 400;
        }

        .preview-body {
            padding: 16px 20px;
        }

        .preview-body-title {
            font-size: 20px;
            font-weight: 600;
            color: #262262;
            margin-bottom: 4px;
        }

        .preview-body-date {
            font-size: 14px;
            color: #666;
            margin-bottom: 16px;
        }

        .preview-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 12px;
        }

        .preview-item-label {
            color: #666;
            font-size: 14px;
        }

        .preview-item-value {
            color: #262262;
            font-weight: 500;
            font-size: 14px;
        }

        .preview-footer {
            padding: 16px 20px;
        }

        .preview-button {
            display: block;
            width: 100%;
            padding: 12px;
            background: #262262;
            color: white;
            text-align: center;
            border-radius: 8px;
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
        }

        /* เพิ่ม CSS สำหรับควบคุมขนาด Preview */
        #livePreview.default { width: 300px; }
        #livePreview.nano { width: 200px; }
        #livePreview.micro { width: 250px; }
        #livePreview.kilo { width: 300px; }
        #livePreview.mega { width: 350px; }
        #livePreview.giga { width: 400px; }

        /* เพิ่มสไตล์ใหม่ */
        .form-section {
            background: #ffffff;
            border-radius: 1rem;
            padding: 2rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
        }

        .form-section:hover {
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            transform: translateY(-2px);
        }

        .section-header {
            display: flex;
            align-items: center;
            margin-bottom: 1.5rem;
            padding-bottom: 0.75rem;
            border-bottom: 2px solid #e5e7eb;
        }

        .section-icon {
            width: 2rem;
            height: 2rem;
            margin-right: 0.75rem;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #4f46e5;
            color: white;
            border-radius: 0.5rem;
        }

        /* ปรับแต่ง Input fields */
        .form-control, .form-select {
            border: 1px solid #e5e7eb;
            border-radius: 0.5rem;
            padding: 0.75rem;
            font-size: 0.95rem;
            transition: all 0.2s;
            background-color: #f9fafb;
        }

        .form-control:focus, .form-select:focus {
            background-color: #ffffff;
            border-color: #6366f1;
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.2);
        }

        /* ปรับแต่ง Labels */
        .form-label {
            font-weight: 500;
            color: #374151;
            margin-bottom: 0.5rem;
            font-size: 0.95rem;
        }

        /* ปรับแต่ง Buttons */
        .btn {
            font-weight: 500;
            padding: 0.75rem 1.5rem;
            border-radius: 0.5rem;
            transition: all 0.2s;
        }

        .btn-primary {
            background: linear-gradient(135deg, #4f46e5, #6366f1);
            border: none;
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #4338ca, #4f46e5);
            transform: translateY(-1px);
        }

        .btn-success {
            background: linear-gradient(135deg, #10b981, #059669);
            border: none;
        }

        .btn-danger {
            background: linear-gradient(135deg, #ef4444, #dc2626);
            border: none;
        }

        /* ปรับแต่ง Live Preview */
        #livePreview {
            background: #ffffff;
            border-radius: 1rem;
            overflow: hidden;
            transition: all 0.3s ease;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }

        #livePreview:hover {
            box-shadow: 0 8px 12px rgba(0,0,0,0.15);
        }

        .preview-header {
            background: linear-gradient(135deg, #262262, #1a174d);
        }

        /* ปรับแต่ง Color inputs */
        input[type="color"] {
            height: 2.5rem;
            padding: 0.25rem;
            border-radius: 0.5rem;
            cursor: pointer;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .form-section {
                padding: 1.5rem;
            }

            .btn {
                width: 100%;
                margin-bottom: 0.5rem;
            }
        }

        /* Loading animation */
        .loading-indicator {
            width: 100%;
            height: 3px;
            background: linear-gradient(90deg, #4f46e5 0%, #6366f1 100%);
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1000;
            animation: loading 2s infinite;
        }

        @keyframes loading {
            0% { transform: translateX(-100%); }
            100% { transform: translateX(100%); }
        }

        /* Tooltip styles */
        .tooltip {
            position: relative;
            display: inline-block;
        }

        .tooltip .tooltiptext {
            visibility: hidden;
            background-color: #374151;
            color: #ffffff;
            text-align: center;
            border-radius: 0.5rem;
            padding: 0.5rem 1rem;
            position: absolute;
            z-index: 1;
            bottom: 125%;
            left: 50%;
            transform: translateX(-50%);
            opacity: 0;
            transition: opacity 0.3s;
        }

        .tooltip:hover .tooltiptext {
            visibility: visible;
            opacity: 1;
        }

        /* เพิ่ม Style สำหรับการเลื่อน Preview */
        #livePreview {
            transform: translateX(0);
        }

        #livePreview.hidden-preview {
            transform: translateX(calc(100% + 20px));
        }

        /* ปรับแต่งปุ่มควบคุม */
        #togglePreview {
            background: transparent;
            border: none;
            cursor: pointer;
            opacity: 0.8;
        }

        #togglePreview:hover {
            opacity: 1;
        }

        /* เพิ่มปุ่มแสดง Preview ที่มุมบนขวา (จะแสดงเฉพาะเมื่อ Preview ถูกซ่อน) */
        #showPreviewBtn {
            transition: all 0.3s ease;
        }

        #showPreviewBtn:hover {
            transform: scale(1.05);
        }
    </style>
</head>
<body class="bg-gray-50">
    <!-- แก้ไข Live Preview div -->
    <div id="livePreview" class="transition-all duration-300">
        <div class="preview-header">
            <h3 id="previewHeaderTitle"></h3>
            <img id="previewLogo" src="" alt="Logo">
        </div>
        <div class="preview-body">
            <div class="preview-body-title">รายงานยอดประจำวัน</div>
            <div class="preview-body-date" id="previewDateTime"></div>
            <div id="previewItems"></div>
        </div>
        <div class="preview-footer">
            <a href="#" id="previewButton" class="preview-button"></a>
            <!-- ย้ายปุ่มมาที่นี่และปรับแต่ง style -->
            <button id="togglePreview" class="w-full mt-2 py-2 text-gray-600 hover:text-gray-800 text-sm transition-all duration-200">
                <span class="hide-preview-text">ซ่อน Preview</span>
                <span class="show-preview-text" style="display: none;">แสดง Preview</span>
            </button>
        </div>
    </div>

    <div class="container max-w-4xl mx-auto mt-8 p-6">
        <div class="bg-white rounded-lg shadow-lg p-8">
            <h2 class="text-3xl font-bold text-gray-800 mb-6">กรอกข้อมูลรายงานยอดประจำวัน</h2>
            
            <form method="POST" action="" class="space-y-6" id="reportForm">
                <!-- ส่วนการเลือกรูปแบบ -->
                <div class="mb-4">
                    <label class="form-label font-semibold text-gray-700">เลือกรูปแบบการส่ง:</label>
                    <select name="template" class="form-select shadow-sm" id="templateSelect">
                        <option value="form_group.php">ส่งแบบทั้งกรุ๊ป</option>
                        <option value="form_userid.php">ส่งแบบรายบุคคล</option>
                    </select>
                </div>

                <!-- ส่วนการตั้งค่าส่วนหัว -->
                <div class="border-t pt-4">
                    <h3 class="text-xl font-semibold mb-4">ตั้งค่าส่วนหัว</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="form-label">หัวข้อส่วนหัว:</label>
                            <input type="text" name="header_title" class="form-control" value="สำหรับผู้บริหารระดับสูง">
                        </div>
                        <div>
                            <label class="form-label">URL โลโก้:</label>
                            <input type="text" name="header_logo" class="form-control" value="https://img5.pic.in.th/file/secure-sv1/JIB_white_logo.png">
                        </div>
                        <div>
                            <label class="form-label">สีพื้นหลังส่วนหัว:</label>
                            <input type="color" name="header_bg_color" class="form-control" value="#262262">
                        </div>
                        <div>
                            <label class="form-label">สีตัวอักษรส่วนหัว:</label>
                            <input type="color" name="header_title_color" class="form-control" value="#FFFFFF">
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
                <div id="userIdSection" style="display:none;" class="mb-4 border-t pt-4">
                    <h3 class="text-xl font-semibold mb-4">ตั้งค่า User IDs</h3>
                    <div id="userIdInputs" class="space-y-2">
                        <div class="input-group">
                            <input type="text" name="user_ids[]" class="form-control shadow-sm" placeholder="กรอก User ID">
                            <button type="button" class="btn btn-success shadow-sm" onclick="addUserIdInput()">+</button>
                        </div>
                    </div>
                </div>

                <!-- ส่วนขนาด Form -->
                <div class="border-t pt-4">
                    <h3 class="text-xl font-semibold mb-4">ขนาด Form</h3>
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
                <div class="border-t pt-4">
                    <h3 class="text-xl font-semibold mb-4">ข้อมูลหลัก</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="form-label">ชื่อรายงาน:</label>
                            <input type="text" name="body_title" class="form-control" value="รายงานยอดประจำวัน">
                        </div>
                        <div>
                            <label class="form-label">สีชื่อรายงาน:</label>
                            <input type="color" name="body_title_color" class="form-control" value="#262262">
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
                    <div id="dataInputs" class="space-y-4">
                        <!-- ข้อมูลจะถูกเพิ่มที่นี่ -->
                    </div>

                    <button type="button" class="btn btn-success mt-4" onclick="addDataInput()">
                        เพิ่มข้อมูล
                    </button>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                        <div>
                            <label class="form-label">สีตัวอักษรเนื้อหา:</label>
                            <input type="color" name="body_text_color" class="form-control" value="#262262">
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

                <!-- ส่วนการตั้งค่า Footer -->
                <div class="border-t pt-4">
                    <h3 class="text-xl font-semibold mb-4">ตั้งค่าส่วนท้าย</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="form-label">ข้อความปุ่ม:</label>
                            <input type="text" name="footer_button_label" class="form-control" value="ดูรายละเอียดเพิ่ม">
                        </div>
                        <div>
                            <label class="form-label">URL ปุ่ม:</label>
                            <input type="text" name="footer_button_url" class="form-control" value="https://linecorp.com">
                        </div>
                        <div>
                            <label class="form-label">สีปุ่ม:</label>
                            <input type="color" name="footer_button_color" class="form-control" value="#262262">
                        </div>
                    </div>
                </div>

                <!-- ส่วนวันที่และเวลา -->
                <div class="border-t pt-4">
                    <div class="mb-4">
                        <label class="form-label">วันที่และเวลา:</label>
                        <input type="text" name="datetime" class="form-control shadow-sm" 
                               value="<?php echo date('j M y H:i', strtotime('+543 years')); ?> น." required>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary w-full py-3 text-lg font-semibold shadow-lg transition duration-200 ease-in-out transform hover:scale-105">
                    ส่งข้อมูล
                </button>
            </form>
        </div>
    </div>

    <!-- เพิ่มปุ่มแสดง Preview ที่มุมบนขวา (จะแสดงเฉพาะเมื่อ Preview ถูกซ่อน) -->
    <button id="showPreviewBtn" class="fixed top-4 right-4 z-50 bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg shadow-lg transition-all duration-200" style="display: none;">
        แสดง Preview
    </button>

    <script>
        // เพิ่ม Loading Indicator
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

        // เพิ่ม Smooth Scroll
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                document.querySelector(this.getAttribute('href')).scrollIntoView({
                    behavior: 'smooth'
                });
            });
        });

        // ปรับปรุงฟังก์ชัน updatePreview
        function updatePreview() {
            // ตรวจสอบว่า preview กำลังถูกซ่อนอยู่หรือไม่
            const preview = document.getElementById('livePreview');
            if (preview.classList.contains('hidden-preview')) {
                return; // ไม่อัพเดทถ้า preview ถูกซ่อนอยู่
            }

            showLoading();
            setTimeout(() => {
                const form = document.getElementById('reportForm');
                const formData = new FormData(form);
                const preview = document.getElementById('livePreview');

                // อัพเดทขนาดฟอร์ม
                preview.className = ''; // ล้าง class เดิม
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

                // ถ้าไม่มีข้อมูล ให้แสดงข้อมูลตัวอย่าง
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

        // ฟังก์ชันแปลงขนาดตัวอักษร
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

        // เพิ่ม Event Listeners สำหรับการอัพเดท Preview แบบ Real-time
        document.querySelectorAll('input, select').forEach(element => {
            element.addEventListener('input', updatePreview);
            element.addEventListener('change', updatePreview);
        });

        // อัพเดท Preview เมื่อเพิ่มหรือลบข้อมูล
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

        // อัพเดท Preview ครั้งแรกเมื่อโหลดหน้า
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

        document.getElementById('templateSelect').addEventListener('change', function() {
            var userIdSection = document.getElementById('userIdSection');
            var template1Preview = document.getElementById('template1Preview');
            var template2Preview = document.getElementById('template2Preview');
            
            if(this.value === 'form_userid.php') {
                userIdSection.style.display = 'block';
                template1Preview.classList.remove('active');
                template2Preview.classList.add('active');
            } else {
                userIdSection.style.display = 'none';
                template1Preview.classList.add('active');
                template2Preview.classList.remove('active');
            }
            updatePreview();
        });

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

        // ปรับปรุงฟังก์ชันควบคุมการซ่อน/แสดง Preview
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
                updatePreview(); // อัพเดทข้อมูลเมื่อแสดง Preview
            });
        });
    </script>
</body>
</html> 
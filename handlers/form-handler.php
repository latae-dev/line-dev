<?php
// ไม่ต้องมี session_start() เพราะถูกเรียกใน config.php แล้ว

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $template = $_POST['template'];
    
    // เก็บข้อมูลส่วนหัว
    $header = [
        'title' => $_POST['header_title'] ?? DEFAULT_HEADER_TITLE, 
        'logo_url' => $_POST['header_logo'] ?? DEFAULT_LOGO_URL,
        'bg_color' => $_POST['header_bg_color'] ?? DEFAULT_BG_COLOR,
        'title_size' => $_POST['header_title_size'] ?? DEFAULT_TITLE_SIZE,
        'title_color' => $_POST['header_title_color'] ?? DEFAULT_TEXT_COLOR
    ];

    // เก็บข้อมูลส่วนเนื้อหา
    $body = [
        'title' => $_POST['body_title'] ?? DEFAULT_BODY_TITLE,
        'title_size' => $_POST['body_title_size'] ?? DEFAULT_BODY_TITLE_SIZE,
        'title_color' => $_POST['body_title_color'] ?? DEFAULT_BODY_TITLE_COLOR,
        'datetime' => $_POST['datetime'],
        'text_color' => $_POST['body_text_color'] ?? DEFAULT_BODY_TEXT_COLOR,
        'label_size' => $_POST['body_label_size'] ?? DEFAULT_BODY_LABEL_SIZE,
        'value_size' => $_POST['body_value_size'] ?? DEFAULT_BODY_VALUE_SIZE,
        'size' => $_POST['form_size'] ?? DEFAULT_FORM_SIZE
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
        'button_label' => $_POST['footer_button_label'] ?? DEFAULT_FOOTER_BUTTON_LABEL,
        'button_url' => $_POST['footer_button_url'] ?? DEFAULT_FOOTER_BUTTON_URL,
        'button_color' => $_POST['footer_button_color'] ?? DEFAULT_FOOTER_BUTTON_COLOR,
        'button_text_size' => $_POST['footer_button_text_size'] ?? DEFAULT_FOOTER_BUTTON_TEXT_SIZE
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
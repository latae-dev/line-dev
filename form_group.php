<?php
session_start();

$access_token = "InK32TA8K71iAw7moN5wy24+1ne3tK9/UHtQDL7xMCdN6OAToLgGnDFlCDSQMTmWGAW7KitNW2IoQVZR1LdiwpDmMXzn3ka5RyP7KrWIhOCdOGDHxoVJqXBM0HJr/VNktAVQrpF/MspTlYd5wc9w6wdB04t89/1O/w1cDnyilFU="; // ใส่ Line Channel Access Token ของคุณที่นี่

// ตรวจสอบว่ามีข้อมูลใน session หรือไม่
if (!isset($_SESSION['line_data'])) {
    die("ไม่พบข้อมูล กรุณากรอกข้อมูลก่อน");
}

$data = $_SESSION['line_data'];

// กำหนดตัวคั่นระหว่างส่วน
$separator = [
    "type" => "separator",
    "margin" => "lg"
];

$flexMessage = [
    "messages" => [
        [
            "type" => "flex",
            "altText" => "รายงานยอดประจำวัน",
            "contents" => [
                "type" => "bubble",
                "size" => $data['body']['size'], // ใช้ size จาก form_size
                "header" => [
                    "type" => "box", 
                    "layout" => "vertical",
                    "flex" => 0,
                    "backgroundColor" => $data['header']['bg_color'],
                    "contents" => [
                        [
                            "type" => "text",
                            "text" => $data['header']['title'],
                            "weight" => "bold", 
                            "size" => $data['header']['title_size'],
                            "color" => $data['header']['title_color'],
                            "align" => "start",
                            "contents" => [
                                [
                                    "type" => "span",
                                    "text" => $data['header']['title']
                                ]
                            ]
                        ],
                        [
                            "type" => "image",
                            "url" => $data['header']['logo_url'],
                            "align" => "end",
                            "gravity" => "center",
                            "size" => "xs",
                            "aspectMode" => "fit",
                            "position" => "absolute",
                            "offsetTop" => "10px",
                            "offsetBottom" => "7px", 
                            "offsetEnd" => "15px"
                        ]
                    ]
                ],
                "body" => [
                    "type" => "box",
                    "layout" => "vertical",
                    "spacing" => "md",
                    "action" => [
                        "type" => "uri",
                        "label" => "Action",
                        "uri" => $data['footer']['button_url']
                    ],
                    "contents" => [
                        [
                            "type" => "text",
                            "text" => $data['body']['title'],
                            "weight" => "bold",
                            "size" => $data['body']['title_size'],
                            "color" => $data['body']['title_color'],
                            "contents" => []
                        ],
                        [
                            "type" => "text",
                            "text" => $data['body']['datetime'],
                            "size" => "xs",
                            "color" => "#AAAAAA",
                            "wrap" => true,
                            "contents" => []
                        ],
                        $separator,
                        [
                            "type" => "box",
                            "layout" => "vertical",
                            "spacing" => "sm",
                            "contents" => array_map(function($item) use ($data, $separator) {
                                return [
                                    "type" => "box",
                                    "layout" => "baseline",
                                    "contents" => [
                                        [
                                            "type" => "text",
                                            "text" => $item['label'],
                                            "weight" => "bold",
                                            "size" => $data['body']['label_size'],
                                            "color" => $data['body']['text_color'],
                                            "flex" => 0,
                                            "margin" => "sm",
                                            "contents" => []
                                        ],
                                        [
                                            "type" => "text",
                                            "text" => $item['value'],
                                            "weight" => "bold",
                                            "size" => $data['body']['value_size'],
                                            "color" => $data['body']['text_color'],
                                            "align" => "end",
                                            "contents" => []
                                        ]
                                    ]
                                ];
                            }, $data['body']['items'])
                        ]
                    ]
                ],
                "footer" => [
                    "type" => "box",
                    "layout" => "vertical",
                    "contents" => [
                        [
                            "type" => "spacer",
                            "size" => "xxl"
                        ],
                        [
                            "type" => "button",
                            "action" => [
                                "type" => "uri",
                                "label" => $data['footer']['button_label'],
                                "uri" => $data['footer']['button_url']
                            ],
                            "color" => $data['footer']['button_color'],
                            "style" => "primary"
                        ]
                    ]
                ]
            ]
        ]
    ]
];

try {
    // ส่งไปยัง LINE API
    $ch = curl_init("https://api.line.me/v2/bot/message/broadcast");
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Content-Type: application/json",
        "Authorization: Bearer " . $access_token
    ]);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($flexMessage, JSON_UNESCAPED_UNICODE));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $result = curl_exec($ch);
    
    if(curl_errno($ch)) {
        throw new Exception(curl_error($ch));
    }
    
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    if($httpCode !== 200) {
        throw new Exception("LINE API returned HTTP code: " . $httpCode);
    }
    
    curl_close($ch);
    
    // แสดงผลลัพธ์
    echo "ส่งข้อความสำเร็จ!";
    
    // ล้าง session หลังจากส่งข้อมูลเสร็จ
    unset($_SESSION['line_data']);
    
} catch(Exception $e) {
    echo "เกิดข้อผิดพลาด: " . $e->getMessage();
}

?>

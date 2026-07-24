<?php
header("Content-Type: application/json; charset=utf-8");

// 1. API Key của bạn
$apiKey = trim((string) getenv('GEMINI_API_KEY'));

if ($apiKey === '') {
    http_response_code(500);
    echo json_encode([
        "reply" => "PHP chưa nhận được biến GEMINI_API_KEY. Hãy khởi động lại Apache."
    ], JSON_UNESCAPED_UNICODE);
    exit();
}

// 2. Nhận dữ liệu câu hỏi
$rawData = file_get_contents("php://input");
$data = json_decode($rawData, true);
$message = trim($data["message"] ?? $_POST["message"] ?? $_GET["message"] ?? "");

if (empty($message)) {
    echo json_encode(["reply" => "Vui lòng nhập nội dung câu hỏi!"], JSON_UNESCAPED_UNICODE);
    exit();
}

// 3. System Instruction - Vai diễn AI
$systemInstruction = "Bạn là Trợ lý AI thông minh của Cổng Dịch vụ công Quốc gia Việt Nam. "
    . "Nhiệm vụ của bạn là tư vấn thủ tục hành chính (CCCD, Hộ chiếu, Khai sinh, Đăng ký kết hôn, Thuế, BHYT, v.v.) "
    . "cho người dân một cách lịch sự, rõ ràng, dễ hiểu và chuyên nghiệp. "
    . "Nếu người dân hỏi ngoài chủ đề hành chính/pháp luật/dịch vụ công, hãy lịch sự lái họ quay lại chủ đề chính.";

// 4. Payload gửi lên Gemini
$payload = [
    "system_instruction" => [
        "parts" => [
            ["text" => $systemInstruction]
        ]
    ],
    "contents" => [
        [
            "parts" => [
                ["text" => $message]
            ]
        ]
    ]
];

// 5. Endpoint dùng model gemini-2.0-flash mới nhất và chuẩn xác
$endpoint = "https://generativelanguage.googleapis.com/v1beta/models/gemini-3.5-flash:generateContent";
$ch = curl_init();
curl_setopt_array($ch, [
    CURLOPT_URL => $endpoint,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_HTTPHEADER => [
        'Content-Type: application/json',
        'x-goog-api-key: ' . $apiKey,
    ],
    CURLOPT_POSTFIELDS => json_encode($payload, JSON_UNESCAPED_UNICODE),
    CURLOPT_TIMEOUT => 30,
]);

$response = curl_exec($ch);
$curlError = curl_error($ch);
curl_close($ch);

// 6. Trích xuất câu trả lời
if ($curlError) {
    $reply = "Lỗi cURL: " . $curlError;
} else {
    $resultData = json_decode($response, true);

    if (isset($resultData['candidates'][0]['content']['parts'][0]['text'])) {
        $reply = $resultData['candidates'][0]['content']['parts'][0]['text'];
    } elseif (isset($resultData['error']['message'])) {
        $reply = "Lỗi Google API: " . $resultData['error']['message'];
    } else {
        $reply = "Lỗi không xác định: " . $response;
    }
}

// 7. Trả JSON về
echo json_encode(["reply" => $reply], JSON_UNESCAPED_UNICODE);
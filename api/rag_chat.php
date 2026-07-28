
<?php
require_once "./config.php";
session_start();
header('Content-Type: application/json; charset=utf-8');

function respond(int $status, array $data): void {
    http_response_code($status);
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    exit();
}

// 1. Cấu hình khóa API 
// LƯU Ý: Thay chuỗi bên dưới bằng API Key chính xác tạo từ Google AI Studio (dạng AIzaSy...)
$apiKey = $GEMINI_API_KEY;

if (trim($apiKey) === '') {
    respond(500, ['reply' => 'Máy chủ chưa cấu hình GEMINI_API_KEY.']);
}

$input = json_decode((string) file_get_contents('php://input'), true) ?? $_POST;
$message = trim((string) ($input['message'] ?? ''));
$imageBase64 = $input['image_base64'] ?? null;

if ($message === '' && !$imageBase64) {
    respond(422, ['reply' => 'Vui lòng nhập câu hỏi hoặc tải ảnh giấy tờ.']);
}

// 2. Chuẩn bị lịch sử hội thoại (Xử lý an toàn bối cảnh)
$contents = [];
$rawHistory = is_array($input['history'] ?? null) ? $input['history'] : [];

foreach (array_slice($rawHistory, -10) as $turn) {
    $role = $turn['role'] ?? '';
    if ($role !== 'user' && $role !== 'model') {
        continue;
    }

    $parts = [];
    if (isset($turn['parts']) && is_array($turn['parts'])) {
        foreach ($turn['parts'] as $part) {
            $text = trim((string) ($part['text'] ?? ''));
            if ($text !== '') {
                $parts[] = ['text' => $text];
            }
        }
    }

    if (!empty($parts)) {
        $contents[] = [
            'role' => $role,
            'parts' => $parts
        ];
    }
}

// 3. Xử lý dữ liệu lượt gửi hiện tại của User
$userParts = [];
if ($message !== '') {
    $userParts[] = ['text' => $message];
}

if ($imageBase64) {
    if (preg_match('/^data:(image\/[a-zA-Z0-9\+\-]+);base64,(.*)$/', $imageBase64, $matches)) {
        $mimeType = $matches[1];
        $dataData = $matches[2];
    } else {
        $mimeType = 'image/jpeg';
        $dataData = $imageBase64;
    }

    $userParts[] = [
        'inline_data' => [
            'mime_type' => $mimeType,
            'data' => $dataData
        ]
    ];
}

$contents[] = ['role' => 'user', 'parts' => $userParts];

// 4. System Instruction chuẩn hóa
$systemInstruction = <<<'PROMPT'
Bạn là “Đắc Lắk Một Cửa AI” – Hệ thống trợ lý hoàn thiện hồ sơ dịch vụ công.
Mục tiêu: "Nộp đúng ngay lần đầu".

Nhiệm vụ của bạn:
1. Định hướng nhu cầu: Hỏi ngắn gọn từng câu để xác định chính xác đối tượng, địa điểm, ngành nghề, và các giấy tờ hiện có.
2. Tạo Checklist cá nhân hóa: Dùng kí hiệu ✓ (Đã có), ○ (Cần chuẩn bị), ⚠ (Cần kiểm tra/Nguy cơ sai).
3. Thẩm định ảnh/giấy tờ (khi có hình ảnh): Bắt buộc kiểm tra độ rõ nét, góc chụp, chữ ký, thời hạn và đối chiếu thông tin. Nếu phát hiện lỗi, hãy cảnh báo ngay nguy cơ bị trả lại hồ sơ.
4. Minh bạch pháp lý: Cuối các hướng dẫn quan trọng, luôn có phần "CĂN CỨ PHÁP LÝ" ghi rõ tên văn bản quy định và cơ quan tiếp nhận tại Đắc Lắk.

Quy tắc ứng xử:
- Ngôn ngữ thân thiện, dễ hiểu, tránh từ ngữ hành chính hóc húa.
- Không bịa đặt quy định hay lệ phí. Tuyệt đối không lưu giữ dữ liệu cá nhân nhạy cảm.
PROMPT;

// 5. Payload gửi API
$payload = [
    'system_instruction' => [
        'parts' => [['text' => $systemInstruction]]
    ],
    'contents' => $contents
];

// Cập nhật Endpoint model chính thức hiện tại: gemini-3.6-flash
$endpoint = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-3.6-flash:generateContent';

$ch = curl_init();
curl_setopt_array($ch, [
    CURLOPT_URL => $endpoint,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_HTTPHEADER => [
        'Content-Type: application/json',
        'x-goog-api-key: ' . $apiKey
    ],
    CURLOPT_POSTFIELDS => json_encode($payload, JSON_UNESCAPED_UNICODE),
    CURLOPT_TIMEOUT => 30
]);

$response = curl_exec($ch);
$curlError = curl_error($ch);
$httpCode = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($curlError !== '') {
    respond(502, ['reply' => 'Không thể kết nối tới dịch vụ AI: ' . $curlError]);
}

$result = json_decode((string) $response, true);

// Kiểm tra lỗi phản hồi từ API Google
if (isset($result['error'])) {
    $errorMsg = $result['error']['message'] ?? 'Chưa xác định';
    respond(500, ['reply' => 'Lỗi từ Google API: ' . $errorMsg]);
}

if ($httpCode !== 200) {
    respond($httpCode, ['reply' => 'Yêu cầu không thành công, mã phản hồi HTTP: ' . $httpCode]);
}

$reply = $result['candidates'][0]['content']['parts'][0]['text'] ?? 'Không nhận được phản hồi từ AI.';

respond(200, ['reply' => $reply]);
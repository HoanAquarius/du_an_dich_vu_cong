<?php
session_start();
header('Content-Type: application/json; charset=utf-8');

function respond(int $status, array $data): void {
    http_response_code($status);
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    exit();
}

$apiKey = trim((string) getenv('GEMINI_API_KEY'));
if ($apiKey === '') {
    respond(500, ['reply' => 'Máy chủ chưa cấu hình GEMINI_API_KEY.']);
}

$input = json_decode((string) file_get_contents('php://input'), true) ?? $_POST;
$message = trim((string) ($input['message'] ?? ''));
$imageBase64 = $input['image_base64'] ?? null;

if ($message === '' && !$imageBase64) {
    respond(422, ['reply' => 'Vui lòng nhập câu hỏi hoặc tải ảnh giấy tờ.']);
}

// 1. Chuẩn bị lịch sử hội thoại
$contents = [];
foreach (array_slice((array) ($input['history'] ?? []), -10) as $turn) {
    $role = $turn['role'] ?? '';
    $text = trim((string) ($turn['parts'][0]['text'] ?? ''));
    if (($role === 'user' || $role === 'model') && $text !== '') {
        $contents[] = ['role' => $role, 'parts' => [['text' => $text]]];
    }
}

// 2. Xử lý phần gửi kèm ảnh (Kiểm tra giấy tờ - Camera AI)
$userParts = [];
if ($message !== '') {
    $userParts[] = ['text' => $message];
}

if ($imageBase64) {
    if (preg_match('/data:image\/(.*?);base64,(.*)/', $imageBase64, $matches)) {
        $mimeType = 'image/' . $matches[1];
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

// 3. System Instruction chuẩn hóa
$systemInstruction = <<<'PROMPT'
Bạn là “Đắc Lắk Một Cửa AI” – Hệ thống trợ lý hoàn thiện hồ sơ dịch vụ công.
Mục tiêu: "Nộp đúng ngay lần đầu".

Nhiệm vụ của bạn:
1. Định hướng nhu cầu: Hỏi ngắn gọn từng câu để xác định chính xác đối tượng, địa điểm, ngành nghề, và các giấy tờ hiện có.
2. Tạo Checklist cá nhân hóa: Dùng kí hiệu ✓ (Đã có), ○ (Cần chuẩn bị), ⚠ (Cần kiểm tra/Nguy cơ sai).
3. Thẩm định ảnh/giấy tờ (khi có hình ảnh): Bắt buộc kiểm tra độ rõ nét, góc chụp, chữ ký, thời hạn và đối chiếu thông tin. Nếu phát hiện lỗi, hãy cảnh báo ngay nguy cơ bị trả lại hồ sơ.
4. Minh bạch pháp lý: Cuối các hướng dẫn quan trọng, luôn có phần "CĂN CỨ PHÁP LÝ" ghi rõ tên văn bản quy định và cơ quan tiếp nhận tại Đắk Lắk.

Quy tắc ứng xử:
- Ngôn ngữ thân thiện, dễ hiểu, tránh từ ngữ hành chính hóc húa.
- Không bịa đặt quy định hay lệ phí. Tuyệt đối không lưu giữ dữ liệu cá nhân nhạy cảm.
PROMPT;

// 4. Payload gửi API
$payload = [
    'system_instruction' => ['parts' => [['text' => $systemInstruction]]],
    'contents' => $contents
];

// Dùng model chính thức của Google Gemini API hỗ trợ Vision & Text
// Dùng model thế hệ mới nhất hỗ trợ cả Văn bản + Hình ảnh (Vision)
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

// Bổ sung kiểm tra thông báo lỗi từ Google API nếu có
if (isset($result['error'])) {
    respond(500, ['reply' => 'Lỗi từ Google API: ' . ($result['error']['message'] ?? 'Chưa xác định')]);
}

$reply = $result['candidates'][0]['content']['parts'][0]['text'] ?? 'Không nhận được phản hồi từ AI.';

respond(200, ['reply' => $reply]);
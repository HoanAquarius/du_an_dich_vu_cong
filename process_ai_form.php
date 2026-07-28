<?php
require_once "./config.php";
session_start();
header('Content-Type: application/json; charset=utf-8');

function respond(int $status, array $data): void {
    http_response_code($status);
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    exit();
}

// 1. Gán trực tiếp API Key theo yêu cầu
$apiKey = $GEMINI_API_KEY;

if (trim($apiKey) === '') {
    respond(500, ['success' => false, 'message' => 'Máy chủ chưa cấu hình GEMINI_API_KEY.']);
}

$imageBase64 = null;
$mimeType = 'image/jpeg';

// Tiếp nhận ảnh gửi lên từ Frontend (Hỗ trợ Multipart Form-data)
if (isset($_FILES['form_image']) && $_FILES['form_image']['error'] === UPLOAD_ERR_OK) {
    $imagePath = $_FILES['form_image']['tmp_name'];
    $imageBase64 = base64_encode(file_get_contents($imagePath));
    $mimeType = $_FILES['form_image']['type'];
} else {
    // Dự phòng trường hợp gửi dạng JSON body (image_base64)
    $input = json_decode((string) file_get_contents('php://input'), true);
    $rawImage = $input['image_base64'] ?? null;
    if ($rawImage) {
        if (preg_match('/data:image\/(.*?);base64,(.*)/', $rawImage, $matches)) {
            $mimeType = 'image/' . $matches[1];
            $imageBase64 = $matches[2];
        } else {
            $mimeType = 'image/jpeg';
            $imageBase64 = $rawImage;
        }
    }
}

if (!$imageBase64) {
    respond(422, ['success' => false, 'message' => 'Vui lòng tải lên hoặc chụp lại ảnh biểu mẫu cần số hóa.']);
}

// 2. Dữ liệu công dân sẵn có trên hệ thống để tự động điền (Auto-fill)
// Đã bổ sung thêm Cấp ngày và Nơi cấp
$user_profile = [
    'full_name' => 'Nguyễn Văn A',
    'dob' => '1995-10-15', 
    'gender' => 'Nam',
    'nationality' => 'Việt Nam',
    'identity_number' => '012345678901',
    'issue_date' => '2021-05-20', // Cấp ngày (Định dạng YYYY-MM-DD để input date nhận diện)
    'issue_place' => 'Cục Cảnh sát quản lý hành chính về trật tự xã hội', // Nơi cấp
    'address' => 'Số 1 Hùng Vương, phường Thắng Lợi, TP. Buôn Ma Thuột, Đắk Lắk'
];

$userProfileJson = json_encode($user_profile, JSON_UNESCAPED_UNICODE);

// 3. Xây dựng prompt phân tích ảnh biểu mẫu và ép xuất dữ liệu JSON
$promptText = "Hãy phân tích kỹ bức ảnh biểu mẫu hành chính đính kèm. Thực hiện các nhiệm vụ sau:\n" .
              "1. Xác định chính xác Tên loại đơn/biểu mẫu này (Ví dụ: Đơn xin nghỉ phép, Tờ khai đăng ký kết hôn...).\n" .
              "2. Quét toàn bộ biểu mẫu để tìm tất cả các trường dữ liệu trống cần điền thông tin.\n" .
              "3. Đối chiếu các trường cần điền đó với Hồ sơ thông tin cá nhân của tôi sau đây:\n" .
              "{$userProfileJson}\n" .
              "Nếu trường dữ liệu nào trong ảnh khớp hoàn toàn hoặc khớp một phần ý nghĩa với thông tin cá nhân của tôi (Ví dụ: Tên tôi là, Họ và tên -> full_name; Ngày tháng năm sinh -> dob; Cấp ngày -> issue_date; Nơi cấp -> issue_place), hãy tự động điền giá trị đó vào thuộc tính 'suggested_value'. Nếu không khớp hoặc là trường cần điền mới (Ví dụ: Lý do, Đơn vị công tác), hãy để chuỗi rỗng \"\".\n\n" .
              "BẮT BUỘC TRẢ VỀ ĐỊNH DẠNG JSON THEO ĐÚNG CẤU TRÚC MẪU SAU, KHÔNG CHỨA BẤT KỲ CHỮ GIẢI THÍCH NÀO KHÁC NGOÀI JSON:\n" .
              "{\n" .
              "  \"form_name\": \"Tên biểu mẫu trích xuất được\",\n" .
              "  \"fields\": [\n" .
              "    {\"id\": \"id_tieng_anh_viet_lien\", \"label\": \"Tên trường hiển thị bằng Tiếng Việt\", \"type\": \"text|date|number\", \"suggested_value\": \"Giá trị tự điền\"}\n" .
              "  ]\n" .
              "}";

$userParts = [
    ['text' => $promptText],
    [
        'inline_data' => [
            'mime_type' => $mimeType,
            'data' => $imageBase64
        ]
    ]
];

$contents = [['role' => 'user', 'parts' => $userParts]];
$systemInstruction = "Bạn là một API chuyên nghiệp được tích hợp vào Cổng hành chính công. Nhiệm vụ duy nhất của bạn là chuyển đổi ảnh chụp biểu mẫu giấy thành cấu trúc dữ liệu JSON để tái dựng form trên nền tảng điện tử và thực hiện Auto-fill thông tin sẵn có cho người dân.";

$payload = [
    'system_instruction' => ['parts' => [['text' => $systemInstruction]]],
    'contents' => $contents,
    'generationConfig' => [
        'responseMimeType' => 'application/json'
    ]
];

// Endpoint chính thức của Gemini API 
$endpoint = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-3.6-flash:generateContent';

$ch = curl_init();
curl_setopt_array($ch, [
    CURLOPT_URL => $endpoint,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_SSL_VERIFYPEER => false,
    CURLOPT_SSL_VERIFYHOST => false,
    CURLOPT_HTTPHEADER => [
        'Content-Type: application/json',
        "x-goog-api-key: {$apiKey}"
    ],
    CURLOPT_POSTFIELDS => json_encode($payload, JSON_UNESCAPED_UNICODE),
    CURLOPT_TIMEOUT => 45
]);

$response = curl_exec($ch);
$curlError = curl_error($ch);
curl_close($ch);

if ($curlError !== '') {
    respond(502, ['success' => false, 'message' => "Không thể kết nối tới dịch vụ phân tích AI: {$curlError}"]);
}

$result = json_decode((string) $response, true);

// Kiểm tra lỗi trực tiếp từ Google API
if (isset($result['error'])) {
    respond(500, ['success' => false, 'message' => 'Lỗi cấu hình API Google: ' . ($result['error']['message'] ?? 'Không xác định')]);
}

// Lấy văn bản JSON phản hồi
$rawReply = $result['candidates'][0]['content']['parts'][0]['text'] ?? null;

if (!$rawReply) {
    respond(500, ['success' => false, 'message' => 'Đã kết nối API thành công nhưng cấu trúc biểu mẫu trống hoặc sai chỉ mục mảng.']);
}

$formStructure = json_decode(trim($rawReply), true);

// Xử lý làm sạch chuỗi phòng trường hợp AI trả về chuỗi bọc mã Markdown
if (json_last_error() !== JSON_ERROR_NONE) {
    $cleanReply = preg_replace('/^```json\s*|```$/m', '', $rawReply);
    $formStructure = json_decode(trim($cleanReply), true);
    
    if (json_last_error() !== JSON_ERROR_NONE) {
        respond(500, ['success' => false, 'message' => 'Dữ liệu phân tích cấu trúc form của AI bị sai định dạng hệ thống.']);
    }
}

// Lưu phôi biểu mẫu vào CSDL mẫu
try {
    $db_host = 'localhost'; $db_name = 'dich_vu_cong'; $db_user = 'root'; $db_pass = '';
    $conn_pdo = new PDO("mysql:host=$db_host;dbname=$db_name;charset=utf8mb4", $db_user, $db_pass);
    
    $clean_structure = $formStructure;
    if (isset($clean_structure['fields'])) {
        foreach ($clean_structure['fields'] as &$f) {
            $f['suggested_value'] = ""; 
        }
    }
    
    $stmt_template = $conn_pdo->prepare("INSERT IGNORE INTO mau_bieu_mau (ten_bieu_mau, cau_truc_fields, created_at) VALUES (?, ?, NOW())");
    $stmt_template->execute([$formStructure['form_name'], json_encode($clean_structure, JSON_UNESCAPED_UNICODE)]);
    
} catch (Exception $e) {
    // Bỏ qua lỗi kết nối CSDL nếu môi trường local chưa cấu hình CSDL này
}

respond(200, [
    'success' => true,
    'data' => $formStructure
]);
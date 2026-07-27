<?php
session_start();
header('Content-Type: application/json; charset=utf-8');

// 1. Nhận dữ liệu JSON gửi từ giao diện JavaScript lên
$input = json_decode(file_get_contents('php://input'), true);
$formName = $input['form_name'] ?? 'Biểu mẫu AI';
$fieldsData = $input['fields_data'] ?? null;

if (!$fieldsData) {
    echo json_encode(['success' => false, 'message' => 'Dữ liệu biểu mẫu trống.']);
    exit;
}

// 2. Cấu hình thông tin kết nối Cơ sở dữ liệu MySQL của bạn
$host = 'localhost';
$db   = 'dich_vu_cong';
 // Kiểm tra xem tên Database này đã đúng chưa nhé
$user = 'root';
$pass = ''; // Điền mật khẩu MySQL của bạn nếu có (mặc định XAMPP thường để rỗng)
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
    
    // Giả lập ID của công dân đang đăng nhập (Ví dụ: Nguyễn Văn A có ID là 1 trong bảng users của bạn)
    $userId = 1; 
    
    // Nén toàn bộ các ô nhập liệu thành một chuỗi văn bản JSON để lưu gọn vào 1 cột duy nhất
    $jsonData = json_encode($fieldsData, JSON_UNESCAPED_UNICODE);

    // 3. Chèn dữ liệu vào bảng ho_so_ai vừa tạo ở Bước 1
    $stmt = $pdo->prepare("INSERT INTO ho_so_ai (user_id, form_name, data_content, created_at) VALUES (?, ?, ?, NOW())");
    $stmt->execute([$userId, $formName, $jsonData]);

    echo json_encode(['success' => true, 'message' => 'Hồ sơ đã được số hóa và lưu thành công!']);

} catch (\PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Lỗi kết nối hoặc thực thi Database: ' . $e->getMessage()]);
}

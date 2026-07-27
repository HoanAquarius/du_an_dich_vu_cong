<?php
header('Content-Type: application/json; charset=utf-8');

$formName = $_GET['form_name'] ?? '';
if ($formName === '') {
    echo json_encode(['success' => false, 'message' => 'Thiếu tên biểu mẫu cần lấy.']);
    exit;
}

$host = 'localhost'; $db = 'dich_vu_cong'; $user = 'root'; $pass = '';
try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);

    // Lấy cấu trúc phôi rỗng từ database
    $stmt = $pdo->prepare("SELECT cau_truc_fields FROM mau_bieu_mau WHERE ten_bieu_mau = ?");
    $stmt->execute([$formName]);
    $template = $stmt->fetch();

    if ($template) {
        $data = json_decode($template['cau_truc_fields'], true);
        
        // Tiến hành Auto-fill dữ liệu người dùng đang đăng nhập vào phôi rỗng này
        $user_profile = ['full_name' => 'Nguyễn Văn A', 'dob' => '1995-10-15', 'identity_number' => '012345678901'];
        
        if (isset($data['fields'])) {
            foreach ($data['fields'] as &$field) {
                if ($field['id'] === 'full_name' || $field['id'] === 'full_name_buyer' || $field['id'] === 'full_name_seller') $field['suggested_value'] = $user_profile['full_name'];
                if ($field['id'] === 'dob') $field['suggested_value'] = $user_profile['dob'];
                if ($field['id'] === 'identity_number') $field['suggested_value'] = $user_profile['identity_number'];
            }
        }

        echo json_encode(['success' => true, 'data' => $data]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Không tìm thấy phôi mẫu đơn này trong hệ thống.']);
    }
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Lỗi kết nối cơ sở dữ liệu: ' . $e->getMessage()]);
}

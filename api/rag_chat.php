<?php 
header("Content-Type: application/json; charset=utf-8"); 

// 1. Thử đọc dữ liệu dạng JSON từ fetch/axios
$rawData = file_get_contents("php://input");
$data = json_decode($rawData, true); 

// 2. Lấy message từ JSON, nếu không có thì thử lấy từ $_POST (FormData) hoặc $_GET
$message = $data["message"] ?? $_POST["message"] ?? $_GET["message"] ?? "";
$message = strtolower(trim($message)); 

$reply = "Xin lỗi, tôi chưa hiểu câu hỏi của bạn."; 

// Các điều kiện kiểm tra giữ nguyên
if (strpos($message, "cccd") !== false) { 
    $reply = "📄 Hồ sơ cấp đổi CCCD gồm:\n\n- CCCD cũ\n- Ảnh chân dung\n- Khai thông tin cá nhân."; 
} elseif (strpos($message, "khai sinh") !== false) { 
    $reply = "👶 Hồ sơ khai sinh gồm:\n\n- Giấy chứng sinh\n- CCCD của cha mẹ."; 
} elseif (strpos($message, "hộ chiếu") !== false) { 
    $reply = "🛂 Hồ sơ cấp hộ chiếu gồm:\n\n- CCCD\n- Ảnh 4x6\n- Lệ phí."; 
} elseif (strpos($message, "kết hôn") !== false) { 
    $reply = "💍 Hồ sơ đăng ký kết hôn gồm:\n\n- CCCD hai bên\n- Giấy xác nhận tình trạng hôn nhân."; 
} elseif (strpos($message, "xin chào") !== false || strpos($message, "hello") !== false) { 
    $reply = "👋 Xin chào! Tôi là trợ lý AI của Cổng Dịch vụ công."; 
} 

echo json_encode([ "reply" => $reply ], JSON_UNESCAPED_UNICODE);

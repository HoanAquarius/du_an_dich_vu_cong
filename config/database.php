<?php
// =========================================================================
// THIẾT KẾ DATABASE & THIẾT LẬP KẾT NỐI PDO
// Thư mục: /config/database.php
// Đóng góp: Mã Minh Hoan
// =========================================================================

// 1. Khai báo các hằng số cấu hình kết nối MySQL
define('DB_HOST', '127.0.0.1');
define('DB_NAME', 'dich_vu_cong');
define('DB_USER', 'root');
define('DB_PASS', ''); // Mặc định trên XAMPP là chuỗi rỗng
define('DB_CHARSET', 'utf8mb4');

/**
 * Hàm khởi tạo kết nối cơ sở dữ liệu qua PDO (Single Source of Truth)
 * @return PDO|null Trả về đối tượng PDO nếu thành công, hoặc null nếu lỗi
 */
function getDatabaseConnection() {
    $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
    
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // Đẩy lỗi ra ngoại lệ để dễ kiểm soát
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // Trả về mảng key-value (tên cột)
        PDO::ATTR_EMULATE_PREPARES   => false,                  // Tắt giả lập để tối ưu bảo mật SQL Injection
    ];

    try {
        $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
        return $pdo;
    } catch (\PDOException $e) {
        // Log lỗi hoặc in ra màn hình trong môi trường Development
        error_log("Lỗi kết nối cơ sở dữ liệu công: " . $e->getMessage());
        die("Hệ thống dịch vụ công đang bảo trì kết nối CSDL. Vui lòng thử lại sau.");
    }
}

// Tự động kiểm tra và khởi tạo kết nối khi file này được include/require
$pdo = getDatabaseConnection();
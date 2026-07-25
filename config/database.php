<?php
// =========================================================================
// THIẾT KẾ DATABASE & THIẾT LẬP KẾT NỐI PDO
// Thư mục: /config/database.php
// Đóng góp: Mã Minh Hoan
// =========================================================================

// 1. Khai báo các hằng số cấu hình kết nối MySQL
if (!defined('DB_HOST')) define('DB_HOST', '127.0.0.1');
if (!defined('DB_NAME')) define('DB_NAME', 'dich_vu_cong');
if (!defined('DB_USER')) define('DB_USER', 'root');
if (!defined('DB_PASS')) define('DB_PASS', ''); // Mặc định trên XAMPP là chuỗi rỗng
if (!defined('DB_CHARSET')) define('DB_CHARSET', 'utf8mb4');

/**
 * Class Database tương thích với các file sử dụng class new Database()
 */
if (!class_exists('Database')) {
    class Database {
        private $host = DB_HOST;
        private $db = DB_NAME;
        private $user = DB_USER;
        private $pass = DB_PASS;
        private $charset = DB_CHARSET;

        public function getConnection() {
            $dsn = "mysql:host={$this->host};dbname={$this->db};charset={$this->charset}";
            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ];

            try {
                return new PDO($dsn, $this->user, $this->pass, $options);
            } catch (\PDOException $e) {
                error_log("Lỗi kết nối cơ sở dữ liệu công: " . $e->getMessage());
                die("Hệ thống dịch vụ công đang bảo trì kết nối CSDL. Vui lòng thử lại sau.");
            }
        }
    }
}

/**
 * Hàm khởi tạo kết nối cơ sở dữ liệu qua PDO (Single Source of Truth)
 * @return PDO|null Trả về đối tượng PDO nếu thành công
 */
function getDatabaseConnection() {
    $db = new Database();
    return $db->getConnection();
}

// Tự động khởi tạo biến $pdo toàn cục để hỗ trợ các file gọi trực tiếp $pdo
$pdo = getDatabaseConnection();
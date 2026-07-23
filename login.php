<?php
require_once "config/database.php";
session_start();

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Khởi tạo kết nối DB từ Class Database
    $db = new Database();
    $conn = $db->getConnection();

    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Email không đúng định dạng!";
    } else {
        try {
            $sql = "SELECT * FROM users WHERE email = :email LIMIT 1";
            $stmt = $conn->prepare($sql);
            $stmt->execute([':email' => $email]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user && password_verify($password, $user['password'])) {
                // Đổi Session ID mới để bảo mật chống tấn công Session Fixation
                session_regenerate_id(true);

                // Lưu dữ liệu vào Session
                $_SESSION['user_id']   = $user['id'];
                $_SESSION['full_name'] = $user['full_name'];
                $_SESSION['email']     = $user['email'];
                $_SESSION['role']      = $user['role'] ?? 'user';

                // Điều hướng dựa theo vai trò (Role)
                if (isset($user['role']) && $user['role'] === 'admin') {
                    header("Location: admin_dashboard.php");
                } else {
                    header("Location: index.php");
                }
                exit();
            } else {
                $error = "Email hoặc mật khẩu không chính xác!";
            }
        } catch (PDOException $e) {
            $error = "Đã xảy ra lỗi hệ thống. Vui lòng thử lại sau!";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập | Cổng Dịch vụ công Quốc gia</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: #f3f7fb;
        }
        .bg-left {
            background: linear-gradient(135deg, #2563eb, #1d4ed8, #1e3a8a);
        }
        input:focus {
            box-shadow: none !important;
        }
    </style>
</head>
<body>

<div class="min-h-screen flex">
    <!-- BÊN TRÁI: BANNER DỊCH VỤ CÔNG -->
    <div class="hidden lg:flex w-1/2 bg-left text-white p-16 flex-col justify-between">
        <div>
            <div class="flex items-center gap-4">
                <div class="w-16 h-16 rounded-full bg-white text-red-600 flex items-center justify-center text-3xl font-bold">
                    ★
                </div>
                <div>
                    <h1 class="text-3xl font-bold">CỔNG DỊCH VỤ CÔNG</h1>
                    <p class="opacity-80">Smart Government Platform</p>
                </div>
            </div>

            <div class="mt-20">
                <h2 class="text-4xl font-bold leading-tight">
                    Nền tảng dịch vụ công thông minh
                </h2>
                <p class="mt-5 text-lg opacity-90">
                    Thực hiện thủ tục hành chính trực tuyến nhanh chóng cùng AI.
                </p>
            </div>

            <div class="mt-14 space-y-6 text-lg">
                <div class="flex items-center gap-4">
                    <i class="fa-solid fa-robot text-2xl"></i> AI Chatbot hỗ trợ 24/7
                </div>
                <div class="flex items-center gap-4">
                    <i class="fa-solid fa-id-card text-2xl"></i> OCR tự động đọc CCCD
                </div>
                <div class="flex items-center gap-4">
                    <i class="fa-solid fa-list-check text-2xl"></i> Sinh Checklist hồ sơ
                </div>
                <div class="flex items-center gap-4">
                    <i class="fa-solid fa-circle-check text-2xl"></i> Tiền kiểm hồ sơ bằng AI
                </div>
            </div>
        </div>

        <div class="text-sm opacity-70">
            © 2026 Smart Government
        </div>
    </div>

    <!-- BÊN PHẢI: FORM ĐĂNG NHẬP -->
    <div class="flex-1 flex items-center justify-center p-8">
        <div class="bg-white rounded-3xl shadow-2xl p-10 w-full max-w-md">
            <div class="text-center">
                <div class="mx-auto w-20 h-20 rounded-full bg-blue-100 flex items-center justify-center">
                    <i class="fa-solid fa-user text-blue-600 text-3xl"></i>
                </div>
                <h2 class="text-3xl font-bold mt-6">Đăng nhập</h2>
                <p class="text-gray-500 mt-2">Chào mừng bạn quay trở lại</p>
            </div>

            <?php if (!empty($error)): ?>
            <div class="mt-6 bg-red-100 text-red-600 p-3 rounded-xl text-center font-medium">
                <?= htmlspecialchars($error) ?>
            </div>
            <?php endif; ?>

            <form method="POST" class="mt-8 space-y-5">
                <div>
                    <label class="font-medium">Email</label>
                    <div class="mt-2 relative">
                        <i class="fa-solid fa-envelope absolute left-4 top-4 text-gray-400"></i>
                        <input
                            type="email"
                            name="email"
                            required
                            class="w-full border rounded-xl py-3 pl-12 pr-4 focus:border-blue-600 outline-none"
                            placeholder="Nhập email của bạn"
                            value="<?= isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '' ?>"
                        >
                    </div>
                </div>

                <div>
                    <label class="font-medium">Mật khẩu</label>
                    <div class="mt-2 relative">
                        <i class="fa-solid fa-lock absolute left-4 top-4 text-gray-400"></i>
                        <input
                            id="password"
                            type="password"
                            name="password"
                            required
                            class="w-full border rounded-xl py-3 pl-12 pr-12 focus:border-blue-600 outline-none"
                            placeholder="••••••••"
                        >
                        <i
                            onclick="togglePassword()"
                            class="fa-solid fa-eye absolute right-4 top-4 cursor-pointer text-gray-400 hover:text-gray-600"
                        ></i>
                    </div>
                </div>

                <div class="flex justify-between items-center text-sm">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" class="rounded text-blue-600">
                        <span>Ghi nhớ</span>
                    </label>
                    <a href="#" class="text-blue-600 hover:underline">Quên mật khẩu?</a>
                </div>

                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 transition text-white rounded-xl py-3 font-semibold shadow-lg shadow-blue-500/30">
                    Đăng nhập
                </button>
            </form>

            <div class="mt-8 text-center text-gray-600">
                Chưa có tài khoản?
                <a href="register.php" class="text-blue-600 font-semibold hover:underline">Đăng ký</a>
            </div>
        </div>
    </div>
</div>

<script>
function togglePassword() {
    const input = document.getElementById("password");
    input.type = input.type === "password" ? "text" : "password";
}
</script>

</body>
</html>
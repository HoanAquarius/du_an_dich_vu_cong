<?php
require_once "config/database.php";
session_start();

$success = "";
$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $db = new Database();
    $conn = $db->getConnection();

    $full_name = trim($_POST['full_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    if (mb_strlen($full_name) < 2) {
        $error = "Họ tên phải có ít nhất 2 ký tự!";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Email không đúng định dạng!";
    } elseif (!preg_match('/^(0|\+84)[0-9]{9}$/', $phone)) {
        $error = "Số điện thoại không hợp lệ!";
    } elseif (strlen($password) < 8) {
        $error = "Mật khẩu phải có ít nhất 8 ký tự!";
    } elseif ($password !== $confirm_password) {
        $error = "Mật khẩu xác nhận không trùng khớp!";
    }

    if (empty($error)) {
        try {
            // Kiểm tra email đã được đăng ký chưa
            $check_sql = "SELECT id FROM users WHERE email = :email LIMIT 1";
            $check_stmt = $conn->prepare($check_sql);
            $check_stmt->execute([':email' => $email]);

            if ($check_stmt->fetch()) {
                $error = "Email này đã có người sử dụng!";
            } else {
                // Thêm người dùng mới (mặc định role là 'user')
                $sql = "INSERT INTO users (full_name, email, phone, password, role) 
                        VALUES (:full_name, :email, :phone, :password, 'user')";
                $stmt = $conn->prepare($sql);
                $result = $stmt->execute([
                    ':full_name' => $full_name,
                    ':email'     => $email,
                    ':phone'     => $phone,
                    ':password'  => password_hash($password, PASSWORD_DEFAULT)
                ]);

                if ($result) {
                    $success = "Đăng ký thành công! Bạn có thể đăng nhập ngay.";
                } else {
                    $error = "Có lỗi xảy ra, vui lòng thử lại sau!";
                }
            }
        } catch (PDOException $e) {
            $error = "Lỗi hệ thống. Vui lòng thử lại sau!";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng ký | Cổng Dịch vụ công</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: #f4f8fb;
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
    <!-- BÊN TRÁI: BANNER THÔNG TIN -->
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

            <h2 class="text-5xl font-bold leading-tight mt-20">
                Tham gia nền tảng<br>Dịch vụ công thông minh
            </h2>

            <p class="mt-5 text-lg opacity-90">
                Tạo tài khoản để sử dụng các dịch vụ công trực tuyến cùng AI.
            </p>

            <div class="mt-14 space-y-6 text-lg">
                <div class="flex items-center gap-4">
                    <i class="fa-solid fa-id-card text-2xl"></i>
                    OCR tự động đọc giấy tờ
                </div>
                <div class="flex items-center gap-4">
                    <i class="fa-solid fa-robot text-2xl"></i>
                    AI Chatbot hỗ trợ 24/7
                </div>
                <div class="flex items-center gap-4">
                    <i class="fa-solid fa-list-check text-2xl"></i>
                    Sinh Checklist hồ sơ
                </div>
                <div class="flex items-center gap-4">
                    <i class="fa-solid fa-circle-check text-2xl"></i>
                    Theo dõi tiến độ xử lý
                </div>
            </div>
        </div>

        <div class="opacity-70 text-sm">
            © 2026 Smart Government
        </div>
    </div>

    <!-- BÊN PHẢI: FORM ĐĂNG KÝ -->
    <div class="flex-1 flex justify-center items-center p-8">
        <div class="bg-white rounded-3xl shadow-2xl p-10 w-full max-w-lg">
            <div class="text-center">
                <div class="mx-auto w-20 h-20 rounded-full bg-blue-100 flex justify-center items-center">
                    <i class="fa-solid fa-user-plus text-blue-600 text-3xl"></i>
                </div>
                <h2 class="text-3xl font-bold mt-6">Đăng ký</h2>
                <p class="text-gray-500 mt-2">Tạo tài khoản mới</p>
            </div>

            <?php if (!empty($error)): ?>
            <div class="bg-red-100 text-red-600 p-3 rounded-xl mt-6 text-center font-medium">
                <?= htmlspecialchars($error) ?>
            </div>
            <?php endif; ?>

            <?php if (!empty($success)): ?>
            <div class="bg-green-100 text-green-700 p-3 rounded-xl mt-6 text-center font-medium">
                <?= htmlspecialchars($success) ?>
            </div>
            <?php endif; ?>

            <form method="POST" class="space-y-4 mt-6">
                <!-- Họ và tên -->
                <div>
                    <label class="font-medium text-sm">Họ và tên</label>
                    <div class="mt-1 relative">
                        <i class="fa-solid fa-user absolute left-4 top-3.5 text-gray-400"></i>
                        <input
                            type="text"
                            name="full_name"
                            required
                            class="w-full border rounded-xl py-2.5 pl-12 pr-4 focus:border-blue-600 outline-none"
                            placeholder="Nguyễn Văn A"
                            value="<?= isset($_POST['full_name']) ? htmlspecialchars($_POST['full_name']) : '' ?>"
                        >
                    </div>
                </div>

                <!-- Email -->
                <div>
                    <label class="font-medium text-sm">Email</label>
                    <div class="mt-1 relative">
                        <i class="fa-solid fa-envelope absolute left-4 top-3.5 text-gray-400"></i>
                        <input
                            type="email"
                            name="email"
                            required
                            class="w-full border rounded-xl py-2.5 pl-12 pr-4 focus:border-blue-600 outline-none"
                            placeholder="example@gmail.com"
                            value="<?= isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '' ?>"
                        >
                    </div>
                </div>

                <!-- Số điện thoại -->
                <div>
                    <label class="font-medium text-sm">Số điện thoại</label>
                    <div class="mt-1 relative">
                        <i class="fa-solid fa-phone absolute left-4 top-3.5 text-gray-400"></i>
                        <input
                            type="text"
                            name="phone"
                            required
                            class="w-full border rounded-xl py-2.5 pl-12 pr-4 focus:border-blue-600 outline-none"
                            placeholder="09xxxxxxxx"
                            value="<?= isset($_POST['phone']) ? htmlspecialchars($_POST['phone']) : '' ?>"
                        >
                    </div>
                </div>

                <!-- Mật khẩu -->
                <div>
                    <label class="font-medium text-sm">Mật khẩu</label>
                    <div class="mt-1 relative">
                        <i class="fa-solid fa-lock absolute left-4 top-3.5 text-gray-400"></i>
                        <input
                            type="password"
                            name="password"
                            required
                            class="w-full border rounded-xl py-2.5 pl-12 pr-4 focus:border-blue-600 outline-none"
                            placeholder="••••••••"
                        >
                    </div>
                </div>

                <!-- Xác nhận mật khẩu -->
                <div>
                    <label class="font-medium text-sm">Xác nhận mật khẩu</label>
                    <div class="mt-1 relative">
                        <i class="fa-solid fa-shield-halved absolute left-4 top-3.5 text-gray-400"></i>
                        <input
                            type="password"
                            name="confirm_password"
                            required
                            class="w-full border rounded-xl py-2.5 pl-12 pr-4 focus:border-blue-600 outline-none"
                            placeholder="••••••••"
                        >
                    </div>
                </div>

                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white rounded-xl py-3 font-semibold transition shadow-lg shadow-blue-500/30 mt-2">
                    Đăng ký
                </button>
            </form>

            <div class="text-center mt-6 text-gray-600">
                Đã có tài khoản?
                <a href="login.php" class="text-blue-600 font-semibold hover:underline">
                    Đăng nhập
                </a>
            </div>
        </div>
    </div>
</div>

</body>
</html>
<?php
session_start();

// Xóa toàn bộ dữ liệu session
session_unset();
session_destroy();

// Chuyển hướng về trang chủ
header('Location: index.php'); // Nếu file trang chủ của bạn tên khác (vd: home.php), hãy sửa lại ở đây
exit();
?>
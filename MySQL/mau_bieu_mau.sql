-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th7 27, 2026 lúc 05:15 PM
-- Phiên bản máy phục vụ: 10.4.32-MariaDB
-- Phiên bản PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `dich_vu_cong`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `mau_bieu_mau`
--

CREATE TABLE `mau_bieu_mau` (
  `id` int(11) NOT NULL,
  `ten_bieu_mau` varchar(255) NOT NULL COMMENT 'Ví dụ: Đơn đăng ký bằng lái xe ô tô',
  `cau_truc_fields` text NOT NULL COMMENT 'Lưu cấu trúc JSON rỗng của các trường để JS tự vẽ form',
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `mau_bieu_mau`
--

INSERT INTO `mau_bieu_mau` (`id`, `ten_bieu_mau`, `cau_truc_fields`, `created_at`) VALUES
(1, 'Đơn xin nghỉ phép', '{\"form_name\":\"Đơn xin nghỉ phép\",\"fields\":[{\"id\":\"kinh_gui_cong_ty\",\"label\":\"Kính gửi Ban giám đốc - Công ty\",\"type\":\"text\",\"suggested_value\":\"\"},{\"id\":\"kinh_gui_truong_phong\",\"label\":\"Kính gửi Trưởng phòng\\/Đơn vị\",\"type\":\"text\",\"suggested_value\":\"\"},{\"id\":\"full_name\",\"label\":\"Tên tôi là\",\"type\":\"text\",\"suggested_value\":\"\"},{\"id\":\"gender\",\"label\":\"Nam\\/Nữ\",\"type\":\"text\",\"suggested_value\":\"\"},{\"id\":\"dob\",\"label\":\"Ngày, tháng, năm sinh\",\"type\":\"date\",\"suggested_value\":\"\"},{\"id\":\"pob\",\"label\":\"Nơi sinh\",\"type\":\"text\",\"suggested_value\":\"\"},{\"id\":\"address\",\"label\":\"Địa chỉ thường trú\",\"type\":\"text\",\"suggested_value\":\"\"},{\"id\":\"phone\",\"label\":\"Điện thoại liên hệ khi cần\",\"type\":\"text\",\"suggested_value\":\"\"},{\"id\":\"department\",\"label\":\"Đơn vị công tác\",\"type\":\"text\",\"suggested_value\":\"\"},{\"id\":\"position\",\"label\":\"Chức vụ\",\"type\":\"text\",\"suggested_value\":\"\"},{\"id\":\"de_nghi_cong_ty\",\"label\":\"Đề nghị Ban lãnh đạo - Công ty\",\"type\":\"text\",\"suggested_value\":\"\"},{\"id\":\"from_date\",\"label\":\"Nghỉ phép từ\",\"type\":\"text\",\"suggested_value\":\"\"},{\"id\":\"to_date\",\"label\":\"Nghỉ phép đến\",\"type\":\"text\",\"suggested_value\":\"\"},{\"id\":\"reason\",\"label\":\"Lý do\",\"type\":\"text\",\"suggested_value\":\"\"},{\"id\":\"vacation_place\",\"label\":\"Nơi nghỉ phép\",\"type\":\"text\",\"suggested_value\":\"\"},{\"id\":\"handover_to\",\"label\":\"Bàn giao công việc cho ông (bà)\",\"type\":\"text\",\"suggested_value\":\"\"},{\"id\":\"replacement_person\",\"label\":\"Người thay thế hoàn thành nhiệm vụ\",\"type\":\"text\",\"suggested_value\":\"\"},{\"id\":\"created_location_date\",\"label\":\"Địa điểm, ngày tháng làm đơn\",\"type\":\"text\",\"suggested_value\":\"\"}]}', '2026-07-27 22:13:52');

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `mau_bieu_mau`
--
ALTER TABLE `mau_bieu_mau`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `ten_bieu_mau` (`ten_bieu_mau`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `mau_bieu_mau`
--
ALTER TABLE `mau_bieu_mau`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

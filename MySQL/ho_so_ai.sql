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
-- Cấu trúc bảng cho bảng `ho_so_ai`
--

CREATE TABLE `ho_so_ai` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL COMMENT 'ID của công dân nộp đơn',
  `form_name` varchar(255) NOT NULL COMMENT 'Tên loại đơn AI quét được',
  `data_content` text NOT NULL COMMENT 'Toàn bộ nội dung đơn lưu dạng JSON',
  `created_at` datetime NOT NULL COMMENT 'Thời gian nộp đơn',
  `status` varchar(50) DEFAULT 'Chờ duyệt' COMMENT 'Trạng thái xử lý hồ sơ'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `ho_so_ai`
--

INSERT INTO `ho_so_ai` (`id`, `user_id`, `form_name`, `data_content`, `created_at`, `status`) VALUES
(1, 1, 'Đơn xin nghỉ phép', '{\"company_name\":\"\",\"department_head\":\"\",\"full_name\":\"Nguyễn Văn A\",\"gender\":\"\",\"dob\":\"\",\"pob\":\"\",\"address\":\"Số 1 Hùng Vương, phường Thắng Lợi, TP. Buôn Ma Thuột, Đắk Lắk\",\"phone_number\":\"\",\"workplace\":\"\",\"position\":\"\",\"leave_from_time\":\"\",\"leave_to_time\":\"\",\"reason\":\"\",\"leave_location\":\"\",\"handover_to\":\"\",\"substitute_person\":\"\",\"city_date\":\"\"}', '2026-07-27 21:26:43', 'Chờ duyệt'),
(2, 1, 'ĐƠN XIN NGHỈ PHÉP', '{\"company_name\":\"\",\"department_head\":\"\",\"full_name\":\"Nguyễn Văn A\",\"gender\":\"\",\"dob\":\"\",\"pob\":\"\",\"address\":\"Số 1 Hùng Vương, phường Thắng Lợi, TP. Buôn Ma Thuột, Đắk Lắk\",\"phone_number\":\"\",\"work_unit\":\"\",\"position\":\"\",\"leave_from\":\"\",\"leave_to\":\"\",\"reason\":\"\",\"leave_location\":\"\",\"handover_to\":\"\",\"substitute_person\":\"\",\"created_date\":\"\"}', '2026-07-27 22:07:06', 'Chờ duyệt'),
(3, 1, 'Đơn xin nghỉ phép', '{\"kinh_gui_cong_ty\":\"\",\"kinh_gui_truong_phong\":\"\",\"full_name\":\"Nguyễn Văn A\",\"gender\":\"\",\"dob\":\"\",\"pob\":\"\",\"address\":\"Số 1 Hùng Vương, phường Thắng Lợi, TP. Buôn Ma Thuột, Đắk Lắk\",\"phone\":\"\",\"department\":\"\",\"position\":\"\",\"de_nghi_cong_ty\":\"\",\"from_date\":\"\",\"to_date\":\"\",\"reason\":\"\",\"vacation_place\":\"\",\"handover_to\":\"\",\"replacement_person\":\"\",\"created_location_date\":\"\"}', '2026-07-27 22:13:54', 'Chờ duyệt');

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `ho_so_ai`
--
ALTER TABLE `ho_so_ai`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `ho_so_ai`
--
ALTER TABLE `ho_so_ai`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

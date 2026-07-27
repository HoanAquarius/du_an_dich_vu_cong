-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th7 25, 2026 lúc 03:41 AM
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
-- Cấu trúc bảng cho bảng `ho_so`
--

CREATE TABLE `ho_so` (
  `id` int(11) NOT NULL,
  `ma_ho_so` varchar(50) NOT NULL,
  `thu_tuc` varchar(255) NOT NULL,
  `cong_dan` varchar(255) NOT NULL,
  `target_time` datetime NOT NULL,
  `trang_thai` varchar(50) DEFAULT 'cho_xu_ly',
  `path_minh_chung` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `ho_so`
--

INSERT INTO `ho_so` (`id`, `ma_ho_so`, `thu_tuc`, `cong_dan`, `target_time`, `trang_thai`, `path_minh_chung`, `created_at`) VALUES
(1, 'HSO-2026-37071', 'Đăng ký khai sinh', 'NGUYỄN VĂN A', '2026-07-10 09:21:22', 'cho_xu_ly', NULL, '2026-07-09 07:21:22'),
(2, 'HSO-2026-12119', 'Đăng ký khai sinh', 'NGUYỄN VĂN A', '2026-07-10 09:21:25', 'cho_xu_ly', NULL, '2026-07-09 07:21:25'),
(3, 'HSO-2026-46218', 'Đăng ký khai sinh', 'NGUYỄN VĂN A', '2026-07-10 09:21:38', 'cho_xu_ly', NULL, '2026-07-09 07:21:38'),
(4, 'HSO-2026-23014', 'Đăng ký khai sinh', 'NGUYỄN VĂN A', '2026-07-14 09:33:48', 'cho_xu_ly', NULL, '2026-07-13 07:33:48');

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `ho_so`
--
ALTER TABLE `ho_so`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `ma_ho_so` (`ma_ho_so`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `ho_so`
--
ALTER TABLE `ho_so`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

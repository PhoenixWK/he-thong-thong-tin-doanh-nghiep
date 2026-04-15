-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 24, 2025 at 08:58 PM
-- Server version: 10.5.28-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";
SET FOREIGN_KEY_CHECKS=0;


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
--
-- Database: `bookstore`
--

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `SetGiamGiaSachBanCham` (IN `soSach` INT, IN `phanTramGiam` INT)   BEGIN
    -- 1. Xây dựng câu lệnh SQL động (Dynamic SQL)
    -- Lưu ý: Dấu nháy đơn (') trong chuỗi phải được viết là ('') để escape.
    SET @sql_query = CONCAT('
        UPDATE sach s
        JOIN (
            SELECT 
                s2.maSach
            FROM 
                sach s2
            LEFT JOIN 
                chitietdonhang ct ON s2.maSach = ct.maSach
            WHERE 
                s2.trangThai = ''Đang bán'' 
            GROUP BY 
                s2.maSach
            ORDER BY 
                SUM(COALESCE(ct.tienThu, 0)) ASC, 
                s2.soLuongBan ASC
            LIMIT ', soSach, ' 
        ) AS sach_te_nhat ON s.maSach = sach_te_nhat.maSach
        SET 
            s.phanTramGiamGia = ', phanTramGiam
    );

    -- 2. Chuẩn bị câu lệnh từ chuỗi vừa tạo
    PREPARE stmt FROM @sql_query;

    -- 3. Thực thi câu lệnh
    EXECUTE stmt;

    -- 4. Hủy câu lệnh sau khi dùng xong để giải phóng bộ nhớ
    DEALLOCATE PREPARE stmt;

    -- (Tùy chọn) Hiển thị kết quả kiểm tra
    -- Lưu ý: Lệnh này sẽ hiển thị TOÀN BỘ sách có mức giảm giá đó, 
    -- không chỉ riêng sách vừa update.
    SELECT maSach, tenSach, soLuongBan, phanTramGiamGia 
    FROM sach 
    WHERE phanTramGiamGia = phanTramGiam;
    
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `chitietdonhang`
--

CREATE TABLE `chitietdonhang` (
  `maDonHang` int(11) NOT NULL,
  `maSach` int(11) NOT NULL,
  `soLuong` int(11) NOT NULL,
  `tienThu` int(11) NOT NULL
) ;

--
-- Dumping data for table `chitietdonhang`
--

INSERT INTO `chitietdonhang` (`maDonHang`, `maSach`, `soLuong`, `tienThu`) VALUES
(63, 2, 1, 75000),
(63, 4, 3, 240000),
(63, 6, 1, 70000),
(63, 7, 2, 170000),
(63, 14, 1, 280000),
(63, 19, 1, 210000),
(63, 21, 3, 660000),
(63, 26, 1, 220000),
(63, 31, 5, 425000),
(63, 52, 1, 220000),
(65, 20, 1, 300000),
(89, 6, 8, 560000),
(89, 49, 1, 70000),
(90, 2, 4, 300000),
(90, 24, 4, 280000),
(90, 49, 6, 420000),
(91, 11, 4, 360000),
(91, 41, 4, 360000),
(92, 8, 1, 120000),
(101, 24, 1, 70000),
(101, 49, 1, 70000),
(103, 2, 7, 525000),
(104, 8, 4, 480000),
(104, 29, 1, 140000),
(108, 35, 4, 480000),
(111, 6, 4, 280000),
(111, 24, 4, 280000),
(112, 58, 1, 160000),
(113, 46, 5, 850000),
(114, 6, 1, 70000),
(114, 10, 2, 230000),
(114, 45, 1, 120000),
(114, 49, 11, 770000),
(116, 2, 9, 675000),
(117, 2, 4, 300000),
(117, 6, 18, 1260000),
(117, 20, 25, 7500000),
(118, 1, 99, 9504000),
(119, 6, 1, 70000),
(120, 6, 1, 70000),
(121, 6, 4, 280000),
(121, 49, 3, 210000),
(122, 32, 3, 270000),
(123, 2, 7, 525000),
(123, 7, 1, 85000),
(123, 24, 1, 70000),
(123, 26, 2, 440000),
(123, 32, 3, 270000),
(123, 41, 1, 90000),
(123, 49, 1, 70000),
(123, 52, 1, 220000),
(124, 50, 17, 2720000),
(125, 43, 1, 100000),
(126, 6, 2, 140000),
(127, 2, 85, 6375000),
(128, 18, 6, 1560000),
(129, 5, 6, 660000),
(130, 9, 11, 2750000),
(130, 26, 27, 5940000),
(130, 48, 17, 3910000),
(130, 56, 21, 3780000),
(131, 48, 6, 1380000),
(132, 2, 1, 75000),
(132, 4, 3, 240000),
(132, 11, 10, 900000),
(132, 24, 2, 140000),
(132, 42, 1, 150000),
(162, 7, 4, 340000),
(162, 49, 3, 210000),
(163, 7, 3, 255000),
(164, 4, 1, 80000),
(165, 6, 1, 70000),
(166, 24, 1, 70000),
(167, 24, 1, 70000),
(168, 31, 1, 85000),
(169, 20, 1, 300000),
(170, 48, 1, 230000),
(171, 20, 1, 300000),
(172, 20, 1, 300000),
(173, 20, 1, 300000),
(174, 19, 1, 210000),
(175, 19, 1, 210000),
(176, 48, 1, 230000),
(177, 19, 1, 210000),
(178, 19, 1, 210000),
(179, 19, 1, 210000),
(180, 19, 1, 210000),
(181, 6, 2, 140000),
(181, 32, 1, 90000),
(182, 6, 1, 70000),
(182, 41, 2, 180000),
(183, 5, 1, 110000),
(184, 20, 1, 300000),
(185, 20, 1, 300000);

--
-- Triggers `chitietdonhang`
--
DELIMITER $$
CREATE TRIGGER `trg_chiTietDonHang_tinhTien_insert` BEFORE INSERT ON `chitietdonhang` FOR EACH ROW BEGIN
    DECLARE v_giaBan INT DEFAULT 0;
    DECLARE v_giaSale INT DEFAULT 0;

    -- Lấy giá bán gốc và giá sale từ bảng Sách
    -- Sử dụng IFNULL để tránh lỗi nếu cột chưa có dữ liệu
    SELECT giaBan, IFNULL(giaSale, 0) 
    INTO v_giaBan, v_giaSale 
    FROM sach 
    WHERE maSach = NEW.maSach;

    -- LOGIC TÍNH TIỀN:
    -- Nếu có giá sale (lớn hơn 0) -> Dùng giá sale
    IF v_giaSale > 0 THEN
        SET NEW.tienThu = v_giaSale * NEW.soLuong;
    
    -- Ngược lại -> Dùng giá gốc
    ELSE
        SET NEW.tienThu = v_giaBan * NEW.soLuong;
    END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `trg_chiTietDonHang_tinhTien_update` BEFORE UPDATE ON `chitietdonhang` FOR EACH ROW BEGIN
    DECLARE v_giaBan INT DEFAULT 0;
    DECLARE v_giaSale INT DEFAULT 0;

    SELECT giaBan, IFNULL(giaSale, 0) 
    INTO v_giaBan, v_giaSale 
    FROM sach 
    WHERE maSach = NEW.maSach;

    IF v_giaSale > 0 THEN
        SET NEW.tienThu = v_giaSale * NEW.soLuong;
    ELSE
        SET NEW.tienThu = v_giaBan * NEW.soLuong;
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `chitietphieunhap`
--

CREATE TABLE `chitietphieunhap` (
  `maPhieuNhap` int(11) NOT NULL,
  `maSach` int(11) NOT NULL,
  `giaNhap` int(11) NOT NULL,
  `soLuong` int(11) NOT NULL
) ;

--
-- Dumping data for table `chitietphieunhap`
--

INSERT INTO `chitietphieunhap` (`maPhieuNhap`, `maSach`, `giaNhap`, `soLuong`) VALUES
(1, 1, 200, 2),
(1, 2, 1002, 7),
(1, 3, 2817, 5),
(1, 4, 2480, 5),
(1, 5, 9000, 10),
(1, 14, 1234, 100),
(2, 2, 324, 2),
(2, 3, 546475, 56),
(2, 4, 324, 343),
(2, 6, 324, 2),
(2, 12, 1000, 100),
(3, 1, 50000, 10),
(3, 4, 22222, 22),
(3, 5, 111111, 10),
(3, 11, 10000, 100),
(4, 5, 5678, 234),
(5, 6, 23213, 545),
(5, 7, 56788, 234),
(6, 5, 234, 23),
(6, 8, 234, 23),
(7, 4, 124, 2414),
(7, 6, 334, 342),
(7, 8, 23213, 545),
(8, 3, 80000, 24),
(8, 9, 20000, 5),
(9, 1, 779, 76),
(9, 2, 12412, 24),
(9, 4, 1244, 24),
(9, 12, 24124, 24),
(10, 1, 60000, 2),
(11, 2, 50000, 1),
(14, 3, 50000, 2),
(15, 12, 1000, 2),
(16, 8, 10000, 10),
(17, 1, 10000, 5),
(18, 2, 15000, 10),
(19, 9, 100000, 2),
(20, 2, 50000, 2),
(21, 6, 1000, 200),
(22, 10, 90000, 10),
(23, 80, 70000, 30);

-- --------------------------------------------------------

--
-- Table structure for table `chitietquyen`
--

CREATE TABLE `chitietquyen` (
  `maQuyen` int(11) NOT NULL,
  `maChucNang` int(11) NOT NULL,
  `maHanhDong` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `chitietquyen`
--

INSERT INTO `chitietquyen` (`maQuyen`, `maChucNang`, `maHanhDong`) VALUES
(1, 5, 5),
(1, 6, 5),
(1, 7, 5),
(1, 8, 5),
(1, 9, 5),
(1, 10, 5),
(1, 11, 5),
(1, 12, 5),
(1, 13, 5),
(1, 14, 5),
(1, 15, 5),
(1, 16, 5),
(3, 3, 1),
(3, 9, 1),
(3, 9, 2),
(3, 9, 3),
(3, 9, 4),
(3, 9, 5),
(3, 10, 1),
(3, 10, 2),
(3, 10, 3),
(3, 10, 4),
(3, 10, 5),
(3, 11, 1),
(3, 11, 2),
(3, 11, 3),
(3, 11, 4),
(3, 11, 5),
(3, 12, 1),
(3, 12, 2),
(3, 12, 3),
(3, 12, 4),
(3, 12, 5),
(3, 13, 1),
(3, 13, 2),
(3, 13, 3),
(3, 13, 4),
(3, 13, 5),
(3, 14, 1),
(3, 14, 2),
(3, 14, 3),
(3, 14, 4),
(3, 14, 5),
(3, 15, 1),
(3, 15, 2),
(3, 15, 3),
(3, 15, 4),
(3, 15, 5),
(11, 1, 1),
(11, 2, 1),
(11, 3, 2),
(11, 4, 2),
(11, 5, 3),
(11, 6, 3),
(11, 8, 2),
(11, 10, 1),
(11, 11, 1),
(11, 12, 2),
(11, 13, 2),
(12, 1, 1),
(12, 1, 2),
(12, 1, 3),
(12, 3, 4),
(12, 4, 4),
(13, 3, 1),
(13, 3, 2),
(13, 3, 3),
(13, 3, 4),
(13, 4, 1),
(13, 4, 2),
(13, 5, 1),
(14, 1, 1),
(14, 1, 2),
(14, 7, 2),
(14, 7, 3),
(14, 8, 2),
(14, 9, 2),
(14, 10, 2),
(14, 12, 1),
(14, 13, 1),
(14, 15, 4),
(14, 16, 4),
(15, 1, 1),
(15, 1, 2),
(15, 1, 3),
(15, 1, 4),
(15, 2, 1),
(15, 2, 2),
(15, 2, 3),
(15, 2, 4),
(15, 3, 1),
(15, 3, 2),
(15, 3, 3),
(15, 3, 4),
(15, 4, 1),
(15, 4, 2),
(15, 4, 3),
(15, 4, 4),
(15, 5, 1),
(15, 5, 2),
(15, 5, 3),
(15, 5, 4),
(15, 5, 5),
(15, 6, 1),
(15, 6, 2),
(15, 6, 3),
(15, 6, 4),
(15, 6, 5),
(15, 7, 1),
(15, 7, 2),
(15, 7, 3),
(15, 7, 4),
(15, 7, 5),
(15, 8, 1),
(15, 8, 2),
(15, 8, 3),
(15, 8, 4),
(15, 8, 5),
(15, 9, 1),
(15, 9, 2),
(15, 9, 3),
(15, 9, 4),
(15, 9, 5),
(15, 10, 1),
(15, 10, 2),
(15, 10, 3),
(15, 10, 4),
(15, 10, 5),
(15, 11, 1),
(15, 11, 2),
(15, 11, 3),
(15, 11, 4),
(15, 11, 5),
(15, 12, 1),
(15, 12, 2),
(15, 12, 3),
(15, 12, 4),
(15, 12, 5),
(15, 13, 1),
(15, 13, 2),
(15, 13, 3),
(15, 13, 4),
(15, 13, 5),
(15, 14, 1),
(15, 14, 2),
(15, 14, 3),
(15, 14, 4),
(15, 14, 5),
(15, 15, 1),
(15, 15, 2),
(15, 15, 3),
(15, 15, 4),
(15, 15, 5),
(15, 16, 1),
(15, 16, 2),
(15, 16, 3),
(15, 16, 4),
(15, 16, 5),
(16, 2, 1),
(16, 5, 1),
(16, 5, 2),
(16, 5, 3),
(16, 5, 4),
(16, 5, 5),
(16, 6, 1),
(16, 6, 2),
(16, 6, 3),
(16, 6, 4),
(16, 6, 5),
(16, 16, 1),
(16, 16, 2),
(16, 16, 3),
(16, 16, 4),
(16, 16, 5);

-- --------------------------------------------------------

--
-- Table structure for table `chucnang`
--

CREATE TABLE `chucnang` (
  `maChucNang` int(11) NOT NULL,
  `tenChucNang` varchar(100) NOT NULL,
  `trangThai` enum('Hoạt động','Tạm dừng') DEFAULT 'Hoạt động'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `chucnang`
--

INSERT INTO `chucnang` (`maChucNang`, `tenChucNang`, `trangThai`) VALUES
(1, 'Thống kê lợi nhuận', 'Hoạt động'),
(2, 'Thống kê doanh thu', 'Hoạt động'),
(3, 'Thống kê phiếu nhập', 'Hoạt động'),
(4, 'Thống kê đơn hàng', 'Hoạt động'),
(5, 'Đơn hàng', 'Hoạt động'),
(6, 'Phiếu giảm giá', 'Hoạt động'),
(7, 'Nhóm quyền', 'Hoạt động'),
(8, 'Người dùng', 'Hoạt động'),
(9, 'Nhà cung cấp', 'Hoạt động'),
(10, 'Phiếu nhập', 'Hoạt động'),
(11, 'Sách', 'Hoạt động'),
(12, 'Tác giả', 'Hoạt động'),
(13, 'Thể loại', 'Hoạt động'),
(14, 'Loại bìa', 'Hoạt động'),
(15, 'Nhà xuất bản', 'Hoạt động'),
(16, 'Thẻ thanh toán', 'Hoạt động');

-- --------------------------------------------------------

--
-- Table structure for table `diachinguoidung`
--

CREATE TABLE `diachinguoidung` (
  `maDiaChi` int(11) NOT NULL,
  `maNguoiDung` int(11) NOT NULL,
  `tinhThanh` varchar(255) DEFAULT NULL,
  `quanHuyen` varchar(255) DEFAULT NULL,
  `phuongXa` varchar(255) DEFAULT NULL,
  `soNha` varchar(255) DEFAULT NULL,
  `macDinh` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `diachinguoidung`
--

INSERT INTO `diachinguoidung` (`maDiaChi`, `maNguoiDung`, `tinhThanh`, `quanHuyen`, `phuongXa`, `soNha`, `macDinh`) VALUES
(6, 23, 'Thành phố Hồ Chí Minh', 'Quận Bình Tân', 'Phường Bình Trị Đông', '479 Hương Lộ 2', NULL),
(7, 22, 'undefined', 'undefined', 'undefined', 'Chưa có địa chỉ', NULL),
(8, 52, 'TP Hồ Chí Minh', 'Quận 1', 'Phường Bến Nghé', '12 Nguyễn Huệ', NULL),
(9, 53, 'Thành Phố Hà Nội', 'Quận Ba Đình', 'Phường Ngọc Hà', '123', NULL),
(10, 23, 'Thành phố Hồ Chí Minh', 'Quận 11', 'Phường 14', '100 Hàm Tử', NULL),
(11, 1, '  TP Hồ Chí Minh', '  Quận 1', '  Phường Bến Nghé', '12 Nguyễn Huệ', NULL),
(12, 1, '  TP Hồ Chí Minh', '  Quận 1', '  Phường Bến Nghé', '12 Nguyễn Huệ', NULL),
(13, 1, '  TP Hồ Chí Minh', '  Quận 1', '  Phường Bến Nghé', '12 Nguyễn Huệ', NULL),
(14, 1, '  TP Hồ Chí Minh', '  Quận 1', '  Phường Bến Nghé', '12 Nguyễn Huệ', NULL),
(15, 1, '  TP Hồ Chí Minh', '  Quận 1', '  Phường Bến Nghé', '12 Nguyễn Huệ', NULL),
(16, 23, 'Tỉnh Bắc Kạn', 'Huyện Ba Bể', 'Xã Địa Linh', '100 Hàm Tử', NULL),
(17, 55, 'Thành phố Hà Nội', 'Huyện Mê Linh', 'Xã Văn Khê', 'bÁdadasd', NULL),
(18, 23, 'Tỉnh Ninh Bình', 'Thành phố Hoa Lư', 'Phường Nam Bình', '123 ronaldo', NULL),
(20, 64, 'Tỉnh Bắc Kạn', 'Huyện Bạch Thông', 'Xã Mỹ Thanh', 'quanlykho1', NULL),
(21, 65, 'undefined', 'undefined', 'undefined', 'Chưa có địa chỉ', NULL),
(22, 68, 'TP HCM', 'Quận 12', 'phường Hiệp Thành', '12 Đường Hiệp Thành', NULL),
(23, 69, ' TP Hồ Chí Minh', ' Quận 1', ' Phường Bến Nghé', '14', NULL),
(24, 70, 'Thành Phố Hà Nội', 'Quận Ba Đình', 'Phường Ngọc Hà', '123', NULL),
(25, 73, 'Tỉnh Điện Biên', 'Huyện Tủa Chùa', 'Xã Tả Phìn', '123', NULL),
(26, 74, ' Tỉnh Đồng Nai', ' Thành phố Long Khánh', ' Phường Xuân Lập', '142 Tổ 12 KP Phú Mỹ', NULL),
(27, 76, 'Thành phố Hồ Chí Minh', 'Quận 1', 'Phường Bến Nghé', 'abc', NULL),
(28, 78, 'Thành phố Hồ Chí Minh', 'Quận 10', 'Phường 1', 'Hồ Thị Kỉ', NULL),
(29, 80, 'Thành phố Hồ Chí Minh', 'Quận 10', 'Phường 1', 'Hồ Thị Kỉ', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `donhang`
--

CREATE TABLE `donhang` (
  `maDonHang` int(11) NOT NULL,
  `ngayTaoDon` datetime DEFAULT current_timestamp(),
  `maKhachHang` int(11) NOT NULL,
  `maKhuyenMai` int(11) DEFAULT NULL,
  `dcNguoiNhan` text DEFAULT NULL,
  `tongTienThu` int(11) NOT NULL DEFAULT 0,
  `ngayCapNhat` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `maNhanVien` int(11) DEFAULT NULL,
  `maPhuongThuc` int(11) NOT NULL,
  `trangThaiThanhToan` enum('Đã thanh toán','Chưa thanh toán') DEFAULT NULL,
  `trangThai` enum('Đang chờ xác nhận','Đã xác nhận','Đã giao hàng','Đã hủy đơn') DEFAULT 'Đang chờ xác nhận',
  `hvtNguoiNhan` text DEFAULT NULL,
  `sdtNguoiNhan` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `donhang`
--

INSERT INTO `donhang` (`maDonHang`, `ngayTaoDon`, `maKhachHang`, `maKhuyenMai`, `dcNguoiNhan`, `tongTienThu`, `ngayCapNhat`, `maNhanVien`, `maPhuongThuc`, `trangThaiThanhToan`, `trangThai`, `hvtNguoiNhan`, `sdtNguoiNhan`) VALUES
(63, '2025-04-22 07:11:06', 23, NULL, 'Thành phố Hồ Chí Minh / Quận 11 / Phường 11 / 123 Trần Bình Trọng', 2570000, '2025-05-12 15:38:01', 55, 1, 'Đã thanh toán', 'Đã giao hàng', 'Nguyễn Nam Dương', '0388853835'),
(65, '2025-04-22 07:58:23', 1, NULL, 'Thành phố Hồ Chí Minh / Quận 5 / Phường 2 / 237 An Dương Vương', 300000, '2025-05-12 15:36:14', 55, 1, 'Đã thanh toán', 'Đã xác nhận', 'Nguyễn Nam Dương', '0388853835'),
(89, '2025-05-10 02:02:30', 23, NULL, 'Thành phố Hồ Chí Minh / Quận Bình Tân / Phường Bình Trị Đông / 479 Hương Lộ 2', 650000, '2025-05-12 16:11:08', 55, 1, 'Chưa thanh toán', 'Đã hủy đơn', 'Nguyễn Nam Dương', '0388853835'),
(90, '2025-05-10 02:03:48', 23, NULL, 'Thành phố Hồ Chí Minh / Quận Bình Tân / Phường Bình Trị Đông / 479 Hương Lộ 2', 1020000, '2025-05-13 11:46:20', 23, 1, 'Đã thanh toán', 'Đã giao hàng', 'Nguyễn Nam Dương', '0388853835'),
(91, '2025-05-10 02:05:09', 23, NULL, 'Thành phố Hồ Chí Minh / Quận Bình Tân / Phường Bình Trị Đông / 479 Hương Lộ 2', 740000, '2025-05-13 11:47:40', 1, 1, 'Chưa thanh toán', 'Đã hủy đơn', 'Nguyễn Nam Dương', '0388853835'),
(92, '2025-05-10 02:06:02', 23, NULL, 'Thành phố Hồ Chí Minh / Quận Bình Tân / Phường Bình Trị Đông / 479 Hương Lộ 2', 140000, '2025-05-14 10:07:11', 55, 1, 'Chưa thanh toán', 'Đã xác nhận', 'Nguyễn Nam Dương', '0388853835'),
(100, '2025-05-10 02:24:38', 23, NULL, 'Test địa chỉ giao', 400000, '2025-05-10 02:24:38', NULL, 1, 'Chưa thanh toán', 'Đang chờ xác nhận', NULL, NULL),
(101, '2025-05-10 02:30:17', 55, NULL, 'Tỉnh Đồng Nai / Thành phố Long Khánh / Phường Xuân Lập / Bảo Bình', 160000, '2025-05-10 02:30:17', NULL, 1, 'Chưa thanh toán', 'Đang chờ xác nhận', 'Phạm Thị Trà My', 'Phạm Thị Trà My'),
(103, '2025-05-10 02:35:43', 55, NULL, 'Tỉnh Hải Dương / Thành phố Chí Linh / Phường Đồng Lạc / Hello My name is Quy', 545000, '2025-05-10 02:35:43', NULL, 1, 'Chưa thanh toán', 'Đang chờ xác nhận', 'Trần Thanh Quy', '0388853835'),
(104, '2025-05-10 02:37:57', 23, NULL, 'Thành phố Hồ Chí Minh / Quận Bình Tân / Phường Bình Trị Đông / 479 Hương Lộ 2', 640000, '2025-05-10 02:37:57', NULL, 1, 'Chưa thanh toán', 'Đang chờ xác nhận', 'Chenh Tien Lam', '0909380633'),
(108, '2025-05-10 02:46:13', 23, NULL, 'Thành phố Hồ Chí Minh / Quận Bình Tân / Phường Bình Trị Đông / 479 Hương Lộ 2', 500000, '2025-05-10 02:46:13', NULL, 1, 'Chưa thanh toán', 'Đang chờ xác nhận', 'My name is Nam Duong', '0388853835'),
(111, '2025-05-10 02:54:39', 22, NULL, 'undefined / undefined / undefined / Chưa có địa chỉ', 580000, '2025-05-10 02:54:39', NULL, 1, 'Chưa thanh toán', 'Đang chờ xác nhận', 'Nguyen Nam Duong', '0909385922'),
(112, '2025-05-10 02:57:34', 23, 12, 'Thành phố Hồ Chí Minh / Quận Bình Tân / Phường Bình Trị Đông / 479 Hương Lộ 2', 150000, '2025-05-10 02:57:34', NULL, 1, 'Chưa thanh toán', 'Đang chờ xác nhận', 'Chênh Tiên Luân', '0909380633'),
(113, '2025-05-10 03:04:24', 23, 12, 'Thành phố Hồ Chí Minh / Quận 11 / Phường 14 / 100 Hàm Tử', 657500, '2025-05-10 03:04:24', NULL, 1, 'Chưa thanh toán', 'Đang chờ xác nhận', 'Triệu Phú Lâm', '0909380666'),
(114, '2025-05-11 08:24:17', 55, NULL, 'Thành phố Hà Nội / Huyện Mê Linh / Xã Văn Khê / bÁdadasd', 1800000, '2025-05-11 08:24:17', NULL, 1, 'Chưa thanh toán', 'Đang chờ xác nhận', 'Nguyễn Thành Đạt', '0388853835'),
(116, '2025-05-12 04:45:13', 55, 12, 'Thành phố Hà Nội / Huyện Mê Linh / Xã Văn Khê / bÁdadasd', 526250, '2025-05-12 04:45:13', NULL, 1, 'Chưa thanh toán', 'Đang chờ xác nhận', 'Triệu Lâm', '0909385339'),
(117, '2025-05-12 07:32:42', 55, NULL, 'Thành phố Hà Nội / Huyện Mê Linh / Xã Văn Khê / bÁdadasd', 9080000, '2025-05-12 16:06:35', 1, 3, 'Chưa thanh toán', 'Đang chờ xác nhận', 'Phạm Thị Trà My', '0329276436'),
(118, '2025-05-12 08:56:09', 55, NULL, 'Thành phố Hà Nội / Huyện Mê Linh / Xã Văn Khê / bÁdadasd', 9524000, '2025-05-12 15:59:06', 55, 1, 'Chưa thanh toán', 'Đang chờ xác nhận', 'Trần Thanh Quy', '0965162116'),
(119, '2025-05-13 03:17:18', 23, 12, 'Thành phố Hồ Chí Minh / Quận Bình Tân / Phường Bình Trị Đông / 479 Hương Lộ 2', 90000, '2025-05-13 03:17:18', NULL, 2, 'Chưa thanh toán', 'Đang chờ xác nhận', 'Chênh Tiên Luân', '0909385339'),
(120, '2025-05-13 03:19:21', 23, NULL, 'Thành phố Hồ Chí Minh / Quận Bình Tân / Phường Bình Trị Đông / 479 Hương Lộ 2', 90000, '2025-05-13 03:19:21', NULL, 2, 'Chưa thanh toán', 'Đang chờ xác nhận', 'Chênh Tiên Luân', '0909385339'),
(121, '2025-05-13 04:32:15', 23, 12, 'Thành phố Hồ Chí Minh / Quận Bình Tân / Phường Bình Trị Đông / 479 Hương Lộ 2', 387500, '2025-05-13 04:32:15', NULL, 2, 'Chưa thanh toán', 'Đang chờ xác nhận', 'Chênh Tiên Luân', '0909385339'),
(122, '2025-05-13 04:34:01', 23, 12, 'Thành phố Hồ Chí Minh / Quận Bình Tân / Phường Bình Trị Đông / 479 Hương Lộ 2', 252500, '2025-05-13 04:34:01', NULL, 2, 'Chưa thanh toán', 'Đang chờ xác nhận', 'Chenh Tien Lam', '0909385339'),
(123, '2025-05-13 12:01:29', 1, NULL, 'undefined / undefined / undefined / Chưa có địa chỉ', 1790000, '2025-05-13 12:01:29', NULL, 1, 'Chưa thanh toán', 'Đang chờ xác nhận', 'Nguyễn Nam Dương', '0388853835'),
(124, '2025-05-13 12:53:38', 23, NULL, 'Thành phố Hồ Chí Minh / Quận Bình Tân / Phường Bình Trị Đông / 479 Hương Lộ 2', 2740000, '2025-05-13 19:54:46', 1, 2, 'Chưa thanh toán', 'Đã hủy đơn', 'nkJZCnm,czxnm,ZXCnm,xcz', '0966970576'),
(125, '2025-05-13 23:48:44', 55, NULL, 'Thành phố Hà Nội / Huyện Mê Linh / Xã Văn Khê / bÁdadasd', 150000, '2025-05-13 23:48:44', NULL, 1, 'Chưa thanh toán', 'Đang chờ xác nhận', 'Nguyễn Nam Dương', '0388853835'),
(126, '2025-05-14 01:48:31', 60, NULL, 'Thành phố Hồ Chí Minh / Quận 12 / Phường Hiệp Thành / 17/4D Đường HT43 , phường Hiệp Thành, Quận 12', 160000, '2025-05-14 08:53:22', 60, 1, 'Chưa thanh toán', 'Đã xác nhận', 'autan', '0869547255'),
(127, '2025-05-14 03:01:06', 55, NULL, 'Thành phố Hà Nội / Huyện Mê Linh / Xã Văn Khê / bÁdadasd', 6405000, '2025-05-14 03:01:06', NULL, 1, 'Chưa thanh toán', 'Đang chờ xác nhận', 'thanh quy', '0909090909'),
(128, '2025-05-14 03:02:37', 55, NULL, 'Thành phố Hà Nội / Huyện Mê Linh / Xã Văn Khê / bÁdadasd', 1580000, '2025-05-14 10:03:17', 55, 1, 'Chưa thanh toán', 'Đã giao hàng', 'nam duong', '1234567890'),
(129, '2025-05-14 03:05:06', 55, NULL, 'Thành phố Hà Nội / Huyện Mê Linh / Xã Văn Khê / bÁdadasd', 680000, '2025-05-14 10:05:52', 55, 1, 'Chưa thanh toán', 'Đã hủy đơn', 'tuan', '0909090909'),
(130, '2025-05-14 03:08:31', 55, NULL, 'Thành phố Hà Nội / Huyện Mê Linh / Xã Văn Khê / bÁdadasd', 16400000, '2025-05-14 10:09:12', 55, 1, 'Chưa thanh toán', 'Đã giao hàng', 'Nguyễn Dương', '0388853835'),
(131, '2025-05-14 03:13:39', 55, 15, 'Thành phố Hà Nội / Huyện Mê Linh / Xã Văn Khê / bÁdadasd', 1300000, '2025-05-14 03:13:39', NULL, 1, 'Chưa thanh toán', 'Đang chờ xác nhận', 'thanh quy', '0909090923'),
(132, '2025-05-14 09:14:44', 23, NULL, 'Thành phố Hồ Chí Minh / Quận Bình Tân / Phường Bình Trị Đông / 479 Hương Lộ 2', 1525000, '2025-05-14 09:14:44', NULL, 1, 'Chưa thanh toán', 'Đang chờ xác nhận', 'Chênh Tiên Luân', '0909385339'),
(133, '2025-05-15 07:38:49', 76, NULL, 'Thành phố Hồ Chí Minh / Quận 1 / Phường Bến Nghé / abc', 4340000, '2025-05-15 07:38:49', NULL, 2, 'Chưa thanh toán', 'Đang chờ xác nhận', 'abc', '0965162116'),
(134, '2025-05-15 07:39:22', 76, NULL, 'Thành phố Hồ Chí Minh / Quận 1 / Phường Bến Nghé / abc', 4350000, '2025-05-15 07:39:22', NULL, 4, 'Chưa thanh toán', 'Đang chờ xác nhận', 'abc', '0388853835'),
(135, '2025-05-15 07:40:22', 76, NULL, 'Thành phố Hồ Chí Minh / Quận 1 / Phường Bến Nghé / abc', 1100000, '2025-05-15 07:40:22', NULL, 1, 'Chưa thanh toán', 'Đang chờ xác nhận', 'abc', '0388853835'),
(136, '2025-10-21 23:48:58', 78, NULL, 'Thành phố Hồ Chí Minh / Quận 10 / Phường 1 / Hồ Thị Kỉ', 105000, '2025-10-21 23:48:58', NULL, 3, 'Chưa thanh toán', 'Đang chờ xác nhận', 'minh', '0903961231'),
(137, '2025-10-22 00:04:24', 78, NULL, 'Thành phố Hồ Chí Minh / Quận 10 / Phường 1 / Hồ Thị Kỉ', 105000, '2025-10-22 00:04:24', NULL, 3, 'Chưa thanh toán', 'Đang chờ xác nhận', 'minh', '0123456789'),
(138, '2025-10-22 00:06:03', 78, NULL, 'Thành phố Hồ Chí Minh / Quận 10 / Phường 1 / Hồ Thị Kỉ', 105000, '2025-10-22 00:06:03', NULL, 3, 'Chưa thanh toán', 'Đang chờ xác nhận', 'minh', '0123456789'),
(139, '2025-10-22 00:07:19', 78, NULL, 'Thành phố Hồ Chí Minh / Quận 10 / Phường 1 / Hồ Thị Kỉ', 105000, '2025-10-22 00:07:19', NULL, 3, 'Chưa thanh toán', 'Đang chờ xác nhận', 'minh', '0123456789'),
(140, '2025-10-22 00:15:13', 78, NULL, 'Thành phố Hồ Chí Minh / Quận 10 / Phường 1 / Hồ Thị Kỉ', 115000, '2025-10-22 00:15:13', NULL, 3, 'Chưa thanh toán', 'Đang chờ xác nhận', 'minh', '0123456789'),
(141, '2025-10-22 00:39:23', 78, NULL, 'Thành phố Hồ Chí Minh / Quận 10 / Phường 1 / Hồ Thị Kỉ', 230000, '2025-10-22 00:39:23', NULL, 3, 'Chưa thanh toán', 'Đang chờ xác nhận', 'minh', '0123456789'),
(142, '2025-10-22 01:11:56', 78, NULL, 'Thành phố Hồ Chí Minh / Quận 10 / Phường 1 / Hồ Thị Kỉ', 120000, '2025-10-22 01:11:56', NULL, 3, 'Chưa thanh toán', 'Đang chờ xác nhận', 'Võ Chí Minh', '0123456789'),
(143, '2025-10-22 01:12:45', 78, NULL, 'Thành phố Hồ Chí Minh / Quận 10 / Phường 1 / Hồ Thị Kỉ', 150000, '2025-10-22 01:12:45', NULL, 3, 'Chưa thanh toán', 'Đang chờ xác nhận', 'Võ Chí Minh', '0123456789'),
(144, '2025-10-22 01:18:33', 78, NULL, 'Thành phố Hồ Chí Minh / Quận 10 / Phường 1 / Hồ Thị Kỉ', 180000, '2025-10-22 01:18:33', NULL, 3, 'Chưa thanh toán', 'Đang chờ xác nhận', 'Võ Chí Minh', '0123456789'),
(145, '2025-10-22 01:18:58', 78, NULL, 'Thành phố Hồ Chí Minh / Quận 10 / Phường 1 / Hồ Thị Kỉ', 210000, '2025-10-22 01:18:58', NULL, 3, 'Chưa thanh toán', 'Đang chờ xác nhận', 'Võ Chí Minh', '0123456789'),
(146, '2025-10-22 01:22:37', 78, NULL, 'Thành phố Hồ Chí Minh / Quận 10 / Phường 1 / Hồ Thị Kỉ', 270000, '2025-10-22 01:22:37', NULL, 3, 'Chưa thanh toán', 'Đang chờ xác nhận', 'Võ Chí Minh', '0123456789'),
(147, '2025-10-22 01:28:27', 78, NULL, 'Thành phố Hồ Chí Minh / Quận 10 / Phường 1 / Hồ Thị Kỉ', 320000, '2025-10-22 01:28:27', NULL, 3, 'Chưa thanh toán', 'Đang chờ xác nhận', 'Võ Chí Minh', '0123456789'),
(148, '2025-10-22 01:29:51', 78, NULL, 'Thành phố Hồ Chí Minh / Quận 10 / Phường 1 / Hồ Thị Kỉ', 430000, '2025-10-22 01:29:51', NULL, 3, 'Chưa thanh toán', 'Đang chờ xác nhận', 'Võ Chí Minh', '0123456789'),
(149, '2025-10-22 01:30:58', 78, NULL, 'Thành phố Hồ Chí Minh / Quận 10 / Phường 1 / Hồ Thị Kỉ', 420000, '2025-10-22 01:30:58', NULL, 3, 'Chưa thanh toán', 'Đang chờ xác nhận', 'Võ Chí Minh', '0123456789'),
(150, '2025-10-22 01:34:35', 78, NULL, 'Thành phố Hồ Chí Minh / Quận 10 / Phường 1 / Hồ Thị Kỉ', 420000, '2025-10-22 01:34:35', NULL, 1, 'Chưa thanh toán', 'Đang chờ xác nhận', 'Võ Chí Minh', '0123456789'),
(151, '2025-10-22 01:37:13', 78, NULL, 'Thành phố Hồ Chí Minh / Quận 10 / Phường 1 / Hồ Thị Kỉ', 430000, '2025-10-22 01:37:13', NULL, 1, 'Chưa thanh toán', 'Đang chờ xác nhận', 'Võ Chí Minh', '0903961231'),
(152, '2025-10-22 01:41:08', 78, NULL, 'Thành phố Hồ Chí Minh / Quận 10 / Phường 1 / Hồ Thị Kỉ', 330000, '2025-10-22 01:41:08', NULL, 3, 'Chưa thanh toán', 'Đang chờ xác nhận', 'Minh Vo Chi', '0903961231'),
(153, '2025-10-22 01:43:31', 78, NULL, 'Thành phố Hồ Chí Minh / Quận 10 / Phường 1 / Hồ Thị Kỉ', 300000, '2025-10-22 01:43:31', NULL, 3, 'Chưa thanh toán', 'Đang chờ xác nhận', 'Minh Vo Chi', '0903961231'),
(154, '2025-10-22 01:50:30', 78, NULL, 'Thành phố Hồ Chí Minh / Quận 10 / Phường 1 / Hồ Thị Kỉ', 310000, '2025-10-22 01:50:30', NULL, 3, 'Chưa thanh toán', 'Đang chờ xác nhận', 'Minh Vo Chi', '0903961231'),
(155, '2025-10-22 01:52:10', 78, NULL, 'Thành phố Hồ Chí Minh / Quận 10 / Phường 1 / Hồ Thị Kỉ', 310000, '2025-10-22 01:52:10', NULL, 1, 'Chưa thanh toán', 'Đang chờ xác nhận', 'Minh Vo Chi', '0903961231'),
(156, '2025-10-23 09:29:07', 78, NULL, 'Thành phố Hồ Chí Minh / Quận 10 / Phường 1 / Hồ Thị Kỉ', 210000, '2025-10-23 09:29:07', NULL, 3, 'Chưa thanh toán', 'Đang chờ xác nhận', 'Võ Chí Minh', '0123456789'),
(157, '2025-10-28 10:11:51', 79, NULL, 'Thành phố Hà Nội / Quận Ba Đình / Phường Kim Mã / abc', 200000, '2025-10-28 10:11:51', NULL, 3, 'Chưa thanh toán', 'Đang chờ xác nhận', 'test', '0912345678'),
(158, '2025-11-11 15:36:14', 80, NULL, 'Thành phố Hồ Chí Minh / Quận 10 / Phường 1 / Hồ Thị Kỉ', 230000, '2025-11-11 15:36:14', NULL, 3, 'Chưa thanh toán', 'Đang chờ xác nhận', 'Võ Chí Minh', '0912345678'),
(159, '2025-11-11 16:02:54', 80, NULL, 'Thành phố Hồ Chí Minh / Quận 10 / Phường 1 / Hồ Thị Kỉ', 290000, '2025-11-11 16:02:54', NULL, 3, 'Chưa thanh toán', 'Đang chờ xác nhận', 'Võ Chí Minh', '0912345678'),
(160, '2025-11-11 16:26:30', 80, NULL, 'Thành phố Hồ Chí Minh / Quận 10 / Phường 1 / Hồ Thị Kỉ', 125000, '2025-11-11 16:26:30', NULL, 3, 'Chưa thanh toán', 'Đang chờ xác nhận', 'Võ Chí Minh', '0912345678'),
(161, '2025-11-11 16:55:20', 80, NULL, 'Thành phố Hồ Chí Minh / Quận 10 / Phường 1 / Hồ Thị Kỉ', 120000, '2025-11-11 16:55:20', NULL, 3, 'Chưa thanh toán', 'Đang chờ xác nhận', 'Võ Chí Minh', '0912345678'),
(162, '2025-11-11 20:07:53', 80, NULL, 'Thành phố Hồ Chí Minh / Quận 10 / Phường 1 / Hồ Thị Kỉ', 580000, '2025-11-11 20:07:53', NULL, 3, 'Chưa thanh toán', 'Đang chờ xác nhận', 'Võ Chí Minh', '0912345678'),
(163, '2025-11-17 16:02:38', 80, NULL, 'Thành phố Hồ Chí Minh / Quận 10 / Phường 1 / Hồ Thị Kỉ', 275000, '2025-11-17 16:02:38', NULL, 1, 'Chưa thanh toán', 'Đang chờ xác nhận', 'Võ Chí Minh', '0123456789'),
(164, '2025-11-17 16:03:34', 80, NULL, 'Thành phố Hồ Chí Minh / Quận 10 / Phường 1 / Hồ Thị Kỉ', 100000, '2025-11-17 16:03:34', NULL, 3, 'Chưa thanh toán', 'Đang chờ xác nhận', 'Võ Chí Minh', '0912345678'),
(165, '2025-11-17 16:35:15', 80, NULL, 'Thành phố Hồ Chí Minh / Quận 10 / Phường 1 / Hồ Thị Kỉ', 120000, '2025-11-17 16:35:15', NULL, 3, 'Chưa thanh toán', 'Đang chờ xác nhận', 'Võ Chí Minh', '0912345678'),
(166, '2025-11-17 16:57:52', 80, NULL, 'Thành phố Hồ Chí Minh / Quận 10 / Phường 1 / Hồ Thị Kỉ', 90000, '2025-11-17 16:57:52', NULL, 3, 'Chưa thanh toán', 'Đang chờ xác nhận', 'Võ Chí Minh', '0912345678'),
(167, '2025-11-17 17:07:57', 80, NULL, 'Thành phố Hồ Chí Minh / Quận 10 / Phường 1 / Hồ Thị Kỉ', 110000, '2025-11-17 17:07:57', NULL, 3, 'Chưa thanh toán', 'Đang chờ xác nhận', 'Võ Chí Minh', '0912345678'),
(168, '2025-11-17 17:10:12', 80, NULL, 'Thành phố Hồ Chí Minh / Quận 10 / Phường 1 / Hồ Thị Kỉ', 115000, '2025-11-22 18:56:48', 75, 3, 'Chưa thanh toán', 'Đã giao hàng', 'Võ Chí Minh', '0912345678'),
(169, '2025-11-17 23:32:30', 80, NULL, 'Thành phố Hồ Chí Minh / Quận 10 / Phường 1 / Hồ Thị Kỉ', 350000, '2025-11-17 23:32:30', NULL, 3, 'Chưa thanh toán', 'Đang chờ xác nhận', 'Võ Chí Minh', '0912345678'),
(170, '2025-11-17 23:37:56', 80, NULL, 'Thành phố Hồ Chí Minh / Quận 10 / Phường 1 / Hồ Thị Kỉ', 280000, '2025-11-22 19:00:48', 75, 3, 'Chưa thanh toán', 'Đã giao hàng', 'Võ Chí Minh', '0912345678'),
(171, '2025-11-17 23:43:38', 80, NULL, 'Thành phố Hồ Chí Minh / Quận 10 / Phường 1 / Hồ Thị Kỉ', 330000, '2025-11-17 23:43:38', NULL, 3, 'Chưa thanh toán', 'Đang chờ xác nhận', 'minh', '0912345678'),
(172, '2025-11-17 23:49:09', 80, NULL, 'Thành phố Hồ Chí Minh / Quận 10 / Phường 1 / Hồ Thị Kỉ', 360000, '2025-11-22 19:00:20', 75, 3, 'Chưa thanh toán', 'Đã giao hàng', 'minh', '0912345678'),
(173, '2025-11-17 23:51:16', 80, NULL, 'Thành phố Hồ Chí Minh / Quận 10 / Phường 1 / Hồ Thị Kỉ', 390000, '2025-11-22 19:00:04', 75, 3, 'Chưa thanh toán', 'Đã giao hàng', 'minh', '0912345678'),
(174, '2025-11-17 23:53:22', 80, NULL, 'Thành phố Hồ Chí Minh / Quận 10 / Phường 1 / Hồ Thị Kỉ', 230000, '2025-11-22 18:59:39', 75, 3, 'Chưa thanh toán', 'Đã xác nhận', 'minh', '0912345678'),
(175, '2025-11-18 00:02:24', 80, NULL, 'Thành phố Hồ Chí Minh / Quận 10 / Phường 1 / Hồ Thị Kỉ', 240000, '2025-11-22 18:59:32', 75, 3, 'Chưa thanh toán', 'Đã xác nhận', 'minh', '0123456789'),
(176, '2025-11-18 00:49:30', 80, NULL, 'Thành phố Hồ Chí Minh / Quận 10 / Phường 1 / Hồ Thị Kỉ', 250000, '2025-11-22 18:59:27', 75, 3, 'Chưa thanh toán', 'Đã xác nhận', 'minh', '0912345678'),
(177, '2025-11-18 01:09:00', 80, NULL, 'Thành phố Hồ Chí Minh / Quận 10 / Phường 1 / Hồ Thị Kỉ', 260000, '2025-11-22 18:59:48', 75, 3, 'Chưa thanh toán', 'Đã giao hàng', 'minh', '0912345678'),
(178, '2025-11-18 01:18:15', 80, NULL, 'Thành phố Hồ Chí Minh / Quận 10 / Phường 1 / Hồ Thị Kỉ', 230000, '2025-11-22 18:59:17', 75, 3, 'Chưa thanh toán', 'Đã giao hàng', 'minh', '0912345678'),
(179, '2025-11-18 01:19:44', 80, NULL, 'Thành phố Hồ Chí Minh / Quận 10 / Phường 1 / Hồ Thị Kỉ', 260000, '2025-11-22 18:59:06', 75, 3, 'Chưa thanh toán', 'Đã giao hàng', 'test', '0912345678'),
(180, '2025-11-18 10:07:30', 80, NULL, 'Thành phố Hồ Chí Minh / Quận 10 / Phường 1 / Hồ Thị Kỉ', 230000, '2025-11-20 22:30:16', 75, 3, 'Đã thanh toán', 'Đã giao hàng', 'Chí Minh', '0902636286'),
(181, '2025-11-22 18:53:53', 80, NULL, 'Thành phố Hồ Chí Minh / Quận 10 / Phường 1 / Hồ Thị Kỉ', 280000, '2025-11-22 18:58:54', 75, 1, 'Chưa thanh toán', 'Đã giao hàng', 'Chí Minh', '0912345678'),
(182, '2025-11-22 21:18:15', 80, NULL, 'Thành phố Hồ Chí Minh / Quận 10 / Phường 1 / Hồ Thị Kỉ', 270000, '2025-11-22 21:22:41', 75, 3, 'Đã thanh toán', 'Đã giao hàng', 'Chí Minh', '0903961231'),
(183, '2025-11-22 23:24:21', 80, 16, 'Thành phố Hồ Chí Minh / Quận 10 / Phường 1 / Hồ Thị Kỉ', 119000, '2025-11-22 23:26:59', 75, 1, 'Đã thanh toán', 'Đã giao hàng', 'Chí Minh', '0903961231'),
(184, '2025-11-23 01:05:56', 82, NULL, ' /  /  / 793/28/48b Trần Xuân Soạn', 350000, '2025-11-23 01:07:43', 75, 1, 'Đã thanh toán', 'Đã giao hàng', 'Chí Minh', '0903961231'),
(185, '2025-11-23 01:10:54', 75, NULL, 'Thành phố Hà Nội / Quận Ba Đình / Phường Phúc Xá / abc', 320000, '2025-11-23 01:12:03', 75, 1, 'Đã thanh toán', 'Đã giao hàng', 'Chí Minh', '0912345678');

--
-- Triggers `donhang`
--
DELIMITER $$
CREATE TRIGGER `trg_update_soluong_sach_va_ban_chay` AFTER UPDATE ON `donhang` FOR EACH ROW BEGIN
  -- Khi đơn hàng thành công (Xác nhận hoặc Giao hàng): TRỪ KHO, TĂNG BÁN CHẠY
  IF OLD.trangThai = 'Đang chờ xác nhận' AND (NEW.trangThai = 'Đã xác nhận' OR NEW.trangThai = 'Đã giao hàng') THEN
    UPDATE sach s
    JOIN chiTietDonHang ctdh ON ctdh.maSach = s.maSach
    SET
        s.soLuong = s.soLuong - ctdh.soLuong,       -- Trừ tồn kho
        s.soLuongBan = s.soLuongBan + ctdh.soLuong  -- Tăng số lượng bán
    WHERE ctdh.maDonHang = NEW.maDonHang;

  -- Khi hủy đơn hàng (đã từng thành công): TRẢ KHO, GIẢM BÁN CHẠY
  ELSEIF (OLD.trangThai = 'Đã xác nhận' OR OLD.trangThai = 'Đã giao hàng') AND NEW.trangThai = 'Đã hủy đơn' THEN
    UPDATE sach s
    JOIN chiTietDonHang ctdh ON ctdh.maSach = s.maSach
    SET
        s.soLuong = s.soLuong + ctdh.soLuong,       -- Trả tồn kho
        s.soLuongBan = s.soLuongBan - ctdh.soLuong  -- Giảm số lượng bán
    WHERE ctdh.maDonHang = NEW.maDonHang;
  END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `giohang`
--

CREATE TABLE `giohang` (
  `maNguoiDung` int(11) NOT NULL,
  `maSach` int(11) NOT NULL,
  `soLuong` int(11) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `giohang`
--

INSERT INTO `giohang` (`maNguoiDung`, `maSach`, `soLuong`) VALUES
(24, 3, 1),
(24, 44, 1),
(55, 5, 1),
(55, 46, 7),
(76, 8, 9),
(79, 51, 3);

-- --------------------------------------------------------

--
-- Table structure for table `hanhdong`
--

CREATE TABLE `hanhdong` (
  `maHanhDong` int(11) NOT NULL,
  `tenHanhDong` text DEFAULT NULL,
  `ngayCapNhat` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hanhdong`
--

INSERT INTO `hanhdong` (`maHanhDong`, `tenHanhDong`, `ngayCapNhat`) VALUES
(1, 'Lọc', '2025-04-26 01:58:56'),
(2, 'Chi tiết', '2025-04-26 01:58:56'),
(3, 'Thêm', '2025-04-26 01:58:56'),
(4, 'Sửa', '2025-04-26 01:58:56'),
(5, 'Xóa/Khóa', '2025-04-26 01:58:56');

-- --------------------------------------------------------

--
-- Table structure for table `loaibia`
--

CREATE TABLE `loaibia` (
  `maLoaiBia` int(11) NOT NULL,
  `tenLoaiBia` varchar(100) NOT NULL,
  `ngayCapNhat` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `trangThai` enum('Hoạt động','Tạm dừng') DEFAULT 'Hoạt động'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `loaibia`
--

INSERT INTO `loaibia` (`maLoaiBia`, `tenLoaiBia`, `ngayCapNhat`, `trangThai`) VALUES
(1, 'Bìa mềm', '2025-04-19 16:45:00', 'Hoạt động'),
(2, 'Bìa cứng', '2025-04-06 13:29:46', 'Hoạt động'),
(3, 'Bìa gập', '2025-04-19 16:44:54', 'Hoạt động'),
(4, 'Bìa áo', '2025-03-30 02:03:45', 'Hoạt động'),
(5, 'Bìa lụa', '2025-03-30 02:03:45', 'Hoạt động'),
(6, 'Bìa da', '2025-03-30 02:03:45', 'Hoạt động'),
(7, 'Bìa nhựa', '2025-03-30 02:03:45', 'Hoạt động'),
(8, 'Bìa vải', '2025-05-12 20:29:23', 'Hoạt động'),
(9, 'Bìa giấy kraft', '2025-03-30 02:03:45', 'Hoạt động'),
(10, 'Bìa trong suốt', '2025-03-30 02:03:45', 'Hoạt động'),
(13, 'Bìa lông', '2025-05-09 17:27:02', 'Hoạt động'),
(14, 'Bìa Spoce', '2025-05-15 04:19:23', 'Tạm dừng');

-- --------------------------------------------------------

--
-- Table structure for table `nguoidung`
--

CREATE TABLE `nguoidung` (
  `maNguoiDung` int(11) NOT NULL,
  `hoVaTen` varchar(100) NOT NULL,
  `soDT` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `tenTaiKhoan` varchar(100) NOT NULL,
  `matKhau` varchar(255) NOT NULL,
  `maQuyen` int(11) DEFAULT NULL,
  `ngayCapNhat` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `trangThai` enum('Hoạt động','Tạm dừng') DEFAULT 'Hoạt động'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `nguoidung`
--

INSERT INTO `nguoidung` (`maNguoiDung`, `hoVaTen`, `soDT`, `email`, `tenTaiKhoan`, `matKhau`, `maQuyen`, `ngayCapNhat`, `trangThai`) VALUES
(1, 'Nguyễn Nam Dương', '0388853835', 'nguyennamduong205@gmail.com', 'namduongit', '89a76f3e5e7ce360ca7487cd6dfe94d1', NULL, '2025-05-15 05:19:05', 'Hoạt động'),
(11, 'Dương Văn Sơn', '0921345678', 'son.duong@example.com', 'duongson', 'f26a97e1c8e180233559be24baa310b1', 3, '2025-05-14 20:53:59', 'Hoạt động'),
(12, 'Ngô Thị Hạnh', '0919988776', 'hanh.ngo@example.com', 'ngohanh', '1fdd11acb0a259f86b0b3bce2d8d61f2', NULL, '2025-05-12 17:23:54', 'Hoạt động'),
(13, 'Vũ Anh Dũng', '0988111222', 'dung.vu@example.com', 'vudung', 'cf2e578e1e1a79528cac8b9ed524c314', NULL, '2025-05-10 09:07:28', 'Hoạt động'),
(14, 'Trịnh Thị Thu', '0977222333', 'thu.trinh@example.com', 'trinhthu', 'c168a5ae38e6c3d1aa737674825a9de6', NULL, '2025-03-28 01:38:56', 'Hoạt động'),
(15, 'Lâm Văn Hòa', '0955333444', 'hoa.lam@example.com', 'lamhoa', '83b959282926655244495d10f565ff0f', NULL, '2025-03-28 01:38:56', 'Hoạt động'),
(16, 'Phan Thị Ngọc', '0944555666', 'ngoc.phan@example.com', 'phanngoc', '02837a8426b54c25b74559fb186f377d', NULL, '2025-03-28 01:38:56', 'Hoạt động'),
(17, 'Tô Minh Hải', '0933666777', 'hai.to@example.com', 'tohai', '41fe9e4e7f2e855f8dc2a9c473f270cf', NULL, '2025-03-28 01:38:56', 'Hoạt động'),
(18, 'Đoàn Thị Cẩm Ly', '0922777888', 'ly.doan@example.com', 'doanly', 'c3c6eae239774cc195079611df4f9ddb', NULL, '2025-03-28 01:38:56', 'Hoạt động'),
(19, 'Châu Anh Vũ', '0911888999', 'vu.chau@example.com', 'chauvu', '1e97fb94ef3a655aec8ab876ae9accb6', NULL, '2025-03-28 01:38:56', 'Hoạt động'),
(20, 'Nguyễn Hoàng Bảo', '0900777666', 'bao.nguyen@example.com', 'nguyenbao', 'b6c6cfe1a7ba5eac0f984f3ef97c8490', NULL, '2025-03-28 01:38:56', 'Hoạt động'),
(22, 'Phạm Thị Trà My', '0329276436', 'tramy6436@gmail.com', 'tramy6436', '89a76f3e5e7ce360ca7487cd6dfe94d1', 1, '2025-05-12 13:25:16', 'Hoạt động'),
(23, 'Chênh Tiên Luân', '0909380633', 'tienluanchenh192@gmail.com', 'luanchenh', '5d9cfb0271f1b9d066fcec2858049cae', 15, '2025-05-12 08:08:25', 'Hoạt động'),
(55, 'My name is Duong', '0969297316', 'tramy64362@gmail.com', 'namduong', '7ca44494898a453dd6ab41dc538c6eb8', 15, '2025-05-12 14:54:11', 'Hoạt động'),
(58, 'VOTRINHKHANG', NULL, NULL, 'VOTRINHKHANG', '72cdc7d4a29f8da8210a3a06801319bf', 15, '2025-05-12 10:01:03', 'Hoạt động'),
(60, 'atuan', '', '', 'atuan123', '202cb962ac59075b964b07152d234b70', 15, '2025-05-14 21:01:35', 'Hoạt động'),
(64, 'quanlykho1', '0777777777', 'quanlykho1@gmail.com', 'quanlykho1', 'aaa2e6461d3b21a96dbaa7f39ed2c2d2', 3, '2025-05-14 09:50:31', 'Hoạt động'),
(65, 'quanlybanhang1', '0555555555', 'quanlybanhang1@gmail.com', 'quanlybanhang1', 'c7cd0622daa647737a903ba336b797ed', 15, '2025-05-14 10:15:01', 'Hoạt động'),
(66, 'Ngô Thị Hạnh', '0964665580', 'ocd@gmail.com', 'builana', 'e10adc3949ba59abbe56e057f20f883e', 16, '2025-05-14 19:00:30', 'Hoạt động'),
(67, 'test lần n', '0964665587', 'abcdef@gmail.com', 'abcdef', 'ef8d3823014e45d7572664fbaf99dfe6', 15, '2025-05-14 19:12:44', 'Hoạt động'),
(68, 'test lần n2', '0869547200', 'sgp@gmail.com', 'saigon', '1e5c810d5d45f6de90ca1ecb3f4dcc48', 3, '2025-05-14 19:14:33', 'Hoạt động'),
(69, 'flash', '0869547202', 'flash@gmail.com', 'flash', 'a39401275d1b300aa789fb22aea4148a', 3, '2025-05-14 19:29:44', 'Hoạt động'),
(70, 'testn3', '0919988771', 'testn3@gmail.com,', 'testn3', 'f211e7ed1bb84727e22247c2c8c56f21', 3, '2025-05-14 19:32:15', 'Hoạt động'),
(71, 'testn4', '0388853830', 'testn4@gmail.com', 'testn4', 'd26fa2b98df6e862509d3cb37938424b', 16, '2025-05-14 19:40:34', 'Hoạt động'),
(73, 'testn5', '0919988777', 'testn5@gmail.com', 'testn5', 'a2a11c8f75854e0aeb78fc188da74750', 1, '2025-05-14 22:08:18', 'Hoạt động'),
(74, 'testn6', '0947891230', 'testn6@gmail.com', 'testn6', 'f4c97932a40cdef886b7501a265a0e9f', NULL, '2025-05-14 22:16:13', 'Hoạt động'),
(75, 'Quản trị viên', '0385872250', 'quantrivien@gmail.com', 'quantrivien', 'fef1ddf26aacab614a822e243bdb16cb', 15, '2025-05-15 06:53:04', 'Hoạt động'),
(76, 'abcabc', '0388853838', 'abcabc@gmail.com', 'abcabc', '440ac85892ca43ad26d44c7ad9d47d3e', NULL, '2025-05-15 07:36:21', 'Hoạt động'),
(77, 'Minh', NULL, NULL, 'Minh177', 'f06b79760f06bb9718b13b1332f6ca6e', NULL, '2025-10-14 15:46:26', 'Hoạt động'),
(78, 'minh', '0903961231', 'abc@gmail.com', 'minhvo', '4297f44b13955235245b2497399d7a93', NULL, '2025-10-21 23:15:42', 'Hoạt động'),
(79, 'tes17', NULL, NULL, 'test17', '202cb962ac59075b964b07152d234b70', NULL, '2025-10-28 10:07:48', 'Hoạt động'),
(80, 'Võ Chí Minh', NULL, NULL, 'chminh', '202cb962ac59075b964b07152d234b70', NULL, '2025-11-11 15:31:47', 'Hoạt động'),
(81, 'Võ Chí Minh', '0912345678', 'vcm@gmail.com', 'CMinh', 'c92f1d1f2619172bf87a12e5915702a6', 2, '2025-11-18 10:56:11', 'Hoạt động'),
(82, 'Test', NULL, NULL, 'Chi Minh', '202cb962ac59075b964b07152d234b70', NULL, '2025-11-23 01:04:31', 'Hoạt động'),
(83, 'HR Manager', '0900000083', 'hrmanager@bookstore.com', 'hrmanager', 'e10adc3949ba59abbe56e057f20f883e', NULL, '2026-04-14 00:00:00', 'Hoạt động');

-- --------------------------------------------------------

--
-- Table structure for table `nhacungcap`
--

CREATE TABLE `nhacungcap` (
  `maNCC` int(11) NOT NULL,
  `tenNCC` varchar(100) NOT NULL,
  `soDT` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `diaChi` varchar(100) DEFAULT NULL,
  `ngayCapNhat` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `trangThai` enum('Hoạt động','Tạm dừng') DEFAULT 'Hoạt động'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `nhacungcap`
--

INSERT INTO `nhacungcap` (`maNCC`, `tenNCC`, `soDT`, `email`, `diaChi`, `ngayCapNhat`, `trangThai`) VALUES
(1, 'Nhà cung cấp Thái Bình Dương', '0123456789', 'thaibinhduong@gmail.com', 'Long Khánh Đồng Nai', '2025-05-15 05:16:54', 'Hoạt động'),
(2, 'BBB', '012345678', 'b@gmail.com', 'HN', '2025-04-06 20:27:03', 'Hoạt động'),
(3, 'khagn', '643894853', 'khang@gmai.com', 'QN', '2025-03-29 02:40:58', 'Hoạt động'),
(4, 'Spoce Print', '0909386033', 'spoceprint@gmail.com', '125 Trần Bình Trọng', '2025-05-06 09:53:10', 'Hoạt động'),
(5, 'fgdkhkhagn', '0574395', 'khang345@gmai.com', 'QN', '2025-04-06 20:31:20', 'Hoạt động'),
(9, 'Trần Thanh Quy', '1234567890', 'tq@gmail.com', '124, Phường 10, Thành phố Vũng Tàu, Tỉnh Bà Rịa - Vũng Tàu', '2025-04-20 09:50:02', 'Tạm dừng'),
(11, 'Nhà cung cấp test', '09876543211', 'ncctest@gmail.com', '643334, Xã Giáo Hiệu, Huyện Pác Nặm, Tỉnh Bắc Kạn', '2025-05-01 15:31:55', 'Tạm dừng'),
(12, 'NCC Spoce', '1212454567', 'spocestore@gmail.com', '12512, Xã An Phúc, Huyện Đông Hải, Tỉnh Bạc Liêu', '2025-04-20 07:01:16', 'Tạm dừng'),
(14, 'khang', '01234567891', 'khang@gmail.com', '11, Xã Phước Tỉnh, Huyện Long Điền, Tỉnh Bà Rịa - Vũng Tàu', '2025-05-11 13:14:12', 'Tạm dừng');

-- --------------------------------------------------------

--
-- Table structure for table `nhaxuatban`
--

CREATE TABLE `nhaxuatban` (
  `maNXB` int(11) NOT NULL,
  `tenNXB` varchar(100) NOT NULL,
  `ngayCapNhat` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `trangThai` enum('Hoạt động','Tạm dừng') DEFAULT 'Hoạt động'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `nhaxuatban`
--

INSERT INTO `nhaxuatban` (`maNXB`, `tenNXB`, `ngayCapNhat`, `trangThai`) VALUES
(1, 'Nhà xuất bản Trẻ', '2025-05-15 04:17:17', 'Hoạt động'),
(2, 'Nhà xuất bản Kim Đồng', '2025-04-06 14:24:41', 'Hoạt động'),
(3, 'Nhà xuất bản Tổng hợp Thành phố Hồ Chí Minh', '2025-03-27 02:50:47', 'Hoạt động'),
(4, 'Nhà xuất bản Hội Nhà văn', '2025-03-27 02:50:47', 'Hoạt động'),
(5, 'Nhà xuất bản Chính trị Quốc gia Sự thật', '2025-04-21 20:04:40', 'Hoạt động'),
(6, 'Nhà xuất bản Phụ nữ Việt Nam', '2025-03-27 02:50:47', 'Hoạt động'),
(7, 'Nhà xuất bản Lao Động', '2025-03-28 13:14:35', 'Hoạt động'),
(8, 'Nhã Nam', '2025-03-27 02:50:47', 'Hoạt động'),
(9, 'Đinh Tị Books', '2025-03-27 02:50:47', 'Hoạt động'),
(10, 'Nhà xuất bản Giáo dục Việt Nam', '2025-03-27 02:50:47', 'Hoạt động'),
(11, 'khan', '2025-05-15 04:16:02', 'Tạm dừng'),
(13, 'NXB thửuu', '2025-05-10 10:03:25', 'Tạm dừng'),
(14, 'quy ngu', '2025-04-19 12:38:54', 'Tạm dừng'),
(15, '12401204810', '2025-04-19 12:41:08', 'Tạm dừng'),
(16, 'Thanh Quy', '2025-04-20 06:00:10', 'Tạm dừng'),
(18, 'NXB Spoce', '2025-05-11 13:12:08', 'Hoạt động'),
(19, 'test', '2025-05-14 08:56:14', 'Tạm dừng');

-- --------------------------------------------------------

--
-- Table structure for table `phieugiamgia`
--

CREATE TABLE `phieugiamgia` (
  `maPGG` int(11) NOT NULL,
  `tenPGG` varchar(100) NOT NULL,
  `toiThieu` int(11) DEFAULT NULL,
  `toiDa` int(11) DEFAULT NULL,
  `ngayBatDau` date NOT NULL,
  `ngayKetThuc` date NOT NULL,
  `ngayCapNhat` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `giaTriGiam` int(11) DEFAULT NULL,
  `trangThai` enum('Hoạt động','Tạm dừng') DEFAULT 'Hoạt động',
  `loai` enum('Phần trăm','Tiền') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `phieugiamgia`
--

INSERT INTO `phieugiamgia` (`maPGG`, `tenPGG`, `toiThieu`, `toiDa`, `ngayBatDau`, `ngayKetThuc`, `ngayCapNhat`, `giaTriGiam`, `trangThai`, `loai`) VALUES
(1, 'Khuyến mãi Tết 2025', 300000, 2000000, '2025-06-04', '2025-10-01', '2025-05-10 09:47:43', 10, 'Hoạt động', 'Phần trăm'),
(2, 'TTT', 200000, 2000, '2025-05-10', '2025-05-11', '2025-05-10 09:48:27', 1000000, 'Hoạt động', 'Tiền'),
(3, 'TEST', 200000, 2000, '2025-05-10', '2025-05-11', '2025-05-10 09:48:46', 10, 'Hoạt động', 'Tiền'),
(4, 'Test3', 200000, 20000, '2025-04-02', '2025-04-05', '2025-04-19 11:19:13', NULL, 'Hoạt động', 'Phần trăm'),
(5, 'tesst', 1000, 100, '2025-04-03', '2025-04-04', '2025-04-20 00:55:35', NULL, 'Hoạt động', 'Tiền'),
(6, 'Mã thành viên', 200000, 100000, '2025-05-02', '2025-05-03', '2025-04-23 07:33:28', 100000, 'Hoạt động', 'Phần trăm'),
(7, 'Khách hàng mới', 100000, 10000, '2025-04-02', '2025-04-04', '2025-04-23 07:33:31', NULL, 'Hoạt động', 'Phần trăm'),
(8, 'Phiếu này do Thanh Quy tạo', 1, 9999999, '2025-04-20', '2025-12-31', '2025-04-20 09:37:17', 100, 'Tạm dừng', 'Phần trăm'),
(9, 'Phiếu này cũng do Thanh Quy tạo nhưng nhầm do bị mù', 1, 9999999, '2025-04-20', '2025-12-31', '2025-04-20 09:38:19', 100, 'Tạm dừng', 'Phần trăm'),
(10, 'Spoce Deal 40% !!!', 1, 9999999, '2025-04-21', '2025-04-30', '2025-04-21 08:53:17', 40, 'Tạm dừng', 'Phần trăm'),
(11, 'test', 10000, 1000, '2025-04-23', '2025-04-25', '2025-04-22 11:42:34', 11, 'Tạm dừng', 'Phần trăm'),
(12, 'Hè 2025', 100000, 1000000, '2025-05-09', '2025-09-01', '2025-05-10 00:23:51', 25, 'Hoạt động', 'Phần trăm'),
(13, 'PGG têst', 433, 23, '2025-05-10', '2025-05-17', '2025-05-10 10:28:36', 33, 'Tạm dừng', 'Phần trăm'),
(14, 'PGG', 34, 4, '2025-05-17', '2025-05-31', '2025-05-10 10:31:32', 4, 'Hoạt động', 'Tiền'),
(15, '123', 100, 1000000, '2025-05-14', '2025-05-17', '2025-05-14 10:12:33', 100000, 'Hoạt động', 'Tiền'),
(16, 'NOEL25', 50000, 100000, '2025-11-22', '2025-12-31', '2025-11-22 23:22:50', 10, 'Hoạt động', 'Phần trăm');

-- --------------------------------------------------------

--
-- Table structure for table `phieunhap`
--

CREATE TABLE `phieunhap` (
  `maPhieuNhap` int(11) NOT NULL,
  `ngayTaoPhieu` datetime DEFAULT current_timestamp(),
  `maNCC` int(11) NOT NULL,
  `tongTienNhap` int(11) NOT NULL DEFAULT 0,
  `ngayCapNhat` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `trangThai` enum('Đang chờ xác nhận','Đã xác nhận','Đã thanh toán','Đã hủy phiếu') DEFAULT 'Đang chờ xác nhận',
  `maNhanVien` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `phieunhap`
--

INSERT INTO `phieunhap` (`maPhieuNhap`, `ngayTaoPhieu`, `maNCC`, `tongTienNhap`, `ngayCapNhat`, `trangThai`, `maNhanVien`) VALUES
(1, '2010-05-04 00:00:00', 3, 16733, '2025-05-12 17:37:59', 'Đã thanh toán', 1),
(2, '2010-05-15 00:00:00', 2, 548447, '2025-05-12 17:38:17', 'Đã thanh toán', 1),
(3, '2025-04-04 00:00:00', 4, 3099994, '2025-05-10 10:22:33', 'Đã thanh toán', 1),
(4, '2025-04-08 00:00:00', 4, 1328652, '2025-04-21 20:00:49', 'Đang chờ xác nhận', 1),
(5, '2025-04-16 00:00:00', 3, 25939477, '2025-04-21 19:06:36', 'Đang chờ xác nhận', 1),
(6, '2025-04-08 00:00:00', 9, 10764, '2025-04-21 19:06:36', 'Đang chờ xác nhận', 1),
(7, '2025-04-16 00:00:00', 9, 13064649, '2025-04-22 13:08:43', 'Đã hủy phiếu', 1),
(8, '2025-04-22 00:00:00', 12, 2020000, '2025-04-22 13:08:57', 'Đã xác nhận', 1),
(9, '2025-04-22 00:00:00', 9, 965924, '2025-04-22 13:09:37', 'Đã thanh toán', 1),
(10, '2025-05-11 00:00:00', 1, 120, '2025-05-11 23:55:13', 'Đang chờ xác nhận', 55),
(11, '2025-05-11 00:00:00', 2, 50, '2025-05-11 23:57:31', 'Đang chờ xác nhận', 55),
(12, '2025-05-11 00:00:00', 2, 100, '2025-05-12 00:02:37', 'Đang chờ xác nhận', 55),
(13, '2025-05-11 00:00:00', 2, 100, '2025-05-12 00:03:10', 'Đã hủy phiếu', 1),
(14, '2025-05-11 00:00:00', 2, 100, '2025-05-12 00:03:36', 'Đã xác nhận', 1),
(15, '2025-05-11 00:00:00', 3, 2, '2025-05-12 00:07:01', 'Đã xác nhận', 1),
(16, '2025-05-11 00:00:00', 3, 100, '2025-05-12 00:15:34', 'Đang chờ xác nhận', 55),
(17, '2025-05-12 00:00:00', 1, 50, '2025-05-12 17:28:03', 'Đã xác nhận', 1),
(18, '2025-05-12 00:00:00', 5, 150, '2025-05-12 17:33:22', 'Đã thanh toán', 1),
(19, '2025-05-12 00:00:00', 3, 200, '2025-05-12 20:03:47', 'Đang chờ xác nhận', 55),
(20, '2025-05-12 00:00:00', 2, 100, '2025-05-12 20:08:49', 'Đang chờ xác nhận', 55),
(21, '2025-05-12 00:00:00', 3, 200000, '2025-05-12 20:10:31', 'Đang chờ xác nhận', 55),
(22, '2025-11-20 00:00:00', 1, 900000, '2025-11-20 21:37:59', 'Đang chờ xác nhận', 75),
(23, '2025-11-24 00:00:00', 4, 2100000, '2025-11-25 01:19:03', 'Đã xác nhận', 1);

--
-- Triggers `phieunhap`
--
DELIMITER $$
CREATE TRIGGER `trg_update_soluong_sach_phieuNhap` AFTER UPDATE ON `phieunhap` FOR EACH ROW BEGIN
  IF OLD.trangThai = 'Đang chờ xác nhận' AND NEW.trangThai = 'Đã xác nhận' THEN
    UPDATE sach s
    JOIN chiTietPhieuNhap ct ON ct.maSach = s.maSach
    SET s.soLuong = s.soLuong + ct.soLuong
    WHERE ct.maPhieuNhap = NEW.maPhieuNhap;
  ELSEIF OLD.trangThai != 'Đang chờ xác nhận' AND NEW.trangThai = 'Đã hủy phiếu' THEN
    UPDATE sach s
    JOIN chiTietPhieuNhap ct ON ct.maSach = s.maSach
    SET s.soLuong = s.soLuong - ct.soLuong
    WHERE ct.maPhieuNhap = NEW.maPhieuNhap;
  END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `phuongthucthanhtoan`
--

CREATE TABLE `phuongthucthanhtoan` (
  `maPhuongThuc` int(11) NOT NULL,
  `icon_url` varchar(255) DEFAULT NULL,
  `tenPhuongThuc` varchar(100) NOT NULL,
  `thongTin` text DEFAULT NULL,
  `moTa` text DEFAULT NULL,
  `ngayCapNhat` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `trangThai` enum('Hoạt động','Tạm dừng') DEFAULT 'Hoạt động',
  `trucTuyen` tinyint(1) DEFAULT 0,
  `taiKhoan` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `phuongthucthanhtoan`
--

INSERT INTO `phuongthucthanhtoan` (`maPhuongThuc`, `icon_url`, `tenPhuongThuc`, `thongTin`, `moTa`, `ngayCapNhat`, `trangThai`, `trucTuyen`, `taiKhoan`) VALUES
(1, 'NamABank.png', 'Thanh toán khi nhận hàng (COD)', NULL, NULL, '2025-05-15 07:39:59', 'Hoạt động', 0, NULL),
(2, 'Techcombank.png', 'Chuyển khoản ngân hàng Techcombank', 'Chi nhánh: Long Khánh - Đồng Nai', 'Nội dung: Tên đăng nhập với số tiền, VD: namduong 100000', '2025-05-12 13:23:40', 'Hoạt động', 0, 'Chuyển khoản: 8685.8888.888 - NGUYEN NAM DUONG'),
(3, 'CardBank.png', 'Thanh toán qua VNPAY', NULL, NULL, '2025-05-12 13:25:13', 'Hoạt động', 1, NULL),
(4, 'PVBank.png', 'Chuyển khoản ngân hàng mới', NULL, NULL, '2025-05-12 13:22:52', 'Hoạt động', 1, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `quyen`
--

CREATE TABLE `quyen` (
  `maQuyen` int(11) NOT NULL,
  `tenQuyen` varchar(100) NOT NULL,
  `ngayCapNhat` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `trangThai` enum('Hoạt động','Tạm dừng') DEFAULT 'Hoạt động'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `quyen`
--

INSERT INTO `quyen` (`maQuyen`, `tenQuyen`, `ngayCapNhat`, `trangThai`) VALUES
(1, 'Nhân viên', '2025-05-15 05:13:50', 'Hoạt động'),
(2, 'Quản lí', '2025-03-27 14:11:43', 'Hoạt động'),
(3, 'Quản lý kho', '2025-05-14 02:19:10', 'Hoạt động'),
(15, 'Quản trị viên', '2025-05-06 12:32:06', 'Hoạt động'),
(16, 'Quản lý bán hàng', '2025-05-14 02:18:51', 'Hoạt động');

-- --------------------------------------------------------

--
-- Table structure for table `ratings`
--

CREATE TABLE `ratings` (
  `id` int(11) NOT NULL,
  `book_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `rating` tinyint(4) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `ratings`
--

INSERT INTO `ratings` (`id`, `book_id`, `order_id`, `user_id`, `rating`, `created_at`) VALUES
(1, 31, 168, 80, 5, '2025-11-22 11:08:35'),
(2, 19, 180, 80, 5, '2025-11-22 11:56:23'),
(3, 6, 181, 80, 5, '2025-11-22 12:01:43'),
(7, 5, 183, 80, 3, '2025-11-22 16:27:54'),
(8, 20, 172, 80, 2, '2025-11-22 16:30:41'),
(9, 48, 170, 80, 2, '2025-11-22 16:50:35'),
(10, 32, 181, 80, 3, '2025-11-22 18:02:43'),
(11, 20, 184, 82, 4, '2025-11-22 18:07:57'),
(12, 20, 185, 75, 5, '2025-11-22 18:13:09');

--
-- Triggers `ratings`
--
DELIMITER $$
CREATE TRIGGER `update_rating_on_insert` AFTER INSERT ON `ratings` FOR EACH ROW BEGIN
    DECLARE total_score INT;  -- Lưu Tổng Điểm từ bảng ratings
    DECLARE total_count INT;  -- Lưu Tổng Số Lượng đánh giá

    -- 1. Tính toán TỔNG ĐIỂM (SUM) và TỔNG SỐ LƯỢNG (COUNT) hiện tại cho cuốn sách
    SELECT 
        SUM(r.rating),
        COUNT(*)
    INTO 
        total_score, 
        total_count
    FROM ratings r
    WHERE r.book_id = NEW.book_id;

    -- 2. Cập nhật bảng sach
    UPDATE sach
    SET 
        -- Cập nhật cột tongDanhGia trong bảng sach
        tongDanhGia = total_count, 
        
        -- Tính và cập nhật trungBinhDanhGia (Tổng điểm / Tổng số lượng)
        trungBinhDanhGia = CASE 
                            -- Đảm bảo không chia cho 0
                            WHEN total_count > 0 
                            THEN total_score / total_count
                            ELSE 0.0
                           END
    WHERE maSach = NEW.book_id;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `sach`
--

CREATE TABLE `sach` (
  `maSach` int(11) NOT NULL,
  `tenSach` varchar(100) NOT NULL,
  `soTrang` int(11) DEFAULT NULL,
  `kichThuoc` int(11) DEFAULT NULL,
  `moTa` varchar(1000) DEFAULT NULL,
  `maTacGia` int(11) DEFAULT NULL,
  `maTheLoai` int(11) DEFAULT NULL,
  `maLoaiBia` int(11) DEFAULT NULL,
  `maNXB` int(11) DEFAULT NULL,
  `namXuatBan` int(11) DEFAULT NULL,
  `giaTran` int(11) DEFAULT NULL,
  `giaBan` int(11) DEFAULT NULL,
  `giaSale` int(11) DEFAULT 0,
  `hinhAnh` varchar(200) DEFAULT NULL,
  `ngayCapNhat` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `soLuong` int(11) DEFAULT 0,
  `trangThai` enum('Đang bán','Dừng bán') DEFAULT 'Đang bán',
  `soLuongBan` int(11) DEFAULT 0,
  `tongDanhGia` int(11) DEFAULT 0,
  `trungBinhDanhGia` decimal(3,1) DEFAULT 0.0,
  `phanTramGiamGia` tinyint(4) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sach`
--

INSERT INTO `sach` (`maSach`, `tenSach`, `soTrang`, `kichThuoc`, `moTa`, `maTacGia`, `maTheLoai`, `maLoaiBia`, `maNXB`, `namXuatBan`, `giaTran`, `giaBan`, `giaSale`, `hinhAnh`, `ngayCapNhat`, `soLuong`, `trangThai`, `soLuongBan`, `tongDanhGia`, `trungBinhDanhGia`, `phanTramGiamGia`) VALUES
(1, 'Tôi thấy hoa vàng trên cỏ xanhh', 375, 15, 'Một tác phẩm nhẹ nhàng, trong trẻo nhưng cũng đầy day dứt của Nguyễn Nhật Ánh. Cuốn sách đưa người đọc trở về với làng quê Việt Nam nghèo khó nhưng đầm ấm, qua đôi mắt của cậu bé Thiều. Đó là những rung động đầu đời, tình anh em cảm động và những bài học về sự trưởng thành. Một tấm vé trở về tuổi thơ đẹp đẽ nhất.', 1, 1, 2, 5, 2022, 76000, 96000, 0, '1.png', '2025-11-25 00:41:33', 72, 'Dừng bán', 0, 0, 0.0, 0),
(2, 'Người lớn không khóc', 325, 13, 'Tản văn đầy cảm xúc về cuộc sống và tình cảm', 4, 1, 2, 5, 2025, 60000, 75000, 0, '2.png', '2025-11-21 10:52:46', 62, 'Đang bán', 5, 0, 0.0, 0),
(3, 'Đừng lựa chọn an nhàn khi còn trẻ', 280, 14, 'Sách self-help dành cho người trẻ', 3, 5, 3, 3, 2025, 85000, 99000, 0, '3.png', '2025-11-24 23:43:50', 123, 'Đang bán', 0, 0, 0.0, 0),
(4, 'Cho tôi xin một vé đi tuổi thơ', 250, 13, '“Cho tôi xin một vé đi tuổi thơ” không chỉ là sách dành cho thiếu nhi, mà là dành cho những ai từng là trẻ con. Tác phẩm là chuyến tàu ngược thời gian tìm về cậu bé Mùi, Hải cò, Tí sún... với những trò nghịch ngợm dở khóc dở cười, để rồi nhận ra người lớn đôi khi thật rắc rối và khó hiểu.', 1, 1, 1, 1, 2025, 65000, 80000, 0, '4.png', '2025-11-25 00:41:33', 409, 'Đang bán', 3, 0, 0.0, 0),
(5, 'Bắt trẻ đồng xanh', 277, 14, 'Tiểu thuyết văn học nổi tiếng', 5, 1, 2, 5, 2025, 90000, 110000, 0, '5.png', '2025-11-23 15:16:42', 64, 'Đang bán', 1, 1, 3.0, 0),
(6, 'Gió đầu mùa', 200, 12, 'Tập truyện ngắn tinh tế và đượm buồn của Thạch Lam. Văn của ông không có cốt truyện ly kỳ, mà đi sâu vào thế giới nội tâm, những cảm xúc mong manh của con người trước sự thay đổi của đất trời và phận người. \"Gió đầu mùa\" là tiếng thở dài thương cảm cho những kiếp người nhỏ bé trong xã hội cũ.', 6, 2, 3, 4, 2025, 50000, 70000, 0, '6.png', '2025-11-25 00:41:33', 76, 'Đang bán', 6, 1, 5.0, 0),
(7, 'Cánh đồng bất tận', 320, 14, 'Một tiếng kêu xé lòng từ vùng sông nước miền Tây. Nguyễn Ngọc Tư đã khắc họa nên một bức tranh hiện thực trần trụi, đau đớn nhưng cũng đầy nhân văn về thân phận những người phụ nữ, những đứa trẻ lớn lên không mái nhà, lênh đênh trên những cánh đồng. Tác phẩm gây ám ảnh sâu sắc về tình người và sự tha hóa.', 7, 2, 1, 6, 2025, 70000, 85000, 0, '7.png', '2025-11-25 00:41:33', 78, 'Đang bán', 2, 0, 0.0, 0),
(8, 'Truyện ngắn Nguyễn Huy Thiệp', 450, 15, 'Tuyển tập truyện ngắn hay nhất của Nguyễn Huy Thiệp', 8, 2, 2, 7, 2025, 100000, 120000, 0, '8.png', '2025-11-23 15:16:42', 81, 'Đang bán', 1, 0, 0.0, 0),
(9, 'Những người khốn khổ', 1200, 17, 'Kiệt tác vĩ đại của Victor Hugo và văn học Pháp. Tác phẩm là bản anh hùng ca về tình yêu thương, lòng nhân ái và sự chuộc tội giữa bối cảnh xã hội Paris đầy biến động. Câu chuyện về Jean Valjean - từ một tù khổ sai trở thành người cha vĩ đại - sẽ lấy đi nước mắt của bất kỳ ai.', 5, 1, 5, 5, 2025, 200000, 250000, 0, '9.png', '2025-11-25 00:44:41', 36, 'Đang bán', 11, 0, 0.0, 0),
(10, 'Đắc Nhân Tâm', 320, 13, 'Cuốn sách \"gối đầu giường\" của nhiều thế hệ về nghệ thuật ứng xử và thu phục lòng người. Dale Carnegie không dạy những thủ thuật giả tạo, mà hướng dẫn cách thấu hiểu, tôn trọng và quan tâm chân thành đến người khác. Đây là chìa khóa để thành công trong giao tiếp và xây dựng các mối quan hệ bền vững.', 4, 5, 1, 2, 2025, 90000, 115000, 0, '10.png', '2025-11-25 00:44:52', 39, 'Đang bán', 0, 0, 0.0, 0),
(11, 'Nhà giả kim', 208, 14, 'Cuốn sách bán chạy chỉ sau Kinh Thánh. Hành trình đi tìm kho báu của chàng chăn cừu Santiago thực chất là hành trình đi tìm \"Vận mệnh\" của chính mình. Với văn phong giản dị nhưng thấm đẫm triết lý phương Đông, Paulo Coelho nhắn nhủ: \"Khi bạn khao khát một điều gì đó, cả vũ trụ sẽ hợp lực giúp bạn đạt được điều đó\".', 7, 1, 1, 3, 2025, 70000, 90000, 0, '11.png', '2025-11-25 00:44:41', 176, 'Đang bán', 0, 0, 0.0, 0),
(12, 'Muôn kiếp nhân sinh', 400, 15, 'Tác phẩm về tâm linh và nhân quả', 6, 5, 2, 6, 2025, 160000, 180000, 126000, '12.png', '2025-11-25 00:18:46', 134, 'Đang bán', 0, 0, 0.0, 30),
(13, 'Dám nghĩ lớn', 250, 14, 'Sách phát triển tư duy thành công', 8, 5, 3, 4, 2025, 85000, 99000, 69300, '13.png', '2025-11-25 00:18:46', 43, 'Đang bán', 0, 0, 0.0, 30),
(14, 'Chiến tranh và hòa bình', 1300, 18, 'Tiểu thuyết lịch sử nổi tiếng của Tolstoy', 5, 7, 4, 5, 2025, 230000, 280000, 0, '14.png', '2025-11-21 10:52:46', 148, 'Đang bán', 1, 0, 0.0, 0),
(15, 'Lịch sử thế giới', 700, 16, 'Cuốn sách tổng hợp về lịch sử nhân loại', 9, 8, 3, 7, 2025, 150000, 180000, 0, '15.png', '2025-05-10 01:44:05', 80, 'Đang bán', 0, 0, 0.0, 0),
(16, 'Sapiens: Lược sử loài người', 450, 15, 'Cuốn sách lịch sử nhân loại nổi tiếng', 10, 8, 2, 4, 2025, 180000, 210000, 0, '16.png', '2025-05-10 01:44:05', 73, 'Đang bán', 0, 0, 0.0, 0),
(17, '1984', 328, 14, 'Tiểu thuyết dystopia kinh điển', 3, 1, 1, 3, 2025, 95000, 115000, 0, '17.png', '2025-05-10 01:44:05', 44, 'Đang bán', 0, 0, 0.0, 0),
(18, 'Dune', 800, 17, 'Tiểu thuyết khoa học viễn tưởng nổi tiếng', 4, 9, 5, 8, 2025, 220000, 260000, 0, '18.png', '2025-11-23 15:16:42', 67, 'Đang bán', 6, 0, 0.0, 0),
(19, 'Harry Potter và Hòn đá phù thủy', 350, 15, 'Khởi đầu của huyền thoại. Cậu bé Harry Potter mồ côi sống trong gầm cầu thang bỗng phát hiện mình là một phù thủy nổi tiếng. Chuyến tàu tốc hành đưa cậu đến trường Hogwarts, nơi bắt đầu những cuộc phiêu lưu kỳ thú, tình bạn diệu kỳ và cuộc chiến chống lại thế lực hắc ám. Một thế giới phép thuật mở ra rực rỡ trước mắt người đọc.', 2, 9, 4, 9, 2025, 180000, 210000, 0, '19.png', '2025-11-25 00:45:06', 45, 'Đang bán', 7, 1, 5.0, 0),
(20, 'Sherlock Holmes toàn tập', 1200, 17, 'Tuyển tập đầy đủ nhất về vị thám tử tài ba Sherlock Holmes và bác sĩ Watson. Với khả năng quan sát tinh tường và tư duy diễn dịch siêu phàm, Holmes đã phá giải những vụ án hóc búa nhất sương mù London. Tác phẩm của Conan Doyle là chuẩn mực không thể vượt qua của thể loại trinh thám cổ điển.', 1, 10, 1, 10, 2025, 250000, 300000, 0, '20.png', '2025-11-25 00:45:06', 53, 'Đang bán', 5, 3, 3.7, 0),
(21, 'Bố già', 600, 16, 'Đỉnh cao của dòng văn học tội phạm. Mario Puzo đã tạo dựng nên một thế giới Mafia đầy quyền lực, tàn bạo nhưng cũng trọng danh dự và tình cảm gia đình. Hình tượng Don Vito Corleone trở thành tượng đài bất hủ về người đàn ông bản lĩnh, mưu lược và nguyên tắc: \"Một người đàn ông không dành thời gian cho gia đình thì không bao giờ trở thành người đàn ông thực sự\".', 1, 1, 1, 1, 2025, 180000, 220000, 0, '21.png', '2025-11-25 00:44:41', 53, 'Đang bán', 3, 0, 0.0, 0),
(22, 'Thiên thần và ác quỷ', 710, 16, 'Tiểu thuyết trinh thám ly kỳ của Dan Brown', 2, 10, 2, 2, 2025, 150000, 180000, 126000, '22.png', '2025-11-25 00:18:46', 62, 'Đang bán', 0, 0, 0.0, 30),
(23, 'Mật mã Da Vinci', 689, 16, 'Một cuộc chạy đua nghẹt thở giải mã những bí ẩn tôn giáo chấn động lịch sử. Dan Brown dẫn dắt người đọc đi từ bất ngờ này đến bất ngờ khác thông qua các ký hiệu, mật mã ẩn giấu trong các tác phẩm nghệ thuật của Da Vinci. Kịch tính, thông minh và đầy tranh cãi, đây là cuốn sách bạn không thể đặt xuống khi chưa đọc trang cuối cùng.', 3, 10, 3, 3, 2025, 160000, 190000, 0, '23.png', '2025-11-25 00:45:06', 72, 'Đang bán', 0, 0, 0.0, 0),
(24, 'Hoàng tử bé', 120, 12, 'Một câu chuyện cổ tích dành cho người lớn. Qua cuộc phiêu lưu của Hoàng tử bé từ tiểu tinh cầu B612 đến Trái Đất, Saint-Exupéry đã gửi gắm những thông điệp sâu sắc về tình yêu, tình bạn và bản chất của con người. \"Người ta chỉ nhìn thấy thật rõ ràng bằng trái tim. Cái cốt yếu thì mắt thường không thấy được\".', 4, 1, 4, 4, 2025, 50000, 70000, 0, '24.png', '2025-11-25 00:44:41', 40, 'Đang bán', 4, 0, 0.0, 0),
(25, 'Hai vạn dặm dưới đáy biển', 500, 15, 'Tác phẩm khoa học viễn tưởng kinh điển', 5, 9, 5, 5, 2025, 120000, 150000, 0, '25.png', '2025-05-10 01:44:05', 76, 'Đang bán', 0, 0, 0.0, 0),
(26, 'Tội ác và trừng phạt', 670, 17, 'Tác phẩm kinh điển của Dostoyevsky', 6, 1, 1, 6, 2025, 180000, 220000, 0, '26.png', '2025-11-23 15:16:42', 37, 'Đang bán', 28, 0, 0.0, 0),
(27, 'Những cuộc phiêu lưu của Tom Sawyer', 300, 14, 'Tiểu thuyết thiếu nhi kinh điển', 7, 1, 2, 7, 2025, 80000, 100000, 0, '27.png', '2025-11-24 23:44:03', 62, 'Đang bán', 0, 0, 0.0, 0),
(28, 'Bí mật tư duy triệu phú', 256, 14, 'Sách kỹ năng tài chính nổi tiếng', 8, 5, 3, 8, 2025, 90000, 115000, 80500, '28.png', '2025-11-25 00:18:46', 39, 'Đang bán', 0, 0, 0.0, 30),
(29, 'Trí tuệ Do Thái', 420, 15, 'Sách phát triển tư duy', 9, 5, 4, 9, 2025, 120000, 140000, 0, '29.png', '2025-05-10 01:44:05', 80, 'Đang bán', 0, 0, 0.0, 0),
(30, 'Suy nghĩ nhanh và chậm', 610, 16, 'Sách tâm lý học kinh điển', 10, 5, 5, 10, 2025, 180000, 200000, 140000, '30.png', '2025-11-25 00:18:46', 50, 'Đang bán', 0, 0, 0.0, 30),
(31, 'Bí mật của may mắn', 200, 12, 'Câu chuyện truyền cảm hứng về thành công', 1, 5, 1, 2, 2025, 70000, 85000, 0, '31.png', '2025-11-23 15:16:42', 31, 'Đang bán', 6, 1, 5.0, 0),
(32, 'Tuổi trẻ đáng giá bao nhiêu?', 280, 13, 'Cuốn sách truyền cảm hứng mạnh mẽ cho giới trẻ Việt Nam. Rosie Nguyễn chia sẻ những bài học thực tế về việc học, làm việc và đi. Đừng để tuổi trẻ trôi qua trong tẻ nhạt và hối tiếc. Hãy sống, trải nghiệm và cống hiến hết mình, vì \"Tuổi trẻ giống như một cơn mưa rào, cho dù bị cảm lạnh vẫn muốn quay lại để được ướt thêm một lần nữa\".', 2, 5, 2, 3, 2025, 75000, 90000, 0, '32.png', '2025-11-25 00:44:53', 29, 'Đang bán', 1, 1, 3.0, 0),
(33, 'Cách nghĩ để thành công', 320, 14, 'Cuốn sách kinh điển về thành công', 3, 5, 3, 4, 2025, 90000, 110000, 0, '33.png', '2025-05-10 01:44:05', 74, 'Đang bán', 0, 0, 0.0, 0),
(34, 'Tâm lý học đám đông', 250, 14, 'Cuốn sách tâm lý học nổi tiếng', 4, 5, 4, 5, 2025, 95000, 115000, 0, '34.png', '2025-05-10 01:44:05', 50, 'Đang bán', 0, 0, 0.0, 0),
(35, 'Cư xử như đàn bà suy nghĩ như đàn ông', 340, 15, 'Cuốn sách tâm lý tình cảm phổ biến', 5, 5, 5, 6, 2025, 100000, 120000, 0, '35.png', '2025-05-10 01:44:05', 49, 'Đang bán', 0, 0, 0.0, 0),
(36, 'Lược sử thời gian', 280, 14, 'Cuốn sách khoa học nổi tiếng của Stephen Hawking', 6, 8, 1, 7, 2025, 140000, 170000, 119000, '36.png', '2025-11-25 00:18:46', 64, 'Đang bán', 0, 0, 0.0, 30),
(37, 'Bách khoa toàn thư vũ trụ', 500, 17, 'Cuốn sách khoa học về vũ trụ', 7, 8, 2, 8, 2025, 200000, 230000, 161000, '37.png', '2025-11-25 00:18:46', 42, 'Đang bán', 0, 0, 0.0, 30),
(38, 'Hành trình về phương Đông', 320, 14, 'Cuốn sách huyền bí về tri thức phương Đông', 8, 5, 3, 9, 2025, 120000, 150000, 0, '38.png', '2025-05-10 01:44:05', 39, 'Đang bán', 0, 0, 0.0, 0),
(39, 'Vũ trụ trong vỏ hạt dẻ', 450, 16, 'Cuốn sách khoa học của Stephen Hawking', 9, 8, 4, 10, 2025, 180000, 200000, 0, '39.png', '2025-05-10 01:44:05', 38, 'Đang bán', 0, 0, 0.0, 0),
(40, 'Những người sống sót', 600, 16, 'Tiểu thuyết tâm lý ly kỳ', 10, 10, 5, 1, 2025, 150000, 180000, 0, '40.png', '2025-05-10 01:44:05', 46, 'Đang bán', 0, 0, 0.0, 0),
(41, 'Hạt giống tâm hồn', 250, 14, 'Tuyển tập những câu chuyện truyền cảm hứng', 1, 5, 1, 1, 2025, 70000, 90000, 0, '41.png', '2025-11-23 15:16:42', 33, 'Đang bán', 2, 0, 0.0, 0),
(42, 'Giết con chim nhại', 400, 16, 'Tiểu thuyết kinh điển về phân biệt chủng tộc', 2, 1, 2, 2, 2025, 120000, 150000, 0, '42.png', '2025-05-10 01:44:05', 56, 'Đang bán', 0, 0, 0.0, 0),
(43, 'Tôi là Bêtô', 300, 14, 'Truyện thiếu nhi nổi tiếng của Nguyễn Nhật Ánh', 3, 1, 3, 3, 2025, 80000, 100000, 0, '43.png', '2025-05-10 01:44:05', 47, 'Đang bán', 0, 0, 0.0, 0),
(44, 'Cuộc sống không giới hạn', 280, 14, 'Tự truyện của Nick Vujicic', 4, 5, 4, 4, 2025, 90000, 110000, 77000, '44.png', '2025-11-25 00:18:46', 36, 'Đang bán', 0, 0, 0.0, 30),
(45, 'Bí mật của Phan Thiên Ân', 350, 15, 'Cuốn sách phát triển bản thân đầy triết lý', 5, 5, 5, 5, 2025, 95000, 120000, 0, '45.png', '2025-05-10 01:44:05', 61, 'Đang bán', 0, 0, 0.0, 0),
(46, 'Sherlock Holmes: Dấu bộ tứ', 400, 15, 'Tập truyện trinh thám nổi tiếng', 6, 10, 1, 6, 2025, 140000, 170000, 0, '46.png', '2025-05-10 01:44:05', 66, 'Đang bán', 0, 0, 0.0, 0),
(47, 'Thế giới phẳng', 500, 17, 'Cuốn sách về toàn cầu hóa', 7, 8, 2, 7, 2025, 160000, 190000, 0, '47.png', '2025-05-10 01:44:05', 68, 'Đang bán', 0, 0, 0.0, 0),
(48, 'Nhà thờ Đức Bà Paris', 650, 17, 'Tác phẩm kinh điển của Victor Hugo', 8, 1, 3, 8, 2025, 200000, 230000, 0, '48.png', '2025-11-23 15:16:42', 39, 'Đang bán', 19, 1, 2.0, 0),
(49, 'Lão Hạc', 120, 12, 'Truyện ngắn nổi tiếng của Nam Cao', 9, 2, 4, 9, 2025, 50000, 70000, 0, '49.png', '2025-11-21 10:52:46', 52, 'Đang bán', 6, 0, 0.0, 0),
(50, 'Những kẻ xuất chúng', 320, 14, 'Cuốn sách về sự thành công', 10, 5, 5, 10, 2025, 130000, 160000, 0, '50.png', '2025-05-10 01:44:05', 36, 'Đang bán', 0, 0, 0.0, 0),
(51, 'Những tấm lòng cao cả', 280, 13, 'Truyện thiếu nhi kinh điển', 1, 1, 1, 2, 2025, 70000, 90000, 0, '51.png', '2025-11-24 23:44:13', 77, 'Đang bán', 0, 0, 0.0, 0),
(52, 'Đông A liệt truyện', 750, 18, 'Lịch sử nhà Trần', 2, 8, 2, 3, 2025, 180000, 220000, 0, '52.png', '2025-11-21 10:52:46', 43, 'Đang bán', 1, 0, 0.0, 0),
(53, 'Lược sử tương lai', 430, 15, 'Sách khoa học dự đoán tương lai', 3, 8, 3, 4, 2025, 160000, 190000, 133000, '53.png', '2025-11-25 00:18:46', 57, 'Đang bán', 0, 0, 0.0, 30),
(54, 'Truyện Kiều', 500, 16, 'Tác phẩm kinh điển của Nguyễn Du', 4, 2, 4, 5, 2025, 100000, 130000, 0, '54.png', '2025-05-10 01:44:05', 74, 'Đang bán', 0, 0, 0.0, 0),
(55, 'Sống xanh', 270, 14, 'Cuốn sách về bảo vệ môi trường', 5, 5, 5, 6, 2025, 110000, 130000, 0, '55.png', '2025-05-10 01:44:05', 70, 'Đang bán', 0, 0, 0.0, 0),
(56, 'Kẻ trộm sách', 600, 17, 'Tiểu thuyết về chiến tranh và sách', 6, 1, 1, 7, 2025, 150000, 180000, 0, '56.png', '2025-11-23 15:16:42', 25, 'Đang bán', 21, 0, 0.0, 0),
(57, 'Sự im lặng của bầy cừu', 380, 14, 'Tiểu thuyết trinh thám nổi tiếng', 7, 10, 2, 8, 2025, 140000, 170000, 0, '57.png', '2025-05-10 01:44:05', 43, 'Đang bán', 0, 0, 0.0, 0),
(58, 'Nghệ thuật tư duy rành mạch', 350, 14, 'Bạn có chắc mình luôn suy nghĩ logic? Cuốn sách vạch trần 99 lỗi tư duy (cognitive biases) mà chúng ta thường mắc phải hàng ngày như: hiệu ứng hào quang, thiên kiến xác nhận... Một cuốn cẩm nang giúp bạn đưa ra những quyết định sáng suốt hơn trong cuộc sống và công việc.', 8, 5, 3, 9, 2025, 130000, 160000, 0, '58.png', '2025-11-25 00:44:53', 47, 'Đang bán', 0, 0, 0.0, 0),
(59, 'Làm giàu không khó', 300, 14, 'Cuốn sách dạy kinh doanh', 9, 5, 4, 10, 2025, 90000, 110000, 0, '59.png', '2025-11-24 23:44:19', 78, 'Đang bán', 0, 0, 0.0, 0),
(60, 'Mắt biếc', 260, 13, 'Câu chuyện tình yêu đơn phương buồn nhất và đẹp nhất của Nguyễn Nhật Ánh. Ngạn và Hà Lan, làng Đo Đo và thành phố thị phi. \"Mắt biếc\" là nỗi tiếc nuối khôn nguôi của tuổi trẻ, là tình yêu thuần khiết nhưng đầy bi kịch, để lại trong lòng độc giả một nốt trầm xao xuyến mãi không tan.', 10, 1, 5, 1, 2025, 80000, 100000, 70000, '60.png', '2025-11-25 00:41:34', 67, 'Đang bán', 0, 0, 0.0, 30),
(80, 'Giáng Sinh Yêu Thương', 336, 13, 'Câu chuyện kể về cuộc phiêu lưu trong đêm giáng sinh của ông già Ebezener Scrooger ham mê đồng tiền khiến ông ta chẳng bao giờ được tận hưởng một mùa lễ Giáng sinh, ông ta luôn cho rằng giáng sinh chỉ là cái ngày thừa thãi để những người khác có lý do đòi hỏi và bòn rút tiền của lão mà thôi.', 24, 1, 1, 3, 2015, 70000, 800000, 0, 'book_6924a0e7b86d8.png', '2025-11-25 01:19:03', 30, 'Đang bán', 0, 0, 0.0, 0);

--
-- Triggers `sach`
--
DELIMITER $$
CREATE TRIGGER `trg_CapNhatGiaSale_TuDong` BEFORE UPDATE ON `sach` FOR EACH ROW BEGIN
    DECLARE giaMoi DECIMAL(15,2);

    -- Trường hợp 1: Có giảm giá
    IF NEW.phanTramGiamGia > 0 THEN
        -- Tính giá sau giảm
        SET giaMoi = NEW.giaBan * (100 - NEW.phanTramGiamGia) / 100;
        
        -- LÀM TRÒN SỐ (Chọn 1 trong 2 dòng dưới đây tùy ý thích của bạn)
        
        -- Cách 1: Làm tròn đến hàng trăm (VD: 63.750 giữ nguyên, 63.755 -> 63.800) -> KHUYÊN DÙNG
        SET NEW.giaSale = ROUND(giaMoi, -2);

        -- Cách 2: Làm tròn đến hàng nghìn (VD: 63.750 -> 64.000)
        -- SET NEW.giaSale = ROUND(giaMoi, -3);

    -- Trường hợp 2: Không giảm giá
    ELSE
        SET NEW.giaSale = 0;
    END IF;

    -- Bổ sung: Logic cập nhật khi sửa giá gốc (như cũ)
    IF NEW.giaBan <> OLD.giaBan AND NEW.phanTramGiamGia > 0 THEN
         SET giaMoi = NEW.giaBan * (100 - NEW.phanTramGiamGia) / 100;
         SET NEW.giaSale = ROUND(giaMoi, -2); -- Nhớ sửa số -2 hoặc -3 cho khớp ở trên
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `tacgia`
--

CREATE TABLE `tacgia` (
  `maTacGia` int(11) NOT NULL,
  `tenTacGia` varchar(100) NOT NULL,
  `ngayCapNhat` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `trangThai` enum('Hoạt động','Tạm dừng') DEFAULT 'Hoạt động'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tacgia`
--

INSERT INTO `tacgia` (`maTacGia`, `tenTacGia`, `ngayCapNhat`, `trangThai`) VALUES
(1, 'Nguyễn Nhật Ánh', '2025-05-13 01:18:38', 'Hoạt động'),
(2, 'Nguyễn Ngọc Thạch', '2025-04-06 08:27:23', 'Hoạt động'),
(3, 'Trang Hạ', '2025-04-06 08:19:12', 'Hoạt động'),
(4, 'Gào (Vũ Phương Thanh)', '2025-03-28 07:37:46', 'Hoạt động'),
(5, 'Minh Nhật', '2025-03-28 07:38:05', 'Hoạt động'),
(6, 'Phan Ý Yên', '2025-04-21 20:16:45', 'Hoạt động'),
(7, 'Tờ Pi', '2025-03-27 02:50:47', 'Hoạt động'),
(8, 'Kawi Hồng Phương', '2025-03-27 02:50:47', 'Hoạt động'),
(9, 'Nguyễn Huy Thiệp', '2025-03-28 05:07:14', 'Hoạt động'),
(10, 'Nguyễn Ngọc Tư', '2025-03-27 02:50:47', 'Hoạt động'),
(22, 'Trần Thanh Quy ngu', '2025-05-15 04:24:55', 'Hoạt động'),
(23, 'Tác giả Spoce', '2025-05-09 17:53:33', 'Tạm dừng'),
(24, ' Charles Dickens', '2025-11-25 01:09:33', 'Hoạt động');

-- --------------------------------------------------------

--
-- Table structure for table `theloai`
--

CREATE TABLE `theloai` (
  `maTheLoai` int(11) NOT NULL,
  `tenTheLoai` varchar(100) NOT NULL,
  `ngayCapNhat` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `trangThai` enum('Hoạt động','Tạm dừng') DEFAULT 'Hoạt động'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `theloai`
--

INSERT INTO `theloai` (`maTheLoai`, `tenTheLoai`, `ngayCapNhat`, `trangThai`) VALUES
(1, 'Tiểu thuyết', '2025-04-07 02:40:31', 'Hoạt động'),
(2, 'Truyện ngắn', '2025-04-06 10:08:23', 'Hoạt động'),
(3, 'Thơ ca', '2025-03-28 02:57:01', 'Hoạt động'),
(4, 'Khoa học viễn tưởng', '2025-03-28 03:18:35', 'Hoạt động'),
(5, 'Tâm lý học', '2025-03-28 02:26:50', 'Hoạt động'),
(6, 'Kinh tế', '2025-03-28 05:39:57', 'Hoạt động'),
(7, 'Lịch sử', '2025-03-27 02:50:47', 'Hoạt động'),
(8, 'Tôn giáo', '2025-03-27 02:50:47', 'Hoạt động'),
(9, 'Giáo dục', '2025-03-27 02:50:47', 'Hoạt động'),
(10, 'Truyện tranh', '2025-04-20 00:32:38', 'Hoạt động'),
(13, 'Chính kịch', '2025-05-13 07:21:37', 'Hoạt động'),
(14, 'Thần thoại', '2025-05-09 17:39:36', 'Hoạt động'),
(15, 'Thể loại Spoce', '2025-05-09 18:21:17', 'Tạm dừng'),
(16, 'Trinh thám', '2025-05-12 10:45:02', 'Tạm dừng'),
(17, 'Địa lý', '2025-05-12 10:44:55', 'Tạm dừng'),
(18, 'Kinh dị', '2025-05-12 10:44:49', 'Tạm dừng'),
(19, 'Lập trình', '2025-05-12 10:44:46', 'Tạm dừng'),
(20, 'Thiếu nhi', '2025-05-12 10:44:42', 'Tạm dừng'),
(21, 'Khoa học', '2025-05-12 10:44:36', 'Tạm dừng'),
(22, 'Giải trí', '2025-05-12 10:44:31', 'Tạm dừng');

-- --------------------------------------------------------

--
-- Table structure for table `vnpay`
--

CREATE TABLE `vnpay` (
  `maGiaoDichVNPAY` varchar(50) NOT NULL,
  `maDonHang` int(11) NOT NULL,
  `soTien` int(11) NOT NULL,
  `trangThai` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `vnpay`
--

INSERT INTO `vnpay` (`maGiaoDichVNPAY`, `maDonHang`, `soTien`, `trangThai`) VALUES
('0', 162, 580000, 'Thanh toán thất bại'),
('202517160340', 164, 10000000, 'Thanh toán thất bại'),
('202517160804', 164, 10000000, 'Thanh toán thất bại'),
('202517160851', 164, 10000000, 'Thanh toán thất bại'),
('202517160945', 164, 10000000, 'Thanh toán thất bại'),
('202522150016', 66, 280000000, 'Thanh toán thành công');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `chitietdonhang`
--
ALTER TABLE `chitietdonhang`
  ADD PRIMARY KEY (`maDonHang`,`maSach`),
  ADD KEY `maSach` (`maSach`);

--
-- Indexes for table `chitietphieunhap`
--
ALTER TABLE `chitietphieunhap`
  ADD PRIMARY KEY (`maPhieuNhap`,`maSach`),
  ADD KEY `maSach` (`maSach`);

--
-- Indexes for table `chitietquyen`
--
ALTER TABLE `chitietquyen`
  ADD PRIMARY KEY (`maQuyen`,`maChucNang`,`maHanhDong`),
  ADD KEY `maChucNang` (`maChucNang`),
  ADD KEY `maHanhDong` (`maHanhDong`);

--
-- Indexes for table `chucnang`
--
ALTER TABLE `chucnang`
  ADD PRIMARY KEY (`maChucNang`),
  ADD UNIQUE KEY `tenchucNang` (`tenChucNang`);

--
-- Indexes for table `diachinguoidung`
--
ALTER TABLE `diachinguoidung`
  ADD PRIMARY KEY (`maDiaChi`),
  ADD KEY `maNguoiDung` (`maNguoiDung`);

--
-- Indexes for table `donhang`
--
ALTER TABLE `donhang`
  ADD PRIMARY KEY (`maDonHang`),
  ADD KEY `maKhachHang` (`maKhachHang`),
  ADD KEY `maKhuyenMai` (`maKhuyenMai`),
  ADD KEY `fk_maPhuongThuc` (`maPhuongThuc`),
  ADD KEY `fk_maNhanVien` (`maNhanVien`);

--
-- Indexes for table `giohang`
--
ALTER TABLE `giohang`
  ADD PRIMARY KEY (`maNguoiDung`,`maSach`),
  ADD KEY `maSach` (`maSach`);

--
-- Indexes for table `hanhdong`
--
ALTER TABLE `hanhdong`
  ADD PRIMARY KEY (`maHanhDong`);

--
-- Indexes for table `loaibia`
--
ALTER TABLE `loaibia`
  ADD PRIMARY KEY (`maLoaiBia`);

--
-- Indexes for table `nguoidung`
--
ALTER TABLE `nguoidung`
  ADD PRIMARY KEY (`maNguoiDung`),
  ADD UNIQUE KEY `tenTaiKhoan` (`tenTaiKhoan`),
  ADD UNIQUE KEY `soDT` (`soDT`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `maQuyen` (`maQuyen`);

--
-- Indexes for table `nhacungcap`
--
ALTER TABLE `nhacungcap`
  ADD PRIMARY KEY (`maNCC`),
  ADD UNIQUE KEY `soDT` (`soDT`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `nhaxuatban`
--
ALTER TABLE `nhaxuatban`
  ADD PRIMARY KEY (`maNXB`);

--
-- Indexes for table `phieugiamgia`
--
ALTER TABLE `phieugiamgia`
  ADD PRIMARY KEY (`maPGG`);

--
-- Indexes for table `phieunhap`
--
ALTER TABLE `phieunhap`
  ADD PRIMARY KEY (`maPhieuNhap`),
  ADD KEY `maNCC` (`maNCC`),
  ADD KEY `fk_maTaiKhoanNhanVien` (`maNhanVien`);

--
-- Indexes for table `phuongthucthanhtoan`
--
ALTER TABLE `phuongthucthanhtoan`
  ADD PRIMARY KEY (`maPhuongThuc`);

--
-- Indexes for table `quyen`
--
ALTER TABLE `quyen`
  ADD PRIMARY KEY (`maQuyen`),
  ADD UNIQUE KEY `tenQuyen` (`tenQuyen`);

--
-- Indexes for table `ratings`
--
ALTER TABLE `ratings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_user_book_rating` (`user_id`,`book_id`),
  ADD KEY `book_id` (`book_id`),
  ADD KEY `danhGia_ibfk_1` (`order_id`,`book_id`);

--
-- Indexes for table `sach`
--
ALTER TABLE `sach`
  ADD PRIMARY KEY (`maSach`),
  ADD KEY `maTacGia` (`maTacGia`),
  ADD KEY `maTheLoai` (`maTheLoai`),
  ADD KEY `maLoaiBia` (`maLoaiBia`),
  ADD KEY `maNXB` (`maNXB`);

--
-- Indexes for table `tacgia`
--
ALTER TABLE `tacgia`
  ADD PRIMARY KEY (`maTacGia`);

--
-- Indexes for table `theloai`
--
ALTER TABLE `theloai`
  ADD PRIMARY KEY (`maTheLoai`);

--
-- Indexes for table `vnpay`
--
ALTER TABLE `vnpay`
  ADD PRIMARY KEY (`maGiaoDichVNPAY`),
  ADD KEY `fk_vnpay_donhang` (`maDonHang`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `chucnang`
--
ALTER TABLE `chucnang`
  MODIFY `maChucNang` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `diachinguoidung`
--
ALTER TABLE `diachinguoidung`
  MODIFY `maDiaChi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `donhang`
--
ALTER TABLE `donhang`
  MODIFY `maDonHang` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=186;

--
-- AUTO_INCREMENT for table `hanhdong`
--
ALTER TABLE `hanhdong`
  MODIFY `maHanhDong` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `loaibia`
--
ALTER TABLE `loaibia`
  MODIFY `maLoaiBia` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `nguoidung`
--
ALTER TABLE `nguoidung`
  MODIFY `maNguoiDung` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=83;

--
-- AUTO_INCREMENT for table `nhacungcap`
--
ALTER TABLE `nhacungcap`
  MODIFY `maNCC` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `nhaxuatban`
--
ALTER TABLE `nhaxuatban`
  MODIFY `maNXB` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `phieugiamgia`
--
ALTER TABLE `phieugiamgia`
  MODIFY `maPGG` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `phieunhap`
--
ALTER TABLE `phieunhap`
  MODIFY `maPhieuNhap` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `phuongthucthanhtoan`
--
ALTER TABLE `phuongthucthanhtoan`
  MODIFY `maPhuongThuc` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `quyen`
--
ALTER TABLE `quyen`
  MODIFY `maQuyen` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `ratings`
--
ALTER TABLE `ratings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `sach`
--
ALTER TABLE `sach`
  MODIFY `maSach` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=81;

--
-- AUTO_INCREMENT for table `tacgia`
--
ALTER TABLE `tacgia`
  MODIFY `maTacGia` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `theloai`
--
ALTER TABLE `theloai`
  MODIFY `maTheLoai` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `chitietdonhang`
--
ALTER TABLE `chitietdonhang`
  ADD CONSTRAINT `chiTietDonHang_ibfk_1` FOREIGN KEY (`maDonHang`) REFERENCES `donhang` (`maDonHang`) ON DELETE CASCADE,
  ADD CONSTRAINT `chiTietDonHang_ibfk_2` FOREIGN KEY (`maSach`) REFERENCES `sach` (`maSach`) ON DELETE CASCADE;

--
-- Constraints for table `chitietphieunhap`
--
ALTER TABLE `chitietphieunhap`
  ADD CONSTRAINT `chiTietPhieuNhap_ibfk_1` FOREIGN KEY (`maPhieuNhap`) REFERENCES `phieunhap` (`maPhieuNhap`) ON DELETE CASCADE,
  ADD CONSTRAINT `chiTietPhieuNhap_ibfk_2` FOREIGN KEY (`maSach`) REFERENCES `sach` (`maSach`) ON DELETE CASCADE;

--
-- Constraints for table `chitietquyen`
--
ALTER TABLE `chitietquyen`
  ADD CONSTRAINT `chiTietQuyen_ibfk_1` FOREIGN KEY (`maQuyen`) REFERENCES `quyen` (`maQuyen`) ON DELETE CASCADE,
  ADD CONSTRAINT `chiTietQuyen_ibfk_2` FOREIGN KEY (`maChucNang`) REFERENCES `chucnang` (`maChucNang`) ON DELETE CASCADE,
  ADD CONSTRAINT `chiTietQuyen_ibfk_3` FOREIGN KEY (`maHanhDong`) REFERENCES `hanhdong` (`maHanhDong`) ON DELETE CASCADE;

--
-- Constraints for table `diachinguoidung`
--
ALTER TABLE `diachinguoidung`
  ADD CONSTRAINT `diaChiNguoiDung_ibfk_1` FOREIGN KEY (`maNguoiDung`) REFERENCES `nguoidung` (`manguoiDung`) ON DELETE CASCADE;

--
-- Constraints for table `donhang`
--
ALTER TABLE `donhang`
  ADD CONSTRAINT `donHang_ibfk_1` FOREIGN KEY (`maKhachHang`) REFERENCES `nguoidung` (`manguoiDung`) ON DELETE CASCADE,
  ADD CONSTRAINT `donHang_ibfk_2` FOREIGN KEY (`maKhuyenMai`) REFERENCES `phieugiamgia` (`maPGG`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_maNhanVien` FOREIGN KEY (`maNhanVien`) REFERENCES `nguoidung` (`manguoiDung`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_phuongThucThanhToan` FOREIGN KEY (`maPhuongThuc`) REFERENCES `phuongthucthanhtoan` (`maPhuongThuc`);

--
-- Constraints for table `giohang`
--
ALTER TABLE `giohang`
  ADD CONSTRAINT `gioHang_ibfk_1` FOREIGN KEY (`maNguoiDung`) REFERENCES `nguoidung` (`manguoiDung`) ON DELETE CASCADE,
  ADD CONSTRAINT `gioHang_ibfk_2` FOREIGN KEY (`maSach`) REFERENCES `sach` (`maSach`) ON DELETE CASCADE;

--
-- Constraints for table `nguoidung`
--
ALTER TABLE `nguoidung`
  ADD CONSTRAINT `nguoiDung_ibfk_1` FOREIGN KEY (`maQuyen`) REFERENCES `quyen` (`maQuyen`) ON DELETE SET NULL;

--
-- Constraints for table `phieunhap`
--
ALTER TABLE `phieunhap`
  ADD CONSTRAINT `fk_maTaiKhoanNhanVien` FOREIGN KEY (`maNhanVien`) REFERENCES `nguoidung` (`maNguoiDung`),
  ADD CONSTRAINT `phieuNhap_ibfk_2` FOREIGN KEY (`maNCC`) REFERENCES `nhacungcap` (`maNCC`) ON DELETE CASCADE;

--
-- Constraints for table `ratings`
--
ALTER TABLE `ratings`
  ADD CONSTRAINT `danhGia_ibfk_1` FOREIGN KEY (`order_id`,`book_id`) REFERENCES `chitietdonhang` (`maDonHang`, `maSach`) ON DELETE CASCADE,
  ADD CONSTRAINT `ratings_ibfk_1` FOREIGN KEY (`book_id`) REFERENCES `sach` (`maSach`),
  ADD CONSTRAINT `ratings_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `nguoidung` (`maNguoiDung`);

--
-- Constraints for table `sach`
--
ALTER TABLE `sach`
  ADD CONSTRAINT `sach_ibfk_1` FOREIGN KEY (`maTacGia`) REFERENCES `tacgia` (`matacGia`) ON DELETE SET NULL,
  ADD CONSTRAINT `sach_ibfk_2` FOREIGN KEY (`maTheLoai`) REFERENCES `theloai` (`matheLoai`) ON DELETE SET NULL,
  ADD CONSTRAINT `sach_ibfk_3` FOREIGN KEY (`maLoaiBia`) REFERENCES `loaibia` (`maLoaiBia`) ON DELETE SET NULL,
  ADD CONSTRAINT `sach_ibfk_4` FOREIGN KEY (`maNXB`) REFERENCES `nhaxuatban` (`maNXB`) ON DELETE SET NULL;

--
-- Constraints for table `vnpay`
--
ALTER TABLE `vnpay`
  ADD CONSTRAINT `fk_vnpay_donhang` FOREIGN KEY (`maDonHang`) REFERENCES `donhang` (`maDonHang`) ON DELETE CASCADE;
SET FOREIGN_KEY_CHECKS=1;

-- --------------------------------------------------------
-- HR Module Tables (Module 3.1 & 3.2)
-- --------------------------------------------------------

CREATE TABLE IF NOT EXISTS `nhanVien` (
  `maNhanVien` int(11) NOT NULL AUTO_INCREMENT,
  `maNguoiDung` int(11) NOT NULL,
  `chucVu` varchar(100) NOT NULL,
  `ngayVaoLam` date NOT NULL,
  `luongCoBan` decimal(15,2) NOT NULL DEFAULT 5000000.00,
  `heSoLuong` decimal(4,2) NOT NULL DEFAULT 1.00,
  `phuCapCoDinh` decimal(15,2) NOT NULL DEFAULT 0.00,
  `trangThai` enum('Dang lam','Da nghi') NOT NULL DEFAULT 'Dang lam',
  PRIMARY KEY (`maNhanVien`),
  UNIQUE KEY `maNguoiDung` (`maNguoiDung`),
  CONSTRAINT `nhanvien_ibfk_1` FOREIGN KEY (`maNguoiDung`) REFERENCES `nguoidung` (`maNguoiDung`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `bangLuong` (
  `maBangLuong` int(11) NOT NULL AUTO_INCREMENT,
  `maNhanVien` int(11) NOT NULL,
  `thang` tinyint(2) NOT NULL,
  `nam` year(4) NOT NULL,
  `luongCoBan` decimal(15,2) NOT NULL,
  `heSoLuong` decimal(4,2) NOT NULL,
  `phuCap` decimal(15,2) NOT NULL DEFAULT 0.00,
  `thuong` decimal(15,2) NOT NULL DEFAULT 0.00,
  `khauTru` decimal(15,2) NOT NULL DEFAULT 0.00,
  `soNgayLam` decimal(4,1) NOT NULL DEFAULT 22.0,
  `soNgayNghiPhep` decimal(4,1) NOT NULL DEFAULT 0.0,
  `thucLinh` decimal(15,2) NOT NULL,
  `ghiChu` text DEFAULT NULL,
  PRIMARY KEY (`maBangLuong`),
  UNIQUE KEY `uk_nhanvien_thang_nam` (`maNhanVien`,`thang`,`nam`),
  CONSTRAINT `bangluong_ibfk_1` FOREIGN KEY (`maNhanVien`) REFERENCES `nhanVien` (`maNhanVien`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `donNghi` (
  `maDon` int(11) NOT NULL AUTO_INCREMENT,
  `maNhanVien` int(11) NOT NULL,
  `loaiNghi` enum('Nghi phep','Nghi om dau','Nghi thai san','Nghi viec') NOT NULL DEFAULT 'Nghi phep',
  `ngayBatDau` date NOT NULL,
  `ngayKetThuc` date NOT NULL,
  `lyDo` text DEFAULT NULL,
  `trangThai` enum('Cho duyet','Da duyet','Tu choi') NOT NULL DEFAULT 'Cho duyet',
  `ghiChuDuyet` text DEFAULT NULL,
  `ngayNop` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`maDon`),
  CONSTRAINT `donnghi_ibfk_1` FOREIGN KEY (`maNhanVien`) REFERENCES `nhanVien` (`maNhanVien`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `lichSuChucVu` (
  `maLich` int(11) NOT NULL AUTO_INCREMENT,
  `maNhanVien` int(11) NOT NULL,
  `chucVuCu` varchar(100) DEFAULT NULL,
  `chucVuMoi` varchar(100) DEFAULT NULL,
  `luongCoBanCu` decimal(15,2) DEFAULT NULL,
  `luongCoBanMoi` decimal(15,2) DEFAULT NULL,
  `heSoLuongCu` decimal(4,2) DEFAULT NULL,
  `heSoLuongMoi` decimal(4,2) DEFAULT NULL,
  `ngayHieuLuc` date NOT NULL,
  `ghiChu` text DEFAULT NULL,
  `thoiGianTao` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`maLich`),
  CONSTRAINT `lichsuchucvu_ibfk_1` FOREIGN KEY (`maNhanVien`) REFERENCES `nhanVien` (`maNhanVien`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

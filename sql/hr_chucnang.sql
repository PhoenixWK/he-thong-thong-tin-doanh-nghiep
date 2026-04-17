-- ============================================================
-- Bổ sung chức năng module quản lý nhân sự vào bảng chucnang
-- Chạy file này một lần để seed dữ liệu
-- ============================================================

-- Thêm các chức năng HR vào bảng chucnang
INSERT INTO `chucnang` (`maChucNang`, `tenChucNang`, `trangThai`) VALUES
(17, 'Quản lý nhân viên',        'Hoạt động'),
(18, 'Quản lý lương',            'Hoạt động'),
(19, 'Duyệt nghỉ phép',          'Hoạt động'),
(20, 'Thông tin cá nhân',        'Hoạt động'),
(21, 'Đơn xin nghỉ phép',        'Hoạt động'),
(22, 'Bảng lương nhân viên',     'Hoạt động');

-- Tạo nhóm quyền "Quản lý nhân sự"
INSERT INTO `quyen` (`tenQuyen`, `trangThai`, `ngayCapNhat`) VALUES
('Quản lý nhân sự', 'Hoạt động', NOW());

-- Lấy maQuyen vừa tạo (thay @maQuyen bằng ID thực tế nếu chạy thủ công)
SET @maQuyen = LAST_INSERT_ID();

-- Gán các hành động cho chức năng quản lý nhân viên (maChucNang=17): Lọc, Chi tiết, Thêm, Sửa, Xóa/Khóa
INSERT INTO `chitietquyen` (`maQuyen`, `maChucNang`, `maHanhDong`) VALUES
(@maQuyen, 17, 1), -- Lọc
(@maQuyen, 17, 2), -- Chi tiết
(@maQuyen, 17, 3), -- Thêm
(@maQuyen, 17, 4), -- Sửa
(@maQuyen, 17, 5), -- Xóa/Khóa

-- Quản lý lương (maChucNang=18): Lọc, Chi tiết, Thêm, Sửa
(@maQuyen, 18, 1),
(@maQuyen, 18, 2),
(@maQuyen, 18, 3),
(@maQuyen, 18, 4),

-- Duyệt nghỉ phép (maChucNang=19): Lọc, Chi tiết, Sửa
(@maQuyen, 19, 1),
(@maQuyen, 19, 2),
(@maQuyen, 19, 4);

-- ============================================================
-- Gán chức năng HR cho nhóm quyền "Nhân viên" (maQuyen = 1)
-- ============================================================
INSERT INTO `chitietquyen` (`maQuyen`, `maChucNang`, `maHanhDong`) VALUES
-- Thông tin cá nhân (maChucNang=20): Chi tiết, Sửa
(1, 20, 2),
(1, 20, 4),

-- Đơn xin nghỉ phép (maChucNang=21): Lọc, Chi tiết, Thêm
(1, 21, 1),
(1, 21, 2),
(1, 21, 3),

-- Bảng lương (maChucNang=22): Lọc, Chi tiết
(1, 22, 1),
(1, 22, 2);

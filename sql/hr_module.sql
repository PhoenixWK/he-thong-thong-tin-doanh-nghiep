-- =============================================
-- Module Quản lý Nhân sự - HR Module
-- Decoupled from main bookstore system
-- Only references nguoidung table via maNguoiDung
-- =============================================

-- Thông tin nhân viên (mở rộng từ nguoiDung)
CREATE TABLE IF NOT EXISTS nhanVien (
    maNhanVien  INT AUTO_INCREMENT PRIMARY KEY,
    maNguoiDung INT NOT NULL UNIQUE,
    chucVu      VARCHAR(100)     DEFAULT '',
    ngayVaoLam  DATE             NULL,
    luongCoBan  DECIMAL(15,2)    NOT NULL DEFAULT 0,
    heSoLuong   DECIMAL(5,2)     NOT NULL DEFAULT 1.00,
    phuCapCoDinh DECIMAL(15,2)   NOT NULL DEFAULT 0,
    trangThai   ENUM('Đang làm','Đã nghỉ') NOT NULL DEFAULT 'Đang làm',
    ngayTao     DATETIME         NOT NULL DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_nguoidung (maNguoiDung)
) ENGINE=InnoDB CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Bảng lương hàng tháng
CREATE TABLE IF NOT EXISTS bangLuong (
    maBangLuong INT AUTO_INCREMENT PRIMARY KEY,
    maNhanVien  INT          NOT NULL,
    thang       TINYINT      NOT NULL COMMENT '1-12',
    nam         YEAR         NOT NULL,
    luongCoBan  DECIMAL(15,2) NOT NULL DEFAULT 0,
    heSoLuong   DECIMAL(5,2)  NOT NULL DEFAULT 1.00,
    phuCap      DECIMAL(15,2) NOT NULL DEFAULT 0,
    thuong      DECIMAL(15,2) NOT NULL DEFAULT 0,
    khauTru     DECIMAL(15,2) NOT NULL DEFAULT 0,
    soNgayLam   DECIMAL(5,2)  NOT NULL DEFAULT 0,
    soNgayNghiPhep DECIMAL(5,2) NOT NULL DEFAULT 0,
    thucLinh    DECIMAL(15,2) NOT NULL DEFAULT 0 COMMENT 'luongCoBan*heSoLuong*(soNgayLam/22)+phuCap+thuong-khauTru',
    ghiChu      TEXT          NULL,
    ngayTao     DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY uk_luong_thang (maNhanVien, thang, nam),
    FOREIGN KEY (maNhanVien) REFERENCES nhanVien(maNhanVien) ON DELETE CASCADE
) ENGINE=InnoDB CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Đơn xin nghỉ
CREATE TABLE IF NOT EXISTS donNghi (
    maDon       INT AUTO_INCREMENT PRIMARY KEY,
    maNhanVien  INT          NOT NULL,
    loaiNghi    ENUM('Nghỉ phép','Nghỉ ốm đau','Nghỉ thai sản','Nghỉ việc') NOT NULL,
    ngayBatDau  DATE         NOT NULL,
    ngayKetThuc DATE         NULL COMMENT 'NULL nếu là Nghỉ việc không rõ ngày',
    lyDo        TEXT         NOT NULL,
    trangThai   ENUM('Chờ duyệt','Đã duyệt','Từ chối') NOT NULL DEFAULT 'Chờ duyệt',
    ghiChuDuyet TEXT         NULL COMMENT 'Ghi chú của người duyệt',
    ngayNop     DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (maNhanVien) REFERENCES nhanVien(maNhanVien) ON DELETE CASCADE
) ENGINE=InnoDB CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- =============================================
-- Dữ liệu mẫu (seed data)
-- =============================================

-- Tạo nhân viên mẫu từ user ID 83 (Anh Độ Mixi - Nhân viên)
INSERT IGNORE INTO nhanVien (maNguoiDung, chucVu, ngayVaoLam, luongCoBan, heSoLuong, phuCapCoDinh)
VALUES (83, 'Nhân viên bán hàng', '2024-01-15', 5000000, 1.50, 500000);

-- Bảng lương mẫu
INSERT IGNORE INTO bangLuong (maNhanVien, thang, nam, luongCoBan, heSoLuong, phuCap, thuong, khauTru, soNgayLam, soNgayNghiPhep, thucLinh, ghiChu)
SELECT maNhanVien, 1, 2026, luongCoBan, heSoLuong, phuCapCoDinh, 0, 0, 22, 0,
       ROUND(luongCoBan * heSoLuong * (22/22) + phuCapCoDinh, 0), 'Tháng đủ công'
FROM nhanVien WHERE maNguoiDung = 83;

INSERT IGNORE INTO bangLuong (maNhanVien, thang, nam, luongCoBan, heSoLuong, phuCap, thuong, khauTru, soNgayLam, soNgayNghiPhep, thucLinh, ghiChu)
SELECT maNhanVien, 2, 2026, luongCoBan, heSoLuong, phuCapCoDinh, 500000, 0, 20, 2,
       ROUND(luongCoBan * heSoLuong * (20/22) + phuCapCoDinh + 500000, 0), 'Nghỉ 2 ngày, có thưởng doanh số'
FROM nhanVien WHERE maNguoiDung = 83;

INSERT IGNORE INTO bangLuong (maNhanVien, thang, nam, luongCoBan, heSoLuong, phuCap, thuong, khauTru, soNgayLam, soNgayNghiPhep, thucLinh, ghiChu)
SELECT maNhanVien, 3, 2026, luongCoBan, heSoLuong, phuCapCoDinh, 0, 0, 22, 0,
       ROUND(luongCoBan * heSoLuong * (22/22) + phuCapCoDinh, 0), 'Tháng đủ công'
FROM nhanVien WHERE maNguoiDung = 83;

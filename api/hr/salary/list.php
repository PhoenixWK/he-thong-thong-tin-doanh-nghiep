<?php
/**
 * HR API - Lấy danh sách bảng lương của nhân viên
 * GET /api/hr/salary/list.php?nam=2026
 */
require_once __DIR__ . '/../bootstrap.php';

$emp = hr_require_employee();
$db  = HR_DB::get();

$nam = isset($_GET['nam']) ? (int)$_GET['nam'] : (int)date('Y');

$stmt = $db->prepare(
    "SELECT maBangLuong, thang, nam, luongCoBan, heSoLuong, phuCap, thuong, khauTru,
            soNgayLam, soNgayNghiPhep, thucLinh, ghiChu
     FROM bangLuong WHERE maNhanVien = ? AND nam = ? ORDER BY thang ASC"
);
$stmt->execute([$emp['maNhanVien'], $nam]);
$rows = $stmt->fetchAll();

// Bổ sung công thức tính lương để nhân viên biết cách tính
$formula = [
    'moTa'   => 'Lương thực lĩnh = Lương cơ bản × Hệ số × (Số ngày làm / 22) + Phụ cấp + Thưởng - Khấu trừ',
    'soNgayChuanThang' => 22
];

hr_json(['formula' => $formula, 'bangLuong' => $rows, 'nam' => $nam]);

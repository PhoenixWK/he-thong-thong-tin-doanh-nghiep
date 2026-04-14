<?php
/**
 * HR API - Lấy chi tiết bảng lương theo tháng cụ thể
 * GET /api/hr/salary/detail.php?thang=1&nam=2026
 */
require_once __DIR__ . '/../bootstrap.php';

$emp   = hr_require_employee();
$db    = HR_DB::get();
$thang = isset($_GET['thang']) ? (int)$_GET['thang'] : 0;
$nam   = isset($_GET['nam'])   ? (int)$_GET['nam']   : 0;

if ($thang < 1 || $thang > 12 || $nam < 2000) {
    hr_json(['error' => 'Tháng hoặc năm không hợp lệ'], 400);
}

$stmt = $db->prepare(
    "SELECT bl.*, nd.hoVaTen, nd.email, nd.soDT, nv.chucVu
     FROM bangLuong bl
     JOIN nhanVien nv ON bl.maNhanVien = nv.maNhanVien
     JOIN nguoidung nd ON nv.maNguoiDung = nd.maNguoiDung
     WHERE bl.maNhanVien = ? AND bl.thang = ? AND bl.nam = ? LIMIT 1"
);
$stmt->execute([$emp['maNhanVien'], $thang, $nam]);
$row = $stmt->fetch();

if (!$row) hr_json(['error' => 'Không có bảng lương tháng ' . $thang . '/' . $nam], 404);

hr_json($row);

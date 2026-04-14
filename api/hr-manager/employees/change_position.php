<?php
/** POST /api/hr-manager/employees/change_position.php — đổi chức vụ + lưu lịch sử */
require_once __DIR__ . '/../bootstrap.php';
hrm_require_auth();
if ($_SERVER['REQUEST_METHOD'] !== 'POST') hrm_json(['error' => 'Method not allowed'], 405);

$b = json_decode(file_get_contents('php://input'), true) ?? [];
$maNhanVien   = (int)($b['maNhanVien']   ?? 0);
$chucVuMoi    = trim($b['chucVuMoi']     ?? '');
$luongCoBanMoi= (float)($b['luongCoBanMoi'] ?? 0);
$heSoLuongMoi = (float)($b['heSoLuongMoi']  ?? 0);
$ngayHieuLuc  = trim($b['ngayHieuLuc']   ?? date('Y-m-d'));
$ghiChu       = trim($b['ghiChu']        ?? '');

if (!$maNhanVien || !$chucVuMoi || $luongCoBanMoi <= 0 || $heSoLuongMoi <= 0)
    hrm_json(['error' => 'Thiếu thông tin bắt buộc'], 400);

$pdo = HRM_DB::get();
$cur = $pdo->prepare("SELECT chucVu, luongCoBan, heSoLuong FROM nhanVien WHERE maNhanVien=?");
$cur->execute([$maNhanVien]);
$old = $cur->fetch();
if (!$old) hrm_json(['error' => 'Nhân viên không tồn tại'], 404);

// Save history
$pdo->prepare("INSERT INTO lichSuChucVu
    (maNhanVien, chucVuCu, chucVuMoi, luongCoBanCu, luongCoBanMoi, heSoLuongCu, heSoLuongMoi, ngayHieuLuc, ghiChu)
    VALUES (?,?,?,?,?,?,?,?,?)")
->execute([$maNhanVien, $old['chucVu'], $chucVuMoi, $old['luongCoBan'], $luongCoBanMoi,
           $old['heSoLuong'], $heSoLuongMoi, $ngayHieuLuc, $ghiChu]);

// Update employee
$pdo->prepare("UPDATE nhanVien SET chucVu=?, luongCoBan=?, heSoLuong=? WHERE maNhanVien=?")
->execute([$chucVuMoi, $luongCoBanMoi, $heSoLuongMoi, $maNhanVien]);

hrm_json(['success' => true]);

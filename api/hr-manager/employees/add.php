<?php
/** POST /api/hr-manager/employees/add.php — thêm nhân viên */
require_once __DIR__ . '/../bootstrap.php';
hrm_require_auth();
if ($_SERVER['REQUEST_METHOD'] !== 'POST') hrm_json(['error' => 'Method not allowed'], 405);

$b = json_decode(file_get_contents('php://input'), true) ?? [];
$maNguoiDung  = (int)($b['maNguoiDung']  ?? 0);
$chucVu       = trim($b['chucVu']        ?? '');
$ngayVaoLam   = trim($b['ngayVaoLam']    ?? date('Y-m-d'));
$luongCoBan   = (float)($b['luongCoBan'] ?? 0);
$heSoLuong    = (float)($b['heSoLuong']  ?? 1.0);
$phuCapCoDinh = (float)($b['phuCapCoDinh'] ?? 0);

if (!$maNguoiDung || !$chucVu || $luongCoBan <= 0)
    hrm_json(['error' => 'Thiếu thông tin bắt buộc'], 400);

$pdo = HRM_DB::get();
// Check account exists
$acc = $pdo->prepare("SELECT maNguoiDung FROM nguoidung WHERE maNguoiDung=? AND trangThai=1");
$acc->execute([$maNguoiDung]);
if (!$acc->fetch()) hrm_json(['error' => 'Tài khoản không tồn tại'], 404);

// Check already employee
$dup = $pdo->prepare("SELECT maNhanVien FROM nhanVien WHERE maNguoiDung=? AND trangThai='Dang lam'");
$dup->execute([$maNguoiDung]);
if ($dup->fetch()) hrm_json(['error' => 'Tài khoản này đã là nhân viên'], 409);

$stmt = $pdo->prepare("INSERT INTO nhanVien (maNguoiDung, chucVu, ngayVaoLam, luongCoBan, heSoLuong, phuCapCoDinh, trangThai)
                        VALUES (?,?,?,?,?,?,'Dang lam')");
$stmt->execute([$maNguoiDung, $chucVu, $ngayVaoLam, $luongCoBan, $heSoLuong, $phuCapCoDinh]);
hrm_json(['success' => true, 'maNhanVien' => $pdo->lastInsertId()]);

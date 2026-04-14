<?php
/**
 * HR API - Đăng nhập nhân viên
 * POST /api/hr/login.php
 * Body: { tenTaiKhoan, matKhau }
 */
require_once __DIR__ . '/bootstrap.php';

if (session_status() === PHP_SESSION_NONE) session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    hr_json(['error' => 'Method not allowed'], 405);
}

$body = json_decode(file_get_contents('php://input'), true) ?? [];
$tenTaiKhoan = trim($body['tenTaiKhoan'] ?? '');
$matKhau     = trim($body['matKhau']     ?? '');

if ($tenTaiKhoan === '' || $matKhau === '') {
    hr_json(['error' => 'Thiếu tài khoản hoặc mật khẩu'], 400);
}

$db = HR_DB::get();

// Lấy người dùng
$stmt = $db->prepare("SELECT maNguoiDung, hoVaTen, tenTaiKhoan, matKhau, trangThai FROM nguoidung WHERE tenTaiKhoan = ? LIMIT 1");
$stmt->execute([$tenTaiKhoan]);
$user = $stmt->fetch();

if (!$user) {
    hr_json(['error' => 'Tài khoản không tồn tại'], 401);
}

if ($user['trangThai'] !== 'Hoạt động') {
    hr_json(['error' => 'Tài khoản đã bị khóa'], 403);
}

// Kiểm tra mật khẩu (MD5 hoặc plain theo hệ thống hiện tại)
$pwMatch = ($user['matKhau'] === $matKhau) || ($user['matKhau'] === md5($matKhau));
if (!$pwMatch) {
    hr_json(['error' => 'Mật khẩu không đúng'], 401);
}

// Lấy thông tin nhân viên
$stmt2 = $db->prepare("SELECT nv.*, nd.hoVaTen, nd.soDT, nd.email FROM nhanVien nv
    JOIN nguoidung nd ON nv.maNguoiDung = nd.maNguoiDung
    WHERE nv.maNguoiDung = ? AND nv.trangThai = 'Dang lam' LIMIT 1");
$stmt2->execute([$user['maNguoiDung']]);
$nv = $stmt2->fetch();

if (!$nv) {
    hr_json(['error' => 'Tài khoản không có hồ sơ nhân viên hoặc đã nghỉ việc'], 403);
}

$_SESSION['hr_employee'] = [
    'maNhanVien'  => $nv['maNhanVien'],
    'maNguoiDung' => $nv['maNguoiDung'],
    'hoVaTen'     => $nv['hoVaTen'],
    'chucVu'      => $nv['chucVu'],
];

hr_json(['success' => true, 'hoVaTen' => $nv['hoVaTen'], 'chucVu' => $nv['chucVu']]);

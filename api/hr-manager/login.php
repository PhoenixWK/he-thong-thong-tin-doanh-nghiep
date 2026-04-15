<?php
require_once __DIR__ . '/bootstrap.php';
if ($_SERVER['REQUEST_METHOD'] !== 'POST') hrm_json(['error' => 'Method not allowed'], 405);

$body = json_decode(file_get_contents('php://input'), true) ?? [];
$user = trim($body['username'] ?? '');
$pass = trim($body['password'] ?? '');
if (!$user || !$pass) hrm_json(['error' => 'Thiếu thông tin đăng nhập'], 400);

$pdo = HRM_DB::get();
// Chỉ cho phép Quản lí (maQuyen=2) hoặc Quản trị viên (maQuyen=15) truy cập cổng HR Manager
$stmt = $pdo->prepare("SELECT maNguoiDung, hoVaTen, tenTaiKhoan FROM nguoidung WHERE tenTaiKhoan=? AND matKhau=? AND trangThai='Hoạt động' AND maQuyen IN (2, 15)");
$stmt->execute([$user, md5($pass)]);
$row = $stmt->fetch();
if (!$row) hrm_json(['error' => 'Tên đăng nhập hoặc mật khẩu không đúng, hoặc tài khoản không có quyền truy cập'], 401);

$_SESSION['hrm_manager'] = [
    'maNguoiDung' => $row['maNguoiDung'],
    'hoVaTen'     => $row['hoVaTen'],
    'tenTaiKhoan' => $row['tenTaiKhoan'],
];
hrm_json(['success' => true, 'hoVaTen' => $row['hoVaTen']]);

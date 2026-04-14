<?php
require_once __DIR__ . '/bootstrap.php';
if ($_SERVER['REQUEST_METHOD'] !== 'POST') hrm_json(['error' => 'Method not allowed'], 405);

$body = json_decode(file_get_contents('php://input'), true) ?? [];
$user = trim($body['username'] ?? '');
$pass = trim($body['password'] ?? '');
if (!$user || !$pass) hrm_json(['error' => 'Thiếu thông tin đăng nhập'], 400);

$pdo = HRM_DB::get();
$stmt = $pdo->prepare("SELECT maNguoiDung, hoVaTen, tenTaiKhoan FROM nguoidung WHERE tenTaiKhoan=? AND matKhau=? AND trangThai=1");
$stmt->execute([$user, md5($pass)]);
$row = $stmt->fetch();
if (!$row) hrm_json(['error' => 'Tên đăng nhập hoặc mật khẩu không đúng'], 401);

$_SESSION['hrm_manager'] = [
    'maNguoiDung' => $row['maNguoiDung'],
    'hoVaTen'     => $row['hoVaTen'],
    'tenTaiKhoan' => $row['tenTaiKhoan'],
];
hrm_json(['success' => true, 'hoVaTen' => $row['hoVaTen']]);

<?php
/**
 * HR API - Lấy / Cập nhật thông tin nhân viên (của chính mình)
 * GET  /api/hr/employee/profile.php  -> lấy thông tin
 * PUT  /api/hr/employee/profile.php  -> cập nhật hoVaTen, soDT, email
 */
require_once __DIR__ . '/../bootstrap.php';

$emp = hr_require_employee();
$db  = HR_DB::get();

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $stmt = $db->prepare(
        "SELECT nv.maNhanVien, nv.chucVu, nv.ngayVaoLam, nv.luongCoBan, nv.heSoLuong, nv.phuCapCoDinh,
                nd.hoVaTen, nd.soDT, nd.email
         FROM nhanVien nv
         JOIN nguoidung nd ON nv.maNguoiDung = nd.maNguoiDung
         WHERE nv.maNhanVien = ?"
    );
    $stmt->execute([$emp['maNhanVien']]);
    $data = $stmt->fetch();
    if (!$data) hr_json(['error' => 'Không tìm thấy hồ sơ'], 404);
    hr_json($data);
}

if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    $body  = json_decode(file_get_contents('php://input'), true) ?? [];
    $hoVaTen = trim($body['hoVaTen'] ?? '');
    $soDT    = trim($body['soDT']    ?? '');
    $email   = trim($body['email']   ?? '');

    if ($hoVaTen === '') hr_json(['error' => 'Họ và tên không được để trống'], 400);
    if ($email !== '' && !filter_var($email, FILTER_VALIDATE_EMAIL)) hr_json(['error' => 'Email không hợp lệ'], 400);
    if ($soDT !== '' && !preg_match('/^[0-9]{9,11}$/', $soDT)) hr_json(['error' => 'Số điện thoại không hợp lệ'], 400);

    $stmt = $db->prepare(
        "UPDATE nguoidung SET hoVaTen = ?, soDT = ?, email = ?, ngayCapNhat = NOW()
         WHERE maNguoiDung = ?"
    );
    $stmt->execute([$hoVaTen, $soDT, $email, $emp['maNguoiDung']]);
    hr_json(['success' => true]);
}

hr_json(['error' => 'Method not allowed'], 405);

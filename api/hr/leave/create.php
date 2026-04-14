<?php
/**
 * HR API - Nộp đơn xin nghỉ
 * POST /api/hr/leave/create.php
 * Body: { loaiNghi, ngayBatDau, ngayKetThuc?, lyDo }
 */
require_once __DIR__ . '/../bootstrap.php';

$emp = hr_require_employee();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    hr_json(['error' => 'Method not allowed'], 405);
}

$body       = json_decode(file_get_contents('php://input'), true) ?? [];
$loaiNghi   = trim($body['loaiNghi']   ?? '');
$ngayBatDau = trim($body['ngayBatDau'] ?? '');
$ngayKetThuc = isset($body['ngayKetThuc']) ? trim($body['ngayKetThuc']) : null;
$lyDo       = trim($body['lyDo']       ?? '');

$allowed = ['Nghi phep', 'Nghi om dau', 'Nghi thai san', 'Nghi viec'];
if (!in_array($loaiNghi, $allowed, true)) {
    hr_json(['error' => 'Loại nghỉ không hợp lệ. Chọn: ' . implode(', ', $allowed)], 400);
}
if ($ngayBatDau === '' || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $ngayBatDau)) {
    hr_json(['error' => 'Ngày bắt đầu không hợp lệ (YYYY-MM-DD)'], 400);
}
if ($ngayKetThuc !== null && $ngayKetThuc !== '' && !preg_match('/^\d{4}-\d{2}-\d{2}$/', $ngayKetThuc)) {
    hr_json(['error' => 'Ngày kết thúc không hợp lệ (YYYY-MM-DD)'], 400);
}
if ($lyDo === '') {
    hr_json(['error' => 'Lý do không được để trống'], 400);
}
if ($ngayKetThuc !== null && $ngayKetThuc !== '' && $ngayKetThuc < $ngayBatDau) {
    hr_json(['error' => 'Ngày kết thúc phải >= ngày bắt đầu'], 400);
}

$db = HR_DB::get();
$stmt = $db->prepare(
    "INSERT INTO donNghi (maNhanVien, loaiNghi, ngayBatDau, ngayKetThuc, lyDo)
     VALUES (?, ?, ?, ?, ?)"
);
$stmt->execute([
    $emp['maNhanVien'],
    $loaiNghi,
    $ngayBatDau,
    ($ngayKetThuc === '' ? null : $ngayKetThuc),
    $lyDo
]);

hr_json(['success' => true, 'maDon' => (int)$db->lastInsertId()]);

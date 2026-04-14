<?php
/** POST /api/hr-manager/leaves/approve.php — duyệt / từ chối đơn nghỉ */
require_once __DIR__ . '/../bootstrap.php';
hrm_require_auth();
if ($_SERVER['REQUEST_METHOD'] !== 'POST') hrm_json(['error' => 'Method not allowed'], 405);

$b = json_decode(file_get_contents('php://input'), true) ?? [];
$maDon     = (int)($b['maDon']     ?? 0);
$trangThai = trim($b['trangThai']  ?? '');   // 'Da duyet' or 'Tu choi'
$ghiChu    = trim($b['ghiChu']     ?? '');

if (!$maDon || !in_array($trangThai, ['Da duyet', 'Tu choi']))
    hrm_json(['error' => 'Dữ liệu không hợp lệ'], 400);

$pdo = HRM_DB::get();
$pdo->prepare("UPDATE donNghi SET trangThai=?, ghiChuDuyet=? WHERE maDon=?")
->execute([$trangThai, $ghiChu, $maDon]);

hrm_json(['success' => true]);

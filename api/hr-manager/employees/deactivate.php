<?php
/** POST /api/hr-manager/employees/deactivate.php — nghỉ việc nhân viên */
require_once __DIR__ . '/../bootstrap.php';
hrm_require_auth();
if ($_SERVER['REQUEST_METHOD'] !== 'POST') hrm_json(['error' => 'Method not allowed'], 405);

$b = json_decode(file_get_contents('php://input'), true) ?? [];
$maNhanVien = (int)($b['maNhanVien'] ?? 0);
if (!$maNhanVien) hrm_json(['error' => 'Thiếu maNhanVien'], 400);

$pdo = HRM_DB::get();
$stmt = $pdo->prepare("UPDATE nhanVien SET trangThai='Da nghi' WHERE maNhanVien=?");
$stmt->execute([$maNhanVien]);
hrm_json(['success' => true]);

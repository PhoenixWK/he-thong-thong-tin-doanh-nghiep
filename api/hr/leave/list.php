<?php
/**
 * HR API - Lấy danh sách đơn nghỉ của nhân viên
 * GET /api/hr/leave/list.php
 */
require_once __DIR__ . '/../bootstrap.php';

$emp = hr_require_employee();
$db  = HR_DB::get();

$stmt = $db->prepare(
    "SELECT maDon, loaiNghi, ngayBatDau, ngayKetThuc, lyDo, trangThai, ngayNop, ghiChuDuyet
     FROM donNghi WHERE maNhanVien = ? ORDER BY ngayNop DESC"
);
$stmt->execute([$emp['maNhanVien']]);
hr_json($stmt->fetchAll());

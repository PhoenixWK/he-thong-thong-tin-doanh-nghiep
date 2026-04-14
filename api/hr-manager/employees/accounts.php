<?php
/** GET /api/hr-manager/employees/accounts.php — danh sách tài khoản chưa là nhân viên */
require_once __DIR__ . '/../bootstrap.php';
hrm_require_auth();

$pdo = HRM_DB::get();
$rows = $pdo->query("
    SELECT maNguoiDung, hoVaTen, tenTaiKhoan, email, soDT
    FROM nguoidung
    WHERE trangThai = 1
      AND maNguoiDung NOT IN (SELECT maNguoiDung FROM nhanVien WHERE trangThai = 'Dang lam')
    ORDER BY hoVaTen ASC
")->fetchAll();
hrm_json(['accounts' => $rows]);

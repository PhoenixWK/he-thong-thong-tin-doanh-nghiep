<?php
/** GET /api/hr-manager/employees/list.php — danh sách nhân viên */
require_once __DIR__ . '/../bootstrap.php';
hrm_require_auth();

$pdo = HRM_DB::get();
$rows = $pdo->query("
    SELECT nv.maNhanVien, nv.maNguoiDung, nd.hoVaTen, nd.soDT, nd.email,
           nv.chucVu, nv.ngayVaoLam, nv.luongCoBan, nv.heSoLuong, nv.phuCapCoDinh, nv.trangThai
    FROM nhanVien nv
    JOIN nguoidung nd ON nd.maNguoiDung = nv.maNguoiDung
    ORDER BY nv.trangThai DESC, nd.hoVaTen ASC
")->fetchAll();
hrm_json(['nhanVien' => $rows]);

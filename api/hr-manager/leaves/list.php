<?php
/** GET /api/hr-manager/leaves/list.php — danh sách đơn xin nghỉ */
require_once __DIR__ . '/../bootstrap.php';
hrm_require_auth();

$pdo = HRM_DB::get();
$trangThai = $_GET['trangThai'] ?? '';  // '' = all, 'Cho duyet', 'Da duyet', 'Tu choi'

$sql = "SELECT d.maDon, d.maNhanVien, nd.hoVaTen, nv.chucVu,
               d.loaiNghi, d.ngayBatDau, d.ngayKetThuc, d.lyDo,
               d.trangThai, d.ghiChuDuyet, d.ngayNop
        FROM donNghi d
        JOIN nhanVien nv ON nv.maNhanVien = d.maNhanVien
        JOIN nguoidung nd ON nd.maNguoiDung = nv.maNguoiDung";
$params = [];
if ($trangThai) {
    $sql .= " WHERE d.trangThai = ?";
    $params[] = $trangThai;
}
$sql .= " ORDER BY d.ngayNop DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
hrm_json(['donNghi' => $stmt->fetchAll()]);

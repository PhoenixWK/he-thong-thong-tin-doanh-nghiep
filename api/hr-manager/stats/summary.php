<?php
/** GET /api/hr-manager/stats/summary.php — thống kê lương tháng/năm */
require_once __DIR__ . '/../bootstrap.php';
hrm_require_auth();

$pdo  = HRM_DB::get();
$nam  = (int)($_GET['nam']   ?? date('Y'));
$thang = isset($_GET['thang']) ? (int)$_GET['thang'] : null;

if ($thang) {
    // Monthly detail: each employee
    $stmt = $pdo->prepare("
        SELECT nd.hoVaTen, nv.chucVu, bl.thang, bl.nam,
               bl.luongCoBan, bl.heSoLuong, bl.phuCap, bl.thuong, bl.khauTru,
               bl.soNgayLam, bl.thucLinh
        FROM bangLuong bl
        JOIN nhanVien nv ON nv.maNhanVien = bl.maNhanVien
        JOIN nguoidung nd ON nd.maNguoiDung = nv.maNguoiDung
        WHERE bl.thang=? AND bl.nam=?
        ORDER BY nd.hoVaTen
    ");
    $stmt->execute([$thang, $nam]);
    $rows = $stmt->fetchAll();
    $total = array_sum(array_column($rows, 'thucLinh'));
    hrm_json(['type' => 'monthly', 'thang' => $thang, 'nam' => $nam, 'rows' => $rows, 'total' => $total]);
} else {
    // Yearly summary: each employee total
    $stmt = $pdo->prepare("
        SELECT nd.hoVaTen, nv.chucVu,
               SUM(bl.thucLinh) AS tongThucLinh,
               SUM(bl.thuong)   AS tongThuong,
               COUNT(bl.maBangLuong) AS soThangCoBangLuong
        FROM bangLuong bl
        JOIN nhanVien nv ON nv.maNhanVien = bl.maNhanVien
        JOIN nguoidung nd ON nd.maNguoiDung = nv.maNguoiDung
        WHERE bl.nam=?
        GROUP BY bl.maNhanVien
        ORDER BY tongThucLinh DESC
    ");
    $stmt->execute([$nam]);
    $rows = $stmt->fetchAll();
    $total = array_sum(array_column($rows, 'tongThucLinh'));
    hrm_json(['type' => 'yearly', 'nam' => $nam, 'rows' => $rows, 'total' => $total]);
}

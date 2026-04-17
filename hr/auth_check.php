<?php
/**
 * HR Portal - Auth check helper
 * Include this at top of every portal page.
 * Chấp nhận cả session nhân viên HR riêng hoặc session quản trị chính (có privilegeId 20/21/22).
 */
if (session_status() === PHP_SESSION_NONE) session_start();

if (isset($_SESSION['hr_employee'])) {
    $hrEmp = $_SESSION['hr_employee'];
} elseif (!empty($_SESSION['role']['data'])) {
    // Cho phép người dùng đã đăng nhập qua cổng chính với quyền nhân viên HR
    $hrPrivileges = [20, 21, 22];
    $ids = array_column($_SESSION['role']['data'], 'privilegeId');
    if (empty(array_intersect($ids, $hrPrivileges))) {
        header('Location: /hr/index.php');
        exit;
    }

    // Tra DB để lấy maNhanVien — cần thiết cho tất cả AJAX API calls
    $u = $_SESSION['user'];
    $maNguoiDung = $u['id'] ?? null;

    require_once __DIR__ . '/../api/hr/bootstrap.php';
    $db = HR_DB::get();
    $stmt = $db->prepare(
        "SELECT nv.maNhanVien, nv.chucVu, nd.hoVaTen
         FROM nhanVien nv
         JOIN nguoidung nd ON nv.maNguoiDung = nd.maNguoiDung
         WHERE nv.maNguoiDung = ? AND nv.trangThai = 'Đang làm' LIMIT 1"
    );
    $stmt->execute([$maNguoiDung]);
    $nv = $stmt->fetch();

    if (!$nv) {
        include __DIR__ . '/../404-Page/index.php';
        exit;
    }

    $_SESSION['hr_employee'] = [
        'maNhanVien'  => $nv['maNhanVien'],
        'maNguoiDung' => $maNguoiDung,
        'hoVaTen'     => $nv['hoVaTen'],
        'chucVu'      => $nv['chucVu'],
    ];
    $hrEmp = $_SESSION['hr_employee'];
} else {
    header('Location: /hr/index.php');
    exit;
}

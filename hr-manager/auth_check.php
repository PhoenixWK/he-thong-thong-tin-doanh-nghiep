<?php
/**
 * HR Manager - Auth check helper
 * Chấp nhận cả session manager HR riêng hoặc session quản trị chính (có privilegeId 17/18/19).
 */
if (session_status() === PHP_SESSION_NONE) session_start();

if (isset($_SESSION['hrm_manager'])) {
    $hrmUser = $_SESSION['hrm_manager'];
} elseif (!empty($_SESSION['role']['data'])) {
    $hrmPrivileges = [17, 18, 19];
    $ids = array_column($_SESSION['role']['data'], 'privilegeId');
    if (empty(array_intersect($ids, $hrmPrivileges))) {
        header('Location: /hr-manager/index.php');
        exit;
    }
    $u = $_SESSION['user'];
    $hrmUser = [
        'maNguoiDung' => $u['id'] ?? null,
        'hoVaTen'     => $u['name'] ?? $u['full_name'] ?? '',
        'vaiTro'      => 'Quản lý nhân sự',
    ];
} else {
    header('Location: /hr-manager/index.php');
    exit;
}

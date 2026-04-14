<?php
/**
 * HR Portal - Shared sidebar/layout header
 * Usage: include 'partials/layout_header.php'; (pass $pageTitle, $activeMenu)
 */
$pageTitle  = $pageTitle  ?? 'HR Portal';
$activeMenu = $activeMenu ?? '';
?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= htmlspecialchars($pageTitle) ?> - Cổng Nhân Viên</title>
  <link rel="stylesheet" href="/hr/css/style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer">
</head>
<body>
<div class="hr-layout">
  <!-- Sidebar -->
  <aside class="hr-sidebar">
    <div class="hr-sidebar__brand">
      <i class="fa-solid fa-id-badge"></i> Cổng Nhân Viên
    </div>
    <div class="hr-sidebar__user">
      <div class="name"><?= htmlspecialchars($hrEmp['hoVaTen']) ?></div>
      <div class="role"><?= htmlspecialchars($hrEmp['chucVu']) ?></div>
    </div>
    <nav class="hr-sidebar__nav">
      <a href="/hr/dashboard.php" class="<?= $activeMenu==='dashboard'?'active':'' ?>">
        <i class="fa-solid fa-house"></i> Tổng quan
      </a>
      <a href="/hr/profile.php" class="<?= $activeMenu==='profile'?'active':'' ?>">
        <i class="fa-solid fa-user-pen"></i> Thông tin cá nhân
      </a>
      <a href="/hr/leave.php" class="<?= $activeMenu==='leave'?'active':'' ?>">
        <i class="fa-solid fa-file-medical"></i> Đơn xin nghỉ
      </a>
      <a href="/hr/salary.php" class="<?= $activeMenu==='salary'?'active':'' ?>">
        <i class="fa-solid fa-money-bill-wave"></i> Bảng lương
      </a>
    </nav>
    <div class="hr-sidebar__logout">
      <button id="logoutBtn">
        <i class="fa-solid fa-right-from-bracket"></i> Đăng xuất
      </button>
    </div>
  </aside>
  <!-- Main Content -->
  <main class="hr-main">

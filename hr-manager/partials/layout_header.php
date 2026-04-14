<?php
$pageTitle  = $pageTitle  ?? 'HR Manager';
$activeMenu = $activeMenu ?? '';
?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title><?= htmlspecialchars($pageTitle) ?> - Quản lý Nhân sự</title>
  <link rel="stylesheet" href="/hr/css/style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" crossorigin="anonymous">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.8.2/jspdf.plugin.autotable.min.js"></script>
  <style>
    .badge-active   { background:#d4edda; color:#155724; padding:3px 10px; border-radius:20px; font-size:.78rem; font-weight:600; }
    .badge-inactive { background:#f8d7da; color:#721c24; padding:3px 10px; border-radius:20px; font-size:.78rem; font-weight:600; }
    .badge-pending  { background:#fff3cd; color:#856404; padding:3px 10px; border-radius:20px; font-size:.78rem; }
    .badge-approved { background:#d4edda; color:#155724; padding:3px 10px; border-radius:20px; font-size:.78rem; }
    .badge-rejected { background:#f8d7da; color:#721c24; padding:3px 10px; border-radius:20px; font-size:.78rem; }
    .modal-overlay  { display:none; position:fixed; inset:0; background:rgba(0,0,0,.5); z-index:1000; align-items:center; justify-content:center; }
    .modal-overlay.open { display:flex; }
    .modal-box { background:#fff; border-radius:14px; padding:32px; width:480px; max-width:95vw; max-height:90vh; overflow-y:auto; box-shadow:0 8px 40px rgba(0,0,0,.2); }
    .modal-box h3 { margin:0 0 20px; color:#1a3c6e; font-size:1.1rem; }
    .modal-footer { display:flex; gap:10px; justify-content:flex-end; margin-top:20px; }
  </style>
</head>
<body>
<div class="hr-layout">
  <aside class="hr-sidebar">
    <div class="hr-sidebar__brand"><i class="fa-solid fa-users-gear"></i> Quản lý Nhân sự</div>
    <div class="hr-sidebar__user">
      <div class="name"><?= htmlspecialchars($hrmUser['hoVaTen']) ?></div>
      <div class="role">Quản lý</div>
    </div>
    <nav class="hr-sidebar__nav">
      <a href="/hr-manager/employees.php" class="<?= $activeMenu==='employees'?'active':'' ?>">
        <i class="fa-solid fa-users"></i> Nhân sự
      </a>
      <a href="/hr-manager/salary.php" class="<?= $activeMenu==='salary'?'active':'' ?>">
        <i class="fa-solid fa-calculator"></i> Tính lương
      </a>
      <a href="/hr-manager/leaves.php" class="<?= $activeMenu==='leaves'?'active':'' ?>">
        <i class="fa-solid fa-calendar-check"></i> Duyệt đơn nghỉ
      </a>
      <a href="/hr-manager/stats.php" class="<?= $activeMenu==='stats'?'active':'' ?>">
        <i class="fa-solid fa-chart-bar"></i> Thống kê
      </a>
    </nav>
    <div class="hr-sidebar__logout">
      <button id="logoutBtn"><i class="fa-solid fa-right-from-bracket"></i> Đăng xuất</button>
    </div>
  </aside>
  <main class="hr-main">

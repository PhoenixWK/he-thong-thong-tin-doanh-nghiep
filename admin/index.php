<?php
session_start();


if (!isset($_SESSION["user"]) || !isset($_SESSION["role"])) {
    // include 'index.php';
    include '../404-Page/index.php';
    die();
}

$user = $_SESSION["user"];
$role = $_SESSION["role"]["data"] ?? [];
if (count($role) <= 0) {
    include '../404-Page/index.php';
    die();
}
?>



<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin</title>

    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
        integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- Reset CSS -->
    <link rel="stylesheet" href="assets/css/reset.css">
    <!-- Styles CSS -->
    <link rel="stylesheet" href="assets/css/base.css">
    <link rel="stylesheet" href="assets/css/common.css">

    <link rel="stylesheet" href="../public/css/toast.css">
    <link rel="stylesheet" href="responsive/Responsive.css">

    <!-- Library -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    <!-- jsPDF for HR salary PDF export -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.8.2/jspdf.plugin.autotable.min.js"></script>
    <!-- jsPDF for HR salary export -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.8.2/jspdf.plugin.autotable.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"
        integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.26.1/axios.min.js"
        integrity="sha512-bPh3uwgU5qEMipS/VOmRqynnMXGGSRv+72H/N260MQeXZIK4PG48401Bsby9Nq5P5fz7hy5UGNmC/W1Z51h2GQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <style>
    /* ── HR Manager Inline Styles ──────────────────────────────────── */
    :root {
      --hrm-primary: #1a3c6e;
      --hrm-accent:  #27ae60;
      --hrm-danger:  #e74c3c;
      --hrm-bg:      #f0f2f5;
    }
    .hrm-wrapper { padding: 4px 0; }
    .hrm-page-header { margin-bottom: 4px; }
    .hrm-title { font-size:1.4rem; font-weight:700; color:var(--hrm-primary); margin:0 0 16px; display:flex; align-items:center; gap:10px; }

    /* Toolbar */
    .hrm-toolbar { display:flex; gap:12px; flex-wrap:wrap; align-items:flex-end; padding:16px 20px; }
    .hrm-filter-group { display:flex; align-items:center; gap:8px; }
    .hrm-filter-group label { font-size:.83rem; font-weight:600; white-space:nowrap; color:#2c3e50; }

    /* Card */
    .hrm-card { background:#fff; border-radius:10px; box-shadow:0 2px 15px rgba(0,0,0,.08); padding:20px; margin-bottom:16px; }
    .hrm-card-header { display:flex; align-items:center; justify-content:space-between; margin-bottom:16px; }
    .hrm-card-header h2 { font-size:1rem; color:var(--hrm-primary); margin:0; font-weight:700; }

    /* Table */
    .hrm-table-wrap { overflow-x:auto; }
    .hrm-table { width:100%; border-collapse:collapse; font-size:.88rem; }
    .hrm-table th { background:var(--hrm-primary); color:#fff; padding:10px 12px; text-align:left; font-weight:600; white-space:nowrap; }
    .hrm-table td { padding:10px 12px; border-bottom:1px solid #eaecef; vertical-align:middle; }
    .hrm-table tr:last-child td { border-bottom:none; }
    .hrm-table tr:hover td { background:#f5f7fa; }
    .hrm-tfoot-total { background:#f0f4ff; }
    .hrm-tfoot-total td { border-top:2px solid var(--hrm-primary); }
    .hrm-empty { text-align:center; color:#aaa; font-style:italic; padding:30px !important; }
    .hrm-td-ellipsis { max-width:180px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; }
    .hrm-actions { white-space:nowrap; }

    /* Buttons */
    .hrm-btn { display:inline-flex; align-items:center; gap:6px; padding:9px 18px; border:none; border-radius:7px; font-size:.88rem; font-weight:600; cursor:pointer; transition:background .2s, color .2s; text-decoration:none; }
    .hrm-btn-primary  { background:var(--hrm-primary); color:#fff; }
    .hrm-btn-primary:hover  { background:#2d5a9e; color:#fff; }
    .hrm-btn-outline  { background:transparent; border:1.5px solid var(--hrm-primary); color:var(--hrm-primary); }
    .hrm-btn-outline:hover  { background:var(--hrm-primary); color:#fff; }
    .hrm-btn-danger   { background:var(--hrm-danger); color:#fff; }
    .hrm-btn-danger:hover   { background:#c0392b; }
    .hrm-btn-success  { background:var(--hrm-accent); color:#fff; }
    .hrm-btn-success:hover  { background:#219a52; }
    .hrm-btn-sm { padding:5px 10px; font-size:.80rem; }

    /* Form */
    .hrm-form-group { margin-bottom:14px; }
    .hrm-form-group label { display:block; font-size:.83rem; font-weight:600; margin-bottom:5px; color:#2c3e50; }
    .hrm-form-row { display:grid; grid-template-columns:1fr 1fr; gap:12px; }
    .hrm-input { width:100%; padding:9px 12px; border:1.5px solid #dde1e7; border-radius:7px; font-size:.88rem; box-sizing:border-box; }
    .hrm-input:focus { outline:none; border-color:#2d5a9e; }

    /* Alerts */
    .hrm-alert { padding:11px 16px; border-radius:7px; margin-bottom:14px; font-size:.88rem; }
    .hrm-alert-info { background:#eaf4fd; color:#1a6fa3; border-left:4px solid #1a6fa3; }

    /* Modals */
    .hrm-modal-overlay { display:none; position:fixed; inset:0; background:rgba(0,0,0,.5); z-index:2000; align-items:center; justify-content:center; }
    .hrm-modal-overlay.open { display:flex; }
    .hrm-modal-box { background:#fff; border-radius:14px; padding:0; width:500px; max-width:95vw; max-height:90vh; overflow-y:auto; box-shadow:0 8px 40px rgba(0,0,0,.22); }
    .hrm-modal-head { padding:20px 24px 16px; border-bottom:1px solid #eaecef; margin-bottom:4px; }
    .hrm-modal-head h3 { margin:0; color:var(--hrm-primary); font-size:1.05rem; display:flex; align-items:center; gap:8px; }
    .hrm-modal-box .hrm-form-group,
    .hrm-modal-box .hrm-form-row,
    .hrm-modal-box .hrm-alert { padding-left:24px; padding-right:24px; }
    .hrm-modal-box .hrm-form-row { padding:0 24px; }
    .hrm-modal-footer { display:flex; gap:10px; justify-content:flex-end; padding:16px 24px; border-top:1px solid #eaecef; margin-top:8px; }

    /* Badges */
    .hrm-badge { display:inline-block; padding:3px 10px; border-radius:20px; font-size:.78rem; font-weight:600; }
    .hrm-badge-active   { background:#d4edda; color:#155724; }
    .hrm-badge-inactive { background:#f8d7da; color:#721c24; }
    .hrm-badge-pending  { background:#fff3cd; color:#856404; }
    .hrm-badge-approved { background:#d4edda; color:#155724; }
    .hrm-badge-rejected { background:#f8d7da; color:#721c24; }

    /* Stats grid */
    .hrm-stats-grid { display:grid; grid-template-columns:repeat(auto-fit,minmax(180px,1fr)); gap:14px; margin-bottom:16px; }
    .hrm-stat-card { background:#fff; border-radius:10px; box-shadow:0 2px 15px rgba(0,0,0,.08); padding:20px; text-align:center; }
    .hrm-stat-card.accent .hrm-stat-value { color:var(--hrm-accent); }
    .hrm-stat-value { font-size:1.5rem; font-weight:700; color:var(--hrm-primary); margin-bottom:4px; }
    .hrm-stat-label { font-size:.83rem; color:#7f8c8d; }
    </style>

</head>

<body>
    <!-- thông báo -->
    <div id="toast"></div>
    <!-- Sidebar -->
    <sidebar class="sidebar">
        <!-- Brand -->
        <a href="#" class="sidebar__brand">
            <i class="icon fa-solid fa-shop"></i>
            <span class="name-brand">SPOCE STORE</span>
        </a>
        <!-- Menu -->
        <ul class="sidebar__menu">
            <?php

            // if (!isset($_SESSION["user"]) || !isset($_SESSION["role"])) {
            //     include '../404-Page/index.php';
            //     die();
            // }

            $user = $_SESSION["user"];
            $role = $_SESSION["role"]["data"] ?? [];
            if (count($role) <= 0) {
                include '../404-Page/index.php';
                die();
            }

            $role = $_SESSION["role"]["data"] ?? [];
            $printedPrivilegeIds = []; // Mảng lưu các privilegeId đã in

            foreach ($role as $item) {
                $privilegeId = $item["privilegeId"];

                // Kiểm tra nếu privilegeId chưa được in
                if (!in_array($privilegeId, $printedPrivilegeIds)) {

                    if ($privilegeId == 1) {
                        echo '
                        <li class="sidebar__item">
                            <a href="/profit_dashboard" class="sidebar__action" data-main-content="profit_dashboard">
                                <i class="icon fa-solid fa-dollar-sign"></i>
                                <span class="text">Thống kê lợi nhuận</span>
                            </a>
                        </li>
                        ';
                    } else if ($privilegeId == 2) {
                        echo '
                        <li class="sidebar__item">
                            <a href="#" class="sidebar__action" data-main-content="revenue_dashboard">
                                <i class="icon fa-solid fa-money-bill-trend-up"></i>
                                <span class="text">Thống kê doanh thu</span>
                            </a>
                        </li>
                        ';
                    } else if ($privilegeId == 3) {
                        echo '
                        <li class="sidebar__item">
                            <a href="#" class="sidebar__action" data-main-content="invest_dashboard">
                                <i class="icon fa-solid fa-file-invoice-dollar"></i>
                                <span class="text">Thống kê chi tiêu</span>
                            </a>
                        </li>
                        ';
                    } else if ($privilegeId == 4) {
                        echo '
                        <li class="sidebar__item">
                            <a href="#" class="sidebar__action" data-main-content="order_dashboard">
                                <i class="icon fa-solid fa-hand-holding-dollar"></i>
                                <span class="text">Thống kê đơn hàng</span>
                            </a>
                        </li>
                        ';
                    } else if ($privilegeId == 5) {
                        echo '
                        <li class="sidebar__item">
                            <a href="#" class="sidebar__action" data-main-content="order">
                                <i class="icon fa-solid fa-receipt"></i>
                                <span class="text">Đơn hàng</span>
                            </a>
                        </li>
                        ';
                    } else if ($privilegeId == 6) {
                        echo '
                        <li class="sidebar__item">
                            <a href="#" class="sidebar__action" data-main-content="discount">
                                <i class="icon fa-solid fa-percent"></i>
                                <span class="text">Phiếu giảm giá</span>
                            </a>
                        </li>
                        ';
                    } else if ($privilegeId == 7) {
                        echo '
                        <li class="sidebar__item">
                            <a href="#" class="sidebar__action" data-main-content="privilege">
                                <i class="icon fa-solid fa-users-line"></i>
                                <span class="text">Nhóm quyền</span>
                            </a>
                        </li>
                        ';
                    } else if ($privilegeId == 8) {
                        echo '
                        <li class="sidebar__item">
                            <a href="#" class="sidebar__action" data-main-content="account">
                                <i class="icon fa-solid fa-user"></i>
                                <span class="text">Người dùng</span>
                            </a>
                        </li>
                        ';
                    } else if ($privilegeId == 9) {
                        echo '
                        <li class="sidebar__item">
                            <a href="#" class="sidebar__action" data-main-content="supplier">
                                <i class="icon fa-solid fa-user-shield"></i>
                                <span class="text">Nhà cung cấp</span>
                            </a>
                        </li>
                        ';
                    } else if ($privilegeId == 10) {
                        echo '
                        <li class="sidebar__item">
                            <a href="#" class="sidebar__action" data-main-content="input_ticket">
                                <i class="icon fa-solid fa-file-pen"></i>
                                <span class="text">Phiếu nhập</span>
                            </a>
                        </li>
                        ';
                    } else if ($privilegeId == 11) {
                        echo '
                        <li class="sidebar__item">
                            <a href="#" class="sidebar__action" data-main-content="book">
                                <i class="icon fa-solid fa-book"></i>
                                <span class="text">Sách</span>
                            </a>
                        </li>
                        ';
                    } else if ($privilegeId == 12) {
                        echo '
                        <li class="sidebar__item">
                            <a href="#" class="sidebar__action" data-main-content="author">
                                <i class="icon fa-solid fa-user-pen"></i>
                                <span class="text">Tác giả</span>
                            </a>
                        </li>
                        ';
                    } else if ($privilegeId == 13) {
                        echo '
                        <li class="sidebar__item">
                            <a href="#" class="sidebar__action" data-main-content="category">
                                <i class="icon fa-solid fa-font-awesome"></i>
                                <span class="text">Thể loại</span>
                            </a>
                        </li>
                        ';
                    } else if ($privilegeId == 14) {
                        echo '
                        <li class="sidebar__item">
                            <a href="#" class="sidebar__action" data-main-content="cover">
                                <i class="icon fa-solid fa-book-open"></i>
                                <span class="text">Loại bìa</span>
                            </a>
                        </li>
                        ';
                    } else if ($privilegeId == 15) {
                        echo '
                        <li class="sidebar__item">
                            <a href="#" class="sidebar__action" data-main-content="publisher">
                                <i class="icon fa-solid fa-user-tag"></i>
                                <span class="text">Nhà xuất bản</span>
                            </a>
                        </li>
                        ';
                    } else if ($privilegeId == 16) {
                        echo '
                       <li class="sidebar__item">
                            <a href="#" class="sidebar__action" data-main-content="payment">
                                <i class="icon fa-solid fa-money-check-dollar"></i>
                                <span class="text">Thẻ thanh toán</span>
                            </a>
                        </li>
                        ';
                    // ---- Module quản lý nhân sự (HR Manager) ----
                    } else if ($privilegeId == 17) {
                        echo '
                        <li class="sidebar__item">
                            <a href="#" class="sidebar__action" data-main-content="hrm_employees">
                                <i class="icon fa-solid fa-users"></i>
                                <span class="text">Quản lý nhân viên</span>
                            </a>
                        </li>
                        <li class="sidebar__item">
                            <a href="#" class="sidebar__action" data-main-content="hrm_stats">
                                <i class="icon fa-solid fa-chart-bar"></i>
                                <span class="text">Thống kê lương</span>
                            </a>
                        </li>
                        ';
                    } else if ($privilegeId == 18) {
                        echo '
                        <li class="sidebar__item">
                            <a href="#" class="sidebar__action" data-main-content="hrm_salary">
                                <i class="icon fa-solid fa-money-bill-wave"></i>
                                <span class="text">Quản lý lương</span>
                            </a>
                        </li>
                        ';
                    } else if ($privilegeId == 19) {
                        echo '
                        <li class="sidebar__item">
                            <a href="#" class="sidebar__action" data-main-content="hrm_leaves">
                                <i class="icon fa-solid fa-calendar-check"></i>
                                <span class="text">Duyệt nghỉ phép</span>
                            </a>
                        </li>
                        ';
                    // ---- Module nhân viên tự phục vụ (HR Employee) ----
                    } else if ($privilegeId == 21) {
                        echo '
                        <li class="sidebar__item">
                            <a href="#" class="sidebar__action" data-main-content="hr_leave">
                                <i class="icon fa-solid fa-file-circle-plus"></i>
                                <span class="text">Đơn xin nghỉ phép</span>
                            </a>
                        </li>
                        ';
                    } else if ($privilegeId == 22) {
                        echo '
                        <li class="sidebar__item">
                            <a href="#" class="sidebar__action" data-main-content="hr_salary">
                                <i class="icon fa-solid fa-file-invoice-dollar"></i>
                                <span class="text">Bảng lương</span>
                            </a>
                        </li>
                        ';
                    }

                    $printedPrivilegeIds[] = $privilegeId;
                }
            }
            ?>
        </ul>
    </sidebar>

    <!-- Main -->
    <main class="main">
        <!-- Line -->
        <nav class="main__line">
            <i class="icon fa-solid fa-bars"></i>
            <i class="icon fa-solid fa-gear tab-home"></i>
            <i class="icon fa-solid fa-power-off tab-logout"></i>
        </nav>
        <!-- Content -->
        <div class="main__content" id="main-content"></div>
    </main>

    <!-- Spinner chờ trong khi lấy dữ liệu từ Server -->
    <div class="loading-overlay" id="loading-overlay"
        style="position: absolute; top: 0; left: 0; height: auto; background: red">
        <div class="spinner"></div>
    </div>

    <script src="../public/js/spinner.js"></script>

    <!-- Javascript -->
    <script type="module" src="js/main.js"></script>
    <script type="module" src="js/changeMainContent.js"></script>
    <script type="module" src="js/showSidebar.js"></script>
    <script type="module" src="responsive/responsive.js"></script>
    <script type="module" src="../public/js/toast.js"></script>

</body>

</html>
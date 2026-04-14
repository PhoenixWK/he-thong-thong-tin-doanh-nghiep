<?php
if (session_status() === PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['hrm_manager'])) {
    header('Location: /hr-manager/index.php');
    exit;
}
$hrmUser = $_SESSION['hrm_manager'];

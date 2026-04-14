<?php
/**
 * HR Portal - Auth check helper
 * Include this at top of every portal page
 */
session_start();
if (!isset($_SESSION['hr_employee'])) {
    header('Location: /hr/index.php');
    exit;
}
$hrEmp = $_SESSION['hr_employee'];

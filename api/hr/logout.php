<?php
/**
 * HR API - Đăng xuất
 * POST /api/hr/logout.php
 */
require_once __DIR__ . '/bootstrap.php';
if (session_status() === PHP_SESSION_NONE) session_start();
unset($_SESSION['hr_employee']);
hr_json(['success' => true]);

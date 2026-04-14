<?php
require_once __DIR__ . '/bootstrap.php';
if (session_status() === PHP_SESSION_NONE) session_start();
unset($_SESSION['hrm_manager']);
hrm_json(['success' => true]);

<?php
/**
 * HR Manager API Bootstrap
 * Separate from employee HR bootstrap — manager auth via own session key
 */
if (session_status() === PHP_SESSION_NONE) session_start();

class HRM_DB {
    private static $pdo = null;
    public static function get(): PDO {
        if (!self::$pdo) {
            self::$pdo = new PDO(
                'mysql:host=localhost;dbname=bookstore;charset=utf8mb4',
                'root', '123456',
                [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                 PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC]
            );
        }
        return self::$pdo;
    }
}

function hrm_json(array $data, int $code = 200): void {
    http_response_code($code);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    exit;
}

function hrm_require_auth(): array {
    if (empty($_SESSION['hrm_manager'])) {
        hrm_json(['error' => 'Chưa đăng nhập'], 401);
    }
    return $_SESSION['hrm_manager'];
}

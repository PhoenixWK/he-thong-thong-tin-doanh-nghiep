<?php
/**
 * HR Module - Database Connection
 * Decoupled from main app - has own connection class
 */
class HR_DB {
    private static $pdo = null;

    public static function get(): PDO {
        if (self::$pdo === null) {
            self::$pdo = new PDO(
                'mysql:host=localhost;port=3306;dbname=bookstore;charset=utf8mb4',
                'root',
                '123456',
                [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC]
            );
        }
        return self::$pdo;
    }
}

function hr_json($data, int $code = 200): void {
    http_response_code($code);
    header('Content-Type: application/json; charset=UTF-8');
    echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    exit;
}

function hr_require_employee(): array {
    if (session_status() === PHP_SESSION_NONE) session_start();
    if (!isset($_SESSION['hr_employee'])) {
        hr_json(['error' => 'Chưa đăng nhập'], 401);
    }
    return $_SESSION['hr_employee'];
}

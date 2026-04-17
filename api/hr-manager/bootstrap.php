<?php
/**
 * HR Manager API Bootstrap
 * Separate from employee HR bootstrap — manager auth via own session key
 */
if (session_status() === PHP_SESSION_NONE) session_start();

require_once __DIR__ . '/../../app/libs/DBConnection.php';

class HRM_DB {
    public static function get(): PDO {
        return (new app_libs_DBConnection())->open_connect();
    }
}

function hrm_json(array $data, int $code = 200): void {
    http_response_code($code);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    exit;
}

function hrm_require_auth(): array {
    // Ưu tiên session hr-manager riêng
    if (!empty($_SESSION['hrm_manager'])) {
        return $_SESSION['hrm_manager'];
    }
    // Cho phép dùng session admin nếu có privilegeId 17/18/19
    if (!empty($_SESSION['role']['data'])) {
        $hrmPrivileges = [17, 18, 19];
        $ids = array_column($_SESSION['role']['data'], 'privilegeId');
        if (!empty(array_intersect($ids, $hrmPrivileges))) {
            $u = $_SESSION['user'];
            return [
                'maNguoiDung' => $u['id'] ?? null,
                'hoVaTen'     => $u['name'] ?? '',
                'vaiTro'      => 'Quản lý nhân sự',
            ];
        }
    }
    hrm_json(['error' => 'Chưa đăng nhập'], 401);
}

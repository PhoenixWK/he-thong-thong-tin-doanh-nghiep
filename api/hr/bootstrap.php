<?php
require_once __DIR__ . '/../../app/libs/DBConnection.php';

class HR_DB {
    public static function get(): PDO {
        return (new app_libs_DBConnection())->open_connect();
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

    // Already populated (HR portal session or prior admin call)
    if (isset($_SESSION['hr_employee'])) {
        return $_SESSION['hr_employee'];
    }

    // Allow admin panel session with HR employee privileges (privilegeId 20/21/22)
    if (!empty($_SESSION['role']['data'])) {
        $hrPrivileges = [20, 21, 22];
        $ids = array_column($_SESSION['role']['data'], 'privilegeId');
        if (!empty(array_intersect($ids, $hrPrivileges))) {
            $maNguoiDung = $_SESSION['user']['id'] ?? null;
            if ($maNguoiDung) {
                $db = HR_DB::get();
                $stmt = $db->prepare(
                    "SELECT nv.maNhanVien, nv.maNguoiDung, nv.chucVu, nd.hoVaTen
                     FROM nhanVien nv
                     JOIN nguoidung nd ON nv.maNguoiDung = nd.maNguoiDung
                     WHERE nv.maNguoiDung = ? AND nv.trangThai = 'Dang lam' LIMIT 1"
                );
                $stmt->execute([$maNguoiDung]);
                $nv = $stmt->fetch();
                if ($nv) {
                    $_SESSION['hr_employee'] = [
                        'maNhanVien'  => $nv['maNhanVien'],
                        'maNguoiDung' => $maNguoiDung,
                        'hoVaTen'     => $nv['hoVaTen'],
                        'chucVu'      => $nv['chucVu'],
                    ];
                    return $_SESSION['hr_employee'];
                }
            }
        }
    }

    hr_json(['error' => 'Chưa đăng nhập'], 401);
}

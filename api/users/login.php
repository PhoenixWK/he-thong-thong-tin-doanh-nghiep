<?php
// Thiết lập session tồn tại trong 1 ngày (86400 giây)
session_set_cookie_params([
    'lifetime' => 86400,  // 1 ngày
    'path' => '/',
    'domain' => '',       // Dùng trên cùng một domain
    'secure' => false,    // Đặt true nếu dùng HTTPS
    'httponly' => true,   // Ngăn JavaScript truy cập session cookie
    'samesite' => 'Lax'   // Chống tấn công CSRF
]);
session_start();

// Kiểm tra session có hoạt động không
if (session_status() !== PHP_SESSION_ACTIVE) {
    http_response_code(500);
    echo json_encode(["success" => false, "message" => "Không thể khởi động session!"]);
    exit;
}

include_once '../../app/config.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

header("Content-Type: application/json");

// Cấu hình CORS
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

// Xử lý yêu cầu OPTIONS (preflight request)
if ($_SERVER["REQUEST_METHOD"] === "OPTIONS") {
    exit;
}

// Chỉ chấp nhận phương thức POST
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    echo json_encode(["success" => false, "message" => "Chỉ hỗ trợ phương thức POST!"]);
    exit;
}

$username = $_POST["username"] ?? '';
$password = $_POST["password"] ?? '';

if (empty($username) || empty($password)) {
    echo json_encode(["success" => false, "message" => "Vui lòng nhập đầy đủ thông tin!"]);
    exit;
}

$user_model = new app_models_NguoiDung();
$user = $user_model->loginUser($username, md5($password));

if (!$user) {
    echo json_encode(["success" => false, "message" => "Tài khoản hoặc mật khẩu không đúng!"]);
    exit;
}

if ($user['trangThai'] != 'Hoạt động') {
    echo json_encode(["success" => false, "message" => "Tài khoản bị khóa không đăng nhập được"]);
    exit;
}

// Xóa session cũ, tạo session mới để bảo mật
session_unset();
session_destroy();
session_start();
session_regenerate_id(true);

// Lưu thông tin người dùng vào session
$_SESSION["user"] = [
    "id" => $user["maNguoiDung"],
    "username" => $user["tenTaiKhoan"],
    "name" => $user["hoVaTen"],
    "email" => $user["email"],
    "login_time" => time()
];

$responseDetailRole = [];
if ($user["maQuyen"] != null) {
    $roleDetail_model = new app_models_ChiTietQuyen();
    $result = $roleDetail_model->detailsRole($user["maQuyen"]);
    if ($result) {
        foreach ($result as $item) {
            $responseDetailRole[] = [
                "roleId" => $item['maQuyen'],
                "privilegeId" => $item['maChucNang'],
                "actionId" => $item['maHanhDong']
            ];
        }
    }
}

$_SESSION["role"] = [
    "data" => $responseDetailRole
];

echo json_encode([
    "success" => true,
    "message" => "Đăng nhập thành công!",
    "session_id" => session_id(),
    "user" => $_SESSION["user"],
    "role" => $_SESSION['role']
]);
?>
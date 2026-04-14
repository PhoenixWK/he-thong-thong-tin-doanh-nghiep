<?php
/** POST /api/hr-manager/salary/calculate.php — tính & lưu lương 1 nhân viên */
require_once __DIR__ . '/../bootstrap.php';
hrm_require_auth();
if ($_SERVER['REQUEST_METHOD'] !== 'POST') hrm_json(['error' => 'Method not allowed'], 405);

$b = json_decode(file_get_contents('php://input'), true) ?? [];
$maNhanVien   = (int)($b['maNhanVien']   ?? 0);
$thang        = (int)($b['thang']        ?? 0);
$nam          = (int)($b['nam']          ?? 0);
$thuong       = (float)($b['thuong']     ?? 0);
$khauTru      = (float)($b['khauTru']    ?? 0);
$soNgayLam    = (float)($b['soNgayLam']  ?? 22);
$soNgayNghiPhep = (float)($b['soNgayNghiPhep'] ?? 0);
$ghiChu       = trim($b['ghiChu'] ?? '');

if (!$maNhanVien || !$thang || !$nam || $thang < 1 || $thang > 12)
    hrm_json(['error' => 'Thiếu thông tin'], 400);

$pdo = HRM_DB::get();
$nv = $pdo->prepare("SELECT luongCoBan, heSoLuong, phuCapCoDinh FROM nhanVien WHERE maNhanVien=? AND trangThai='Dang lam'");
$nv->execute([$maNhanVien]);
$data = $nv->fetch();
if (!$data) hrm_json(['error' => 'Nhân viên không tồn tại hoặc đã nghỉ việc'], 404);

// Formula: luongCoBan × heSoLuong × (soNgayLam / 22) + phuCapCoDinh + thuong − khauTru
$thucLinh = $data['luongCoBan'] * $data['heSoLuong'] * ($soNgayLam / 22)
          + $data['phuCapCoDinh'] + $thuong - $khauTru;

// Upsert
$pdo->prepare("INSERT INTO bangLuong
    (maNhanVien, thang, nam, luongCoBan, heSoLuong, phuCap, thuong, khauTru, soNgayLam, soNgayNghiPhep, thucLinh, ghiChu)
    VALUES (?,?,?,?,?,?,?,?,?,?,?,?)
    ON DUPLICATE KEY UPDATE
        luongCoBan=VALUES(luongCoBan), heSoLuong=VALUES(heSoLuong), phuCap=VALUES(phuCap),
        thuong=VALUES(thuong), khauTru=VALUES(khauTru), soNgayLam=VALUES(soNgayLam),
        soNgayNghiPhep=VALUES(soNgayNghiPhep), thucLinh=VALUES(thucLinh), ghiChu=VALUES(ghiChu)")
->execute([$maNhanVien, $thang, $nam, $data['luongCoBan'], $data['heSoLuong'],
           $data['phuCapCoDinh'], $thuong, $khauTru, $soNgayLam, $soNgayNghiPhep, $thucLinh, $ghiChu]);

hrm_json(['success' => true, 'thucLinh' => round($thucLinh, 0)]);

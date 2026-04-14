# Hướng dẫn truy cập hệ thống — Tài khoản & Đường dẫn

> **Base URL:** `http://localhost:8080`  
> **Khởi động server:** `php -S localhost:8080` tại thư mục gốc dự án

---

## 1. Trang khách hàng (Cổng mua sắm)

| Mục | URL |
|-----|-----|
| Trang chủ | `http://localhost:8080/index.php` |
| Đăng nhập / Đăng ký | `http://localhost:8080/index.php` → click Đăng nhập |

### Tài khoản khách hàng mẫu

| Tên đăng nhập | Mật khẩu | Họ và tên |
|---------------|----------|-----------|
| `domixi` | `123456` | Anh Độ Mixi |
| `namduong` | `123456` | My name is Duong |

> Khách hàng có thể tự **đăng ký** tài khoản mới tại trang chủ.

---

## 2. Trang quản trị Admin

| Mục | URL |
|-----|-----|
| Trang đăng nhập Admin | `http://localhost:8080/admin/index.php` |
| Dashboard | `http://localhost:8080/admin/index.php` (sau khi đăng nhập) |

### Các module Admin

| Module | Chức năng |
|--------|-----------|
| Tài khoản | Quản lý người dùng, phân quyền |
| Sách | Thêm / sửa / xóa / khoá sách |
| Tác giả | Quản lý tác giả |
| Thể loại | Quản lý thể loại sách |
| Loại bìa | Quản lý loại bìa |
| Nhà xuất bản | Quản lý NXB |
| Nhà cung cấp | Quản lý nhà cung cấp |
| Phiếu nhập | Nhập kho sách |
| Đơn hàng | Xử lý & theo dõi đơn hàng |
| Giảm giá | Quản lý phiếu giảm giá |
| Thanh toán | Quản lý phương thức thanh toán |
| Vai trò & Quyền | Phân quyền chức năng |
| Dashboard | Thống kê doanh thu, đơn hàng |

### Tài khoản Admin

| Tên đăng nhập | Mật khẩu | Vai trò |
|---------------|----------|---------|
| `quantrivien` | `quantrivien` | Quản trị viên (full quyền) |
| `quanlybanhang1` | `quanlybanhang1` | Quản lý bán hàng |
| `quanlykho1` | `quanlykho1` | Quản lý kho |

---

## 3. Cổng nhân viên — Module 3.1

| Mục | URL |
|-----|-----|
| Đăng nhập | `http://localhost:8080/hr/index.php` |
| Dashboard | `http://localhost:8080/hr/dashboard.php` |
| Thông tin cá nhân | `http://localhost:8080/hr/profile.php` |
| Bảng lương | `http://localhost:8080/hr/salary.php` |
| Đơn xin nghỉ | `http://localhost:8080/hr/leaves.php` |

### Chức năng nhân viên

| Chức năng | Mô tả |
|-----------|-------|
| Xem hồ sơ | Họ tên, chức vụ, ngày vào làm, lương cơ bản |
| Xem bảng lương | Tra cứu lương theo tháng, xuất PDF phiếu lương |
| Xem lương năm | Bảng tổng hợp 12 tháng, xuất PDF bảng năm |
| Nộp đơn xin nghỉ | Nghỉ phép / ốm đau / thai sản / nghỉ việc |
| Theo dõi đơn | Xem trạng thái đơn: Chờ duyệt / Đã duyệt / Từ chối |

### Tài khoản nhân viên mẫu

| Tên đăng nhập | Mật khẩu | Họ và tên | Chức vụ |
|---------------|----------|-----------|---------|
| `domixi` | `123456` | Anh Độ Mixi | Nhân viên bán hàng |

---

## 4. Cổng người quản lý HR — Module 3.2

| Mục | URL |
|-----|-----|
| Đăng nhập | `http://localhost:8080/hr-manager/index.php` |
| Quản lý nhân sự | `http://localhost:8080/hr-manager/employees.php` |
| Tính lương | `http://localhost:8080/hr-manager/salary.php` |
| Duyệt đơn nghỉ | `http://localhost:8080/hr-manager/leaves.php` |
| Thống kê | `http://localhost:8080/hr-manager/stats.php` |

### Chức năng quản lý HR

| Chức năng | Mô tả |
|-----------|-------|
| Thêm nhân sự | Chọn tài khoản hệ thống, gán chức vụ & lương |
| Xoá (vô hiệu hoá) nhân sự | Chuyển trạng thái → "Đã nghỉ" |
| Thay đổi chức vụ | Cập nhật chức vụ + lương, lưu lịch sử với thời điểm hiệu lực |
| Tính lương | Nhập ngày làm, thưởng, khấu trừ → tính và lưu bảng lương |
| Duyệt đơn nghỉ | Duyệt hoặc từ chối đơn xin nghỉ của nhân viên |
| Thống kê theo tháng | Chi tiết lương từng nhân viên trong tháng, tổng chi |
| Thống kê theo năm | Tổng lương & thưởng cả năm, số tháng có bảng lương |
| Xuất PDF | Xuất báo cáo thống kê thành file PDF |

### Tài khoản quản lý HR

| Tên đăng nhập | Mật khẩu | Vai trò |
|---------------|----------|---------|
| `hrmanager` | `123456` | HR Manager |

---

## 5. Thanh toán VNPAY (Sandbox)

| Mục | URL |
|-----|-----|
| Demo thanh toán | `http://localhost:8080/vnpay_php/index.php` |

> Sử dụng thẻ test VNPAY Sandbox. Không dùng thẻ thật.

---

## Tóm tắt nhanh

| Vai trò | URL đăng nhập | Tài khoản | Mật khẩu |
|---------|--------------|-----------|----------|
| Khách hàng | `/index.php` | `domixi` | `123456` |
| Quản trị viên | `/admin/index.php` | `quantrivien` | `quantrivien` |
| Quản lý bán hàng | `/admin/index.php` | `quanlybanhang1` | `quanlybanhang1` |
| Quản lý kho | `/admin/index.php` | `quanlykho1` | `quanlykho1` |
| Nhân viên (HR 3.1) | `/hr/index.php` | `domixi` | `123456` |
| Quản lý HR (HR 3.2) | `/hr-manager/index.php` | `hrmanager` | `123456` |

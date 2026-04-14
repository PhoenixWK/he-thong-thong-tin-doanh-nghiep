## 📜 **Cách sử dụng**
1. Tạo database ở localhost dùng Xampp: Đặt tên bookstore nhe.
2. Import file `bookstore_final.sql` vào database vừa tạo.
3. Tại `app/libs/DBConnection.php`: thay username và password của bạn.
4. Bật SQL của Xampp.
5. Cài đặt các thư viện PHP (nếu thư mục `vendor/` chưa có):
   ```
   composer install
   ```
6. Tại terminal codebase chạy:
   ```
   php -S localhost:8080
   ```
7. Truy cập http://localhost:8080/
8. Tạo tài khoản mới hoặc dùng tài khoản có sẵn bên dưới.

* Một vài tài khoản quan trọng trên hệ thống
   - Quản trị viên (Admin):   `quantrivien`    / `quantrivien`
   - Quản lý bán hàng (Admin): `quanlybanhang1` / `quanlybanhang1`
   - Quản lý kho (Admin):     `quanlykho1`     / `quanlykho1`
   - Nhân viên (HR 3.1):      `domixi`         / `123456`
   - Quản lý HR (HR 3.2):     `hrmanager`      / `123456`

* Chi tiết đường dẫn & tài khoản xem tại: ACCOUNTS.md

* Hàm set phần trăm giảm giá (n - số cuốn sách, m - phần trăm)
CALL SetGiamGiaSachBanCham(n,m);
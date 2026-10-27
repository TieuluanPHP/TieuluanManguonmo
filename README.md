<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

# 🎟️ Đồ Án Phát Triển Web: Website Bán & Mua Vé Sự Kiện Trực Tuyến (Event Ticketing Web App)

> Nền tảng thương mại điện tử chuyên biệt cho việc quảng bá, quản lý và đặt mua vé sự kiện trực tuyến (Âm nhạc, Hội thảo, Thể thao, Workshop, Triển lãm...), được xây dựng trên nền tảng **Laravel 13** với hiệu năng cao, cơ chế giữ chỗ thông minh và bảo mật thanh toán.

---

## 🛠 Công Nghệ Sử Dụng (Tech Stack)

* **Backend:** [Laravel 13](https://laravel.com/) (PHP >= 8.3)
* **Frontend:** Blade Template, Tailwind CSS, Alpine.js / Vite
* **Cơ sở dữ liệu (Database):** MySQL 8.0+ / MariaDB
* **Cache & Khóa chống trùng vé (Concurrency / Lock):** 
* **Thư viện & Dịch vụ tích hợp:**


---

## ✨ Các Chức Năng Chính (Key Features)

### 1. Phân hệ Khách hàng (Customer / Attendee)
* **Khám phá sự kiện:** Tìm kiếm thông minh theo tên sự kiện, nghệ sĩ, diễn giả; lọc theo thể loại (Âm nhạc, Công nghệ, Workshop, Thể thao...), địa điểm (Hà Nội, TP.HCM...) và khoảng thời gian.
* **Chi tiết sự kiện:** Xem thông tin giới thiệu, lịch trình, địa điểm tổ chức (tích hợp bản đồ), danh sách các hạng vé (VIP, Standard, Early Bird,...).
* **Quy trình đặt vé & Giữ chỗ:**
  * Chọn số lượng vé theo hạng hoặc chọn vị trí ghế trên sơ đồ (Seat map).
  * Khóa giữ chỗ tạm thời trong 10-15 phút để khách thực hiện thanh toán (tránh bán trùng vé).
  * Áp dụng mã giảm giá (Coupon/Voucher sự kiện).
* **Thanh toán:** Tích hợp cổng thanh toán VNPay, MoMo hoặc Chuyển khoản ngân hàng.
* **Nhận vé & Quản lý vé:**
  * Tự động gửi Vé điện tử (E-ticket) có kèm **Mã QR Code độc nhất** qua Email và lưu trong mục "Vé của tôi".
  * Hỗ trợ tải vé dạng file PDF để in hoặc lưu về điện thoại.

### 2. Phân hệ Ban tổ chức sự kiện (Organizer Portal)
* **Quản lý sự kiện:** Đăng ký sự kiện mới (upload poster/banner, mô tả, thời gian, địa điểm, quy định sự kiện).
* **Cấu hình vé:** Tạo nhiều loại vé khác nhau với giá bán, số lượng giới hạn, thời gian mở/đóng bán cho từng hạng vé.
* **Soát vé tại cổng (Check-in Scanner):**
  * Giao diện quét mã QR bằng camera điện thoại/webcam để kiểm tra tính hợp lệ của vé ngay tại cổng vào.
  * Đánh dấu vé đã check-in theo thời gian thực, chống tái sử dụng vé gian lận.
* **Thống kê ban tổ chức:** Báo cáo tỉ lệ vé đã bán, doanh thu tạm tính và tỉ lệ khách đã check-in vào sự kiện.

### 3. Phân hệ Quản trị viên (Admin Dashboard)
* **Kiểm duyệt sự kiện:** Phê duyệt hoặc từ chối các sự kiện do các đơn vị tổ chức đăng tải lên hệ thống.
* **Quản trị người dùng & Phân quyền:** Quản lý tài khoản Khách hàng, Ban tổ chức và Nhân viên soát vé (RBAC).
* **Cấu hình phí sàn & Quyết toán:** Thiết lập phần trăm phí dịch vụ sàn, quản lý yêu cầu rút tiền/quyết toán doanh thu cho Ban tổ chức.
* **Báo cáo & Phân tích:** Biểu đồ doanh thu toàn sàn, số lượng vé bán theo tháng, danh mục sự kiện được quan tâm nhất.
* **Chính sách hỗ trợ:** Xử lý khiếu nại, hỗ trợ hoàn tiền hoặc hủy vé theo chính sách sự kiện.

---

## 👨‍💻 Phân Công Nhiệm Vụ (Team Assignment - 4 Thành Viên)

| Thành viên | Vai trò | Nhiệm vụ chính phụ trách |
| :--- | :--- | :--- |
| **Thành viên 1** |
| **Thành viên 2** | 
| **Thành viên 3** |
| **Thành viên 4** | 

---

## 🚀 Hướng Dẫn Cài Đặt (Installation Guide)

### Yêu cầu môi trường
* **PHP:** >= 8.3
* **Composer:** >= 2.x
* **Node.js:** >= 18.x & NPM
* **MySQL:** >= 8.0 (hoặc MariaDB 10.4+)

### Các bước cài đặt chi tiết

**Bước 1: Clone project về máy**
```bash
git clone https://github.com/your-username/event-ticketing-laravel.git
cd event-ticketing-laravel
```

**Bước 2: Cài đặt các thư viện PHP (Composer Dependencies)**
```bash
composer install
```

**Bước 3: Cài đặt các gói thư viện Frontend (NPM Packages)**
```bash
npm install
```

**Bước 4: Cấu hình file môi trường (.env)**
```bash
# Tạo file .env từ file mẫu
cp .env.example .env

# Sinh Application Encryption Key
php artisan key:generate
```

Mở file `.env` và cập nhật thông tin CSDL cùng cấu hình thanh toán, gửi mail:
```env
APP_NAME="EventTicket"
APP_ENV=local
APP_KEY=base64:...
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=event_ticketing_db
DB_USERNAME=root
DB_PASSWORD=

# Cấu hình Mail gửi vé điện tử (dùng Mailtrap để test)
MAIL_MAILER=smtp
MAIL_HOST=sandbox.smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_mailtrap_username
MAIL_PASSWORD=your_mailtrap_password
MAIL_FROM_ADDRESS="no-reply@eventticket.test"
MAIL_FROM_NAME="${APP_NAME}"

# Cấu hình thanh toán VNPay (Sandbox)
VNPAY_TMN_CODE=your_tmn_code
VNPAY_HASH_SECRET=your_hash_secret
VNPAY_URL=https://sandbox.vnpayment.vn/paymentv2/vpcpay.html
```

**Bước 5: Chạy Migration và nạp dữ liệu mẫu (Seeder)**
```bash
# Hãy tạo database event_ticketing_db trên MySQL trước, sau đó chạy:
php artisan migrate --seed
```

**Bước 6: Tạo liên kết thư mục lưu trữ (Storage Link)**
```bash
php artisan storage:link
```

**Bước 7: Khởi chạy dự án**

* Chạy Vite biên dịch asset và CSS:
```bash
npm run dev
```

* Mở một terminal khác và khởi chạy local server:
```bash
php artisan serve
```

* Truy cập hệ thống tại: `http://127.0.0.1:8000`

---

## 🔑 Tài Khoản Thử Nghiệm Mặc Định (Demo Accounts)

Hệ thống cung cấp sẵn các tài khoản mẫu sau khi chạy `migrate --seed`:

| Vai trò | Email đăng nhập | Mật khẩu mặc định | Ghi chú |
| :--- | :--- | :--- | :--- |
| **Quản trị viên (Admin)** | `admin@eventticket.test` | `password` | Quản lý toàn sàn & duyệt sự kiện |
| **Ban tổ chức (Organizer)** | `organizer@eventticket.test` | `password` | Tạo sự kiện & soát vé QR |
| **Khách hàng (Customer)** | `customer@eventticket.test` | `password` | Mua vé, xem vé điện tử |

---

## 🌿 Quy Trình Làm Việc Với Git (Team Git Workflow)

* **Nhánh chính:**
  * **`main`**: Bản hoàn chỉnh, ổn định nhất dùng để báo cáo / thuyết trình.
  * **`develop`**: Nhánh tích hợp thường xuyên của nhóm.
* **Quy ước đặt tên nhánh tính năng:**
  * `feature/auth-payment-vnpay` (Thành viên 1)
  * `feature/event-detail-seatmap` (Thành viên 2)
  * `feature/qr-ticket-checkin` (Thành viên 3)
  * `feature/admin-approval-stats` (Thành viên 4)
* **Quy trình phối hợp:**
  1. Tạo nhánh con từ `develop`: `git checkout -b feature/<ten-chuc-nang>`
  2. Code, test kỹ lưỡng trên máy cá nhân và commit có mô tả rõ ràng.
  3. Đẩy lên remote và tạo **Pull Request (PR)** vào `develop`.
  4. Team Leader review code trước khi gộp (merge).


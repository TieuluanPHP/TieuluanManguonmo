# 🎟️ Đồ Án Phát Triển Web: Website Bán & Mua Vé Sự Kiện Trực Tuyến (Event Ticketing Web App)

> Nền tảng thương mại điện tử chuyên biệt cho việc quảng bá, quản lý và đặt mua vé sự kiện trực tuyến (Âm nhạc, Hội thảo, Thể thao, Workshop, Triển lãm...), được xây dựng trên nền tảng **Laravel 13** với hiệu năng cao, cơ chế giữ chỗ thông minh và bảo mật thanh toán.

---

## 🛠 Công Nghệ Sử Dụng (Tech Stack)

* **Backend:** [Laravel 13](https://laravel.com/) (PHP >= 8.3)
* **Frontend:** Blade Template, Tailwind CSS, Alpine.js / Vite
* **Cơ sở dữ liệu (Database):** MySQL 8.0+ / MariaDB
* **Cache & Khóa chống trùng vé (Concurrency / Lock):** 
* **Thư viện & Dịch vụ tích hợp:**

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
git clone (https://github.com/TieuluanPHP/TieuluanManguonmo.git)
cd 
```

**Bước 2: Cài đặt các thư viện PHP (Composer Dependencies)**
```bash
composer install
```

**Bước 3: Cài đặt các gói thư viện Frontend (NPM Packages)**
```bash
npm install
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

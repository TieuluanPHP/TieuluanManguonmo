# ĐẶC TẢ THIẾT KẾ HỆ THỐNG: BÁN VÉ CONCERT ĐA ĐỐI TÁC (MEGATIX CLONE)
*(Đã tinh gọn triệt để theo triết lý YAGNI: Tập trung 100% vào Bán vé & Cấp vé điện tử)*

---

## 1. TỔNG QUAN HỆ THỐNG & NGUYÊN TẮC CỐT LÕI

* **Tên dự án:** Nền tảng Đặt vé Concert & Sự kiện Âm nhạc Đa đối tác (Laravel Concert Booking System).
* **Mô hình hoạt động:** Multi-vendor kết nối Khách hàng $\leftrightarrow$ Ban tổ chức (Organizer) $\leftrightarrow$ Quản trị viên (Super Admin).
* **Mục tiêu cốt lõi:** **BÁN VÉ & CẤP MÃ VÉ ĐIỆN TỬ** vào tài khoản người dùng sau khi thanh toán.
* **Stack công nghệ:**
  * **Backend:** PHP >= 8.2, Laravel Framework.
  * **Database:** MySQL trên Laragon (hỗ trợ Transaction ACID và `lockForUpdate()` chống Race Condition).
  * **Frontend:** Blade Template, Tailwind CSS v4, AJAX (tải động sơ đồ ghế), Alpine.js / Vanilla JS.
* **Nguyên tắc nghiệp vụ tinh giản:**
  1. **Thanh toán 100% bằng Ví nội bộ (`balance`):** Khách nạp tiền qua cổng Giả lập (Mock Sandbox), sau đó trừ thẳng số dư ví khi mua vé. Loại bỏ nghẽn cổng thanh toán khi mở bán.
  2. **Chọn vé qua Popup Dropdown siêu tốc:** Luồng chọn Hạng vé/Khu $\rightarrow$ Hàng ghế $\rightarrow$ Ghế ngồi qua AJAX, tối ưu cho di động, không dùng bản đồ SVG cồng kềnh.
  3. **Cấp mã vé tức thì trong "Vé của tôi":** Sau khi thanh toán, vé được sinh với mã 8 ký tự (Ví dụ: `TIXA-8392`) kèm vị trí ghế (`A-5-12`) lưu trực tiếp trong trang quản lý vé của khách hàng.
  4. **Loại bỏ hoàn toàn tính năng Check-in / Soát vé cổng:** Không cần máy quét, không cần trang check-in soát vé, chỉ tập trung hoàn thiện nghiệp vụ Bán và Cấp vé.
  5. **Sinh ghế theo Khu/Hàng/Ghế:** Định dạng rõ ràng: `[Khu]-[Hàng]-[Số ghế]` (Ví dụ: `A-5-12`, `A-5-02`).
  6. **Bảo toàn dữ liệu vé đã bán:** Khóa sửa hạng vé & ghế ngay khi có phát sinh đơn hàng đầu tiên.

---

## 2. PHÂN QUYỀN & LUỒNG NGƯỜI DÙNG (USER ROLES & FLOWS)

### 2.1. Khách vãng lai (Guest)
* Xem trang chủ, danh sách concert, banner sự kiện nổi bật.
* Lọc sự kiện theo Thể loại, Địa điểm, Nghệ sĩ.
* Xem chi tiết concert: Poster, thời gian, địa điểm, bảng giá các hạng vé. Bấm "Mua vé" chuyển hướng đến Đăng nhập.

### 2.2. Khách hàng (Customer)
* **Đăng ký / Đăng nhập:** Siêu đơn giản (chỉ cần Tên đăng nhập `name` và Mật khẩu `password`).
* **Quản lý Ví nội bộ:**
  * Xem số dư hiện tại.
  * Nạp tiền qua cổng Giả lập (Mock Sandbox nội bộ) với các nút chọn nhanh mệnh giá (500k, 1tr, 2tr...).
  * Xem lịch sử biến động số dư (`wallet_transactions`).
* **Mua vé:**
  * Vé Đứng: Chọn hạng vé $\rightarrow$ Chọn số lượng vé.
  * Vé Ngồi: Mở Popup Dropdown $\rightarrow$ Chọn Khu/Hạng $\rightarrow$ Chọn Hàng còn trống $\rightarrow$ Chọn Ghế còn trống $\rightarrow$ Thêm vào danh sách đặt.
  * Bấm "Xác nhận trừ tiền": Hệ thống kiểm tra số dư ví và khóa ghế tức thời bằng `lockForUpdate()`.
* **Vé của tôi (My Tickets):** 
  * Hiển thị danh sách vé điện tử đã mua dưới dạng các thẻ vé sang trọng (Card): Tên concert, poster, ngày giờ, địa điểm, **Mã vé 8 ký tự** (vd: `MEGA-4921`), vị trí ghế (`A-5-12`), giá tiền và ngày mua.
* **Đăng ký làm Ban tổ chức:** Form nhập Tên công ty / Đơn vị tổ chức và Số điện thoại liên hệ $\rightarrow$ Gửi yêu cầu lên Admin xét duyệt.

### 2.3. Ban tổ chức (Organizer / Đối tác)
* **Đăng ký & Kích hoạt:** Tài khoản sau khi gửi form được Admin duyệt sẽ tự động nâng cấp role lên `organizer`.
* **Tạo Concert mới (3 phân phần trực quan):**
  * *Phần 1 - Thông tin chung:* Tên sự kiện, upload Poster ảnh, Ngày & giờ biểu diễn, Mô tả sự kiện, chọn Thể loại (Category), Địa điểm (Venue), Nghệ sĩ (Artist).
  * *Phần 2 - Cấu hình Vé & Ghế:*
    * **Concert Đứng (Standing):** Nút `[+ Thêm Hạng vé]` (Tên hạng, Giá, Số lượng vé mở bán).
    * **Concert Ngồi (Seated):** Nút `[+ Thêm Khu ghế]` (Tên khu, Ký hiệu mã khu ví dụ `A`, Giá vé, Số hàng, Số ghế mỗi hàng) $\rightarrow$ Tự động sinh mã ghế theo định dạng `[Khu]-[Hàng]-[Ghế]` (vd: `A-5-12`, `A-5-02`). Hiển thị tóm tắt tổng số vé và tổng doanh thu dự kiến.
  * *Phần 3 - Gửi duyệt:* Sự kiện sau khi tạo ở trạng thái `pending` (chờ Admin duyệt).
* **Quy tắc chỉnh sửa sự kiện:**
  * Khi chưa có ai mua vé: Organizer được sửa tự do mọi thông tin (kể cả xóa/thêm lại hạng vé và ghế).
  * Khi **đã có vé được bán ra**: Hệ thống **khóa hoàn toàn** việc sửa hoặc xóa các Hạng vé / Ghế ngồi để bảo vệ toàn vẹn dữ liệu đơn hàng của khách; chỉ cho phép cập nhật thông tin mô tả hoặc ảnh poster.
* **Quản lý Doanh thu & Rút tiền:**
  * Xem tổng số vé đã bán và doanh thu của từng sự kiện (Nhận đủ 100% doanh thu, 0% phí sàn).
  * Xem danh sách các vé đã cấp của sự kiện mình quản lý (Tên người mua, mã vé, vị trí ghế).
  * Form yêu cầu rút tiền: Nhập Số tiền, Tên ngân hàng, Số tài khoản, Tên chủ thẻ $\rightarrow$ Gửi Admin giải ngân.

### 2.4. Quản trị viên (Super Admin)
* **Khởi tạo:** 1 tài khoản duy nhất được sinh tự động qua Database Seeder.
* **Duyệt Đối tác:** Xem danh sách đơn xin làm Organizer kèm Tên công ty & SĐT $\rightarrow$ Bấm Duyệt / Từ chối.
* **Duyệt Concert:** Xem chi tiết concert kèm chức năng **`[Xem trước / Preview]`** giao diện khách $\rightarrow$ Phê duyệt mở bán (`approved`) hoặc Từ chối (`rejected`) kèm lý do để Organizer chỉnh sửa gửi lại.
* **Quản lý Tài chính:** Xem tổng dòng tiền nạp vào hệ thống, danh sách yêu cầu rút tiền của Organizer $\rightarrow$ Bấm xác nhận "Đã chuyển khoản".
* **Hủy Sự kiện & Tự động Hoàn tiền:** Trường hợp sự kiện bị hủy, hệ thống tự động hoàn 100% tiền vé về ví của các khách hàng đã mua và giải phóng ghế.
* **Quản lý Danh mục:** CRUD Thể loại (Category), Địa điểm (Venue), Nghệ sĩ (Artist) tập trung.

---

## 3. THIẾT KẾ CƠ SỞ DỮ LIỆU (DATABASE SCHEMA - MYSQL)

1. **`users`**:
   * `id`, `name` (string unique), `password`, `role` (enum: `customer`, `organizer`, `admin`), `balance` (decimal: 15,2 default 0), `remember_token`, timestamps.
2. **`organizer_profiles`**:
   * `id`, `user_id` (FK users), `company_name`, `phone`, `status` (enum: `pending`, `approved`, `rejected`), `notes`, timestamps.
3. **`categories`**:
   * `id`, `name`, `slug`, timestamps.
4. **`venues`**:
   * `id`, `name`, `address`, `city`, timestamps.
5. **`artists`**:
   * `id`, `name`, `bio`, `image`, timestamps.
6. **`events`**:
   * `id`, `organizer_id` (FK users), `category_id`, `venue_id`, `artist_id` (nullable), `title`, `slug`, `description`, `poster_image`, `event_date`, `event_type` (enum: `standing`, `seated`), `status` (enum: `pending`, `approved`, `rejected`, `cancelled`, `ended`), `admin_rejection_reason` (nullable text), timestamps.
7. **`ticket_types`**:
   * `id`, `event_id` (FK events), `name` (vd: VIP, GA, Khu A), `zone_code` (vd: A, B, VIP), `price` (decimal), `total_quantity`, `remaining_quantity`, timestamps.
8. **`seats`**:
   * `id`, `event_id` (FK events), `ticket_type_id` (FK ticket_types), `row_number` (int, vd: 5), `seat_number` (int, vd: 12), `seat_code` (string, vd: `A-5-12`), `status` (enum: `available`, `booked`), timestamps.
   * *Index:* `event_id`, `ticket_type_id`, `status`.
9. **`orders`**:
   * `id`, `user_id` (FK users), `event_id` (FK events), `total_amount` (decimal), `status` (enum: `paid`, `cancelled`), timestamps.
10. **`tickets`**:
    * `id`, `order_id` (FK orders), `user_id` (FK users), `ticket_type_id` (FK ticket_types), `seat_id` (nullable FK seats - null nếu là vé đứng), `ticket_code` (string unique: 8 ký tự, vd: `TIXA-8392`), `price` (decimal), `status` (enum: `valid`, `refunded`), timestamps.
    * *Index:* `ticket_code`, `user_id`, `order_id`.
11. **`wallet_transactions`**:
    * `id`, `user_id` (FK users), `amount` (decimal), `type` (enum: `deposit`, `payment`, `refund`, `withdraw`), `description`, timestamps.
12. **`withdrawals`**:
    * `id`, `user_id` (FK users - organizer), `amount` (decimal), `bank_name`, `account_number`, `account_holder`, `status` (enum: `pending`, `approved`, `rejected`), timestamps.

---

## 4. LOGIC KỸ THUẬT THEN CHỐT

### 4.1. Quy tắc sinh Mã vé 8 ký tự
```php
function generateTicketCode(): string {
    do {
        $letters = strtoupper(Str::random(4));
        $numbers = str_pad((string)random_int(0, 9999), 4, '0', STR_PAD_LEFT);
        $code = $letters . '-' . $numbers; // vd: TIXA-8392
    } while (Ticket::where('ticket_code', $code)->exists());
    
    return $code;
}
```

### 4.2. Sinh Ghế tự động theo Khu/Hàng/Ghế
```php
// Ví dụ: Ban tổ chức tạo Khu A (zone_code: 'A'), 5 hàng, mỗi hàng 20 ghế
$seatsData = [];
for ($row = 1; $row <= $totalRows; $row++) {
    for ($seat = 1; $seat <= $seatsPerRow; $seat++) {
        $seatNumberFormatted = str_pad((string)$seat, 2, '0', STR_PAD_LEFT);
        $seatCode = "{$zoneCode}-{$row}-{$seatNumberFormatted}"; // vd: A-5-12, A-5-02
        $seatsData[] = [
            'event_id' => $event->id,
            'ticket_type_id' => $ticketType->id,
            'row_number' => $row,
            'seat_number' => $seat,
            'seat_code' => $seatCode,
            'status' => 'available',
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
Seat::insert($seatsData); // Bulk insert hiệu năng cao
```

### 4.3. Thanh toán Ví, Khóa ghế & Cấp vé chống Race Condition
```php
DB::transaction(function () use ($user, $totalAmount, $seatIds, $event, $ticketTypeId, $quantity) {
    // 1. Kiểm tra số dư ví
    if ($user->balance < $totalAmount) {
        throw new \Exception("Số dư trong ví không đủ. Vui lòng nạp thêm tiền!");
    }

    // 2. Khóa ghế (Đối với Concert Ngồi)
    if ($event->event_type === 'seated') {
        $seats = Seat::whereIn('id', $seatIds)
            ->where('status', 'available')
            ->lockForUpdate()
            ->get();

        if ($seats->count() !== count($seatIds)) {
            throw new \Exception("Một số ghế bạn chọn vừa có người đặt. Vui lòng chọn lại!");
        }

        Seat::whereIn('id', $seatIds)->update(['status' => 'booked']);
    } else {
        // Khóa số lượng (Đối với Concert Đứng)
        $ticketType = TicketType::where('id', $ticketTypeId)->lockForUpdate()->firstOrFail();
        if ($ticketType->remaining_quantity < $quantity) {
            throw new \Exception("Hạng vé này vừa hết, vui lòng chọn số lượng khác!");
        }
        $ticketType->decrement('remaining_quantity', $quantity);
    }

    // 3. Trừ tiền ví và ghi log
    $user->decrement('balance', $totalAmount);
    WalletTransaction::create([
        'user_id' => $user->id,
        'amount' => -$totalAmount,
        'type' => 'payment',
        'description' => "Mua vé xem sự kiện: " . $event->title,
    ]);

    // 4. Tạo Order
    $order = Order::create([
        'user_id' => $user->id,
        'event_id' => $event->id,
        'total_amount' => $totalAmount,
        'status' => 'paid',
    ]);

    // 5. Cấp vé vào tài khoản
    if ($event->event_type === 'seated') {
        foreach ($seats as $seat) {
            Ticket::create([
                'order_id' => $order->id,
                'user_id' => $user->id,
                'ticket_type_id' => $seat->ticket_type_id,
                'seat_id' => $seat->id,
                'ticket_code' => generateTicketCode(),
                'price' => $seat->ticketType->price,
                'status' => 'valid',
            ]);
        }
    } else {
        for ($i = 0; $i < $quantity; $i++) {
            Ticket::create([
                'order_id' => $order->id,
                'user_id' => $user->id,
                'ticket_type_id' => $ticketType->id,
                'seat_id' => null,
                'ticket_code' => generateTicketCode(),
                'price' => $ticketType->price,
                'status' => 'valid',
            ]);
        }
    }
});
```

---

## 5. BẢNG NHẬT KÝ QUYẾT ĐỊNH THIẾT KẾ (DECISION LOG)

| Vấn đề | Quyết định đã chốt | Các lựa chọn đã cân nhắc | Lý do chọn |
| :--- | :--- | :--- | :--- |
| **Phạm vi vòng đời vé** | **Chỉ Bán & Cấp vé trong "Vé của tôi"** | Có Check-in tại cổng vs Bỏ hẳn Check-in | Bỏ hoàn toàn việc soát vé cổng rườm rà. Đúng mục đích sàn bán vé: mua xong là cấp mã và lưu thẻ vé vào tài khoản người dùng. |
| **Cơ sở dữ liệu** | MySQL trên Laragon | SQLite vs MySQL | Đúng theo chuẩn hệ thống production, hỗ trợ đầy đủ `lockForUpdate()` và Transaction ACID cho mua vé đồng thời. |
| **Xác thực (Auth)** | Tên đăng nhập + Mật khẩu đơn giản | Laravel Breeze vs Tự viết Custom Auth | Siêu tối giản, nhanh gọn, không rườm rà email/OTP lúc kiểm thử. |
| **Tài khoản Admin** | Mặc định 1 tài khoản duy nhất qua Seeder | Đăng ký admin tự do vs Seeder | Đảm bảo tính bảo mật và duy nhất của chủ sàn. |
| **Quy trình Đối tác** | Customer gửi đơn (Tên công ty + SĐT) chờ Admin duyệt | Tự do tạo show vs Duyệt qua đơn đăng ký | Đúng chuẩn mô hình Multi-vendor kiểm duyệt chặt chẽ đối tác. |
| **Định danh Vé** | Mã 8 ký tự `ABCD-1234` | QR Code vs Barcode vs Mã 8 ký tự | Dễ đọc, dễ nhớ, sang trọng như mã đặt chỗ PNR vé máy bay. |
| **Cấu hình Ghế ngồi** | Thiết lập theo Khu/Dãy, sinh mã `[Khu]-[Hàng]-[Ghế]` | Nhập lẻ từng hàng vs Chia khoảng khán phòng | Sát thực tế concert (khu A, VIP...), mã ghế dễ đọc và không thể đọc nhầm hàng với số ghế. |
| **Quy tắc Sửa sự kiện** | Khóa sửa Hạng vé & Ghế ngay khi đã có vé được bán ra | Cho sửa tự do vs Khóa toàn bộ | Bảo vệ tính toàn vẹn dữ liệu đơn hàng và vé đã cấp cho khách hàng. |
| **Nạp tiền ví** | Cổng Giả lập (Mock Sandbox nội bộ) | VNPay Sandbox vs MoMo vs Mock Sandbox | Tối ưu tiến độ phát triển, kiểm thử luồng mua vé tức thì mà không bị chặn bởi API key ngân hàng. |
| **Phí sàn Đối tác** | 0% phí (nhận 100% doanh thu vé) | Trích % hoa hồng vs 0% phí | Giảm thiểu phức tạp về đối soát tài chính trong giai đoạn đầu. |
| **Chính sách Hủy vé** | Vé mua không được tự hủy. Chỉ hoàn tiền khi Hủy sự kiện | Tự hủy hoàn tiền vs Không hủy vé | Chuẩn chính sách vé concert thực tế (No Refund) để bảo vệ quyền lợi Ban tổ chức. |
| **Đánh giá Chỗ ngồi** | **Loại bỏ tính năng này** | Đánh giá sao + ảnh vs Bỏ hoàn toàn | YAGNI - tập trung toàn bộ năng lượng vào luồng mua vé và quản lý doanh thu. |
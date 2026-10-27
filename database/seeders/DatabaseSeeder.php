<?php

namespace Database\Seeders;

use App\Models\Artist;
use App\Models\Category;
use App\Models\Event;
use App\Models\OrganizerProfile;
use App\Models\Seat;
use App\Models\TicketType;
use App\Models\User;
use App\Models\Venue;
use App\Models\WalletTransaction;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Super Admin
        $admin = User::create([
            'name' => 'admin',
            'email' => 'admin@megatix.local',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'balance' => 10000000,
        ]);

        // 2. Organizer
        $organizer = User::create([
            'name' => 'organizer1',
            'email' => 'organizer@megatix.local',
            'password' => Hash::make('password123'),
            'role' => 'organizer',
            'balance' => 0,
        ]);

        OrganizerProfile::create([
            'user_id' => $organizer->id,
            'company_name' => 'Mega Stage Entertainment',
            'phone' => '0912345678',
            'status' => 'approved',
            'notes' => 'Đối tác tổ chức sự kiện chuyên nghiệp',
        ]);

        // 3. Customer
        $customer = User::create([
            'name' => 'customer1',
            'email' => 'customer@megatix.local',
            'password' => Hash::make('password123'),
            'role' => 'customer',
            'balance' => 5000000,
        ]);

        WalletTransaction::create([
            'user_id' => $customer->id,
            'amount' => 5000000,
            'type' => 'deposit',
            'description' => 'Nạp tiền ban đầu vào ví (Sandbox Test)',
        ]);

        // 4. Categories
        $categories = [
            ['name' => 'Pop Music', 'slug' => 'pop-music'],
            ['name' => 'Rap / Hip-Hop', 'slug' => 'rap-hip-hop'],
            ['name' => 'Rock / Metal', 'slug' => 'rock-metal'],
            ['name' => 'EDM / Festival', 'slug' => 'edm-festival'],
            ['name' => 'Indie / Acoustic', 'slug' => 'indie-acoustic'],
        ];
        $catModels = [];
        foreach ($categories as $cat) {
            $catModels[$cat['slug']] = Category::create($cat);
        }

        // 5. Venues
        $v1 = Venue::create([
            'name' => 'Sân vận động Quốc gia Mỹ Đình',
            'address' => 'Đường Lê Đức Thọ, Phường Mỹ Đình 1, Nam Từ Liêm',
            'city' => 'Hà Nội',
        ]);

        $v2 = Venue::create([
            'name' => 'Nhà thi đấu Thể dục Thể thao Phú Thọ',
            'address' => 'Số 1 Lữ Gia, Phường 15, Quận 11',
            'city' => 'TP. Hồ Chí Minh',
        ]);

        $v3 = Venue::create([
            'name' => 'Trung tâm Hội nghị Quốc gia',
            'address' => 'Đại lộ Thăng Long, Mễ Trì, Nam Từ Liêm',
            'city' => 'Hà Nội',
        ]);

        // 6. Artists
        $a1 = Artist::create([
            'name' => 'Sơn Tùng M-TP',
            'bio' => 'Nghệ sĩ biểu diễn hàng đầu V-Pop với hàng loạt kỷ lục âm nhạc quốc tế.',
            'image' => 'https://images.unsplash.com/photo-1516450360452-9312f5e86fc7?auto=format&fit=crop&w=800&q=80',
        ]);

        $a2 = Artist::create([
            'name' => 'Đen Vâu',
            'bio' => 'Rapper mộc mạc mang đến những bản rap truyền cảm hứng và triết lý sống.',
            'image' => 'https://images.unsplash.com/photo-1511671782779-c97d3d27a1d4?auto=format&fit=crop&w=800&q=80',
        ]);

        $a3 = Artist::create([
            'name' => 'SOOBIN',
            'bio' => 'Hoàng tử R&B với chất giọng lôi cuốn và khả năng trình diễn sân khấu đỉnh cao.',
            'image' => 'https://images.unsplash.com/photo-1514525253161-7a46d19cd819?auto=format&fit=crop&w=800&q=80',
        ]);

        // 7. Event 1: Seated Concert (Concert Ngồi)
        $event1 = Event::create([
            'organizer_id' => $organizer->id,
            'category_id' => $catModels['pop-music']->id,
            'venue_id' => $v1->id,
            'artist_id' => $a3->id,
            'title' => 'Anh Trai Say Hi - Live Concert 2026',
            'slug' => 'anh-trai-say-hi-live-concert-2026',
            'description' => 'Đại nhạc hội bùng nổ cảm xúc với sân khấu ánh sáng đẳng cấp quốc tế. Hơn 30 tiết mục được dàn dựng công phu cùng dàn nghệ sĩ tài năng nhất.',
            'poster_image' => 'https://images.unsplash.com/photo-1470225620780-dba8ba36b745?auto=format&fit=crop&w=1200&q=80',
            'event_date' => now()->addDays(25)->setTime(19, 30),
            'event_type' => 'seated',
            'status' => 'approved',
        ]);

        // Khu VIP A: 3 hàng x 10 ghế = 30 ghế
        $t1 = TicketType::create([
            'event_id' => $event1->id,
            'name' => 'Khu VIP A (Gần Sân Khấu)',
            'zone_code' => 'A',
            'price' => 1500000,
            'total_quantity' => 30,
            'remaining_quantity' => 30,
        ]);
        $seats1 = [];
        for ($row = 1; $row <= 3; $row++) {
            for ($seat = 1; $seat <= 10; $seat++) {
                $pad = str_pad((string) $seat, 2, '0', STR_PAD_LEFT);
                $seats1[] = [
                    'event_id' => $event1->id,
                    'ticket_type_id' => $t1->id,
                    'row_number' => $row,
                    'seat_number' => $seat,
                    'seat_code' => "A-{$row}-{$pad}",
                    'status' => 'available',
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }
        Seat::insert($seats1);

        // Khán đài B: 4 hàng x 10 ghế = 40 ghế
        $t2 = TicketType::create([
            'event_id' => $event1->id,
            'name' => 'Khán Đài B (Standard)',
            'zone_code' => 'B',
            'price' => 800000,
            'total_quantity' => 40,
            'remaining_quantity' => 40,
        ]);
        $seats2 = [];
        for ($row = 1; $row <= 4; $row++) {
            for ($seat = 1; $seat <= 10; $seat++) {
                $pad = str_pad((string) $seat, 2, '0', STR_PAD_LEFT);
                $seats2[] = [
                    'event_id' => $event1->id,
                    'ticket_type_id' => $t2->id,
                    'row_number' => $row,
                    'seat_number' => $seat,
                    'seat_code' => "B-{$row}-{$pad}",
                    'status' => 'available',
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }
        Seat::insert($seats2);

        // 8. Event 2: Standing Concert (Concert Đứng)
        $event2 = Event::create([
            'organizer_id' => $organizer->id,
            'category_id' => $catModels['rap-hip-hop']->id,
            'venue_id' => $v2->id,
            'artist_id' => $a2->id,
            'title' => 'Show Của Đen - Live in Saigon 2026',
            'slug' => 'show-cua-den-live-in-saigon-2026',
            'description' => 'Đêm nhạc đặc biệt dành riêng cho các Đồng Âm. Không gian âm nhạc mộc mạc, gần gũi và đầy ắp những câu chuyện được kể bằng giai điệu.',
            'poster_image' => 'https://images.unsplash.com/photo-1501386761578-eac5c94b800a?auto=format&fit=crop&w=1200&q=80',
            'event_date' => now()->addDays(15)->setTime(20, 00),
            'event_type' => 'standing',
            'status' => 'approved',
        ]);

        TicketType::create([
            'event_id' => $event2->id,
            'name' => 'Vé Đứng GA Early Bird',
            'zone_code' => null,
            'price' => 500000,
            'total_quantity' => 100,
            'remaining_quantity' => 100,
        ]);

        TicketType::create([
            'event_id' => $event2->id,
            'name' => 'Vé Đứng GA Standard',
            'zone_code' => null,
            'price' => 750000,
            'total_quantity' => 200,
            'remaining_quantity' => 200,
        ]);

        // 9. Event 3: Past / Expired Concert (Concert đã qua thời hạn)
        $event3 = Event::create([
            'organizer_id' => $organizer->id,
            'category_id' => $catModels['rock-metal']->id,
            'venue_id' => $v3->id,
            'artist_id' => $a1->id,
            'title' => 'Sky Tour - Kỷ Niệm 10 Năm Âm Nhạc (Đã Diễn Ra)',
            'slug' => 'sky-tour-ky-niem-10-nam-am-nhac',
            'description' => 'Đại nhạc hội bùng nổ cảm xúc đã diễn ra thành công rực rỡ với sự tham gia của hơn 15.000 khán giả cuồng nhiệt. Sự kiện đã kết thúc và toàn bộ cổng bán vé đã được đóng lại.',
            'poster_image' => 'https://images.unsplash.com/photo-1459749411175-04bf5292ceea?auto=format&fit=crop&w=1200&q=80',
            'event_date' => now()->subDays(12)->setTime(19, 00),
            'event_type' => 'standing',
            'status' => 'approved',
        ]);

        TicketType::create([
            'event_id' => $event3->id,
            'name' => 'Vé Đứng Sky Zone A',
            'zone_code' => null,
            'price' => 600000,
            'total_quantity' => 150,
            'remaining_quantity' => 0,
        ]);

        TicketType::create([
            'event_id' => $event3->id,
            'name' => 'Vé Đứng Sky Zone B',
            'zone_code' => null,
            'price' => 400000,
            'total_quantity' => 200,
            'remaining_quantity' => 0,
        ]);

        // 10. Event 4: Concert diễn ra tối nay (trong 14 giờ nữa - Đã đóng cổng bán vé trước 24h)
        $event4 = Event::create([
            'organizer_id' => $organizer->id,
            'category_id' => $catModels['edm-festival']->id,
            'venue_id' => $v1->id,
            'artist_id' => $a3->id,
            'title' => 'Ravolution Music Festival (Sắp Bắt Đầu Tối Nay)',
            'slug' => 'ravolution-music-festival-sap-bat-dau',
            'description' => 'Lễ hội âm nhạc điện tử cuồng nhiệt quy tụ hàng nghìn bạn trẻ. Sự kiện sẽ diễn ra trong vài giờ tới nên cổng bán vé trực tuyến đã tự động đóng 24 giờ trước để chốt danh sách.',
            'poster_image' => 'https://images.unsplash.com/photo-1470225620780-dba8ba36b745?auto=format&fit=crop&w=1200&q=80',
            'event_date' => now()->addHours(14),
            'event_type' => 'standing',
            'status' => 'approved',
        ]);

        TicketType::create([
            'event_id' => $event4->id,
            'name' => 'Vé Rave Zone',
            'zone_code' => null,
            'price' => 850000,
            'total_quantity' => 100,
            'remaining_quantity' => 0,
        ]);
    }
}

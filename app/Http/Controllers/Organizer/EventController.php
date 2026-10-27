<?php

namespace App\Http\Controllers\Organizer;

use App\Http\Controllers\Controller;
use App\Models\Artist;
use App\Models\Category;
use App\Models\Event;
use App\Models\Seat;
use App\Models\TicketType;
use App\Models\Venue;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class EventController extends Controller
{
    public function create(): View
    {
        $categories = Category::all();
        $venues = Venue::all();
        $artists = Artist::all();

        return view('organizer.events.create', compact('categories', 'venues', 'artists'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'category_id' => ['required', 'exists:categories,id'],
            'venue_id' => ['required', 'exists:venues,id'],
            'artist_id' => ['nullable', 'exists:artists,id'],
            'event_date' => ['required', 'date', 'after:today'],
            'event_type' => ['required', 'in:standing,seated'],
            'poster_file' => ['required', 'file', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'description' => ['required', 'string'],
        ], [
            'title.required' => 'Vui lòng nhập tên concert.',
            'event_date.after' => 'Thời gian biểu diễn phải diễn ra trong tương lai.',
            'poster_file.required' => 'Vui lòng tải ảnh Poster từ máy tính của bạn.',
            'poster_file.image' => 'File tải lên phải là hình ảnh hợp lệ (jpg, jpeg, png, webp).',
            'poster_file.max' => 'Kích thước ảnh không được vượt quá 5MB.',
        ]);

        $posterPath = $request->file('poster_file')->store('events/posters', 'public');

        $organizer = Auth::user();

        DB::transaction(function () use ($request, $organizer, $posterPath) {
            // 1. Create Event
            $slug = Str::slug($request->title).'-'.Str::lower(Str::random(5));
            $event = Event::create([
                'organizer_id' => $organizer->id,
                'category_id' => $request->category_id,
                'venue_id' => $request->venue_id,
                'artist_id' => $request->artist_id,
                'title' => $request->title,
                'slug' => $slug,
                'description' => $request->description,
                'poster_image' => $posterPath,
                'event_date' => $request->event_date,
                'event_type' => $request->event_type,
                'status' => 'pending', // Pending Admin review
            ]);

            // 2. Setup Ticket Types & Seats
            if ($request->event_type === 'standing') {
                $standingTickets = $request->input('standing_tickets', []);
                if (empty($standingTickets)) {
                    throw new \Exception('Vui lòng cấu hình ít nhất một hạng vé đứng.');
                }

                foreach ($standingTickets as $st) {
                    if (empty($st['name']) || empty($st['price']) || empty($st['quantity'])) {
                        continue;
                    }

                    TicketType::create([
                        'event_id' => $event->id,
                        'name' => $st['name'],
                        'zone_code' => null,
                        'price' => (float) $st['price'],
                        'total_quantity' => (int) $st['quantity'],
                        'remaining_quantity' => (int) $st['quantity'],
                    ]);
                }
            } else {
                // Seated Concert
                $seatedZones = $request->input('seated_zones', []);
                if (empty($seatedZones)) {
                    throw new \Exception('Vui lòng cấu hình ít nhất một khu vực ghế ngồi.');
                }

                foreach ($seatedZones as $zone) {
                    if (empty($zone['name']) || empty($zone['zone_code']) || empty($zone['price']) || empty($zone['total_rows']) || empty($zone['seats_per_row'])) {
                        continue;
                    }

                    $totalRows = (int) $zone['total_rows'];
                    $seatsPerRow = (int) $zone['seats_per_row'];
                    $totalZoneSeats = $totalRows * $seatsPerRow;
                    $zoneCode = strtoupper(trim($zone['zone_code']));

                    $ticketType = TicketType::create([
                        'event_id' => $event->id,
                        'name' => $zone['name'],
                        'zone_code' => $zoneCode,
                        'price' => (float) $zone['price'],
                        'total_quantity' => $totalZoneSeats,
                        'remaining_quantity' => $totalZoneSeats,
                    ]);

                    // Generate physical seats in database
                    $seatsData = [];
                    for ($row = 1; $row <= $totalRows; $row++) {
                        for ($seat = 1; $seat <= $seatsPerRow; $seat++) {
                            $seatPad = str_pad((string) $seat, 2, '0', STR_PAD_LEFT);
                            $seatCode = "{$zoneCode}-{$row}-{$seatPad}"; // Format: A-1-01, A-5-20

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

                    Seat::insert($seatsData); // High-performance bulk insert
                }
            }
        });

        return redirect()->route('organizer.dashboard')->with('success', 'Sự kiện đã được tạo thành công và gửi đến Quản trị viên (Admin) để xét duyệt mở bán!');
    }

    public function edit(int $id): View|RedirectResponse
    {
        $event = Event::with(['ticketTypes.seats'])->where('organizer_id', Auth::id())->findOrFail($id);
        $categories = Category::all();
        $venues = Venue::all();
        $artists = Artist::all();

        $hasSoldTickets = $event->hasSoldTickets();

        return view('organizer.events.edit', compact('event', 'categories', 'venues', 'artists', 'hasSoldTickets'));
    }

    public function update(Request $request, int $id): RedirectResponse
    {
        $event = Event::where('organizer_id', Auth::id())->findOrFail($id);

        $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'poster_file' => ['nullable', 'file', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'description' => ['required', 'string'],
        ], [
            'poster_file.image' => 'File tải lên phải là hình ảnh hợp lệ (jpg, jpeg, png, webp).',
            'poster_file.max' => 'Kích thước ảnh không được vượt quá 5MB.',
        ]);

        $updateData = [
            'title' => $request->title,
            'description' => $request->description,
        ];

        if ($request->hasFile('poster_file')) {
            if ($event->poster_image && ! str_starts_with($event->poster_image, 'http')) {
                Storage::disk('public')->delete($event->poster_image);
            }
            $updateData['poster_image'] = $request->file('poster_file')->store('events/posters', 'public');
        }

        $event->update($updateData);

        return redirect()->route('organizer.dashboard')->with('success', 'Thông tin sự kiện đã được cập nhật thành công!');
    }
}

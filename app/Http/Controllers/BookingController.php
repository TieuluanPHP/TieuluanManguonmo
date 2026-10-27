<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Order;
use App\Models\Seat;
use App\Models\Ticket;
use App\Models\TicketType;
use App\Models\WalletTransaction;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BookingController extends Controller
{
    /**
     * Generate unique 8-character code: 4 uppercase letters + 4 digits (e.g. TIXA-8392)
     */
    private function generateTicketCode(): string
    {
        do {
            // 4 uppercase letters
            $letters = '';
            for ($i = 0; $i < 4; $i++) {
                $letters .= chr(random_int(65, 90));
            }
            // 4 digits
            $digits = str_pad((string) random_int(0, 9999), 4, '0', STR_PAD_LEFT);
            $code = "{$letters}-{$digits}";
        } while (Ticket::where('ticket_code', $code)->exists());

        return $code;
    }

    public function checkout(Request $request): RedirectResponse
    {
        $user = Auth::user();

        $request->validate([
            'event_id' => ['required', 'exists:events,id'],
            'ticket_type_id' => ['required', 'exists:ticket_types,id'],
        ]);

        $event = Event::findOrFail($request->event_id);
        if (! $event->canBeBooked()) {
            return back()->with('error', 'Sự kiện này đã kết thúc hoặc không còn mở bán vé.')->withInput();
        }

        $ticketType = TicketType::where('event_id', $event->id)->findOrFail($request->ticket_type_id);

        try {
            $order = DB::transaction(function () use ($user, $event, $ticketType, $request) {

                $seatIds = [];
                $quantity = 1;
                $totalAmount = 0;

                // 1. Calculate amount & validate input by Event Type
                if ($event->isSeated()) {
                    $seatIds = $request->input('seat_ids', []);
                    if (! is_array($seatIds) || empty($seatIds)) {
                        throw new Exception('Vui lòng chọn ít nhất một ghế ngồi hợp lệ.');
                    }

                    $quantity = count($seatIds);
                    $totalAmount = $ticketType->price * $quantity;
                } else {
                    // Standing
                    $quantity = (int) $request->input('quantity', 1);
                    if ($quantity < 1 || $quantity > 10) {
                        throw new Exception('Số lượng vé đứng phải từ 1 đến 10 vé mỗi lượt mua.');
                    }
                    $totalAmount = $ticketType->price * $quantity;
                }

                // 2. Lock & Check User Balance
                // Re-fetch user with lock to prevent double-spend
                $lockedUser = DB::table('users')->where('id', $user->id)->lockForUpdate()->first();
                if ((float) $lockedUser->balance < $totalAmount) {
                    $missing = $totalAmount - (float) $lockedUser->balance;
                    throw new Exception('Số dư ví không đủ. Bạn cần nạp thêm ít nhất '.number_format($missing, 0, ',', '.').' VNĐ để hoàn tất đơn hàng.');
                }

                // 3. Pessimistic Lock for Seats / Tickets
                $seats = collect();
                if ($event->isSeated()) {
                    $seats = Seat::whereIn('id', $seatIds)
                        ->where('ticket_type_id', $ticketType->id)
                        ->where('status', 'available')
                        ->lockForUpdate()
                        ->get();

                    if ($seats->count() !== count($seatIds)) {
                        throw new Exception('Một hoặc nhiều ghế bạn chọn vừa có người khác đặt trước. Vui lòng chọn ghế khác!');
                    }

                    // Mark seats as booked
                    Seat::whereIn('id', $seatIds)->update(['status' => 'booked']);

                    // Decrement remaining tickets
                    $ticketType->decrement('remaining_quantity', $quantity);
                } else {
                    // Standing: lock ticket type row
                    $lockedType = TicketType::where('id', $ticketType->id)->lockForUpdate()->firstOrFail();
                    if ($lockedType->remaining_quantity < $quantity) {
                        throw new Exception('Số lượng vé loại này vừa hết hoặc không đủ cho yêu cầu của bạn.');
                    }
                    $lockedType->decrement('remaining_quantity', $quantity);
                }

                // 4. Deduct User Wallet Balance & Record Transaction
                DB::table('users')->where('id', $user->id)->decrement('balance', $totalAmount);

                WalletTransaction::create([
                    'user_id' => $user->id,
                    'amount' => -$totalAmount,
                    'type' => 'payment',
                    'description' => "Thanh toán mua {$quantity} vé xem concert: {$event->title}",
                ]);

                // 5. Create Order
                $createdOrder = Order::create([
                    'user_id' => $user->id,
                    'event_id' => $event->id,
                    'total_amount' => $totalAmount,
                    'status' => 'paid',
                ]);

                // 6. Issue Tickets (with 8-character unique code)
                if ($event->isSeated()) {
                    foreach ($seats as $seat) {
                        Ticket::create([
                            'order_id' => $createdOrder->id,
                            'user_id' => $user->id,
                            'ticket_type_id' => $ticketType->id,
                            'seat_id' => $seat->id,
                            'ticket_code' => $this->generateTicketCode(),
                            'price' => $ticketType->price,
                            'status' => 'valid',
                        ]);
                    }
                } else {
                    for ($i = 0; $i < $quantity; $i++) {
                        Ticket::create([
                            'order_id' => $createdOrder->id,
                            'user_id' => $user->id,
                            'ticket_type_id' => $ticketType->id,
                            'seat_id' => null,
                            'ticket_code' => $this->generateTicketCode(),
                            'price' => $ticketType->price,
                            'status' => 'valid',
                        ]);
                    }
                }

                return $createdOrder;
            });

            return redirect()->route('tickets.my')->with('success', 'Chúc mừng bạn! Mua vé thành công. Mã vé điện tử đã được cấp trong mục Vé của tôi.');

        } catch (Exception $e) {
            return back()->with('error', $e->getMessage())->withInput();
        }
    }
}

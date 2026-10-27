<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Order;
use App\Models\Seat;
use App\Models\Ticket;
use App\Models\WalletTransaction;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class EventModerationController extends Controller
{
    public function index(Request $request): View
    {
        $status = $request->get('status', 'all');

        $query = Event::with(['organizer.organizerProfile', 'category', 'venue', 'ticketTypes'])
            ->latest();

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        $events = $query->paginate(15)->withQueryString();

        return view('admin.events.index', compact('events', 'status'));
    }

    public function preview(int $id): View
    {
        $event = Event::with(['organizer.organizerProfile', 'category', 'venue', 'artist', 'ticketTypes.seats'])
            ->findOrFail($id);

        return view('admin.events.preview', compact('event'));
    }

    public function approve(int $id): RedirectResponse
    {
        $event = Event::findOrFail($id);
        $event->update([
            'status' => 'approved',
            'admin_rejection_reason' => null,
        ]);

        return redirect()->route('admin.events.index')->with('success', "Đã phê duyệt concert '{$event->title}'. Sự kiện hiện đã được mở bán công khai!");
    }

    public function reject(Request $request, int $id): RedirectResponse
    {
        $request->validate([
            'reason' => ['required', 'string', 'max:500'],
        ]);

        $event = Event::findOrFail($id);
        $event->update([
            'status' => 'rejected',
            'admin_rejection_reason' => $request->reason,
        ]);

        return redirect()->route('admin.events.index')->with('success', "Đã từ chối duyệt concert '{$event->title}'. Lý do đã được gửi tới Ban tổ chức.");
    }

    /**
     * Cancel event and automatically refund 100% to customers' internal wallets
     */
    public function cancel(Request $request, int $id): RedirectResponse
    {
        $event = Event::with('orders.user', 'orders.tickets')->findOrFail($id);

        DB::transaction(function () use ($event) {
            // 1. Mark event as cancelled
            $event->update(['status' => 'cancelled']);

            // 2. Loop through all paid orders and refund 100%
            $paidOrders = Order::where('event_id', $event->id)->where('status', 'paid')->get();

            foreach ($paidOrders as $order) {
                $user = $order->user;
                $refundAmount = $order->total_amount;

                // Refund balance to user
                $user->increment('balance', $refundAmount);

                // Create wallet refund transaction
                WalletTransaction::create([
                    'user_id' => $user->id,
                    'amount' => $refundAmount,
                    'type' => 'refund',
                    'description' => "Hoàn tiền tự động 100% do sự kiện bị hủy: {$event->title}",
                ]);

                // Mark order as cancelled
                $order->update(['status' => 'cancelled']);

                // Mark tickets as refunded
                Ticket::where('order_id', $order->id)->update(['status' => 'refunded']);
            }

            // 3. Release seats back to available
            if ($event->isSeated()) {
                Seat::where('event_id', $event->id)->update(['status' => 'available']);
            }
        });

        return redirect()->back()->with('success', "Đã hủy concert '{$event->title}' thành công. Hệ thống đã tự động hoàn tiền 100% về ví của tất cả khách hàng đã mua vé!");
    }
}

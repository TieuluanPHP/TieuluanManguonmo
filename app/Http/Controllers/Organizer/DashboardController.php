<?php

namespace App\Http\Controllers\Organizer;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Order;
use App\Models\Withdrawal;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $organizer = Auth::user();

        $events = Event::with(['category', 'venue', 'ticketTypes.seats', 'orders'])
            ->where('organizer_id', $organizer->id)
            ->latest()
            ->get();

        // Calculate statistics
        $totalEvents = $events->count();
        $approvedEvents = $events->where('status', 'approved')->count();
        $pendingEvents = $events->where('status', 'pending')->count();

        // Total tickets sold and revenue from all organizer's events
        $eventIds = $events->pluck('id');
        $paidOrders = Order::whereIn('event_id', $eventIds)->where('status', 'paid')->get();
        $totalRevenue = $paidOrders->sum('total_amount');
        $totalTicketsSold = $paidOrders->loadCount('tickets')->sum('tickets_count');

        // Total withdrawn or pending withdrawals
        $withdrawals = Withdrawal::where('user_id', $organizer->id)->latest()->get();
        $withdrawnAmount = $withdrawals->whereIn('status', ['approved', 'pending'])->sum('amount');
        $availableBalance = max(0, $totalRevenue - $withdrawnAmount);

        return view('organizer.dashboard', compact(
            'events',
            'totalEvents',
            'approvedEvents',
            'pendingEvents',
            'totalRevenue',
            'totalTicketsSold',
            'availableBalance',
            'withdrawals'
        ));
    }
}

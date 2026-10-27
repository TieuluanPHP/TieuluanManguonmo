<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Order;
use App\Models\OrganizerProfile;
use App\Models\User;
use App\Models\WalletTransaction;
use App\Models\Withdrawal;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $totalUsers = User::count();
        $totalOrganizers = User::where('role', 'organizer')->count();
        $pendingPartners = OrganizerProfile::where('status', 'pending')->count();

        $pendingEvents = Event::where('status', 'pending')->count();
        $approvedEvents = Event::where('status', 'approved')->count();

        $totalGrossVolume = Order::where('status', 'paid')->sum('total_amount');
        $totalDeposits = WalletTransaction::where('type', 'deposit')->sum('amount');
        $pendingWithdrawalsCount = Withdrawal::where('status', 'pending')->count();

        // Recent items needing attention
        $recentPendingEvents = Event::with(['organizer', 'category', 'venue'])
            ->where('status', 'pending')
            ->latest()
            ->take(5)
            ->get();

        $recentPendingPartners = OrganizerProfile::with('user')
            ->where('status', 'pending')
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalUsers',
            'totalOrganizers',
            'pendingPartners',
            'pendingEvents',
            'approvedEvents',
            'totalGrossVolume',
            'totalDeposits',
            'pendingWithdrawalsCount',
            'recentPendingEvents',
            'recentPendingPartners'
        ));
    }
}

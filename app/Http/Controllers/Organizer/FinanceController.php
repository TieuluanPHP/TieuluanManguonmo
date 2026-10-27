<?php

namespace App\Http\Controllers\Organizer;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Order;
use App\Models\Withdrawal;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class FinanceController extends Controller
{
    public function index(): View
    {
        $organizer = Auth::user();

        $events = Event::with('orders.tickets')
            ->where('organizer_id', $organizer->id)
            ->get();

        $eventIds = $events->pluck('id');
        $paidOrders = Order::whereIn('event_id', $eventIds)->where('status', 'paid')->get();
        $totalRevenue = $paidOrders->sum('total_amount');

        $withdrawals = Withdrawal::where('user_id', $organizer->id)->latest()->get();
        $withdrawnOrPending = $withdrawals->whereIn('status', ['approved', 'pending'])->sum('amount');
        $availableBalance = max(0, $totalRevenue - $withdrawnOrPending);

        return view('organizer.finance.index', compact(
            'events',
            'totalRevenue',
            'availableBalance',
            'withdrawals'
        ));
    }

    public function requestWithdraw(Request $request): RedirectResponse
    {
        $organizer = Auth::user();

        $request->validate([
            'amount' => ['required', 'numeric', 'min:100000'],
            'bank_name' => ['required', 'string', 'max:100'],
            'account_number' => ['required', 'string', 'max:50'],
            'account_holder' => ['required', 'string', 'max:100'],
        ], [
            'amount.min' => 'Số tiền rút tối thiểu là 100.000 VNĐ.',
            'bank_name.required' => 'Vui lòng nhập tên ngân hàng thụ hưởng.',
            'account_number.required' => 'Vui lòng nhập số tài khoản ngân hàng.',
            'account_holder.required' => 'Vui lòng nhập tên chủ tài khoản.',
        ]);

        $amount = (float) $request->amount;

        // Calculate available balance
        $eventIds = Event::where('organizer_id', $organizer->id)->pluck('id');
        $totalRevenue = Order::whereIn('event_id', $eventIds)->where('status', 'paid')->sum('total_amount');
        $used = Withdrawal::where('user_id', $organizer->id)->whereIn('status', ['approved', 'pending'])->sum('amount');
        $available = max(0, $totalRevenue - $used);

        if ($amount > $available) {
            return back()->with('error', 'Số tiền yêu cầu rút vượt quá doanh thu khả dụng hiện tại ('.number_format($available, 0, ',', '.').' VNĐ).')->withInput();
        }

        Withdrawal::create([
            'user_id' => $organizer->id,
            'amount' => $amount,
            'bank_name' => $request->bank_name,
            'account_number' => $request->account_number,
            'account_holder' => strtoupper($request->account_holder),
            'status' => 'pending',
        ]);

        return redirect()->route('organizer.finance')->with('success', 'Đã tạo yêu cầu rút tiền thành công! Admin sẽ tiến hành chuyển khoản và xác nhận giải ngân.');
    }
}

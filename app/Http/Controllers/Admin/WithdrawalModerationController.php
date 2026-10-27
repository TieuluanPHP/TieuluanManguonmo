<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Withdrawal;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class WithdrawalModerationController extends Controller
{
    public function index(): View
    {
        $withdrawals = Withdrawal::with('user.organizerProfile')
            ->latest()
            ->paginate(15);

        return view('admin.withdrawals.index', compact('withdrawals'));
    }

    public function approve(int $id): RedirectResponse
    {
        $withdrawal = Withdrawal::findOrFail($id);
        $withdrawal->update(['status' => 'approved']);

        return redirect()->back()->with('success', 'Đã đánh dấu giải ngân thành công số tiền '.number_format($withdrawal->amount, 0, ',', '.')." VNĐ cho Ban tổ chức ({$withdrawal->bank_name} - {$withdrawal->account_number})!");
    }

    public function reject(int $id): RedirectResponse
    {
        $withdrawal = Withdrawal::findOrFail($id);
        $withdrawal->update(['status' => 'rejected']);

        return redirect()->back()->with('success', 'Đã từ chối lệnh rút tiền của Ban tổ chức.');
    }
}

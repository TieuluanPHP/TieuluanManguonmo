<?php

namespace App\Http\Controllers;

use App\Models\WalletTransaction;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class WalletController extends Controller
{
    public function index(): View
    {
        $user = Auth::user();
        $transactions = $user->walletTransactions()->paginate(15);

        return view('wallet.index', compact('user', 'transactions'));
    }

    public function deposit(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'amount' => ['required', 'numeric', 'min:50000', 'max:50000000'],
        ], [
            'amount.required' => 'Vui lòng chọn hoặc nhập số tiền muốn nạp.',
            'amount.min' => 'Số tiền nạp tối thiểu là 50.000 VNĐ.',
            'amount.max' => 'Số tiền nạp tối đa mỗi lần là 50.000.000 VNĐ.',
        ]);

        $amount = (float) $validated['amount'];
        $user = Auth::user();

        DB::transaction(function () use ($user, $amount) {
            $user->increment('balance', $amount);

            WalletTransaction::create([
                'user_id' => $user->id,
                'amount' => $amount,
                'type' => 'deposit',
                'description' => 'Nạp tiền vào ví qua Cổng Giả lập (Mock Sandbox)',
            ]);
        });

        return redirect()->route('wallet.index')->with('success', 'Nạp thành công '.number_format($amount, 0, ',', '.').' VNĐ vào ví nội bộ!');
    }
}

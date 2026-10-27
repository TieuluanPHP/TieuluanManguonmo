@extends('layouts.app', ['title' => 'Ví Nội Bộ & Nạp Tiền - MEGATIX'])

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    <div class="mb-8">
        <h1 class="text-3xl font-extrabold text-white tracking-tight flex items-center">
            <i class="fa-solid fa-wallet text-emerald-400 mr-3"></i> Ví Nội Bộ Của Tôi
        </h1>
        <p class="mt-1 text-sm text-slate-400">
            Nạp tiền trước để mua vé concert siêu tốc, không bị nghẽn mạng cổng thanh toán trong thời điểm mở bán.
        </p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Left: Virtual Wallet Card & Deposit Form -->
        <div class="lg:col-span-1 space-y-6">
            
            <!-- Virtual Card -->
            <div class="relative overflow-hidden rounded-3xl p-6 bg-gradient-to-br from-slate-900 via-indigo-950 to-slate-900 border border-indigo-500/30 shadow-2xl shadow-indigo-950/50">
                <div class="absolute -right-10 -bottom-10 w-44 h-44 rounded-full bg-fuchsia-600/10 blur-2xl pointer-events-none"></div>
                <div class="absolute -left-10 -top-10 w-44 h-44 rounded-full bg-indigo-600/10 blur-2xl pointer-events-none"></div>

                <div class="flex items-center justify-between mb-8">
                    <span class="text-xs font-bold tracking-widest uppercase text-slate-400">MEGATIX PASS WALLET</span>
                    <i class="fa-solid fa-wifi text-slate-400 transform rotate-90"></i>
                </div>

                <div class="mb-8">
                    <p class="text-xs text-slate-400 uppercase tracking-wider mb-1">Số dư khả dụng</p>
                    <p class="text-3xl font-extrabold text-emerald-400 tracking-tight">
                        {{ number_format($user->balance, 0, ',', '.') }} <span class="text-xl font-medium text-emerald-300">VNĐ</span>
                    </p>
                </div>

                <div class="flex items-end justify-between text-xs text-slate-300">
                    <div>
                        <p class="text-[10px] uppercase text-slate-500 tracking-wider">Chủ tài khoản</p>
                        <p class="font-bold text-white text-sm uppercase">{{ $user->name }}</p>
                    </div>
                    <div class="text-right">
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold uppercase bg-emerald-500/20 text-emerald-300 border border-emerald-500/30">
                            <i class="fa-solid fa-check mr-1"></i> Đang hoạt động
                        </span>
                    </div>
                </div>
            </div>

            <!-- Deposit Box -->
            <div class="bg-slate-900/90 rounded-3xl p-6 border border-slate-800 shadow-xl">
                <h3 class="text-base font-bold text-white mb-2 flex items-center">
                    <i class="fa-solid fa-circle-arrow-up text-fuchsia-400 mr-2"></i> Nạp Tiền Giả Lập (Mock Sandbox)
                </h3>
                <p class="text-xs text-slate-400 mb-6">
                    Chọn nhanh mệnh giá để nạp ngay vào ví phục vụ kiểm thử luồng mua vé:
                </p>

                <form action="{{ route('wallet.deposit') }}" method="POST" class="space-y-4">
                    @csrf

                    <!-- Quick Preset Buttons -->
                    <div class="grid grid-cols-2 gap-2.5">
                        <button type="button" onclick="setDepositAmount(200000)" class="py-2.5 px-3 rounded-xl bg-slate-800/80 hover:bg-slate-700/80 border border-slate-700 text-xs font-bold text-slate-200 transition-all hover:border-fuchsia-500">
                            + 200.000 đ
                        </button>
                        <button type="button" onclick="setDepositAmount(500000)" class="py-2.5 px-3 rounded-xl bg-slate-800/80 hover:bg-slate-700/80 border border-slate-700 text-xs font-bold text-slate-200 transition-all hover:border-fuchsia-500">
                            + 500.000 đ
                        </button>
                        <button type="button" onclick="setDepositAmount(1000000)" class="py-2.5 px-3 rounded-xl bg-slate-800/80 hover:bg-slate-700/80 border border-slate-700 text-xs font-bold text-slate-200 transition-all hover:border-fuchsia-500">
                            + 1.000.000 đ
                        </button>
                        <button type="button" onclick="setDepositAmount(2000000)" class="py-2.5 px-3 rounded-xl bg-slate-800/80 hover:bg-slate-700/80 border border-slate-700 text-xs font-bold text-slate-200 transition-all hover:border-fuchsia-500">
                            + 2.000.000 đ
                        </button>
                        <button type="button" onclick="setDepositAmount(5000000)" class="py-2.5 px-3 rounded-xl bg-slate-800/80 hover:bg-slate-700/80 border border-slate-700 text-xs font-bold text-slate-200 transition-all hover:border-fuchsia-500 col-span-2">
                            + 5.000.000 đ (VIP Hunter)
                        </button>
                    </div>

                    <div class="pt-2">
                        <label for="amount" class="block text-xs font-semibold text-slate-300 mb-1.5">
                            Hoặc nhập số tiền tùy ý (VNĐ)
                        </label>
                        <div class="relative">
                            <input type="number" id="amount" name="amount" min="50000" max="50000000" step="10000" required
                                value="{{ old('amount', 500000) }}"
                                class="w-full pl-4 pr-12 py-3 rounded-xl bg-slate-950 border border-slate-800 text-white font-bold placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 text-sm">
                            <span class="absolute right-4 top-3.5 text-xs font-bold text-slate-400">VNĐ</span>
                        </div>
                        @error('amount')
                            <p class="mt-1 text-xs text-rose-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit" class="w-full py-3.5 px-4 rounded-xl text-sm font-bold text-white bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-500 hover:to-teal-500 shadow-lg shadow-emerald-600/30 transition-all">
                        <i class="fa-solid fa-bolt mr-2"></i> Xác Nhận Nạp Ngay
                    </button>
                </form>
            </div>
        </div>

        <!-- Right: Transaction History -->
        <div class="lg:col-span-2">
            <div class="bg-slate-900/90 rounded-3xl p-6 sm:p-8 border border-slate-800 shadow-xl">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-bold text-white flex items-center">
                        <i class="fa-solid fa-clock-rotate-left text-indigo-400 mr-2.5"></i> Lịch Sử Biến Động Số Dư
                    </h3>
                    <span class="text-xs text-slate-400">Tổng cộng: {{ $transactions->total() }} giao dịch</span>
                </div>

                @if($transactions->isEmpty())
                    <div class="text-center py-12 text-slate-500">
                        <i class="fa-solid fa-receipt text-4xl mb-3 text-slate-600"></i>
                        <p class="text-sm">Chưa có giao dịch biến động số dư nào.</p>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm text-slate-300">
                            <thead class="text-xs uppercase bg-slate-950/60 text-slate-400 border-b border-slate-800">
                                <tr>
                                    <th class="py-3 px-4">Thời gian</th>
                                    <th class="py-3 px-4">Loại giao dịch</th>
                                    <th class="py-3 px-4">Mô tả</th>
                                    <th class="py-3 px-4 text-right">Số tiền</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-800/60">
                                @foreach($transactions as $tx)
                                    <tr class="hover:bg-slate-800/40 transition-colors">
                                        <td class="py-3.5 px-4 text-xs text-slate-400 whitespace-nowrap">
                                            {{ $tx->created_at->format('H:i d/m/Y') }}
                                        </td>
                                        <td class="py-3.5 px-4 whitespace-nowrap">
                                            @if($tx->type === 'deposit')
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-bold bg-emerald-500/20 text-emerald-300 border border-emerald-500/30">
                                                    <i class="fa-solid fa-arrow-down mr-1"></i> Nạp tiền
                                                </span>
                                            @elseif($tx->type === 'payment')
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-bold bg-rose-500/20 text-rose-300 border border-rose-500/30">
                                                    <i class="fa-solid fa-arrow-up mr-1"></i> Mua vé
                                                </span>
                                            @elseif($tx->type === 'refund')
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-bold bg-amber-500/20 text-amber-300 border border-amber-500/30">
                                                    <i class="fa-solid fa-rotate-left mr-1"></i> Hoàn tiền
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-bold bg-purple-500/20 text-purple-300 border border-purple-500/30">
                                                    <i class="fa-solid fa-building-columns mr-1"></i> Rút tiền
                                                </span>
                                            @endif
                                        </td>
                                        <td class="py-3.5 px-4 text-slate-300 text-xs">
                                            {{ $tx->description }}
                                        </td>
                                        <td class="py-3.5 px-4 text-right font-bold whitespace-nowrap {{ $tx->amount > 0 ? 'text-emerald-400' : 'text-rose-400' }}">
                                            {{ $tx->amount > 0 ? '+' : '' }}{{ number_format($tx->amount, 0, ',', '.') }} đ
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $transactions->links() }}
                    </div>
                @endif
            </div>
        </div>

    </div>

</div>

<script>
    function setDepositAmount(value) {
        document.getElementById('amount').value = value;
    }
</script>
@endsection

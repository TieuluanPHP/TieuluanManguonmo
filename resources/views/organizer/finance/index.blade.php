@extends('layouts.app', ['title' => 'Tài Chính & Rút Tiền - MEGATIX'])

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    <div class="mb-8">
        <a href="{{ route('organizer.dashboard') }}" class="text-xs font-bold text-slate-400 hover:text-white flex items-center mb-2">
            <i class="fa-solid fa-arrow-left mr-1.5"></i> Quay lại Dashboard
        </a>
        <h1 class="text-3xl font-extrabold text-white tracking-tight flex items-center">
            <i class="fa-solid fa-building-columns text-emerald-400 mr-3"></i> Đối Soát Tài Chính & Rút Tiền
        </h1>
        <p class="mt-1 text-sm text-slate-400">
            Nền tảng MEGATIX áp dụng chính sách <strong>0% phí hoa hồng sàn</strong>. Bạn nhận được 100% doanh thu vé bán ra.
        </p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Left: Payout Request Form & Balance Card -->
        <div class="lg:col-span-1 space-y-6">
            
            <div class="p-6 rounded-3xl bg-slate-900/90 border border-slate-800 shadow-xl space-y-4">
                <div class="flex items-center justify-between text-xs text-slate-400">
                    <span>Doanh Thu Khả Dụng</span>
                    <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase bg-emerald-500/20 text-emerald-300">100% Doanh Thu</span>
                </div>
                <p class="text-3xl font-black text-emerald-400">
                    {{ number_format($availableBalance, 0, ',', '.') }} <span class="text-lg font-medium text-emerald-300">VNĐ</span>
                </p>
                <div class="pt-2 border-t border-slate-800/80 text-xs text-slate-400 flex justify-between">
                    <span>Tổng tiền vé bán được:</span>
                    <span class="font-bold text-white">{{ number_format($totalRevenue, 0, ',', '.') }} đ</span>
                </div>
            </div>

            <!-- Request Withdrawal Form -->
            <div class="bg-slate-900/90 rounded-3xl p-6 border border-slate-800 shadow-xl">
                <h3 class="text-base font-bold text-white mb-2 flex items-center">
                    <i class="fa-solid fa-money-bill-transfer text-purple-400 mr-2"></i> Tạo Yêu Cầu Rút Tiền
                </h3>
                <p class="text-xs text-slate-400 mb-6">
                    Admin sẽ đối soát doanh thu và chuyển khoản trực tiếp vào tài khoản ngân hàng của bạn.
                </p>

                <form action="{{ route('organizer.withdraw.post') }}" method="POST" class="space-y-4">
                    @csrf

                    <div>
                        <label for="amount" class="block text-xs font-semibold text-slate-300 mb-1">
                            Số tiền muốn rút (VNĐ) <span class="text-rose-500">*</span>
                        </label>
                        <input type="number" id="amount" name="amount" min="100000" max="{{ $availableBalance }}" step="10000" required
                            value="{{ old('amount', $availableBalance > 0 ? $availableBalance : '') }}"
                            class="w-full px-4 py-2.5 rounded-xl bg-slate-950 border border-slate-800 text-emerald-400 font-extrabold text-sm focus:outline-none focus:ring-2 focus:ring-purple-500"
                            placeholder="Tối thiểu 100.000 đ">
                        @error('amount')
                            <p class="mt-1 text-xs text-rose-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="bank_name" class="block text-xs font-semibold text-slate-300 mb-1">
                            Tên Ngân Hàng <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" id="bank_name" name="bank_name" required value="{{ old('bank_name') }}"
                            class="w-full px-4 py-2.5 rounded-xl bg-slate-950 border border-slate-800 text-white text-xs focus:outline-none focus:ring-2 focus:ring-purple-500"
                            placeholder="Ví dụ: Vietcombank, Techcombank, MB Bank">
                        @error('bank_name')
                            <p class="mt-1 text-xs text-rose-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="account_number" class="block text-xs font-semibold text-slate-300 mb-1">
                            Số Tài Khoản Ngân Hàng <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" id="account_number" name="account_number" required value="{{ old('account_number') }}"
                            class="w-full px-4 py-2.5 rounded-xl bg-slate-950 border border-slate-800 text-white text-xs font-mono focus:outline-none focus:ring-2 focus:ring-purple-500"
                            placeholder="Nhập số tài khoản">
                        @error('account_number')
                            <p class="mt-1 text-xs text-rose-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="account_holder" class="block text-xs font-semibold text-slate-300 mb-1">
                            Họ Và Tên Chủ Tài Khoản <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" id="account_holder" name="account_holder" required value="{{ old('account_holder') }}"
                            class="w-full px-4 py-2.5 rounded-xl bg-slate-950 border border-slate-800 text-white text-xs uppercase focus:outline-none focus:ring-2 focus:ring-purple-500"
                            placeholder="NGUYEN VAN A">
                        @error('account_holder')
                            <p class="mt-1 text-xs text-rose-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit" {{ $availableBalance < 100000 ? 'disabled' : '' }}
                        class="w-full py-3.5 px-4 rounded-xl text-xs font-extrabold text-white bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-500 hover:to-indigo-500 disabled:opacity-40 disabled:cursor-not-allowed shadow-lg shadow-purple-600/30 transition-all flex items-center justify-center space-x-1.5">
                        <i class="fa-solid fa-paper-plane"></i>
                        <span>Gửi Yêu Cầu Rút Tiền</span>
                    </button>
                </form>
            </div>
        </div>

        <!-- Right: Revenue per Event & Withdrawal History -->
        <div class="lg:col-span-2 space-y-8">
            
            <!-- Revenue per Event Breakdown -->
            <div class="bg-slate-900/90 rounded-3xl p-6 sm:p-8 border border-slate-800 shadow-xl">
                <h3 class="text-base font-bold text-white mb-4 flex items-center">
                    <i class="fa-solid fa-chart-line text-indigo-400 mr-2"></i> Báo Cáo Doanh Thu Từng Concert
                </h3>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-slate-300">
                        <thead class="text-xs uppercase bg-slate-950/60 text-slate-400 border-b border-slate-800">
                            <tr>
                                <th class="py-3 px-4">Tên Concert</th>
                                <th class="py-3 px-4 text-center">Vé đã bán</th>
                                <th class="py-3 px-4 text-right">Tổng doanh thu</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-800/60">
                            @forelse($events as $event)
                                @php
                                    $rev = $event->orders->where('status', 'paid')->sum('total_amount');
                                    $sold = $event->orders->where('status', 'paid')->sum(function($o) { return $o->tickets()->count(); });
                                @endphp
                                <tr class="hover:bg-slate-800/40 transition-colors">
                                    <td class="py-3 px-4 font-bold text-white">
                                        {{ $event->title }}
                                        <span class="block text-xs font-normal text-slate-400">{{ $event->event_date->format('d/m/Y') }}</span>
                                    </td>
                                    <td class="py-3 px-4 text-center font-bold text-indigo-400">
                                        {{ $sold }} vé
                                    </td>
                                    <td class="py-3 px-4 text-right font-extrabold text-emerald-400">
                                        {{ number_format($rev, 0, ',', '.') }} đ
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="py-6 text-center text-xs text-slate-500">Chưa có concert nào.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Withdrawal Requests History -->
            <div class="bg-slate-900/90 rounded-3xl p-6 sm:p-8 border border-slate-800 shadow-xl">
                <h3 class="text-base font-bold text-white mb-4 flex items-center">
                    <i class="fa-solid fa-clock-rotate-left text-amber-400 mr-2"></i> Lịch Sử Yêu Cầu Rút Tiền
                </h3>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-slate-300">
                        <thead class="text-xs uppercase bg-slate-950/60 text-slate-400 border-b border-slate-800">
                            <tr>
                                <th class="py-3 px-4">Thời gian</th>
                                <th class="py-3 px-4">Thông tin ngân hàng</th>
                                <th class="py-3 px-4">Số tiền</th>
                                <th class="py-3 px-4 text-center">Trạng thái</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-800/60">
                            @forelse($withdrawals as $w)
                                <tr class="hover:bg-slate-800/40 transition-colors">
                                    <td class="py-3 px-4 text-xs text-slate-400">
                                        {{ $w->created_at->format('d/m/Y H:i') }}
                                    </td>
                                    <td class="py-3 px-4 text-xs">
                                        <p class="font-bold text-white">{{ $w->bank_name }}</p>
                                        <p class="text-slate-400 font-mono">{{ $w->account_number }} ({{ $w->account_holder }})</p>
                                    </td>
                                    <td class="py-3 px-4 font-extrabold text-emerald-400 text-sm">
                                        {{ number_format($w->amount, 0, ',', '.') }} đ
                                    </td>
                                    <td class="py-3 px-4 text-center">
                                        @if($w->status === 'approved')
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-bold bg-emerald-500/20 text-emerald-300 border border-emerald-500/30">
                                                <i class="fa-solid fa-circle-check mr-1"></i> Đã chuyển khoản
                                            </span>
                                        @elseif($w->status === 'pending')
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-bold bg-amber-500/20 text-amber-300 border border-amber-500/30">
                                                <i class="fa-solid fa-hourglass-half mr-1"></i> Chờ duyệt
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-bold bg-rose-500/20 text-rose-300 border border-rose-500/30">
                                                <i class="fa-solid fa-circle-xmark mr-1"></i> Từ chối
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="py-6 text-center text-xs text-slate-500">Chưa có yêu cầu rút tiền nào.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>

    </div>

</div>
@endsection

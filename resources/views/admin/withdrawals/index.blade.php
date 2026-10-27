@extends('layouts.app', ['title' => 'Quản Lý Rút Tiền Đối Tác - MEGATIX Admin'])

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    <div class="mb-8 flex items-center justify-between">
        <div>
            <a href="{{ route('admin.dashboard') }}" class="text-xs font-bold text-slate-400 hover:text-white flex items-center mb-2">
                <i class="fa-solid fa-arrow-left mr-1.5"></i> Quay lại Admin Dashboard
            </a>
            <h1 class="text-3xl font-extrabold text-white tracking-tight flex items-center">
                <i class="fa-solid fa-money-bill-transfer text-emerald-400 mr-3"></i> Quản Lý & Duyệt Lệnh Rút Tiền
            </h1>
            <p class="mt-1 text-sm text-slate-400">Kiểm tra thông tin tài khoản ngân hàng và xác nhận đã giải ngân cho Ban tổ chức.</p>
        </div>
    </div>

    <div class="bg-slate-900/90 rounded-3xl p-6 sm:p-8 border border-slate-800 shadow-xl">
        @if($withdrawals->isEmpty())
            <div class="text-center py-12 text-slate-500 text-xs">
                Chưa có yêu cầu rút tiền nào từ Ban tổ chức.
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-slate-300">
                    <thead class="text-xs uppercase bg-slate-950/60 text-slate-400 border-b border-slate-800">
                        <tr>
                            <th class="py-3 px-4">Thời gian</th>
                            <th class="py-3 px-4">Ban tổ chức</th>
                            <th class="py-3 px-4">Thông tin ngân hàng</th>
                            <th class="py-3 px-4 text-right">Số tiền yêu cầu</th>
                            <th class="py-3 px-4 text-center">Trạng thái</th>
                            <th class="py-3 px-4 text-right">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-800/60">
                        @foreach($withdrawals as $w)
                            <tr class="hover:bg-slate-800/40 transition-colors">
                                <td class="py-3.5 px-4 text-xs text-slate-400 whitespace-nowrap">
                                    {{ $w->created_at->format('d/m/Y H:i') }}
                                </td>
                                <td class="py-3.5 px-4 text-xs">
                                    <p class="font-bold text-white">{{ $w->user->organizerProfile->company_name ?? $w->user->name }}</p>
                                    <p class="text-slate-500">SĐT: {{ $w->user->organizerProfile->phone ?? '-' }}</p>
                                </td>
                                <td class="py-3.5 px-4 text-xs">
                                    <p class="font-bold text-indigo-300">{{ $w->bank_name }}</p>
                                    <p class="font-mono text-white text-sm font-extrabold tracking-wide">{{ $w->account_number }}</p>
                                    <p class="text-slate-400 uppercase text-[11px] font-semibold">{{ $w->account_holder }}</p>
                                </td>
                                <td class="py-3.5 px-4 text-right font-black text-emerald-400 text-base whitespace-nowrap">
                                    {{ number_format($w->amount, 0, ',', '.') }} đ
                                </td>
                                <td class="py-3.5 px-4 text-center whitespace-nowrap">
                                    @if($w->status === 'approved')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded text-[11px] font-bold bg-emerald-500/20 text-emerald-300 border border-emerald-500/30">
                                            <i class="fa-solid fa-circle-check mr-1"></i> Đã giải ngân
                                        </span>
                                    @elseif($w->status === 'pending')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded text-[11px] font-bold bg-amber-500/20 text-amber-300 border border-amber-500/30">
                                            <i class="fa-solid fa-hourglass-half mr-1"></i> Chờ chuyển khoản
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded text-[11px] font-bold bg-rose-500/20 text-rose-300 border border-rose-500/30">
                                            <i class="fa-solid fa-circle-xmark mr-1"></i> Bị từ chối
                                        </span>
                                    @endif
                                </td>
                                <td class="py-3.5 px-4 text-right space-x-2 whitespace-nowrap">
                                    @if($w->status === 'pending')
                                        <form action="{{ route('admin.withdrawals.approve', $w->id) }}" method="POST" class="inline" onsubmit="return confirm('Xác nhận bạn đã chuyển khoản số tiền này thành công?')">
                                            @csrf
                                            <button type="submit" class="px-3 py-1.5 rounded-lg bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-bold transition-colors">
                                                Đã Chuyển Khoản
                                            </button>
                                        </form>

                                        <form action="{{ route('admin.withdrawals.reject', $w->id) }}" method="POST" class="inline" onsubmit="return confirm('Bạn có chắc chắn muốn từ chối lệnh rút tiền này?')">
                                            @csrf
                                            <button type="submit" class="px-3 py-1.5 rounded-lg bg-rose-950/60 border border-rose-500/40 text-rose-300 hover:bg-rose-900/60 text-xs font-bold transition-colors">
                                                Từ Chối
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-xs text-slate-500 italic">Đã xử lý</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $withdrawals->links() }}
            </div>
        @endif
    </div>

</div>
@endsection

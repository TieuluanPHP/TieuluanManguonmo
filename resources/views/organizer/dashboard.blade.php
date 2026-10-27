@extends('layouts.app', ['title' => 'Dashboard Ban Tổ Chức - MEGATIX'])

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    <!-- Header & Action -->
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-8">
        <div>
            <div class="flex items-center space-x-2.5">
                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold uppercase bg-purple-500/20 text-purple-300 border border-purple-500/30">
                    Đối tác chính thức
                </span>
                <span class="text-xs text-slate-400">• {{ Auth::user()->organizerProfile->company_name ?? Auth::user()->name }}</span>
            </div>
            <h1 class="text-3xl font-extrabold text-white tracking-tight mt-1 flex items-center">
                <i class="fa-solid fa-gauge-high text-purple-400 mr-3"></i> Quản Lý Concert & Doanh Thu
            </h1>
        </div>

        <div class="flex items-center space-x-3">
            <a href="{{ route('organizer.finance') }}" class="px-5 py-2.5 rounded-xl bg-slate-900 border border-slate-700 hover:border-slate-600 text-slate-200 text-xs font-bold transition-all flex items-center space-x-1.5 shadow">
                <i class="fa-solid fa-building-columns text-emerald-400"></i>
                <span>Rút Tiền Về Ngân Hàng</span>
            </a>
            <a href="{{ route('organizer.events.create') }}" class="px-6 py-2.5 rounded-xl bg-gradient-to-r from-purple-600 via-fuchsia-600 to-indigo-600 hover:from-purple-500 hover:to-indigo-500 text-white text-xs font-extrabold shadow-lg shadow-fuchsia-600/30 transition-all flex items-center space-x-1.5">
                <i class="fa-solid fa-plus"></i>
                <span>Tạo Concert Mới</span>
            </a>
        </div>
    </div>

    <!-- Stat Cards Overview -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
        
        <div class="p-6 rounded-3xl bg-slate-900/90 border border-slate-800 shadow-xl space-y-2">
            <div class="flex items-center justify-between text-xs font-semibold text-slate-400">
                <span>Tổng Sự Kiện Đã Đăng</span>
                <i class="fa-solid fa-calendar-days text-purple-400 text-base"></i>
            </div>
            <p class="text-3xl font-black text-white">{{ $totalEvents }}</p>
            <p class="text-xs text-slate-400"><span class="text-emerald-400 font-bold">{{ $approvedEvents }}</span> đã duyệt • <span class="text-amber-400 font-bold">{{ $pendingEvents }}</span> chờ duyệt</p>
        </div>

        <div class="p-6 rounded-3xl bg-slate-900/90 border border-slate-800 shadow-xl space-y-2">
            <div class="flex items-center justify-between text-xs font-semibold text-slate-400">
                <span>Tổng Vé Đã Bán</span>
                <i class="fa-solid fa-ticket text-indigo-400 text-base"></i>
            </div>
            <p class="text-3xl font-black text-indigo-400">{{ number_format($totalTicketsSold) }}</p>
            <p class="text-xs text-slate-400">Toàn bộ khách hàng đã thanh toán</p>
        </div>

        <div class="p-6 rounded-3xl bg-slate-900/90 border border-slate-800 shadow-xl space-y-2">
            <div class="flex items-center justify-between text-xs font-semibold text-slate-400">
                <span>Tổng Doanh Thu Vé (100%)</span>
                <i class="fa-solid fa-sack-dollar text-emerald-400 text-base"></i>
            </div>
            <p class="text-3xl font-black text-emerald-400">{{ number_format($totalRevenue, 0, ',', '.') }} <span class="text-sm font-medium text-emerald-300">đ</span></p>
            <p class="text-xs text-slate-400">Áp dụng 0% phí sàn nền tảng</p>
        </div>

        <div class="p-6 rounded-3xl bg-slate-900/90 border border-slate-800 shadow-xl space-y-2">
            <div class="flex items-center justify-between text-xs font-semibold text-slate-400">
                <span>Doanh Thu Khả Dụng Để Rút</span>
                <i class="fa-solid fa-money-bill-transfer text-amber-400 text-base"></i>
            </div>
            <p class="text-3xl font-black text-amber-400">{{ number_format($availableBalance, 0, ',', '.') }} <span class="text-sm font-medium text-amber-300">đ</span></p>
            <a href="{{ route('organizer.finance') }}" class="text-xs text-fuchsia-400 hover:underline font-bold inline-block">Yêu cầu rút tiền ngay &rarr;</a>
        </div>

    </div>

    <!-- Events List Section -->
    <div class="bg-slate-900/90 rounded-3xl p-6 sm:p-8 border border-slate-800 shadow-2xl">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-xl font-bold text-white flex items-center">
                <i class="fa-solid fa-list-check text-indigo-400 mr-2.5"></i> Danh Sách Concert Của Bạn
            </h2>
            <span class="text-xs text-slate-400">{{ $events->count() }} concert</span>
        </div>

        @if($events->isEmpty())
            <div class="text-center py-16 text-slate-500">
                <i class="fa-solid fa-calendar-plus text-5xl mb-4 text-slate-700"></i>
                <h3 class="text-base font-bold text-white">Bạn chưa tạo concert nào</h3>
                <p class="text-xs text-slate-400 mt-1 mb-4">Hãy bắt đầu tạo sự kiện đầu tiên để mở bán vé!</p>
                <a href="{{ route('organizer.events.create') }}" class="inline-block px-5 py-2.5 rounded-xl bg-purple-600 text-white text-xs font-bold hover:bg-purple-500">
                    + Tạo Concert Ngay
                </a>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-slate-300">
                    <thead class="text-xs uppercase bg-slate-950/60 text-slate-400 border-b border-slate-800">
                        <tr>
                            <th class="py-3 px-4">Concert</th>
                            <th class="py-3 px-4">Thể loại / Loại vé</th>
                            <th class="py-3 px-4">Thời gian</th>
                            <th class="py-3 px-4 text-center">Trạng thái</th>
                            <th class="py-3 px-4 text-center">Đã bán</th>
                            <th class="py-3 px-4 text-right">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-800/60">
                        @foreach($events as $event)
                            @php
                                $soldCount = $event->orders->where('status', 'paid')->sum(function($o) { return $o->tickets()->count(); });
                            @endphp
                            <tr class="hover:bg-slate-800/40 transition-colors">
                                <td class="py-4 px-4 flex items-center space-x-3">
                                    <img src="{{ $event->poster_url }}" alt="{{ $event->title }}" class="w-12 h-12 rounded-xl object-cover shrink-0 border border-slate-700">
                                    <div>
                                        <a href="{{ route('events.show', $event->slug) }}" class="font-bold text-white hover:text-fuchsia-400 transition-colors line-clamp-1">
                                            {{ $event->title }}
                                        </a>
                                        <p class="text-xs text-slate-400 truncate max-w-xs">{{ $event->venue->name }}</p>
                                    </div>
                                </td>
                                <td class="py-4 px-4 whitespace-nowrap">
                                    <span class="text-xs font-semibold text-slate-300 block">{{ $event->category->name }}</span>
                                    <span class="inline-block mt-0.5 px-2 py-0.5 rounded text-[10px] font-bold uppercase {{ $event->isSeated() ? 'bg-indigo-500/20 text-indigo-300' : 'bg-emerald-500/20 text-emerald-300' }}">
                                        {{ $event->isSeated() ? 'Ghế ngồi' : 'Vé đứng' }}
                                    </span>
                                </td>
                                <td class="py-4 px-4 text-xs text-slate-300 whitespace-nowrap">
                                    {{ $event->event_date->format('H:i - d/m/Y') }}
                                </td>
                                <td class="py-4 px-4 text-center whitespace-nowrap">
                                    @if($event->status === 'approved')
                                        @if($event->isPast())
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-slate-800 text-slate-300 border border-slate-700">
                                                <i class="fa-solid fa-clock-rotate-left mr-1.5 text-amber-400"></i> Đã kết thúc
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-500/20 text-emerald-300 border border-emerald-500/30">
                                                <i class="fa-solid fa-circle-check mr-1.5"></i> Đang mở bán
                                            </span>
                                        @endif
                                    @elseif($event->status === 'pending')
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-amber-500/20 text-amber-300 border border-amber-500/30">
                                            <i class="fa-solid fa-clock mr-1.5"></i> Chờ duyệt
                                        </span>
                                    @elseif($event->status === 'rejected')
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-rose-500/20 text-rose-300 border border-rose-500/30" title="{{ $event->admin_rejection_reason }}">
                                            <i class="fa-solid fa-circle-xmark mr-1.5"></i> Bị từ chối
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-slate-700 text-slate-300">
                                            {{ $event->status }}
                                        </span>
                                    @endif
                                </td>
                                <td class="py-4 px-4 text-center font-bold text-white whitespace-nowrap">
                                    {{ $soldCount }} vé
                                </td>
                                <td class="py-4 px-4 text-right whitespace-nowrap space-x-2">
                                    <a href="{{ route('events.show', $event->slug) }}" target="_blank" class="px-2.5 py-1.5 rounded-lg bg-slate-800 hover:bg-slate-700 text-slate-300 text-xs font-semibold" title="Xem trên web">
                                        <i class="fa-solid fa-arrow-up-right-from-square"></i>
                                    </a>
                                    <a href="{{ route('organizer.events.edit', $event->id) }}" class="px-2.5 py-1.5 rounded-lg bg-purple-950/60 border border-purple-500/40 text-purple-300 text-xs font-semibold hover:bg-purple-900/60" title="Chỉnh sửa">
                                        <i class="fa-solid fa-pen-to-square"></i> Sửa
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

</div>
@endsection

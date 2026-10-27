@extends('layouts.app', ['title' => 'Bảng Điều Khiển Quản Trị - MEGATIX Admin'])

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    
    <!-- Top Bar -->
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-8">
        <div>
            <div class="flex items-center space-x-2">
                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold uppercase bg-rose-500/20 text-rose-300 border border-rose-500/30">
                    Super Admin
                </span>
                <span class="text-xs text-slate-400">• Quản trị toàn hệ thống</span>
            </div>
            <h1 class="text-3xl font-extrabold text-white tracking-tight mt-1 flex items-center">
                <i class="fa-solid fa-shield-halved text-rose-400 mr-3"></i> Trung Tâm Kiểm Soát & Điều Hành
            </h1>
        </div>

        <!-- Quick Admin Links -->
        <div class="flex flex-wrap items-center gap-2">
            <a href="{{ route('admin.events.index', ['status' => 'pending']) }}" class="px-4 py-2 rounded-xl bg-slate-900 border border-slate-700 hover:border-slate-600 text-xs font-bold text-slate-200 transition-colors flex items-center space-x-1.5">
                <i class="fa-solid fa-calendar-check text-indigo-400"></i>
                <span>Duyệt Concert ({{ $pendingEvents }})</span>
            </a>
            <a href="{{ route('admin.organizers.index') }}" class="px-4 py-2 rounded-xl bg-slate-900 border border-slate-700 hover:border-slate-600 text-xs font-bold text-slate-200 transition-colors flex items-center space-x-1.5">
                <i class="fa-solid fa-handshake text-purple-400"></i>
                <span>Duyệt Đối Tác ({{ $pendingPartners }})</span>
            </a>
            <a href="{{ route('admin.withdrawals.index') }}" class="px-4 py-2 rounded-xl bg-slate-900 border border-slate-700 hover:border-slate-600 text-xs font-bold text-slate-200 transition-colors flex items-center space-x-1.5">
                <i class="fa-solid fa-money-bill-transfer text-emerald-400"></i>
                <span>Duyệt Rút Tiền ({{ $pendingWithdrawalsCount }})</span>
            </a>
            <a href="{{ route('admin.taxonomies.index') }}" class="px-4 py-2 rounded-xl bg-slate-900 border border-slate-700 hover:border-slate-600 text-xs font-bold text-slate-200 transition-colors flex items-center space-x-1.5">
                <i class="fa-solid fa-folder-plus text-fuchsia-400"></i>
                <span>Thêm Danh Mục</span>
            </a>
        </div>
    </div>

    <!-- Stat Metrics -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
        
        <div class="p-6 rounded-3xl bg-slate-900/90 border border-slate-800 shadow-xl space-y-2">
            <div class="flex items-center justify-between text-xs font-semibold text-slate-400">
                <span>Tổng Dòng Tiền Đã Bán Vé</span>
                <i class="fa-solid fa-chart-pie text-emerald-400 text-base"></i>
            </div>
            <p class="text-3xl font-black text-emerald-400">{{ number_format($totalGrossVolume, 0, ',', '.') }} <span class="text-sm font-medium text-emerald-300">đ</span></p>
            <p class="text-xs text-slate-400">Tổng doanh thu vé qua ví nội bộ</p>
        </div>

        <div class="p-6 rounded-3xl bg-slate-900/90 border border-slate-800 shadow-xl space-y-2">
            <div class="flex items-center justify-between text-xs font-semibold text-slate-400">
                <span>Tổng Dòng Tiền Nạp Ví</span>
                <i class="fa-solid fa-wallet text-indigo-400 text-base"></i>
            </div>
            <p class="text-3xl font-black text-indigo-400">{{ number_format($totalDeposits, 0, ',', '.') }} <span class="text-sm font-medium text-indigo-300">đ</span></p>
            <p class="text-xs text-slate-400">Tổng số dư khách nạp vào hệ thống</p>
        </div>

        <div class="p-6 rounded-3xl bg-slate-900/90 border border-slate-800 shadow-xl space-y-2">
            <div class="flex items-center justify-between text-xs font-semibold text-slate-400">
                <span>Concert Chờ Phê Duyệt</span>
                <i class="fa-solid fa-clock text-amber-400 text-base"></i>
            </div>
            <p class="text-3xl font-black text-amber-400">{{ $pendingEvents }}</p>
            <a href="{{ route('admin.events.index', ['status' => 'pending']) }}" class="text-xs text-amber-300 hover:underline font-bold inline-block">Xem danh sách cần duyệt &rarr;</a>
        </div>

        <div class="p-6 rounded-3xl bg-slate-900/90 border border-slate-800 shadow-xl space-y-2">
            <div class="flex items-center justify-between text-xs font-semibold text-slate-400">
                <span>Đối Tác Mới Chờ Duyệt</span>
                <i class="fa-solid fa-user-clock text-purple-400 text-base"></i>
            </div>
            <p class="text-3xl font-black text-purple-400">{{ $pendingPartners }}</p>
            <a href="{{ route('admin.organizers.index') }}" class="text-xs text-purple-300 hover:underline font-bold inline-block">Xem hồ sơ đăng ký &rarr;</a>
        </div>

    </div>

    <!-- Pending Sections -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        
        <!-- Recent Pending Concerts -->
        <div class="bg-slate-900/90 rounded-3xl p-6 border border-slate-800 shadow-xl space-y-4">
            <div class="flex items-center justify-between border-b border-slate-800 pb-3">
                <h3 class="text-base font-bold text-white flex items-center">
                    <i class="fa-solid fa-calendar-check text-amber-400 mr-2"></i> Concert Chờ Phê Duyệt
                </h3>
                <a href="{{ route('admin.events.index', ['status' => 'pending']) }}" class="text-xs text-fuchsia-400 hover:underline font-bold">Xem tất cả</a>
            </div>

            @if($recentPendingEvents->isEmpty())
                <div class="py-10 text-center text-xs text-slate-500">
                    <i class="fa-solid fa-circle-check text-2xl text-slate-700 mb-2 block"></i>
                    Không có concert nào đang chờ duyệt.
                </div>
            @else
                <div class="space-y-3">
                    @foreach($recentPendingEvents as $ev)
                        <div class="p-3.5 rounded-2xl bg-slate-950 border border-slate-800/80 flex items-center justify-between space-x-3">
                            <div class="flex items-center space-x-3 min-w-0">
                                <img src="{{ $ev->poster_url }}" alt="" class="w-10 h-10 rounded-lg object-cover shrink-0">
                                <div class="min-w-0">
                                    <p class="text-xs font-bold text-white truncate">{{ $ev->title }}</p>
                                    <p class="text-[11px] text-slate-400 truncate">BTC: {{ $ev->organizer->organizerProfile->company_name ?? $ev->organizer->name }}</p>
                                </div>
                            </div>
                            <div class="flex items-center space-x-1.5 shrink-0">
                                <a href="{{ route('admin.events.preview', $ev->id) }}" class="px-2.5 py-1 rounded-lg bg-slate-800 hover:bg-slate-700 text-white text-xs font-semibold">
                                    Xem trước
                                </a>
                                <form action="{{ route('admin.events.approve', $ev->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="px-3 py-1 rounded-lg bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-bold">
                                        Duyệt
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Recent Pending Organizer Requests -->
        <div class="bg-slate-900/90 rounded-3xl p-6 border border-slate-800 shadow-xl space-y-4">
            <div class="flex items-center justify-between border-b border-slate-800 pb-3">
                <h3 class="text-base font-bold text-white flex items-center">
                    <i class="fa-solid fa-handshake text-purple-400 mr-2"></i> Đơn Xin Làm Đối Tác Chờ Duyệt
                </h3>
                <a href="{{ route('admin.organizers.index') }}" class="text-xs text-fuchsia-400 hover:underline font-bold">Xem tất cả</a>
            </div>

            @if($recentPendingPartners->isEmpty())
                <div class="py-10 text-center text-xs text-slate-500">
                    <i class="fa-solid fa-circle-check text-2xl text-slate-700 mb-2 block"></i>
                    Không có đơn xin làm đối tác nào đang chờ.
                </div>
            @else
                <div class="space-y-3">
                    @foreach($recentPendingPartners as $p)
                        <div class="p-3.5 rounded-2xl bg-slate-950 border border-slate-800/80 flex items-center justify-between space-x-3">
                            <div class="min-w-0">
                                <p class="text-xs font-bold text-white truncate">{{ $p->company_name }}</p>
                                <p class="text-[11px] text-slate-400">SĐT: {{ $p->phone }} • TK: {{ $p->user->name }}</p>
                            </div>
                            <div class="flex items-center space-x-1.5 shrink-0">
                                <form action="{{ route('admin.organizers.approve', $p->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="px-3 py-1 rounded-lg bg-purple-600 hover:bg-purple-500 text-white text-xs font-bold">
                                        Duyệt Đối Tác
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

    </div>

</div>
@endsection

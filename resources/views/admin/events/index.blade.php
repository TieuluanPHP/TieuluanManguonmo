@extends('layouts.app', ['title' => 'Kiểm Duyệt Concert - MEGATIX Admin'])

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    <div class="mb-8 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <a href="{{ route('admin.dashboard') }}" class="text-xs font-bold text-slate-400 hover:text-white flex items-center mb-2">
                <i class="fa-solid fa-arrow-left mr-1.5"></i> Quay lại Admin Dashboard
            </a>
            <h1 class="text-3xl font-extrabold text-white tracking-tight flex items-center">
                <i class="fa-solid fa-calendar-check text-indigo-400 mr-3"></i> Kiểm Duyệt & Quản Lý Concert
            </h1>
        </div>

        <!-- Filter Status Tabs -->
        <div class="flex items-center space-x-1 p-1 rounded-xl bg-slate-900 border border-slate-800 text-xs font-semibold">
            <a href="{{ route('admin.events.index', ['status' => 'all']) }}" class="px-3 py-1.5 rounded-lg transition-colors {{ $status === 'all' ? 'bg-fuchsia-600 text-white' : 'text-slate-400 hover:text-white' }}">
                Tất cả
            </a>
            <a href="{{ route('admin.events.index', ['status' => 'pending']) }}" class="px-3 py-1.5 rounded-lg transition-colors {{ $status === 'pending' ? 'bg-amber-600 text-white' : 'text-slate-400 hover:text-white' }}">
                Chờ duyệt
            </a>
            <a href="{{ route('admin.events.index', ['status' => 'approved']) }}" class="px-3 py-1.5 rounded-lg transition-colors {{ $status === 'approved' ? 'bg-emerald-600 text-white' : 'text-slate-400 hover:text-white' }}">
                Đang mở bán
            </a>
            <a href="{{ route('admin.events.index', ['status' => 'rejected']) }}" class="px-3 py-1.5 rounded-lg transition-colors {{ $status === 'rejected' ? 'bg-rose-600 text-white' : 'text-slate-400 hover:text-white' }}">
                Bị từ chối
            </a>
            <a href="{{ route('admin.events.index', ['status' => 'cancelled']) }}" class="px-3 py-1.5 rounded-lg transition-colors {{ $status === 'cancelled' ? 'bg-slate-700 text-white' : 'text-slate-400 hover:text-white' }}">
                Đã hủy
            </a>
        </div>
    </div>

    <div class="bg-slate-900/90 rounded-3xl p-6 sm:p-8 border border-slate-800 shadow-xl">
        @if($events->isEmpty())
            <div class="text-center py-12 text-slate-500 text-xs">
                Không tìm thấy sự kiện nào trong danh mục này.
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-slate-300">
                    <thead class="text-xs uppercase bg-slate-950/60 text-slate-400 border-b border-slate-800">
                        <tr>
                            <th class="py-3 px-4">Tên Concert</th>
                            <th class="py-3 px-4">Ban tổ chức</th>
                            <th class="py-3 px-4">Thời gian / Địa điểm</th>
                            <th class="py-3 px-4 text-center">Trạng thái</th>
                            <th class="py-3 px-4 text-right">Thao tác duyệt</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-800/60">
                        @foreach($events as $event)
                            <tr class="hover:bg-slate-800/40 transition-colors">
                                <td class="py-4 px-4 flex items-center space-x-3">
                                    <img src="{{ $event->poster_url }}" alt="" class="w-12 h-12 rounded-xl object-cover shrink-0 border border-slate-700">
                                    <div>
                                        <p class="font-bold text-white line-clamp-1">{{ $event->title }}</p>
                                        <span class="text-xs text-fuchsia-400">{{ $event->category->name }} • {{ $event->isSeated() ? 'Ghế ngồi' : 'Vé đứng' }}</span>
                                    </div>
                                </td>
                                <td class="py-4 px-4 text-xs">
                                    <p class="font-bold text-slate-200">{{ $event->organizer->organizerProfile->company_name ?? $event->organizer->name }}</p>
                                    <p class="text-slate-500 font-mono">{{ $event->organizer->organizerProfile->phone ?? '-' }}</p>
                                </td>
                                <td class="py-4 px-4 text-xs">
                                    <p class="text-slate-300 font-medium">{{ $event->event_date->format('H:i - d/m/Y') }}</p>
                                    <p class="text-slate-400 truncate max-w-xs">{{ $event->venue->name }}</p>
                                </td>
                                <td class="py-4 px-4 text-center whitespace-nowrap">
                                    @if($event->status === 'approved')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded text-[11px] font-bold bg-emerald-500/20 text-emerald-300 border border-emerald-500/30">
                                            <i class="fa-solid fa-circle-check mr-1"></i> Đang mở bán
                                        </span>
                                    @elseif($event->status === 'pending')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded text-[11px] font-bold bg-amber-500/20 text-amber-300 border border-amber-500/30">
                                            <i class="fa-solid fa-hourglass-half mr-1"></i> Chờ duyệt
                                        </span>
                                    @elseif($event->status === 'rejected')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded text-[11px] font-bold bg-rose-500/20 text-rose-300 border border-rose-500/30" title="{{ $event->admin_rejection_reason }}">
                                            <i class="fa-solid fa-circle-xmark mr-1"></i> Bị từ chối
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded text-[11px] font-bold bg-slate-700 text-slate-300">
                                            {{ $event->status }}
                                        </span>
                                    @endif
                                </td>
                                <td class="py-4 px-4 text-right whitespace-nowrap space-x-1.5">
                                    <a href="{{ route('admin.events.preview', $event->id) }}" class="px-2.5 py-1.5 rounded-lg bg-slate-800 hover:bg-slate-700 text-slate-200 text-xs font-semibold" title="Xem trước">
                                        <i class="fa-solid fa-eye"></i> Xem trước
                                    </a>

                                    @if($event->status === 'pending' || $event->status === 'rejected')
                                        <form action="{{ route('admin.events.approve', $event->id) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="px-3 py-1.5 rounded-lg bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-bold">
                                                Duyệt
                                            </button>
                                        </form>
                                    @endif

                                    @if($event->status === 'pending')
                                        <form action="{{ route('admin.events.reject', $event->id) }}" method="POST" class="inline" onsubmit="return confirmRejectEvent(this)">
                                            @csrf
                                            <input type="hidden" name="reason" value="">
                                            <button type="submit" class="px-2.5 py-1.5 rounded-lg bg-rose-950/60 border border-rose-500/40 text-rose-300 hover:bg-rose-900/60 text-xs font-semibold">
                                                Từ chối
                                            </button>
                                        </form>
                                    @endif

                                    @if($event->status === 'approved')
                                        <form action="{{ route('admin.events.cancel', $event->id) }}" method="POST" class="inline" onsubmit="return confirm('CẢNH BÁO: Hủy concert sẽ TỰ ĐỘNG HOÀN TIỀN 100% về ví của tất cả khách hàng đã mua vé. Bạn có chắc chắn muốn hủy không?')">
                                            @csrf
                                            <button type="submit" class="px-2.5 py-1.5 rounded-lg bg-rose-900/40 border border-rose-500/50 text-rose-300 hover:bg-rose-900/70 text-xs font-bold" title="Hủy sự kiện và hoàn tiền ví">
                                                <i class="fa-solid fa-ban mr-1"></i> Hủy & Hoàn Tiền
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-6">
                {{ $events->links() }}
            </div>
        @endif
    </div>

</div>

<script>
    function confirmRejectEvent(form) {
        const reason = prompt('Vui lòng nhập lý do từ chối concert này:');
        if (reason === null || reason.trim() === '') return false;
        form.querySelector('input[name="reason"]').value = reason;
        return true;
    }
</script>
@endsection

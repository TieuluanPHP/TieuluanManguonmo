@extends('layouts.app', ['title' => 'Xem Trước Concert - ' . $event->title])

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    <!-- Top Admin Bar -->
    <div class="mb-8 p-6 rounded-3xl bg-slate-900 border border-slate-800 flex flex-col sm:flex-row items-center justify-between gap-4 shadow-2xl">
        <div>
            <a href="{{ route('admin.events.index') }}" class="text-xs font-bold text-slate-400 hover:text-white flex items-center mb-1">
                <i class="fa-solid fa-arrow-left mr-1.5"></i> Quay lại Danh sách duyệt
            </a>
            <h2 class="text-xl font-black text-white">Chế Độ Xem Trước Của Quản Trị Viên</h2>
            <p class="text-xs text-slate-400">Đăng tải bởi: <strong>{{ $event->organizer->organizerProfile->company_name ?? $event->organizer->name }}</strong> (SĐT: {{ $event->organizer->organizerProfile->phone ?? '-' }})</p>
        </div>

        <div class="flex items-center space-x-3 shrink-0">
            @if($event->status !== 'approved')
                <form action="{{ route('admin.events.approve', $event->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="px-6 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-extrabold shadow-lg shadow-emerald-600/30 transition-all">
                        <i class="fa-solid fa-check mr-1.5"></i> Phê Duyệt Mở Bán
                    </button>
                </form>
            @endif

            @if($event->status !== 'rejected')
                <form action="{{ route('admin.events.reject', $event->id) }}" method="POST" onsubmit="return confirmRejectPreview(this)">
                    @csrf
                    <input type="hidden" name="reason" value="">
                    <button type="submit" class="px-5 py-2.5 rounded-xl bg-rose-950/80 border border-rose-500/50 text-rose-300 hover:bg-rose-900/80 text-xs font-bold transition-all">
                        <i class="fa-solid fa-xmark mr-1.5"></i> Từ Chối Duyệt
                    </button>
                </form>
            @endif
        </div>
    </div>

    <!-- Event Content Preview -->
    <div class="bg-slate-900/90 rounded-3xl overflow-hidden border border-slate-800 shadow-2xl">
        <div class="h-72 w-full relative">
            <img src="{{ $event->poster_url }}" alt="" class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-gradient-to-t from-slate-900 via-slate-900/40 to-transparent"></div>
            <div class="absolute bottom-6 left-6">
                <span class="px-3 py-1 rounded-full text-xs font-bold bg-fuchsia-600 text-white uppercase">
                    {{ $event->category->name }}
                </span>
                <h1 class="text-3xl font-black text-white mt-2">{{ $event->title }}</h1>
            </div>
        </div>

        <div class="p-8 space-y-6">
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-xs text-slate-300">
                <div class="p-4 rounded-xl bg-slate-950 border border-slate-800">
                    <p class="text-slate-500 uppercase font-bold text-[10px]">Thời gian</p>
                    <p class="text-white font-bold text-sm mt-1">{{ $event->event_date->format('H:i - d/m/Y') }}</p>
                </div>
                <div class="p-4 rounded-xl bg-slate-950 border border-slate-800">
                    <p class="text-slate-500 uppercase font-bold text-[10px]">Địa điểm</p>
                    <p class="text-white font-bold text-sm mt-1">{{ $event->venue->name }}</p>
                </div>
                <div class="p-4 rounded-xl bg-slate-950 border border-slate-800">
                    <p class="text-slate-500 uppercase font-bold text-[10px]">Hình thức vé</p>
                    <p class="text-white font-bold text-sm mt-1">{{ $event->isSeated() ? 'Concert Ngồi (Sơ đồ ghế)' : 'Concert Đứng (GA)' }}</p>
                </div>
            </div>

            <div>
                <h3 class="text-base font-bold text-white mb-2">Mô Tả Sự Kiện</h3>
                <div class="text-sm text-slate-300 leading-relaxed whitespace-pre-line bg-slate-950 p-4 rounded-xl border border-slate-800">
                    {{ $event->description }}
                </div>
            </div>

            <div>
                <h3 class="text-base font-bold text-white mb-3">Cấu Hình Hạng Vé & Sơ Đồ Ghế</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    @foreach($event->ticketTypes as $type)
                        <div class="p-4 rounded-xl bg-slate-950 border border-slate-800 flex items-center justify-between text-xs">
                            <div>
                                <span class="font-bold text-white text-sm">{{ $type->name }}</span>
                                <span class="block text-slate-400 mt-0.5">Dãy: {{ $type->zone_code ?? 'Đứng' }} • Tổng số: {{ $type->total_quantity }} vé</span>
                            </div>
                            <span class="text-base font-black text-emerald-400">{{ number_format($type->price, 0, ',', '.') }} đ</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

</div>

<script>
    function confirmRejectPreview(form) {
        const reason = prompt('Vui lòng nhập lý do từ chối:');
        if (reason === null || reason.trim() === '') return false;
        form.querySelector('input[name="reason"]').value = reason;
        return true;
    }
</script>
@endsection

@extends('layouts.app', ['title' => 'Vé Của Tôi - MEGATIX'])

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="text-3xl font-extrabold text-white tracking-tight flex items-center">
                <i class="fa-solid fa-ticket text-indigo-400 mr-3"></i> Vé Concert Của Tôi
            </h1>
            <p class="mt-1 text-sm text-slate-400">
                Danh sách các vé điện tử đã thanh toán và cấp mã thành công vào tài khoản của bạn.
            </p>
        </div>
        <a href="{{ route('home') }}" class="px-5 py-2.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-200 text-xs font-bold transition-colors flex items-center space-x-1.5">
            <i class="fa-solid fa-plus"></i>
            <span>Săn Thêm Vé Mới</span>
        </a>
    </div>

    @if($tickets->isEmpty())
        <div class="text-center py-20 bg-slate-900/60 rounded-3xl border border-slate-800">
            <div class="w-16 h-16 rounded-full bg-slate-800 text-slate-500 flex items-center justify-center mx-auto text-2xl mb-4">
                <i class="fa-solid fa-ticket-simple"></i>
            </div>
            <h3 class="text-lg font-bold text-white">Bạn chưa sở hữu vé concert nào</h3>
            <p class="text-sm text-slate-400 mt-1 max-w-sm mx-auto">
                Hãy khám phá các concert đỉnh cao đang mở bán và nạp ví để sở hữu vị trí ngồi đẹp nhất ngay hôm nay!
            </p>
            <a href="{{ route('home') }}" class="inline-block mt-6 px-6 py-3 rounded-2xl bg-gradient-to-r from-fuchsia-600 to-indigo-600 text-white text-xs font-bold shadow-lg shadow-fuchsia-600/30 hover:shadow-fuchsia-600/50 transition-all">
                Khám Phá Concert Ngay
            </a>
        </div>
    @else
        <div class="space-y-6">
            @foreach($tickets as $ticket)
                @php
                    $event = $ticket->order->event;
                @endphp
                <div class="relative overflow-hidden bg-slate-900/90 rounded-3xl border border-slate-800 hover:border-indigo-500/50 shadow-xl transition-all duration-300 flex flex-col md:flex-row">
                    
                    <!-- Left: Event Visual & Date -->
                    <div class="md:w-64 h-48 md:h-auto relative overflow-hidden shrink-0">
                        <img src="{{ $event->poster_url }}" alt="{{ $event->title }}" class="w-full h-full object-cover">
                        <div class="absolute inset-0 bg-gradient-to-t md:bg-gradient-to-r from-slate-900 via-slate-900/40 to-transparent"></div>
                        <div class="absolute top-3 left-3">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-extrabold uppercase bg-slate-950/80 text-white backdrop-blur-md">
                                {{ $ticket->order->event->category->name ?? 'Concert' }}
                            </span>
                        </div>
                    </div>

                    <!-- Center: Concert Details -->
                    <div class="p-6 flex-1 flex flex-col justify-between space-y-4">
                        <div>
                            <span class="text-[11px] font-extrabold uppercase tracking-wider text-fuchsia-400">
                                {{ $ticket->ticketType->name }}
                            </span>
                            <h3 class="text-xl font-bold text-white mt-1">
                                <a href="{{ route('events.show', $event->slug) }}" class="hover:text-fuchsia-400 transition-colors">
                                    {{ $event->title }}
                                </a>
                            </h3>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 mt-3 text-xs text-slate-300">
                                <p class="flex items-center">
                                    <i class="fa-solid fa-calendar text-indigo-400 w-5"></i>
                                    <span>{{ $event->event_date->format('H:i - d/m/Y') }}</span>
                                </p>
                                <p class="flex items-center truncate">
                                    <i class="fa-solid fa-location-dot text-rose-400 w-5"></i>
                                    <span class="truncate" title="{{ $event->venue->name }}">{{ $event->venue->name }}</span>
                                </p>
                            </div>
                        </div>

                        <!-- Seat Position & Order Info -->
                        <div class="pt-3 border-t border-slate-800/80 flex flex-wrap items-center gap-4 text-xs">
                            <div class="flex items-center space-x-2">
                                <span class="text-slate-400">Vị trí:</span>
                                @if($ticket->seat)
                                    <span class="px-2.5 py-1 rounded-lg bg-indigo-600/20 text-indigo-300 font-extrabold border border-indigo-500/30">
                                        <i class="fa-solid fa-chair mr-1"></i> Ghế: {{ $ticket->seat->seat_code }} (Hàng {{ $ticket->seat->row_number }})
                                    </span>
                                @else
                                    <span class="px-2.5 py-1 rounded-lg bg-emerald-600/20 text-emerald-300 font-extrabold border border-emerald-500/30">
                                        <i class="fa-solid fa-users mr-1"></i> Vé Đứng Tự Do (GA)
                                    </span>
                                @endif
                            </div>

                            <div class="text-slate-400">
                                Mã đơn: <span class="font-bold text-white">#{{ $ticket->order_id }}</span>
                            </div>
                            <div class="text-slate-400">
                                Ngày mua: <span class="font-bold text-white">{{ $ticket->created_at->format('d/m/Y H:i') }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Right: Large Ticket Code Display (Perforation divider) -->
                    <div class="md:w-72 p-6 bg-slate-950/60 border-t md:border-t-0 md:border-l border-slate-800 flex flex-col justify-between items-center text-center shrink-0 space-y-4">
                        <div>
                            <span class="text-[10px] font-extrabold tracking-widest uppercase text-slate-500">MÃ VÉ ĐIỆN TỬ</span>
                            <div class="mt-2 py-2 px-4 rounded-2xl bg-slate-900 border-2 border-dashed border-fuchsia-500/40 shadow-inner">
                                <span class="text-2xl font-black text-fuchsia-400 font-mono tracking-wider">
                                    {{ $ticket->ticket_code }}
                                </span>
                            </div>
                        </div>

                        <div class="space-y-1">
                            @if($ticket->isValid())
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-extrabold bg-emerald-500/20 text-emerald-300 border border-emerald-500/30">
                                    <i class="fa-solid fa-circle-check mr-1.5"></i> VÉ HỢP LỆ
                                </span>
                            @else
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-extrabold bg-rose-500/20 text-rose-300 border border-rose-500/30">
                                    <i class="fa-solid fa-rotate-left mr-1.5"></i> ĐÃ HOÀN TIỀN
                                </span>
                            @endif
                            <p class="text-[10px] text-slate-500">Đơn giá: {{ number_format($ticket->price, 0, ',', '.') }} đ</p>
                        </div>

                        <button type="button" onclick="copyTicketCode('{{ $ticket->ticket_code }}')" class="w-full py-2 px-3 rounded-xl bg-slate-800 hover:bg-slate-700 text-xs font-semibold text-slate-300 transition-colors flex items-center justify-center space-x-1.5">
                            <i class="fa-regular fa-copy"></i>
                            <span>Sao chép mã vé</span>
                        </button>
                    </div>

                </div>
            @endforeach

            <div class="mt-8">
                {{ $tickets->links() }}
            </div>
        </div>
    @endif

</div>

@push('scripts')
<script>
    function copyTicketCode(code) {
        navigator.clipboard.writeText(code).then(() => {
            alert('Đã sao chép mã vé: ' + code);
        });
    }
</script>
@endpush
@endsection

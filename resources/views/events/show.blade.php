@extends('layouts.app', ['title' => $event->title . ' - MEGATIX'])

@section('content')
<div class="relative bg-slate-950 pb-20">
    
    <!-- Event Banner Backdrop -->
    <div class="relative h-80 sm:h-96 lg:h-[450px] w-full overflow-hidden border-b border-slate-900">
        <img src="{{ $event->poster_url }}" alt="{{ $event->title }}" class="w-full h-full object-cover filter blur-md opacity-30 scale-105">
        <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-950/70 to-transparent"></div>
        
        <div class="absolute bottom-0 left-0 right-0 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-10">
            <div class="flex flex-wrap items-center gap-3 mb-4">
                @if($event->isEnded())
                    <span class="px-3.5 py-1.5 rounded-full text-xs font-extrabold uppercase tracking-wider backdrop-blur-md bg-rose-600/90 text-white shadow-lg shadow-rose-600/30">
                        <i class="fa-solid fa-calendar-xmark mr-1.5"></i>
                        ĐÃ KẾT THÚC / QUÁ HẠN
                    </span>
                @endif
                <span class="px-3.5 py-1.5 rounded-full text-xs font-extrabold uppercase tracking-wider backdrop-blur-md {{ $event->isSeated() ? 'bg-indigo-600/90 text-white' : 'bg-emerald-600/90 text-white' }}">
                    <i class="fa-solid {{ $event->isSeated() ? 'fa-chair' : 'fa-users' }} mr-1.5"></i>
                    {{ $event->isSeated() ? 'Concert Ghế Ngồi (Sơ đồ ghế phân dãy)' : 'Concert Vé Đứng (GA Pass)' }}
                </span>
                <span class="px-3 py-1 rounded-full text-xs font-bold bg-slate-900/80 text-fuchsia-400 border border-slate-700 backdrop-blur-md">
                    {{ $event->category->name }}
                </span>
            </div>

            <h1 class="text-3xl sm:text-5xl font-black text-white tracking-tight leading-tight max-w-4xl">
                {{ $event->title }}
            </h1>
        </div>
    </div>

    <!-- Main Event Details Container -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-6 relative z-10">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            
            <!-- Left Info Column -->
            <div class="lg:col-span-8 space-y-8">
                
                <!-- Quick Meta Cards -->
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div class="p-5 rounded-2xl bg-slate-900/90 border border-slate-800 flex items-center space-x-3.5 shadow-lg">
                        <div class="w-12 h-12 rounded-xl bg-fuchsia-500/10 text-fuchsia-400 flex items-center justify-center text-xl shrink-0">
                            <i class="fa-solid fa-calendar-star"></i>
                        </div>
                        <div>
                            <p class="text-[11px] font-semibold uppercase tracking-wider text-slate-400">Thời gian</p>
                            <p class="font-bold text-white text-sm mt-0.5">{{ $event->event_date->format('H:i - d/m/Y') }}</p>
                        </div>
                    </div>

                    <div class="p-5 rounded-2xl bg-slate-900/90 border border-slate-800 flex items-center space-x-3.5 shadow-lg">
                        <div class="w-12 h-12 rounded-xl bg-indigo-500/10 text-indigo-400 flex items-center justify-center text-xl shrink-0">
                            <i class="fa-solid fa-location-pin"></i>
                        </div>
                        <div class="truncate">
                            <p class="text-[11px] font-semibold uppercase tracking-wider text-slate-400">Địa điểm</p>
                            <p class="font-bold text-white text-sm mt-0.5 truncate" title="{{ $event->venue->name }}">{{ $event->venue->name }}</p>
                            <p class="text-xs text-slate-400 truncate">{{ $event->venue->address }}</p>
                        </div>
                    </div>

                    <div class="p-5 rounded-2xl bg-slate-900/90 border border-slate-800 flex items-center space-x-3.5 shadow-lg">
                        <div class="w-12 h-12 rounded-xl bg-emerald-500/10 text-emerald-400 flex items-center justify-center text-xl shrink-0">
                            <i class="fa-solid fa-microphone-lines"></i>
                        </div>
                        <div>
                            <p class="text-[11px] font-semibold uppercase tracking-wider text-slate-400">Nghệ sĩ chính</p>
                            <p class="font-bold text-white text-sm mt-0.5">{{ $event->artist->name ?? 'Nhiều nghệ sĩ' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Event Description -->
                <div class="p-8 rounded-3xl bg-slate-900/80 border border-slate-800 shadow-xl space-y-4">
                    <h2 class="text-xl font-bold text-white flex items-center">
                        <i class="fa-solid fa-align-left text-fuchsia-400 mr-2.5"></i> Giới Thiệu Về Sự Kiện
                    </h2>
                    <div class="text-slate-300 leading-relaxed text-sm whitespace-pre-line">
                        {{ $event->description }}
                    </div>
                </div>

                <!-- Ticket Types Table Overview -->
                <div class="p-8 rounded-3xl bg-slate-900/80 border border-slate-800 shadow-xl space-y-4">
                    <h2 class="text-xl font-bold text-white flex items-center">
                        <i class="fa-solid fa-tags text-indigo-400 mr-2.5"></i> Bảng Phân Hạng Vé & Giá
                    </h2>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm text-slate-300">
                            <thead class="text-xs uppercase bg-slate-950/60 text-slate-400 border-b border-slate-800">
                                <tr>
                                    <th class="py-3 px-4">Hạng vé / Khu vực</th>
                                    <th class="py-3 px-4">Khu vực / Dãy</th>
                                    <th class="py-3 px-4 text-center">Tình trạng vé</th>
                                    <th class="py-3 px-4 text-right">Đơn giá</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-800/60">
                                @foreach($event->ticketTypes as $type)
                                    <tr class="hover:bg-slate-800/40 transition-colors">
                                        <td class="py-3.5 px-4 font-bold text-white flex items-center space-x-2">
                                            <span class="w-2.5 h-2.5 rounded-full bg-fuchsia-500"></span>
                                            <span>{{ $type->name }}</span>
                                        </td>
                                        <td class="py-3.5 px-4 text-xs font-semibold text-slate-400">
                                            {{ $type->zone_code ? 'Dãy ' . $type->zone_code : 'Khu vực Đứng' }}
                                        </td>
                                        <td class="py-3.5 px-4 text-center">
                                            @if($type->remaining_quantity > 0)
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-500/20 text-emerald-300 border border-emerald-500/30">
                                                    Còn {{ $type->remaining_quantity }} vé
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-rose-500/20 text-rose-300 border border-rose-500/30">
                                                    Hết vé
                                                </span>
                                            @endif
                                        </td>
                                        <td class="py-3.5 px-4 text-right font-extrabold text-emerald-400 text-base">
                                            {{ number_format($type->price, 0, ',', '.') }} đ
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Artist Bio -->
                @if($event->artist)
                    <div class="p-8 rounded-3xl bg-slate-900/80 border border-slate-800 shadow-xl flex flex-col sm:flex-row items-center sm:items-start space-y-4 sm:space-y-0 sm:space-x-6">
                        @if($event->artist->image)
                            <img src="{{ $event->artist->image_url }}" alt="{{ $event->artist->name }}" class="w-24 h-24 rounded-2xl object-cover shrink-0 border border-slate-700">
                        @endif
                        <div class="space-y-2 text-center sm:text-left">
                            <span class="text-xs uppercase font-extrabold text-fuchsia-400 tracking-wider">Nghệ Sĩ Trình Diễn</span>
                            <h3 class="text-xl font-bold text-white">{{ $event->artist->name }}</h3>
                            <p class="text-xs text-slate-300 leading-relaxed">{{ $event->artist->bio }}</p>
                        </div>
                    </div>
                @endif

            </div>

            <!-- Right Booking Action Box -->
            <div class="lg:col-span-4">
                <div class="sticky top-28 bg-slate-900/95 backdrop-blur-xl rounded-3xl p-6 sm:p-8 border border-slate-800 shadow-2xl space-y-6">
                    
                    <div>
                        <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Giá vé khởi điểm</span>
                        <div class="text-3xl font-black text-emerald-400 mt-1">
                            {{ number_format($minPrice ?? 0, 0, ',', '.') }} <span class="text-lg font-medium text-emerald-300">VNĐ</span>
                        </div>
                        <p class="text-xs text-slate-400 mt-1">Đã bao gồm VAT & toàn bộ quyền lợi sự kiện</p>
                    </div>

                    <div class="p-4 rounded-2xl bg-slate-950/80 border border-slate-800/80 space-y-2 text-xs text-slate-300">
                        <div class="flex items-center justify-between">
                            <span class="text-slate-400">Hình thức vé:</span>
                            <span class="font-bold text-white">{{ $event->isSeated() ? 'Chọn ghế theo dãy (A-5-12)' : 'Vé đứng tự do' }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-slate-400">Cổng thanh toán:</span>
                            <span class="font-bold text-emerald-400">Ví nội bộ MEGATIX</span>
                        </div>
                        @auth
                            <div class="flex items-center justify-between pt-2 border-t border-slate-800">
                                <span class="text-slate-400">Số dư ví của bạn:</span>
                                <span class="font-extrabold text-white text-sm">{{ number_format(Auth::user()->balance, 0, ',', '.') }} đ</span>
                            </div>
                        @endauth
                    </div>

                    @if(! $event->canBeBooked())
                        <div class="space-y-3">
                            <button type="button" disabled class="w-full py-4 px-6 rounded-2xl text-base font-extrabold text-slate-400 bg-slate-800/80 border border-slate-700/80 cursor-not-allowed shadow-lg flex items-center justify-center space-x-2">
                                <i class="fa-solid fa-lock text-rose-400"></i>
                                <span>{{ $event->isPast() ? 'Sự Kiện Đã Diễn Ra (Đóng Cổng)' : 'Đã Đóng Cổng Bán Vé (Trước 24h)' }}</span>
                            </button>
                            <p class="text-xs text-center text-amber-400/90 font-medium leading-relaxed">
                                <i class="fa-solid fa-circle-info mr-1"></i>
                                @if($event->isPast())
                                    Sự kiện đã diễn ra vào {{ $event->event_date->format('H:i - d/m/Y') }}. Cổng bán vé đã đóng.
                                @else
                                    Cổng bán vé đã tự động đóng 24 giờ trước thời gian biểu diễn ({{ $event->event_date->format('H:i - d/m/Y') }}) để Ban tổ chức chốt danh sách khán giả.
                                @endif
                            </p>
                        </div>
                    @elseif(Auth::check())
                        <button type="button" onclick="openBookingModal()" class="w-full py-4 px-6 rounded-2xl text-base font-extrabold text-white bg-gradient-to-r from-fuchsia-600 via-pink-600 to-indigo-600 hover:from-fuchsia-500 hover:to-indigo-500 shadow-xl shadow-fuchsia-600/30 hover:shadow-fuchsia-600/50 transition-all duration-300 transform hover:-translate-y-0.5 flex items-center justify-center space-x-2">
                            <i class="fa-solid fa-ticket"></i>
                            <span>{{ $event->isSeated() ? 'Chọn Ghế & Mua Vé Ngay' : 'Mua Vé Đứng Ngay' }}</span>
                        </button>
                    @else
                        <div class="space-y-3">
                            <a href="{{ route('login') }}" class="w-full py-4 px-6 rounded-2xl text-base font-extrabold text-white bg-gradient-to-r from-fuchsia-600 to-indigo-600 hover:from-fuchsia-500 hover:to-indigo-500 shadow-xl shadow-fuchsia-600/30 transition-all text-center block">
                                <i class="fa-solid fa-right-to-bracket mr-2"></i> Đăng Nhập Để Mua Vé
                            </a>
                            <p class="text-[11px] text-center text-slate-400">Chưa có tài khoản? <a href="{{ route('register') }}" class="text-fuchsia-400 font-bold hover:underline">Đăng ký siêu tốc</a></p>
                        </div>
                    @endif

                    <div class="space-y-2 text-[11px] text-slate-400 pt-2 border-t border-slate-800/80">
                        <p class="flex items-center"><i class="fa-solid fa-shield-check text-emerald-400 mr-2"></i> Bảo mật giao dịch & chống trùng ghế 100%</p>
                        <p class="flex items-center"><i class="fa-solid fa-mobile-screen text-indigo-400 mr-2"></i> Nhận mã vé 8 ký tự lưu trực tiếp trong tài khoản</p>
                        <p class="flex items-center"><i class="fa-solid fa-rotate-left text-fuchsia-400 mr-2"></i> Tự động hoàn tiền 100% nếu sự kiện bị hủy</p>
                    </div>

                </div>
            </div>

        </div>
    </div>
</div>

<!-- ========================================== -->
<!-- BOOKING POPUP MODAL (DROPDOWN SEAT PICKER)  -->
<!-- ========================================== -->
@if($event->canBeBooked())
@auth
<div id="bookingModal" class="fixed inset-0 z-50 overflow-y-auto hidden bg-slate-950/80 backdrop-blur-md flex items-center justify-center p-4">
    <div class="relative w-full max-w-2xl bg-slate-900 border border-slate-800 rounded-3xl shadow-2xl overflow-hidden animate-in fade-in zoom-in-95 duration-200">
        
        <!-- Modal Header -->
        <div class="flex items-center justify-between px-6 py-4 border-b border-slate-800 bg-slate-950/60">
            <div class="flex items-center space-x-2.5">
                <div class="w-8 h-8 rounded-lg bg-fuchsia-500/20 text-fuchsia-400 flex items-center justify-center text-sm">
                    <i class="fa-solid fa-ticket"></i>
                </div>
                <div>
                    <h3 class="text-base font-bold text-white">{{ $event->title }}</h3>
                    <p class="text-xs text-slate-400">{{ $event->isSeated() ? 'Chọn Khu -> Hàng -> Ghế' : 'Chọn số lượng vé đứng' }}</p>
                </div>
            </div>
            <button type="button" onclick="closeBookingModal()" class="w-8 h-8 rounded-lg bg-slate-800 text-slate-400 hover:text-white flex items-center justify-center transition-colors">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <!-- Modal Body Form -->
        <form id="checkoutForm" action="{{ route('booking.checkout') }}" method="POST" class="p-6 space-y-6">
            @csrf
            <input type="hidden" name="event_id" value="{{ $event->id }}">

            <!-- 1. Select Ticket Type (Khu vực / Hạng vé) -->
            <div>
                <label for="modalTicketType" class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-1.5">
                    1. Chọn Hạng Vé / Khu Vực <span class="text-rose-500">*</span>
                </label>
                <select id="modalTicketType" name="ticket_type_id" required onchange="onTicketTypeChange()"
                    class="w-full px-4 py-3 rounded-xl bg-slate-950 border border-slate-800 text-white text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-fuchsia-500">
                    <option value="">-- Vui lòng chọn Hạng vé / Khu --</option>
                    @foreach($event->ticketTypes as $type)
                        <option value="{{ $type->id }}" data-price="{{ $type->price }}" data-remaining="{{ $type->remaining_quantity }}">
                            {{ $type->name }} ({{ number_format($type->price, 0, ',', '.') }} đ) - Còn {{ $type->remaining_quantity }} vé
                        </option>
                    @endforeach
                </select>
            </div>

            @if($event->isSeated())
                <!-- 2. Seated Concert: Row and Seat Dropdown Selectors -->
                <div id="seatedControls" class="p-4 rounded-2xl bg-slate-950/80 border border-slate-800 space-y-4">
                    <p class="text-xs font-bold text-slate-300 flex items-center">
                        <i class="fa-solid fa-chair text-indigo-400 mr-2"></i> 2. Chọn Ghế Ngồi Từng Vị Trí:
                    </p>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <!-- Select Row -->
                        <div>
                            <label for="modalRowSelect" class="block text-[11px] font-semibold text-slate-400 mb-1">
                                Chọn Hàng ghế còn trống
                            </label>
                            <select id="modalRowSelect" onchange="onRowChange()" disabled
                                class="w-full px-3.5 py-2.5 rounded-xl bg-slate-900 border border-slate-700 text-white text-xs font-semibold focus:outline-none focus:ring-2 focus:ring-fuchsia-500 disabled:opacity-50">
                                <option value="">-- Chọn Hạng vé trước --</option>
                            </select>
                        </div>

                        <!-- Select Seat -->
                        <div>
                            <label for="modalSeatSelect" class="block text-[11px] font-semibold text-slate-400 mb-1">
                                Chọn Số ghế còn trống
                            </label>
                            <select id="modalSeatSelect" disabled
                                class="w-full px-3.5 py-2.5 rounded-xl bg-slate-900 border border-slate-700 text-white text-xs font-semibold focus:outline-none focus:ring-2 focus:ring-fuchsia-500 disabled:opacity-50">
                                <option value="">-- Chọn Hàng ghế trước --</option>
                            </select>
                        </div>
                    </div>

                    <!-- Button Add Seat to List -->
                    <div class="pt-1">
                        <button type="button" onclick="addSelectedSeat()" id="btnAddSeat" disabled
                            class="w-full py-2.5 px-4 rounded-xl text-xs font-bold text-white bg-indigo-600 hover:bg-indigo-500 disabled:opacity-50 transition-colors flex items-center justify-center space-x-1.5">
                            <i class="fa-solid fa-plus"></i>
                            <span>Thêm Ghế Này Vào Danh Sách Đặt</span>
                        </button>
                    </div>

                    <!-- Selected Seats Tags Display -->
                    <div class="pt-2">
                        <span class="text-[11px] font-semibold text-slate-400 block mb-1.5">Các ghế đã chọn:</span>
                        <div id="selectedSeatsContainer" class="flex flex-wrap gap-2 min-h-[38px] p-2 rounded-xl bg-slate-900/60 border border-slate-800">
                            <span class="text-xs text-slate-500 italic p-1">Chưa chọn ghế nào.</span>
                        </div>
                    </div>
                </div>
            @else
                <!-- Standing Concert: Quantity Input -->
                <div id="standingControls" class="p-4 rounded-2xl bg-slate-950/80 border border-slate-800 space-y-3">
                    <label for="modalQuantity" class="block text-xs font-bold text-slate-300">
                        2. Nhập số lượng vé cần mua (Tối đa 10 vé)
                    </label>
                    <div class="flex items-center space-x-3">
                        <button type="button" onclick="adjustQuantity(-1)" class="w-10 h-10 rounded-xl bg-slate-800 text-white font-bold hover:bg-slate-700 flex items-center justify-center text-lg">-</button>
                        <input type="number" id="modalQuantity" name="quantity" min="1" max="10" value="1" onchange="calculateTotal()"
                            class="w-24 text-center py-2 bg-slate-900 border border-slate-700 rounded-xl text-white font-extrabold text-base focus:outline-none focus:ring-2 focus:ring-fuchsia-500">
                        <button type="button" onclick="adjustQuantity(1)" class="w-10 h-10 rounded-xl bg-slate-800 text-white font-bold hover:bg-slate-700 flex items-center justify-center text-lg">+</button>
                    </div>
                </div>
            @endif

            <!-- Order Total & Wallet Comparison Box -->
            <div class="p-4 rounded-2xl bg-slate-950 border border-slate-800 space-y-2">
                <div class="flex items-center justify-between text-xs text-slate-400">
                    <span>Tổng tiền vé:</span>
                    <span id="displayTotalAmount" class="text-lg font-black text-emerald-400">0 đ</span>
                </div>
                <div class="flex items-center justify-between text-xs text-slate-400">
                    <span>Số dư ví hiện tại:</span>
                    <span class="font-bold text-white">{{ number_format(Auth::user()->balance, 0, ',', '.') }} đ</span>
                </div>
                
                <div id="balanceStatusBox" class="pt-2 border-t border-slate-800/80">
                    <!-- Populated dynamically via JS -->
                </div>
            </div>

            <!-- Submit Button / Topup CTA -->
            <div id="submitBtnContainer">
                <button type="submit" id="btnSubmitOrder" disabled
                    class="w-full py-3.5 px-6 rounded-2xl text-sm font-extrabold text-white bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-500 hover:to-teal-500 disabled:opacity-40 disabled:cursor-not-allowed shadow-lg shadow-emerald-600/30 transition-all flex items-center justify-center space-x-2">
                    <i class="fa-solid fa-lock"></i>
                    <span>Xác Nhận Trừ Ví & Đặt Vé</span>
                </button>
            </div>
        </form>
    </div>
</div>
@endauth
@endif

@push('scripts')
<script>
    const userBalance = {{ Auth::check() ? Auth::user()->balance : 0 }};
    const isSeatedEvent = {{ $event->isSeated() ? 'true' : 'false' }};
    let currentTicketPrice = 0;
    let selectedSeatsMap = new Map(); // seatId -> seatCode

    function openBookingModal() {
        const modal = document.getElementById('bookingModal');
        if (modal) modal.classList.remove('hidden');
    }

    function closeBookingModal() {
        const modal = document.getElementById('bookingModal');
        if (modal) modal.classList.add('hidden');
    }

    // 1. When Ticket Type Changes
    async function onTicketTypeChange() {
        const typeSelect = document.getElementById('modalTicketType');
        const selectedOption = typeSelect.options[typeSelect.selectedIndex];
        const ticketTypeId = typeSelect.value;

        // Reset selected seats
        selectedSeatsMap.clear();
        updateSelectedSeatsView();

        if (!ticketTypeId) {
            currentTicketPrice = 0;
            if (isSeatedEvent) {
                resetRowSelect('Chọn Hạng vé trước');
                resetSeatSelect('Chọn Hàng ghế trước');
            }
            calculateTotal();
            return;
        }

        currentTicketPrice = parseFloat(selectedOption.getAttribute('data-price')) || 0;

        if (isSeatedEvent) {
            // Load available rows via AJAX
            resetRowSelect('Đang tải hàng ghế...');
            resetSeatSelect('Chọn Hàng ghế trước');

            try {
                const res = await fetch(`/api/ticket-types/${ticketTypeId}/available-rows`);
                const rows = await res.json();

                const rowSelect = document.getElementById('modalRowSelect');
                rowSelect.innerHTML = '<option value="">-- Chọn Hàng ghế --</option>';

                if (rows.length === 0) {
                    rowSelect.innerHTML = '<option value="">Hạng vé này đã hết ghế</option>';
                    rowSelect.disabled = true;
                } else {
                    rows.forEach(r => {
                        const opt = document.createElement('option');
                        opt.value = r;
                        opt.textContent = `Hàng ${r}`;
                        rowSelect.appendChild(opt);
                    });
                    rowSelect.disabled = false;
                }
            } catch (err) {
                console.error(err);
            }
        }

        calculateTotal();
    }

    // 2. When Row Changes
    async function onRowChange() {
        const ticketTypeId = document.getElementById('modalTicketType').value;
        const rowSelect = document.getElementById('modalRowSelect');
        const rowNumber = rowSelect.value;

        if (!rowNumber) {
            resetSeatSelect('Chọn Hàng ghế trước');
            return;
        }

        resetSeatSelect('Đang tải danh sách ghế...');

        try {
            const res = await fetch(`/api/ticket-types/${ticketTypeId}/rows/${rowNumber}/available-seats`);
            const seats = await res.json();

            const seatSelect = document.getElementById('modalSeatSelect');
            seatSelect.innerHTML = '<option value="">-- Chọn Ghế trống --</option>';

            seats.forEach(s => {
                // If not already in selectedSeatsMap
                if (!selectedSeatsMap.has(s.id.toString())) {
                    const opt = document.createElement('option');
                    opt.value = s.id;
                    opt.textContent = `Ghế ${s.seat_code}`;
                    opt.setAttribute('data-code', s.seat_code);
                    seatSelect.appendChild(opt);
                }
            });

            seatSelect.disabled = false;
            document.getElementById('btnAddSeat').disabled = false;
        } catch (err) {
            console.error(err);
        }
    }

    function resetRowSelect(placeholder) {
        const rowSelect = document.getElementById('modalRowSelect');
        rowSelect.innerHTML = `<option value="">-- ${placeholder} --</option>`;
        rowSelect.disabled = true;
    }

    function resetSeatSelect(placeholder) {
        const seatSelect = document.getElementById('modalSeatSelect');
        seatSelect.innerHTML = `<option value="">-- ${placeholder} --</option>`;
        seatSelect.disabled = true;
        const btnAdd = document.getElementById('btnAddSeat');
        if (btnAdd) btnAdd.disabled = true;
    }

    // Add seat to list
    function addSelectedSeat() {
        const seatSelect = document.getElementById('modalSeatSelect');
        const seatId = seatSelect.value;
        if (!seatId) return;

        const selectedOption = seatSelect.options[seatSelect.selectedIndex];
        const seatCode = selectedOption.getAttribute('data-code') || selectedOption.textContent;

        selectedSeatsMap.set(seatId.toString(), seatCode);
        
        // Remove from dropdown so can't add twice
        seatSelect.remove(seatSelect.selectedIndex);

        updateSelectedSeatsView();
        calculateTotal();
    }

    function removeSelectedSeat(seatId) {
        selectedSeatsMap.delete(seatId.toString());
        updateSelectedSeatsView();
        // Reload seat dropdown for current row
        onRowChange();
        calculateTotal();
    }

    function updateSelectedSeatsView() {
        const container = document.getElementById('selectedSeatsContainer');
        if (!container) return;

        container.innerHTML = '';

        if (selectedSeatsMap.size === 0) {
            container.innerHTML = '<span class="text-xs text-slate-500 italic p-1">Chưa chọn ghế nào.</span>';
            return;
        }

        selectedSeatsMap.forEach((code, id) => {
            const badge = document.createElement('span');
            badge.className = 'inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold bg-indigo-600/30 text-indigo-200 border border-indigo-500/40 space-x-1.5';
            badge.innerHTML = `
                <span>${code}</span>
                <button type="button" onclick="removeSelectedSeat('${id}')" class="text-rose-400 hover:text-rose-200 ml-1">
                    <i class="fa-solid fa-xmark"></i>
                </button>
                <input type="hidden" name="seat_ids[]" value="${id}">
            `;
            container.appendChild(badge);
        });
    }

    function adjustQuantity(delta) {
        const input = document.getElementById('modalQuantity');
        let val = parseInt(input.value) || 1;
        val = Math.max(1, Math.min(10, val + delta));
        input.value = val;
        calculateTotal();
    }

    function calculateTotal() {
        let quantity = 0;
        if (isSeatedEvent) {
            quantity = selectedSeatsMap.size;
        } else {
            const qInput = document.getElementById('modalQuantity');
            quantity = qInput ? parseInt(qInput.value) || 0 : 0;
        }

        const totalAmount = currentTicketPrice * quantity;
        document.getElementById('displayTotalAmount').textContent = new Intl.NumberFormat('vi-VN').format(totalAmount) + ' đ';

        const btnSubmit = document.getElementById('btnSubmitOrder');
        const statusBox = document.getElementById('balanceStatusBox');

        if (quantity === 0) {
            statusBox.innerHTML = '<p class="text-xs text-slate-500 italic">Vui lòng chọn vé hoặc ghế để xem trạng thái thanh toán.</p>';
            btnSubmit.disabled = true;
            return;
        }

        if (userBalance >= totalAmount) {
            statusBox.innerHTML = `
                <div class="flex items-center text-xs text-emerald-400 font-bold space-x-1.5">
                    <i class="fa-solid fa-circle-check"></i>
                    <span>Số dư ví của bạn đủ để thanh toán ngay!</span>
                </div>
            `;
            btnSubmit.disabled = false;
        } else {
            const missing = totalAmount - userBalance;
            statusBox.innerHTML = `
                <div class="space-y-1.5">
                    <div class="flex items-center text-xs text-rose-400 font-bold space-x-1.5">
                        <i class="fa-solid fa-triangle-exclamation"></i>
                        <span>Số dư ví còn thiếu: ${new Intl.NumberFormat('vi-VN').format(missing)} đ</span>
                    </div>
                    <a href="{{ route('wallet.index') }}" target="_blank" class="inline-flex items-center text-xs font-bold text-fuchsia-400 hover:text-fuchsia-300 underline">
                        <i class="fa-solid fa-wallet mr-1"></i> Bấm vào đây để Nạp tiền vào ví ngay
                    </a>
                </div>
            `;
            btnSubmit.disabled = true;
        }
    }
</script>
@endpush
@endsection

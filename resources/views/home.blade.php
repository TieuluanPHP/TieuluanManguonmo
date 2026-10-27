@extends('layouts.app', ['title' => 'MEGATIX - Đặt Vé Concert & Live Music Hàng Đầu'])

@section('content')
<!-- Hero Featured Banner -->
@if($featuredEvent)
<div class="relative overflow-hidden bg-slate-950 py-12 lg:py-20 border-b border-slate-900">
    <div class="absolute inset-0 z-0">
        <img src="{{ $featuredEvent->poster_url }}" alt="{{ $featuredEvent->title }}" class="w-full h-full object-cover opacity-20 filter blur-xl scale-110">
        <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-950/80 to-transparent"></div>
    </div>

    <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">
            
            <div class="lg:col-span-7 space-y-6">
                <div class="inline-flex items-center space-x-2 px-3.5 py-1.5 rounded-full bg-fuchsia-500/10 border border-fuchsia-500/30 text-fuchsia-400 text-xs font-bold uppercase tracking-wider">
                    <span class="w-2 h-2 rounded-full bg-fuchsia-400 animate-ping"></span>
                    <span>Sự Kiện Âm Nhạc Nổi Bật 2026</span>
                </div>

                <h1 class="text-4xl sm:text-5xl lg:text-6xl font-black text-white tracking-tight leading-tight">
                    {{ $featuredEvent->title }}
                </h1>

                <p class="text-base sm:text-lg text-slate-300 line-clamp-3">
                    {{ $featuredEvent->description }}
                </p>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2 text-sm text-slate-300">
                    <div class="flex items-center space-x-3 p-3 rounded-2xl bg-slate-900/60 border border-slate-800">
                        <div class="w-10 h-10 rounded-xl bg-fuchsia-500/20 text-fuchsia-400 flex items-center justify-center text-lg">
                            <i class="fa-solid fa-calendar-day"></i>
                        </div>
                        <div>
                            <p class="text-xs text-slate-400 font-semibold uppercase">Thời gian biểu diễn</p>
                            <p class="font-bold text-white">{{ $featuredEvent->event_date->format('H:i - d/m/Y') }}</p>
                        </div>
                    </div>

                    <div class="flex items-center space-x-3 p-3 rounded-2xl bg-slate-900/60 border border-slate-800">
                        <div class="w-10 h-10 rounded-xl bg-indigo-500/20 text-indigo-400 flex items-center justify-center text-lg">
                            <i class="fa-solid fa-location-dot"></i>
                        </div>
                        <div>
                            <p class="text-xs text-slate-400 font-semibold uppercase">Địa điểm tổ chức</p>
                            <p class="font-bold text-white truncate max-w-[200px]" title="{{ $featuredEvent->venue->name }}">{{ $featuredEvent->venue->name }}</p>
                        </div>
                    </div>
                </div>

                <div class="flex flex-wrap items-center gap-4 pt-4">
                    <a href="{{ route('events.show', $featuredEvent->slug) }}" class="px-8 py-4 rounded-2xl text-base font-extrabold text-white bg-gradient-to-r from-fuchsia-600 via-pink-600 to-indigo-600 hover:from-fuchsia-500 hover:to-indigo-500 shadow-xl shadow-fuchsia-600/30 hover:shadow-fuchsia-600/50 transition-all duration-300 transform hover:-translate-y-0.5">
                        <i class="fa-solid fa-ticket mr-2"></i> Mua Vé Ngay Bây Giờ
                    </a>
                    <span class="text-xs font-semibold uppercase text-slate-400 flex items-center">
                        <i class="fa-solid fa-bolt text-amber-400 mr-1.5"></i> Chọn ghế trực tiếp qua Popup siêu tốc
                    </span>
                </div>
            </div>

            <div class="lg:col-span-5 hidden lg:block">
                <div class="relative rounded-3xl overflow-hidden border-2 border-slate-800 shadow-2xl group">
                    <img src="{{ $featuredEvent->poster_url }}" alt="{{ $featuredEvent->title }}" class="w-full h-[450px] object-cover group-hover:scale-105 transition-transform duration-500">
                    <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-transparent to-transparent opacity-80"></div>
                    <div class="absolute bottom-6 left-6 right-6">
                        <span class="inline-block px-3 py-1 rounded-lg text-xs font-extrabold uppercase bg-fuchsia-600 text-white shadow">
                            {{ $featuredEvent->isSeated() ? 'Concert Ngồi (Sơ đồ ghế)' : 'Concert Đứng (GA Pass)' }}
                        </span>
                        <p class="text-xl font-bold text-white mt-2">{{ $featuredEvent->artist->name ?? 'Nghệ sĩ đình đám' }}</p>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endif

<!-- Search & Filters Section -->
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="bg-slate-900/80 backdrop-blur-md rounded-3xl p-6 border border-slate-800 shadow-xl mb-12">
        <form action="{{ route('home') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            
            <!-- Search Keyword -->
            <div>
                <label for="search" class="block text-xs font-semibold uppercase tracking-wider text-slate-400 mb-1.5">
                    Tìm kiếm concert
                </label>
                <div class="relative">
                    <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-3.5 text-slate-500"></i>
                    <input type="text" id="search" name="search" value="{{ request('search') }}"
                        placeholder="Nhập tên show, nghệ sĩ..."
                        class="w-full pl-10 pr-4 py-2.5 rounded-xl bg-slate-950 border border-slate-800 text-white text-sm focus:outline-none focus:ring-2 focus:ring-fuchsia-500">
                </div>
            </div>

            <!-- Category Filter -->
            <div>
                <label for="category" class="block text-xs font-semibold uppercase tracking-wider text-slate-400 mb-1.5">
                    Thể loại âm nhạc
                </label>
                <select id="category" name="category" class="w-full px-4 py-2.5 rounded-xl bg-slate-950 border border-slate-800 text-white text-sm focus:outline-none focus:ring-2 focus:ring-fuchsia-500">
                    <option value="">Tất cả thể loại</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>
                            {{ $cat->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Venue Filter -->
            <div>
                <label for="venue" class="block text-xs font-semibold uppercase tracking-wider text-slate-400 mb-1.5">
                    Địa điểm / Sân vận động
                </label>
                <select id="venue" name="venue" class="w-full px-4 py-2.5 rounded-xl bg-slate-950 border border-slate-800 text-white text-sm focus:outline-none focus:ring-2 focus:ring-fuchsia-500">
                    <option value="">Tất cả địa điểm</option>
                    @foreach($venues as $v)
                        <option value="{{ $v->id }}" {{ request('venue') == $v->id ? 'selected' : '' }}>
                            {{ $v->name }} ({{ $v->city }})
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Submit Filter -->
            <div class="flex items-end space-x-2">
                <button type="submit" class="flex-1 py-2.5 px-4 rounded-xl text-sm font-bold text-white bg-gradient-to-r from-fuchsia-600 to-indigo-600 hover:from-fuchsia-500 hover:to-indigo-500 shadow-lg shadow-fuchsia-600/20 transition-all">
                    <i class="fa-solid fa-filter mr-1.5"></i> Lọc Sự Kiện
                </button>
                @if(request()->hasAny(['search', 'category', 'venue', 'artist']))
                    <a href="{{ route('home') }}" class="py-2.5 px-3 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-300 text-sm font-semibold" title="Xóa bộ lọc">
                        <i class="fa-solid fa-rotate-left"></i>
                    </a>
                @endif
            </div>

        </form>
    </div>

    <!-- Concert Grid -->
    <div class="flex items-center justify-between mb-8">
        <div>
            <h2 class="text-2xl font-extrabold text-white tracking-tight flex items-center">
                <i class="fa-solid fa-bolt text-amber-400 mr-2.5"></i> Danh Sách Sự Kiện Đang Mở Bán
            </h2>
            <p class="text-xs text-slate-400 mt-1">Cập nhật thời gian thực các concert và đại nhạc hội sắp diễn ra</p>
        </div>
        <span class="text-xs text-slate-400 font-medium">Tìm thấy {{ $events->total() }} sự kiện</span>
    </div>

    @if($events->isEmpty())
        <div class="text-center py-20 bg-slate-900/50 rounded-3xl border border-slate-800">
            <i class="fa-solid fa-calendar-xmark text-5xl text-slate-600 mb-4"></i>
            <h3 class="text-lg font-bold text-white">Không tìm thấy sự kiện phù hợp</h3>
            <p class="text-sm text-slate-400 mt-1">Vui lòng thử tìm kiếm với từ khóa hoặc bộ lọc khác.</p>
            <a href="{{ route('home') }}" class="inline-block mt-4 px-6 py-2.5 rounded-xl bg-slate-800 text-white text-xs font-bold hover:bg-slate-700">
                Xóa tất cả bộ lọc
            </a>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($events as $event)
                <div class="bg-slate-900 rounded-3xl overflow-hidden border border-slate-800 hover:border-fuchsia-500/50 transition-all duration-300 hover:shadow-2xl hover:shadow-fuchsia-500/10 flex flex-col group">
                    
                    <!-- Poster Image -->
                    <div class="relative h-56 overflow-hidden">
                        <img src="{{ $event->poster_url }}" alt="{{ $event->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        <div class="absolute inset-0 bg-gradient-to-t from-slate-900 via-transparent to-transparent"></div>
                        
                        <!-- Event Type & Past Badges -->
                        <div class="absolute top-4 left-4 flex flex-col space-y-1.5 items-start">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-[11px] font-extrabold uppercase tracking-wider backdrop-blur-md {{ $event->isSeated() ? 'bg-indigo-600/90 text-white' : 'bg-emerald-600/90 text-white' }}">
                                <i class="fa-solid {{ $event->isSeated() ? 'fa-chair' : 'fa-users' }} mr-1.5"></i>
                                {{ $event->isSeated() ? 'Vé Ghế Ngồi' : 'Vé Đứng (GA)' }}
                            </span>
                            @if($event->isBookingClosed())
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-extrabold uppercase tracking-wider backdrop-blur-md bg-amber-950/90 text-amber-300 border border-amber-500/50 shadow-md">
                                    <i class="fa-solid fa-lock mr-1"></i> Đóng cổng (Trước 24h)
                                </span>
                            @endif
                        </div>

                        <!-- Category Badge -->
                        <div class="absolute top-4 right-4">
                            <span class="inline-block px-2.5 py-1 rounded-lg text-[10px] font-bold uppercase bg-slate-900/80 text-fuchsia-400 border border-slate-700 backdrop-blur-md">
                                {{ $event->category->name }}
                            </span>
                        </div>
                    </div>

                    <!-- Event Content -->
                    <div class="p-6 flex-1 flex flex-col justify-between space-y-4">
                        <div>
                            <div class="flex items-center text-xs font-semibold text-fuchsia-400 mb-2">
                                <i class="fa-solid fa-microphone-lines mr-1.5"></i>
                                <span>{{ $event->artist->name ?? 'Nghệ sĩ biểu diễn' }}</span>
                            </div>

                            <h3 class="text-xl font-bold text-white group-hover:text-fuchsia-400 transition-colors line-clamp-2">
                                <a href="{{ route('events.show', $event->slug) }}">
                                    {{ $event->title }}
                                </a>
                            </h3>

                            <div class="mt-4 space-y-2 text-xs text-slate-300">
                                <p class="flex items-center">
                                    <i class="fa-solid fa-calendar w-5 text-indigo-400"></i>
                                    <span>{{ $event->event_date->format('H:i, d/m/Y') }}</span>
                                </p>
                                <p class="flex items-center truncate">
                                    <i class="fa-solid fa-location-dot w-5 text-rose-400"></i>
                                    <span class="truncate" title="{{ $event->venue->name }}">{{ $event->venue->name }}</span>
                                </p>
                            </div>
                        </div>

                        <!-- Footer & Price -->
                        <div class="pt-4 border-t border-slate-800 flex items-center justify-between">
                            <div>
                                <p class="text-[10px] uppercase font-semibold text-slate-500">Giá chỉ từ</p>
                                <p class="text-base font-extrabold text-emerald-400">
                                    {{ number_format($event->ticketTypes->min('price') ?? 0, 0, ',', '.') }} đ
                                </p>
                            </div>
                            @if($event->canBeBooked())
                                <a href="{{ route('events.show', $event->slug) }}" class="px-5 py-2.5 rounded-xl text-xs font-bold text-white bg-gradient-to-r from-fuchsia-600 to-indigo-600 hover:from-fuchsia-500 hover:to-indigo-500 shadow-md shadow-fuchsia-600/20 transition-all">
                                    Xem & Đặt vé
                                </a>
                            @else
                                <a href="{{ route('events.show', $event->slug) }}" class="px-4 py-2.5 rounded-xl text-xs font-bold text-slate-300 bg-slate-800 hover:bg-slate-700 hover:text-white transition-all flex items-center space-x-1.5">
                                    <i class="fa-solid fa-calendar-xmark text-rose-400"></i>
                                    <span>Đã đóng cổng</span>
                                </a>
                            @endif
                        </div>
                    </div>

                </div>
            @endforeach
        </div>

        <div class="mt-10">
            {{ $events->links() }}
        </div>
    @endif
</div>
@endsection

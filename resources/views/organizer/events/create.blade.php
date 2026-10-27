@extends('layouts.app', ['title' => 'Tạo Concert Mới - MEGATIX'])

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    
    <div class="mb-8 flex items-center justify-between">
        <div>
            <a href="{{ route('organizer.dashboard') }}" class="text-xs font-bold text-slate-400 hover:text-white flex items-center mb-2">
                <i class="fa-solid fa-arrow-left mr-1.5"></i> Quay lại Dashboard
            </a>
            <h1 class="text-3xl font-extrabold text-white tracking-tight">Tạo Concert Mới</h1>
            <p class="mt-1 text-sm text-slate-400">Thiết lập thông tin concert, hạng vé và sinh sơ đồ ghế tự động trong 30 giây.</p>
        </div>
    </div>

    <form action="{{ route('organizer.events.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
        @csrf

        <!-- PHẦN 1: THÔNG TIN CƠ BẢN -->
        <div class="bg-slate-900/90 rounded-3xl p-6 sm:p-8 border border-slate-800 shadow-xl space-y-6">
            <h2 class="text-lg font-bold text-white flex items-center border-b border-slate-800 pb-4">
                <span class="w-7 h-7 rounded-lg bg-purple-600 text-white flex items-center justify-center text-xs mr-2.5">1</span>
                <span>Thông Tin Cơ Bản Sự Kiện</span>
            </h2>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <!-- Title -->
                <div class="sm:col-span-2">
                    <label for="title" class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-1.5">
                        Tên Concert / Đêm nhạc <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" id="title" name="title" required value="{{ old('title') }}"
                        class="w-full px-4 py-3 rounded-xl bg-slate-950 border border-slate-800 text-white text-sm focus:outline-none focus:ring-2 focus:ring-purple-500"
                        placeholder="Ví dụ: Anh Trai Say Hi - Live Concert 2026">
                    @error('title')
                        <p class="mt-1 text-xs text-rose-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Poster Image Upload Zone with Drag & Drop and Instant Preview -->
                <div class="sm:col-span-2 space-y-2">
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-300">
                        Ảnh Poster / Banner Sự Kiện <span class="text-rose-500">*</span>
                    </label>

                    <!-- Drag & Drop Upload Container -->
                    <div id="dropZone" onclick="document.getElementById('poster_file').click()"
                        class="relative border-2 border-dashed border-slate-700 hover:border-purple-500/80 bg-slate-950/60 hover:bg-slate-950/90 rounded-2xl p-6 sm:p-8 text-center cursor-pointer transition-all duration-300 group">
                        
                        <input type="file" id="poster_file" name="poster_file" accept="image/png,image/jpeg,image/jpg,image/webp" class="hidden" onchange="handleFileSelect(event)">

                        <!-- Default State (No image selected yet) -->
                        <div id="uploadPrompt" class="space-y-3">
                            <div class="w-14 h-14 mx-auto rounded-2xl bg-purple-600/10 text-purple-400 flex items-center justify-center text-2xl group-hover:scale-110 group-hover:bg-purple-600/20 transition-all duration-300">
                                <i class="fa-solid fa-cloud-arrow-up"></i>
                            </div>
                            <div>
                                <p class="text-sm font-bold text-white">
                                    Kéo & thả ảnh poster vào đây, hoặc <span class="text-purple-400 underline decoration-purple-500/50">chọn từ máy tính</span>
                                </p>
                                <p class="text-xs text-slate-400 mt-1">Định dạng hỗ trợ: JPG, JPEG, PNG, WEBP (Tối đa 5MB)</p>
                            </div>
                        </div>

                        <!-- Preview State (Shown when file selected) -->
                        <div id="previewContainer" class="hidden space-y-4">
                            <div class="relative inline-block max-w-md mx-auto rounded-2xl overflow-hidden border border-slate-700 shadow-2xl">
                                <img id="imagePreview" src="#" alt="Poster Preview" class="w-full h-56 object-cover rounded-xl">
                                <div class="absolute inset-0 bg-gradient-to-t from-slate-950/80 via-transparent to-transparent flex items-end justify-between p-3">
                                    <span id="fileName" class="text-xs font-bold text-white truncate max-w-[200px]"></span>
                                    <span id="fileSize" class="text-[11px] font-semibold text-purple-300 bg-purple-950/80 px-2 py-0.5 rounded-full border border-purple-800"></span>
                                </div>
                            </div>
                            <div>
                                <button type="button" onclick="event.stopPropagation(); removeSelectedFile()" class="px-4 py-1.5 rounded-xl bg-rose-500/20 text-rose-400 hover:bg-rose-500/30 border border-rose-500/30 text-xs font-bold transition-all">
                                    <i class="fa-solid fa-trash mr-1"></i> Xóa & Chọn lại
                                </button>
                            </div>
                        </div>
                    </div>

                    @error('poster_file')
                        <p class="mt-1 text-xs text-rose-400">{{ $message }}</p>
                    @enderror

                    <!-- LIVE MOCKUP PREVIEW: Xem trước hiển thị thực tế trên website -->
                    <div id="liveMockupSection" class="mt-6 pt-6 border-t border-slate-800/80 space-y-4">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-2">
                                <span class="w-2.5 h-2.5 rounded-full bg-emerald-400 animate-pulse"></span>
                                <span class="text-xs font-extrabold uppercase tracking-wider text-slate-300">
                                    Mô Phỏng Hiển Thị Thực Tế (Live Event Card Preview)
                                </span>
                            </div>
                            <span class="text-[11px] font-bold text-fuchsia-400 bg-fuchsia-950/60 px-2.5 py-0.5 rounded-full border border-fuchsia-800/60">
                                Realtime Sync
                            </span>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-12 gap-6 items-start p-5 rounded-3xl bg-slate-950/90 border border-slate-800 shadow-2xl">
                            
                            <!-- 1. The Main Event Card (Mô phỏng 1:1 Card Trang chủ) -->
                            <div class="md:col-span-7 flex justify-center">
                                <div class="w-full max-w-xs bg-slate-900 rounded-3xl overflow-hidden border border-slate-800 shadow-2xl flex flex-col group transition-all duration-300 hover:border-fuchsia-500/50">
                                    
                                    <!-- Poster Visual with overlays -->
                                    <div class="relative h-48 overflow-hidden bg-slate-950">
                                        <img id="mockupCardImage" src="https://images.unsplash.com/photo-1470225620780-dba8ba36b745?auto=format&fit=crop&w=1200&q=80"
                                            alt="Preview" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                                        <div class="absolute inset-0 bg-gradient-to-t from-slate-900 via-transparent to-transparent"></div>
                                        
                                        <!-- Event Type Badge -->
                                        <div class="absolute top-3 left-3">
                                            <span id="mockupTypeBadge" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-extrabold uppercase tracking-wider backdrop-blur-md bg-indigo-600/90 text-white">
                                                <i id="mockupTypeIcon" class="fa-solid fa-chair mr-1"></i>
                                                <span id="mockupTypeText">Vé Ghế Ngồi</span>
                                            </span>
                                        </div>

                                        <!-- Category Badge -->
                                        <div class="absolute top-3 right-3">
                                            <span id="mockupCategoryBadge" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-slate-950/80 text-fuchsia-400 border border-slate-800 backdrop-blur-md">
                                                Pop Music
                                            </span>
                                        </div>

                                        <!-- Artist Name Overlay -->
                                        <div class="absolute bottom-2.5 left-3 right-3">
                                            <span id="mockupArtistName" class="text-xs font-bold text-white drop-shadow-md truncate block">
                                                <i class="fa-solid fa-microphone-lines text-fuchsia-400 mr-1"></i> Chưa chọn nghệ sĩ
                                            </span>
                                        </div>
                                    </div>

                                    <!-- Card Body -->
                                    <div class="p-4 flex-1 flex flex-col justify-between space-y-3">
                                        <div>
                                            <h3 id="mockupCardTitle" class="text-sm font-extrabold text-white group-hover:text-fuchsia-400 transition-colors line-clamp-1">
                                                Anh Trai Say Hi - Live Concert 2026
                                            </h3>
                                            <p id="mockupCardVenue" class="text-[11px] text-slate-400 mt-1 flex items-center truncate">
                                                <i class="fa-solid fa-location-dot text-indigo-400 mr-1 shrink-0"></i>
                                                <span>SVĐ Mỹ Đình (Hà Nội)</span>
                                            </p>
                                        </div>

                                        <!-- Time & Price Footer -->
                                        <div class="pt-2.5 border-t border-slate-800/80 flex items-center justify-between text-xs">
                                            <div>
                                                <span class="text-[9px] text-slate-400 uppercase tracking-wider block">Thời gian</span>
                                                <span id="mockupCardDate" class="font-bold text-white text-[11px]">Chưa chọn ngày</span>
                                            </div>
                                            <div class="text-right">
                                                <span class="text-[9px] text-slate-400 uppercase tracking-wider block">Trạng thái</span>
                                                <span class="font-extrabold text-emerald-400 text-xs">Sắp mở bán</span>
                                            </div>
                                        </div>

                                        <div class="w-full py-2 rounded-xl bg-gradient-to-r from-fuchsia-600 to-indigo-600 text-white font-bold text-xs shadow-md shadow-fuchsia-600/20 text-center pointer-events-none">
                                            Xem Chi Tiết & Mua Vé
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- 2. Small Avatar & Thumbnail Formats -->
                            <div class="md:col-span-5 space-y-4">
                                <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400 block">
                                    Định Dạng Avatar & Icon Thu Nhỏ:
                                </span>

                                <!-- Format A: Dashboard Row Item (w-12 h-12) -->
                                <div class="p-3.5 rounded-2xl bg-slate-900 border border-slate-800 space-y-1.5">
                                    <span class="text-[11px] font-semibold text-slate-400 flex items-center">
                                        <i class="fa-solid fa-table-list text-purple-400 mr-1.5"></i> Thumbnail Dashboard BTC:
                                    </span>
                                    <div class="flex items-center space-x-3 p-2 rounded-xl bg-slate-950/60 border border-slate-800/50">
                                        <img id="mockupThumbSquare" src="https://images.unsplash.com/photo-1470225620780-dba8ba36b745?auto=format&fit=crop&w=1200&q=80"
                                            alt="" class="w-11 h-11 rounded-xl object-cover shrink-0 border border-slate-700 shadow-md">
                                        <div class="min-w-0">
                                            <p id="mockupThumbTitle" class="text-xs font-bold text-white truncate max-w-[130px]">Anh Trai Say Hi</p>
                                            <p id="mockupThumbVenue" class="text-[10px] text-slate-400 truncate max-w-[130px]">SVĐ Mỹ Đình</p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Format B: Avatar Tròn (Circle Avatar) -->
                                <div class="p-3.5 rounded-2xl bg-slate-900 border border-slate-800 space-y-1.5">
                                    <span class="text-[11px] font-semibold text-slate-400 flex items-center">
                                        <i class="fa-solid fa-circle-user text-indigo-400 mr-1.5"></i> Avatar Tròn (Biểu tượng show):
                                    </span>
                                    <div class="flex items-center space-x-3 p-2 rounded-xl bg-slate-950/60 border border-slate-800/50">
                                        <img id="mockupThumbCircle" src="https://images.unsplash.com/photo-1470225620780-dba8ba36b745?auto=format&fit=crop&w=1200&q=80"
                                            alt="" class="w-11 h-11 rounded-full object-cover shrink-0 border-2 border-fuchsia-500/60 shadow-lg shadow-fuchsia-500/20">
                                        <div class="min-w-0">
                                            <span class="text-xs font-bold text-white block">Hiển thị tròn</span>
                                            <span class="text-[10px] text-slate-400 block">Cắt tâm ảnh tự động</span>
                                        </div>
                                    </div>
                                </div>

                                <p class="text-[11px] text-slate-500 italic leading-snug">
                                    <i class="fa-solid fa-circle-check text-emerald-400 mr-1"></i> Tự động đồng bộ theo Tên sự kiện, Thể loại, Địa điểm và Nghệ sĩ bạn nhập.
                                </p>
                            </div>

                        </div>
                    </div>
                </div>

                <!-- Event Date -->
                <div>
                    <label for="event_date" class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-1.5">
                        Ngày & Giờ Biểu Diễn <span class="text-rose-500">*</span>
                    </label>
                    <input type="datetime-local" id="event_date" name="event_date" required value="{{ old('event_date') }}"
                        class="w-full px-4 py-3 rounded-xl bg-slate-950 border border-slate-800 text-white text-sm focus:outline-none focus:ring-2 focus:ring-purple-500">
                    @error('event_date')
                        <p class="mt-1 text-xs text-rose-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Category -->
                <div>
                    <label for="category_id" class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-1.5">
                        Thể Loại Âm Nhạc <span class="text-rose-500">*</span>
                    </label>
                    <select id="category_id" name="category_id" required
                        class="w-full px-4 py-3 rounded-xl bg-slate-950 border border-slate-800 text-white text-sm focus:outline-none focus:ring-2 focus:ring-purple-500">
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Venue -->
                <div>
                    <label for="venue_id" class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-1.5">
                        Địa Điểm Tổ Chức <span class="text-rose-500">*</span>
                    </label>
                    <select id="venue_id" name="venue_id" required
                        class="w-full px-4 py-3 rounded-xl bg-slate-950 border border-slate-800 text-white text-sm focus:outline-none focus:ring-2 focus:ring-purple-500">
                        @foreach($venues as $v)
                            <option value="{{ $v->id }}" {{ old('venue_id') == $v->id ? 'selected' : '' }}>
                                {{ $v->name }} ({{ $v->city }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Artist -->
                <div>
                    <label for="artist_id" class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-1.5">
                        Nghệ Sĩ Biểu Diễn Chính
                    </label>
                    <select id="artist_id" name="artist_id"
                        class="w-full px-4 py-3 rounded-xl bg-slate-950 border border-slate-800 text-white text-sm focus:outline-none focus:ring-2 focus:ring-purple-500">
                        <option value="">-- Chọn nghệ sĩ --</option>
                        @foreach($artists as $art)
                            <option value="{{ $art->id }}" {{ old('artist_id') == $art->id ? 'selected' : '' }}>
                                {{ $art->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Description -->
                <div class="sm:col-span-2">
                    <label for="description" class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-1.5">
                        Mô Tả Chi Tiết Sự Kiện <span class="text-rose-500">*</span>
                    </label>
                    <textarea id="description" name="description" rows="4" required
                        class="w-full px-4 py-3 rounded-xl bg-slate-950 border border-slate-800 text-white text-sm focus:outline-none focus:ring-2 focus:ring-purple-500"
                        placeholder="Nội dung chương trình, các tiết mục, lưu ý độ tuổi, trang phục...">{{ old('description') }}</textarea>
                </div>
            </div>
        </div>

        <!-- PHẦN 2: CẤU HÌNH LOẠI HÌNH & VÉ/GHẾ -->
        <div class="bg-slate-900/90 rounded-3xl p-6 sm:p-8 border border-slate-800 shadow-xl space-y-6">
            <h2 class="text-lg font-bold text-white flex items-center border-b border-slate-800 pb-4">
                <span class="w-7 h-7 rounded-lg bg-indigo-600 text-white flex items-center justify-center text-xs mr-2.5">2</span>
                <span>Cấu Hình Loại Hình Concert & Sơ Đồ Vé / Ghế</span>
            </h2>

            <!-- Event Type Switcher -->
            <div>
                <label class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-2">
                    Chọn Loại Hình Tổ Chức <span class="text-rose-500">*</span>
                </label>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <label class="relative flex items-center p-4 rounded-2xl border cursor-pointer transition-all border-slate-700 bg-slate-950/60 hover:border-indigo-500">
                        <input type="radio" name="event_type" value="seated" checked onchange="toggleEventType('seated')" class="w-4 h-4 text-indigo-600 focus:ring-indigo-500">
                        <div class="ml-3">
                            <span class="block text-sm font-bold text-white flex items-center">
                                <i class="fa-solid fa-chair text-indigo-400 mr-2"></i> Concert Ghế Ngồi (Seated)
                            </span>
                            <span class="block text-xs text-slate-400 mt-0.5">Hệ thống tự động sinh số ghế theo Khu, Hàng, Số ghế (Ví dụ: A-1-01, A-5-20).</span>
                        </div>
                    </label>

                    <label class="relative flex items-center p-4 rounded-2xl border cursor-pointer transition-all border-slate-700 bg-slate-950/60 hover:border-emerald-500">
                        <input type="radio" name="event_type" value="standing" onchange="toggleEventType('standing')" class="w-4 h-4 text-emerald-600 focus:ring-emerald-500">
                        <div class="ml-3">
                            <span class="block text-sm font-bold text-white flex items-center">
                                <i class="fa-solid fa-users text-emerald-400 mr-2"></i> Concert Vé Đứng (Standing GA)
                            </span>
                            <span class="block text-xs text-slate-400 mt-0.5">Khách hàng chọn số lượng vé đứng tự do, không phân định vị trí ghế cố định.</span>
                        </div>
                    </label>
                </div>
            </div>

            <!-- SEATED CONCERT CONFIGURATION -->
            <div id="seatedConfigSection" class="space-y-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-sm font-bold text-white">Danh Sách Khu Vực Ghế Ngồi</h3>
                        <p class="text-xs text-slate-400">Thêm các khu vực (Ví dụ: Khu VIP A, Khán Đài B) để hệ thống tự động sinh ghế.</p>
                    </div>
                    <button type="button" onclick="addSeatedZoneRow()" class="px-3.5 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-bold transition-colors flex items-center space-x-1.5">
                        <i class="fa-solid fa-plus"></i>
                        <span>Thêm Khu Ghế</span>
                    </button>
                </div>

                <div id="seatedZonesContainer" class="space-y-4">
                    <!-- Zone 1 Default -->
                    <div class="p-5 rounded-2xl bg-slate-950 border border-slate-800 space-y-4 seated-zone-card">
                        <div class="flex items-center justify-between border-b border-slate-800/80 pb-2">
                            <span class="text-xs font-extrabold text-indigo-400 uppercase tracking-wider">Khu vực #1</span>
                            <span class="text-[11px] text-slate-400 font-mono preview-calc">5 hàng x 20 ghế = 100 ghế</span>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-5 gap-3">
                            <div class="sm:col-span-2">
                                <label class="block text-[11px] text-slate-400 mb-1">Tên Hạng/Khu</label>
                                <input type="text" name="seated_zones[0][name]" value="Khu VIP A" required class="w-full px-3 py-2 rounded-lg bg-slate-900 border border-slate-700 text-white text-xs" placeholder="Khu VIP A">
                            </div>
                            <div>
                                <label class="block text-[11px] text-slate-400 mb-1">Mã Dãy (A, B...)</label>
                                <input type="text" name="seated_zones[0][zone_code]" value="A" maxlength="4" required class="w-full px-3 py-2 rounded-lg bg-slate-900 border border-slate-700 text-white text-xs uppercase" placeholder="A">
                            </div>
                            <div>
                                <label class="block text-[11px] text-slate-400 mb-1">Số Hàng</label>
                                <input type="number" name="seated_zones[0][total_rows]" value="5" min="1" max="50" required class="w-full px-3 py-2 rounded-lg bg-slate-900 border border-slate-700 text-white text-xs" placeholder="5">
                            </div>
                            <div>
                                <label class="block text-[11px] text-slate-400 mb-1">Ghế Mỗi Hàng</label>
                                <input type="number" name="seated_zones[0][seats_per_row]" value="20" min="1" max="100" required class="w-full px-3 py-2 rounded-lg bg-slate-900 border border-slate-700 text-white text-xs" placeholder="20">
                            </div>
                            <div class="sm:col-span-5 flex items-center justify-between pt-1">
                                <div class="w-48">
                                    <label class="block text-[11px] text-slate-400 mb-1">Đơn Giá Vé (VNĐ)</label>
                                    <input type="number" name="seated_zones[0][price]" value="1500000" min="10000" step="10000" required class="w-full px-3 py-2 rounded-lg bg-slate-900 border border-slate-700 text-emerald-400 font-bold text-xs" placeholder="1500000">
                                </div>
                                <div class="text-right">
                                    <span class="text-[11px] text-slate-400">Mã ghế sẽ sinh: </span>
                                    <span class="text-xs font-mono font-bold text-fuchsia-400">A-1-01 &rarr; A-5-20</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- STANDING CONCERT CONFIGURATION -->
            <div id="standingConfigSection" class="space-y-4 hidden">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-sm font-bold text-white">Danh Sách Hạng Vé Đứng</h3>
                        <p class="text-xs text-slate-400">Thêm các hạng vé đứng (Ví dụ: Early Bird, Standard GA).</p>
                    </div>
                    <button type="button" onclick="addStandingTicketRow()" class="px-3.5 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-bold transition-colors flex items-center space-x-1.5">
                        <i class="fa-solid fa-plus"></i>
                        <span>Thêm Hạng Vé</span>
                    </button>
                </div>

                <div id="standingTicketsContainer" class="space-y-3">
                    <div class="p-4 rounded-2xl bg-slate-950 border border-slate-800 grid grid-cols-1 sm:grid-cols-3 gap-3">
                        <div>
                            <label class="block text-[11px] text-slate-400 mb-1">Tên Hạng Vé</label>
                            <input type="text" name="standing_tickets[0][name]" value="Vé Đứng GA Standard" class="w-full px-3 py-2 rounded-lg bg-slate-900 border border-slate-700 text-white text-xs" placeholder="Ví dụ: Early Bird">
                        </div>
                        <div>
                            <label class="block text-[11px] text-slate-400 mb-1">Giá Vé (VNĐ)</label>
                            <input type="number" name="standing_tickets[0][price]" value="600000" min="10000" class="w-full px-3 py-2 rounded-lg bg-slate-900 border border-slate-700 text-emerald-400 font-bold text-xs" placeholder="600000">
                        </div>
                        <div>
                            <label class="block text-[11px] text-slate-400 mb-1">Tổng Số Lượng Vé</label>
                            <input type="number" name="standing_tickets[0][quantity]" value="500" min="1" class="w-full px-3 py-2 rounded-lg bg-slate-900 border border-slate-700 text-white text-xs" placeholder="500">
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- PHẦN 3: GỬI DUYỆT -->
        <div class="p-6 rounded-3xl bg-purple-950/30 border border-purple-500/30 flex flex-col sm:flex-row items-center justify-between gap-4 shadow-xl">
            <div class="text-xs text-slate-300 space-y-1">
                <p class="font-bold text-white flex items-center"><i class="fa-solid fa-shield-halved text-purple-400 mr-2"></i> Lưu ý phê duyệt:</p>
                <p>• Sau khi bấm gửi, concert sẽ ở trạng thái Chờ duyệt (Pending) để Admin kiểm duyệt nội dung.</p>
                <p>• Khi đã có khách mua vé, sơ đồ ghế và giá vé sẽ được khóa để đảm bảo an toàn đơn hàng.</p>
            </div>

            <button type="submit" class="w-full sm:w-auto px-8 py-4 rounded-2xl text-sm font-extrabold text-white bg-gradient-to-r from-purple-600 via-fuchsia-600 to-indigo-600 hover:from-purple-500 hover:to-indigo-500 shadow-xl shadow-fuchsia-600/30 transition-all shrink-0">
                <i class="fa-solid fa-paper-plane mr-2"></i> Lưu & Gửi Admin Duyệt Ngay
            </button>
        </div>

    </form>

</div>

@push('scripts')
<script>
    let zoneIndex = 1;
    let standingIndex = 1;

    function toggleEventType(type) {
        if (type === 'seated') {
            document.getElementById('seatedConfigSection').classList.remove('hidden');
            document.getElementById('standingConfigSection').classList.add('hidden');
        } else {
            document.getElementById('seatedConfigSection').classList.add('hidden');
            document.getElementById('standingConfigSection').classList.remove('hidden');
        }
    }

    function addSeatedZoneRow() {
        const container = document.getElementById('seatedZonesContainer');
        const card = document.createElement('div');
        card.className = 'p-5 rounded-2xl bg-slate-950 border border-slate-800 space-y-4 seated-zone-card';
        card.innerHTML = `
            <div class="flex items-center justify-between border-b border-slate-800/80 pb-2">
                <span class="text-xs font-extrabold text-indigo-400 uppercase tracking-wider">Khu vực #${zoneIndex + 1}</span>
                <button type="button" onclick="this.closest('.seated-zone-card').remove()" class="text-rose-400 hover:text-rose-300 text-xs font-semibold">
                    <i class="fa-solid fa-trash mr-1"></i> Xóa khu này
                </button>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-5 gap-3">
                <div class="sm:col-span-2">
                    <label class="block text-[11px] text-slate-400 mb-1">Tên Hạng/Khu</label>
                    <input type="text" name="seated_zones[${zoneIndex}][name]" value="Khán Đài B" required class="w-full px-3 py-2 rounded-lg bg-slate-900 border border-slate-700 text-white text-xs" placeholder="Khán Đài B">
                </div>
                <div>
                    <label class="block text-[11px] text-slate-400 mb-1">Mã Dãy</label>
                    <input type="text" name="seated_zones[${zoneIndex}][zone_code]" value="B" maxlength="4" required class="w-full px-3 py-2 rounded-lg bg-slate-900 border border-slate-700 text-white text-xs uppercase" placeholder="B">
                </div>
                <div>
                    <label class="block text-[11px] text-slate-400 mb-1">Số Hàng</label>
                    <input type="number" name="seated_zones[${zoneIndex}][total_rows]" value="8" min="1" max="50" required class="w-full px-3 py-2 rounded-lg bg-slate-900 border border-slate-700 text-white text-xs" placeholder="8">
                </div>
                <div>
                    <label class="block text-[11px] text-slate-400 mb-1">Ghế Mỗi Hàng</label>
                    <input type="number" name="seated_zones[${zoneIndex}][seats_per_row]" value="25" min="1" max="100" required class="w-full px-3 py-2 rounded-lg bg-slate-900 border border-slate-700 text-white text-xs" placeholder="25">
                </div>
                <div class="sm:col-span-5 flex items-center justify-between pt-1">
                    <div class="w-48">
                        <label class="block text-[11px] text-slate-400 mb-1">Đơn Giá Vé (VNĐ)</label>
                        <input type="number" name="seated_zones[${zoneIndex}][price]" value="800000" min="10000" step="10000" required class="w-full px-3 py-2 rounded-lg bg-slate-900 border border-slate-700 text-emerald-400 font-bold text-xs" placeholder="800000">
                    </div>
                </div>
            </div>
        `;
        container.appendChild(card);
        zoneIndex++;
    }

    function addStandingTicketRow() {
        const container = document.getElementById('standingTicketsContainer');
        const card = document.createElement('div');
        card.className = 'p-4 rounded-2xl bg-slate-950 border border-slate-800 grid grid-cols-1 sm:grid-cols-3 gap-3 relative';
        card.innerHTML = `
            <div>
                <label class="block text-[11px] text-slate-400 mb-1">Tên Hạng Vé</label>
                <input type="text" name="standing_tickets[${standingIndex}][name]" value="Vé Đứng VIP Early" class="w-full px-3 py-2 rounded-lg bg-slate-900 border border-slate-700 text-white text-xs" placeholder="Tên hạng">
            </div>
            <div>
                <label class="block text-[11px] text-slate-400 mb-1">Giá Vé (VNĐ)</label>
                <input type="number" name="standing_tickets[${standingIndex}][price]" value="900000" min="10000" class="w-full px-3 py-2 rounded-lg bg-slate-900 border border-slate-700 text-emerald-400 font-bold text-xs">
            </div>
            <div class="flex items-end space-x-2">
                <div class="flex-1">
                    <label class="block text-[11px] text-slate-400 mb-1">Số Lượng</label>
                    <input type="number" name="standing_tickets[${standingIndex}][quantity]" value="300" min="1" class="w-full px-3 py-2 rounded-lg bg-slate-900 border border-slate-700 text-white text-xs">
                </div>
                <button type="button" onclick="this.closest('.grid').remove()" class="p-2 rounded-lg bg-rose-950/60 text-rose-400 hover:bg-rose-900/60 text-xs">
                    <i class="fa-solid fa-trash"></i>
                </button>
            </div>
        `;
        container.appendChild(card);
        standingIndex++;
    }

    // Drag & Drop and Instant Image Preview
    const dropZone = document.getElementById('dropZone');
    const fileInput = document.getElementById('poster_file');
    const uploadPrompt = document.getElementById('uploadPrompt');
    const previewContainer = document.getElementById('previewContainer');
    const imagePreview = document.getElementById('imagePreview');
    const fileNameSpan = document.getElementById('fileName');
    const fileSizeSpan = document.getElementById('fileSize');

    if (dropZone) {
        ['dragenter', 'dragover'].forEach(eventName => {
            dropZone.addEventListener(eventName, (e) => {
                e.preventDefault();
                e.stopPropagation();
                dropZone.classList.add('border-purple-500', 'bg-purple-950/20');
            }, false);
        });

        ['dragleave', 'drop'].forEach(eventName => {
            dropZone.addEventListener(eventName, (e) => {
                e.preventDefault();
                e.stopPropagation();
                dropZone.classList.remove('border-purple-500', 'bg-purple-950/20');
            }, false);
        });

        dropZone.addEventListener('drop', (e) => {
            const dt = e.dataTransfer;
            const files = dt.files;
            if (files && files.length > 0) {
                fileInput.files = files;
                showFilePreview(files[0]);
            }
        });
    }

    function handleFileSelect(e) {
        const files = e.target.files;
        if (files && files.length > 0) {
            showFilePreview(files[0]);
        }
    }

    function showFilePreview(file) {
        if (!file.type.startsWith('image/')) {
            alert('Vui lòng chọn file hình ảnh hợp lệ (JPG, PNG, WEBP).');
            return;
        }
        if (file.size > 5 * 1024 * 1024) {
            alert('Dung lượng ảnh vượt quá 5MB. Vui lòng chọn ảnh nhỏ hơn.');
            return;
        }

        const reader = new FileReader();
        reader.onload = function(e) {
            imagePreview.src = e.target.result;
            fileNameSpan.textContent = file.name;
            fileSizeSpan.textContent = (file.size / (1024 * 1024)).toFixed(2) + ' MB';
            uploadPrompt.classList.add('hidden');
            previewContainer.classList.remove('hidden');
            updateMockupImages(e.target.result);
        };
        reader.readAsDataURL(file);
    }

    const defaultPlaceholder = 'https://images.unsplash.com/photo-1470225620780-dba8ba36b745?auto=format&fit=crop&w=1200&q=80';

    function updateMockupImages(src) {
        const cardImg = document.getElementById('mockupCardImage');
        const thumbSq = document.getElementById('mockupThumbSquare');
        const thumbCir = document.getElementById('mockupThumbCircle');
        if (cardImg) cardImg.src = src;
        if (thumbSq) thumbSq.src = src;
        if (thumbCir) thumbCir.src = src;
    }

    function syncLiveMockup() {
        const titleInput = document.getElementById('title');
        const eventDateInput = document.getElementById('event_date');
        const categorySelect = document.getElementById('category_id');
        const venueSelect = document.getElementById('venue_id');
        const artistSelect = document.getElementById('artist_id');
        const standingRadio = document.querySelector('input[name="event_type"][value="standing"]');

        // Title
        const title = (titleInput && titleInput.value.trim()) ? titleInput.value.trim() : 'Anh Trai Say Hi - Live Concert 2026';
        const cardTitle = document.getElementById('mockupCardTitle');
        const thumbTitle = document.getElementById('mockupThumbTitle');
        if (cardTitle) cardTitle.textContent = title;
        if (thumbTitle) thumbTitle.textContent = title;

        // Category
        const cardCategory = document.getElementById('mockupCategoryBadge');
        if (cardCategory && categorySelect && categorySelect.selectedIndex >= 0) {
            cardCategory.textContent = categorySelect.options[categorySelect.selectedIndex].text.trim();
        }

        // Venue
        const cardVenue = document.getElementById('mockupCardVenue');
        const thumbVenue = document.getElementById('mockupThumbVenue');
        if (venueSelect && venueSelect.selectedIndex >= 0) {
            const venueName = venueSelect.options[venueSelect.selectedIndex].text.trim();
            if (cardVenue) cardVenue.innerHTML = `<i class="fa-solid fa-location-dot text-indigo-400 mr-1 shrink-0"></i> <span>${venueName}</span>`;
            if (thumbVenue) thumbVenue.textContent = venueName;
        }

        // Artist
        const cardArtist = document.getElementById('mockupArtistName');
        if (cardArtist) {
            if (artistSelect && artistSelect.value && artistSelect.selectedIndex >= 0) {
                cardArtist.innerHTML = `<i class="fa-solid fa-microphone-lines text-fuchsia-400 mr-1"></i> ${artistSelect.options[artistSelect.selectedIndex].text.trim()}`;
            } else {
                cardArtist.innerHTML = `<i class="fa-solid fa-microphone-lines text-fuchsia-400 mr-1"></i> Chưa chọn nghệ sĩ`;
            }
        }

        // Event Date
        const cardDate = document.getElementById('mockupCardDate');
        if (cardDate) {
            if (eventDateInput && eventDateInput.value) {
                const d = new Date(eventDateInput.value);
                if (!isNaN(d.getTime())) {
                    const formatted = `${String(d.getHours()).padStart(2, '0')}:${String(d.getMinutes()).padStart(2, '0')} - ${String(d.getDate()).padStart(2, '0')}/${String(d.getMonth() + 1).padStart(2, '0')}/${d.getFullYear()}`;
                    cardDate.textContent = formatted;
                } else {
                    cardDate.textContent = eventDateInput.value;
                }
            } else {
                cardDate.textContent = 'Chưa chọn ngày';
            }
        }

        // Event Type (Seated vs Standing)
        const typeBadge = document.getElementById('mockupTypeBadge');
        const typeIcon = document.getElementById('mockupTypeIcon');
        const typeText = document.getElementById('mockupTypeText');
        if (typeBadge && standingRadio) {
            if (standingRadio.checked) {
                typeBadge.className = 'inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-extrabold uppercase tracking-wider backdrop-blur-md bg-emerald-600/90 text-white';
                if (typeIcon) typeIcon.className = 'fa-solid fa-users mr-1';
                if (typeText) typeText.textContent = 'Vé Đứng (GA)';
            } else {
                typeBadge.className = 'inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-extrabold uppercase tracking-wider backdrop-blur-md bg-indigo-600/90 text-white';
                if (typeIcon) typeIcon.className = 'fa-solid fa-chair mr-1';
                if (typeText) typeText.textContent = 'Vé Ghế Ngồi';
            }
        }
    }

    function removeSelectedFile() {
        if (fileInput) fileInput.value = '';
        if (imagePreview) imagePreview.src = '#';
        if (previewContainer) previewContainer.classList.add('hidden');
        if (uploadPrompt) uploadPrompt.classList.remove('hidden');
        updateMockupImages(defaultPlaceholder);
    }

    // Attach realtime sync event listeners
    document.addEventListener('DOMContentLoaded', () => {
        ['title', 'event_date', 'category_id', 'venue_id', 'artist_id'].forEach(id => {
            const el = document.getElementById(id);
            if (el) {
                el.addEventListener('input', syncLiveMockup);
                el.addEventListener('change', syncLiveMockup);
            }
        });
        document.querySelectorAll('input[name="event_type"]').forEach(r => {
            r.addEventListener('change', syncLiveMockup);
        });

        syncLiveMockup();
    });
</script>
@endpush
@endsection

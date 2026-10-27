@extends('layouts.app', ['title' => 'Thêm Danh Mục Hệ Thống - MEGATIX Admin'])

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    <div class="mb-8 flex items-center justify-between">
        <div>
            <a href="{{ route('admin.dashboard') }}" class="text-xs font-bold text-slate-400 hover:text-white flex items-center mb-2">
                <i class="fa-solid fa-arrow-left mr-1.5"></i> Quay lại Admin Dashboard
            </a>
            <h1 class="text-3xl font-extrabold text-white tracking-tight flex items-center">
                <i class="fa-solid fa-folder-plus text-fuchsia-400 mr-3"></i> Thêm & Quản Lý Danh Mục Hệ Thống
            </h1>
            <p class="mt-1 text-sm text-slate-400">Thêm mới và quản lý Thể loại âm nhạc, Địa điểm tổ chức và Nghệ sĩ biểu diễn.</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- 1. Thể Loại (Categories) -->
        <div class="bg-slate-900/90 rounded-3xl p-6 border border-slate-800 shadow-xl space-y-6">
            <h3 class="text-base font-bold text-white flex items-center">
                <i class="fa-solid fa-music text-purple-400 mr-2"></i> Thể Loại Âm Nhạc ({{ $categories->count() }})
            </h3>

            <!-- Add Form -->
            <form action="{{ route('admin.taxonomies.category.store') }}" method="POST" class="flex space-x-2">
                @csrf
                <input type="text" name="name" required placeholder="Tên thể loại mới..."
                    class="flex-1 px-3.5 py-2 rounded-xl bg-slate-950 border border-slate-800 text-white text-xs focus:outline-none focus:ring-2 focus:ring-purple-500">
                <button type="submit" class="px-3.5 py-2 rounded-xl bg-purple-600 hover:bg-purple-500 text-white text-xs font-bold shrink-0">
                    + Thêm
                </button>
            </form>

            <!-- List -->
            <div class="space-y-2 max-h-80 overflow-y-auto pr-1">
                @foreach($categories as $cat)
                    <div class="p-3 rounded-xl bg-slate-950/60 border border-slate-800/80 flex items-center justify-between text-xs">
                        <span class="font-bold text-white">{{ $cat->name }}</span>
                        <span class="text-slate-500 text-[11px]">{{ $cat->events_count }} concert</span>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- 2. Địa Điểm (Venues) -->
        <div class="bg-slate-900/90 rounded-3xl p-6 border border-slate-800 shadow-xl space-y-6">
            <h3 class="text-base font-bold text-white flex items-center">
                <i class="fa-solid fa-location-dot text-rose-400 mr-2"></i> Địa Điểm Tổ Chức ({{ $venues->count() }})
            </h3>

            <!-- Add Form -->
            <form action="{{ route('admin.taxonomies.venue.store') }}" method="POST" class="space-y-2">
                @csrf
                <input type="text" name="name" required placeholder="Tên địa điểm (SVĐ, Nhà thi đấu...)"
                    class="w-full px-3.5 py-2 rounded-xl bg-slate-950 border border-slate-800 text-white text-xs focus:outline-none focus:ring-2 focus:ring-rose-500">
                <input type="text" name="address" required placeholder="Địa chỉ chi tiết"
                    class="w-full px-3.5 py-2 rounded-xl bg-slate-950 border border-slate-800 text-white text-xs focus:outline-none focus:ring-2 focus:ring-rose-500">
                <div class="flex space-x-2">
                    <input type="text" name="city" required placeholder="Tỉnh / Thành phố"
                        class="flex-1 px-3.5 py-2 rounded-xl bg-slate-950 border border-slate-800 text-white text-xs focus:outline-none focus:ring-2 focus:ring-rose-500">
                    <button type="submit" class="px-3.5 py-2 rounded-xl bg-rose-600 hover:bg-rose-500 text-white text-xs font-bold shrink-0">
                        + Thêm
                    </button>
                </div>
            </form>

            <!-- List -->
            <div class="space-y-2 max-h-80 overflow-y-auto pr-1">
                @foreach($venues as $v)
                    <div class="p-3 rounded-xl bg-slate-950/60 border border-slate-800/80 text-xs">
                        <div class="flex items-center justify-between">
                            <span class="font-bold text-white">{{ $v->name }}</span>
                            <span class="text-slate-500 text-[11px]">{{ $v->events_count }} concert</span>
                        </div>
                        <p class="text-slate-400 text-[11px] mt-0.5 truncate">{{ $v->address }} ({{ $v->city }})</p>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- 3. Nghệ Sĩ (Artists) -->
        <div class="bg-slate-900/90 rounded-3xl p-6 border border-slate-800 shadow-xl space-y-6">
            <h3 class="text-base font-bold text-white flex items-center">
                <i class="fa-solid fa-microphone-lines text-indigo-400 mr-2"></i> Nghệ Sĩ Biểu Diễn ({{ $artists->count() }})
            </h3>

            <!-- Add Form -->
            <form action="{{ route('admin.taxonomies.artist.store') }}" method="POST" enctype="multipart/form-data" class="space-y-2">
                @csrf
                <input type="text" name="name" required placeholder="Tên nghệ sĩ / ban nhạc"
                    class="w-full px-3.5 py-2 rounded-xl bg-slate-950 border border-slate-800 text-white text-xs focus:outline-none focus:ring-2 focus:ring-indigo-500">
                
                <!-- File Upload -->
                <div class="space-y-1.5">
                    <label class="block text-[11px] text-slate-400 font-semibold">Tải ảnh đại diện từ máy:</label>
                    <input type="file" name="image_file" accept="image/png,image/jpeg,image/jpg,image/webp"
                        class="w-full text-xs text-slate-400 file:mr-2 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-indigo-600/20 file:text-indigo-400 hover:file:bg-indigo-600/30 cursor-pointer">
                </div>

                <div class="flex space-x-2">
                    <input type="text" name="bio" placeholder="Mô tả / Tiểu sử ngắn"
                        class="flex-1 px-3.5 py-2 rounded-xl bg-slate-950 border border-slate-800 text-white text-xs focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <button type="submit" class="px-3.5 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-bold shrink-0">
                        + Thêm
                    </button>
                </div>
            </form>

            <!-- List -->
            <div class="space-y-2 max-h-80 overflow-y-auto pr-1">
                @foreach($artists as $art)
                    <div class="p-3 rounded-xl bg-slate-950/60 border border-slate-800/80 flex items-center space-x-3 text-xs">
                        @if($art->image)
                            <img src="{{ $art->image_url }}" alt="{{ $art->name }}" class="w-8 h-8 rounded-lg object-cover shrink-0 border border-slate-800">
                        @endif
                        <div class="flex-1 min-w-0">
                            <span class="font-bold text-white block truncate">{{ $art->name }}</span>
                            <span class="text-slate-400 text-[11px] truncate block">{{ $art->bio ?? '-' }}</span>
                        </div>
                        <span class="text-slate-500 text-[11px] shrink-0">{{ $art->events_count }} show</span>
                    </div>
                @endforeach
            </div>
        </div>

    </div>

</div>
@endsection

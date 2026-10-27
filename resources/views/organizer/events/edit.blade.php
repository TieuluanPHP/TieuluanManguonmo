@extends('layouts.app', ['title' => 'Chỉnh Sửa Concert - ' . $event->title])

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    
    <div class="mb-8">
        <a href="{{ route('organizer.dashboard') }}" class="text-xs font-bold text-slate-400 hover:text-white flex items-center mb-2">
            <i class="fa-solid fa-arrow-left mr-1.5"></i> Quay lại Dashboard
        </a>
        <h1 class="text-3xl font-extrabold text-white tracking-tight">Chỉnh Sửa Concert</h1>
        <p class="mt-1 text-sm text-slate-400">{{ $event->title }}</p>
    </div>

    @if($hasSoldTickets)
        <div class="mb-6 p-4 rounded-2xl bg-amber-950/40 border border-amber-500/40 text-amber-300 text-xs flex items-center space-x-3">
            <i class="fa-solid fa-lock text-amber-400 text-xl shrink-0"></i>
            <div>
                <p class="font-bold text-sm text-amber-200">Sơ đồ vé & ghế ngồi đã được khóa bảo vệ!</p>
                <p>Sự kiện này đã có khách hàng mua vé. Để đảm bảo toàn vẹn dữ liệu đơn hàng và vé đã phát hành, bạn chỉ có thể cập nhật Tên, Poster và Mô tả sự kiện.</p>
            </div>
        </div>
    @endif

    <form action="{{ route('organizer.events.update', $event->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

        <div class="bg-slate-900/90 rounded-3xl p-6 sm:p-8 border border-slate-800 shadow-xl space-y-6">
            <div>
                <label for="title" class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-1.5">
                    Tên Concert <span class="text-rose-500">*</span>
                </label>
                <input type="text" id="title" name="title" required value="{{ old('title', $event->title) }}"
                    class="w-full px-4 py-3 rounded-xl bg-slate-950 border border-slate-800 text-white text-sm focus:outline-none focus:ring-2 focus:ring-purple-500">
            </div>

            <!-- Current Poster & Change Upload Zone -->
            <div class="space-y-3">
                <label class="block text-xs font-semibold uppercase tracking-wider text-slate-300">
                    Ảnh Poster / Banner Sự Kiện
                </label>

                <!-- Current Poster Preview -->
                <div class="flex items-center space-x-4 p-4 rounded-2xl bg-slate-950/80 border border-slate-800">
                    <img src="{{ $event->poster_url }}" alt="Poster hiện tại" class="w-24 h-24 object-cover rounded-xl border border-slate-700 shadow-md">
                    <div class="text-xs space-y-1">
                        <span class="font-bold text-white block">Poster Hiện Tại</span>
                        <p class="text-slate-400">Nếu bạn không tải ảnh mới, hệ thống sẽ giữ nguyên poster này.</p>
                    </div>
                </div>

                <!-- Drag & Drop Upload Container for New File -->
                <div id="dropZoneEdit" onclick="document.getElementById('poster_file_edit').click()"
                    class="relative border-2 border-dashed border-slate-700 hover:border-purple-500/80 bg-slate-950/60 hover:bg-slate-950/90 rounded-2xl p-6 text-center cursor-pointer transition-all duration-300 group">
                    
                    <input type="file" id="poster_file_edit" name="poster_file" accept="image/png,image/jpeg,image/jpg,image/webp" class="hidden" onchange="handleEditFileSelect(event)">

                    <div id="uploadPromptEdit" class="space-y-2">
                        <div class="w-10 h-10 mx-auto rounded-xl bg-purple-600/10 text-purple-400 flex items-center justify-center text-lg group-hover:scale-110 transition-transform">
                            <i class="fa-solid fa-cloud-arrow-up"></i>
                        </div>
                        <p class="text-xs font-bold text-white">
                            Kéo thả ảnh mới vào đây, hoặc <span class="text-purple-400 underline">chọn từ máy tính để thay thế</span>
                        </p>
                        <p class="text-[11px] text-slate-400">JPG, JPEG, PNG, WEBP (Tối đa 5MB)</p>
                    </div>

                    <div id="previewContainerEdit" class="hidden space-y-3">
                        <img id="imagePreviewEdit" src="#" alt="New Poster Preview" class="w-full max-w-sm h-40 object-cover rounded-xl mx-auto border border-purple-500 shadow-lg">
                        <p id="fileNameEdit" class="text-xs text-purple-300 font-bold"></p>
                        <button type="button" onclick="event.stopPropagation(); removeEditFile()" class="px-3 py-1 rounded-lg bg-rose-500/20 text-rose-400 text-xs font-semibold hover:bg-rose-500/30">
                            Hủy ảnh mới
                        </button>
                    </div>
                </div>

                @error('poster_file')
                    <p class="mt-1 text-xs text-rose-400">{{ $message }}</p>
                @enderror

                <!-- LIVE MOCKUP PREVIEW TRÊN WEBSITE -->
                <div class="mt-6 pt-6 border-t border-slate-800/80 space-y-4">
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
                        <!-- Main Card -->
                        <div class="md:col-span-7 flex justify-center">
                            <div class="w-full max-w-xs bg-slate-900 rounded-3xl overflow-hidden border border-slate-800 shadow-2xl flex flex-col group">
                                <div class="relative h-48 overflow-hidden bg-slate-950">
                                    <img id="mockupCardImageEdit" src="{{ $event->poster_url }}"
                                        alt="Preview" class="w-full h-full object-cover">
                                    <div class="absolute inset-0 bg-gradient-to-t from-slate-900 via-transparent to-transparent"></div>
                                    <div class="absolute top-3 left-3">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-extrabold uppercase tracking-wider backdrop-blur-md {{ $event->isSeated() ? 'bg-indigo-600/90' : 'bg-emerald-600/90' }} text-white">
                                            <i class="fa-solid {{ $event->isSeated() ? 'fa-chair' : 'fa-users' }} mr-1"></i>
                                            <span>{{ $event->isSeated() ? 'Vé Ghế Ngồi' : 'Vé Đứng (GA)' }}</span>
                                        </span>
                                    </div>
                                    <div class="absolute top-3 right-3">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-slate-950/80 text-fuchsia-400 border border-slate-800 backdrop-blur-md">
                                            {{ $event->category->name }}
                                        </span>
                                    </div>
                                    <div class="absolute bottom-2.5 left-3 right-3">
                                        <span class="text-xs font-bold text-white drop-shadow-md truncate block">
                                            <i class="fa-solid fa-microphone-lines text-fuchsia-400 mr-1"></i> {{ $event->artist->name ?? 'Nhiều nghệ sĩ' }}
                                        </span>
                                    </div>
                                </div>
                                <div class="p-4 space-y-3">
                                    <div>
                                        <h3 id="mockupCardTitleEdit" class="text-sm font-extrabold text-white line-clamp-1">
                                            {{ $event->title }}
                                        </h3>
                                        <p class="text-[11px] text-slate-400 mt-1 flex items-center truncate">
                                            <i class="fa-solid fa-location-dot text-indigo-400 mr-1 shrink-0"></i>
                                            <span>{{ $event->venue->name }}</span>
                                        </p>
                                    </div>
                                    <div class="pt-2.5 border-t border-slate-800/80 flex items-center justify-between text-xs">
                                        <div>
                                            <span class="text-[9px] text-slate-400 uppercase tracking-wider block">Thời gian</span>
                                            <span class="font-bold text-white text-[11px]">{{ $event->event_date->format('H:i - d/m/Y') }}</span>
                                        </div>
                                        <div class="text-right">
                                            <span class="text-[9px] text-slate-400 uppercase tracking-wider block">Trạng thái</span>
                                            <span class="font-extrabold text-emerald-400 text-xs">Đang hoạt động</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Mini Formats -->
                        <div class="md:col-span-5 space-y-3">
                            <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400 block">
                                Các Định Dạng Thu Nhỏ:
                            </span>

                            <div class="p-3 rounded-2xl bg-slate-900 border border-slate-800 space-y-1">
                                <span class="text-[11px] font-semibold text-slate-400 block">Thumbnail Dashboard:</span>
                                <div class="flex items-center space-x-3 p-1.5 rounded-xl bg-slate-950/60 border border-slate-800/50">
                                    <img id="mockupThumbSquareEdit" src="{{ $event->poster_url }}" class="w-10 h-10 rounded-xl object-cover shrink-0 border border-slate-700">
                                    <div class="min-w-0">
                                        <p id="mockupThumbTitleEdit" class="text-xs font-bold text-white truncate max-w-[130px]">{{ $event->title }}</p>
                                        <p class="text-[10px] text-slate-400 truncate max-w-[130px]">{{ $event->venue->name }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="p-3 rounded-2xl bg-slate-900 border border-slate-800 space-y-1">
                                <span class="text-[11px] font-semibold text-slate-400 block">Avatar Tròn (Biểu tượng show):</span>
                                <div class="flex items-center space-x-3 p-1.5 rounded-xl bg-slate-950/60 border border-slate-800/50">
                                    <img id="mockupThumbCircleEdit" src="{{ $event->poster_url }}" class="w-10 h-10 rounded-full object-cover shrink-0 border-2 border-fuchsia-500/60 shadow-lg shadow-fuchsia-500/20">
                                    <div class="min-w-0">
                                        <span class="text-xs font-bold text-white block">Hiển thị tròn</span>
                                        <span class="text-[10px] text-slate-400 block">Tự động căn tâm</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div>
                <label for="description" class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-1.5">
                    Mô Tả Chi Tiết <span class="text-rose-500">*</span>
                </label>
                <textarea id="description" name="description" rows="5" required
                    class="w-full px-4 py-3 rounded-xl bg-slate-950 border border-slate-800 text-white text-sm focus:outline-none focus:ring-2 focus:ring-purple-500">{{ old('description', $event->description) }}</textarea>
            </div>

            <!-- Current Ticket Tiers Readonly Info -->
            <div class="pt-4 border-t border-slate-800">
                <span class="block text-xs font-semibold uppercase tracking-wider text-slate-400 mb-3">
                    Danh Sách Hạng Vé / Khu Ghế Đã Thiết Lập
                </span>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    @foreach($event->ticketTypes as $type)
                        <div class="p-3.5 rounded-xl bg-slate-950 border border-slate-800/80 flex items-center justify-between text-xs">
                            <div>
                                <span class="font-bold text-white">{{ $type->name }}</span>
                                <span class="text-slate-500 block text-[11px]">{{ $type->zone_code ? 'Dãy ' . $type->zone_code : 'Vé Đứng' }} • Còn {{ $type->remaining_quantity }}/{{ $type->total_quantity }} vé</span>
                            </div>
                            <span class="font-extrabold text-emerald-400">{{ number_format($type->price, 0, ',', '.') }} đ</span>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="pt-4 flex items-center justify-end space-x-3">
                <a href="{{ route('organizer.dashboard') }}" class="px-5 py-3 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-300 text-xs font-bold">
                    Hủy bỏ
                </a>
                <button type="submit" class="px-6 py-3 rounded-xl bg-purple-600 hover:bg-purple-500 text-white text-xs font-bold shadow-lg shadow-purple-600/30">
                    <i class="fa-solid fa-floppy-disk mr-1.5"></i> Cập Nhật Sự Kiện
                </button>
            </div>
        </div>
    </form>

</div>
@endsection

@push('scripts')
<script>
    const dropZoneEdit = document.getElementById('dropZoneEdit');
    const fileInputEdit = document.getElementById('poster_file_edit');
    const uploadPromptEdit = document.getElementById('uploadPromptEdit');
    const previewContainerEdit = document.getElementById('previewContainerEdit');
    const imagePreviewEdit = document.getElementById('imagePreviewEdit');
    const fileNameEdit = document.getElementById('fileNameEdit');

    if (dropZoneEdit) {
        ['dragenter', 'dragover'].forEach(eventName => {
            dropZoneEdit.addEventListener(eventName, (e) => {
                e.preventDefault();
                e.stopPropagation();
                dropZoneEdit.classList.add('border-purple-500', 'bg-purple-950/20');
            }, false);
        });

        ['dragleave', 'drop'].forEach(eventName => {
            dropZoneEdit.addEventListener(eventName, (e) => {
                e.preventDefault();
                e.stopPropagation();
                dropZoneEdit.classList.remove('border-purple-500', 'bg-purple-950/20');
            }, false);
        });

        dropZoneEdit.addEventListener('drop', (e) => {
            const dt = e.dataTransfer;
            const files = dt.files;
            if (files && files.length > 0) {
                fileInputEdit.files = files;
                showEditPreview(files[0]);
            }
        });
    }

    function handleEditFileSelect(e) {
        const files = e.target.files;
        if (files && files.length > 0) {
            showEditPreview(files[0]);
        }
    }

    function showEditPreview(file) {
        if (!file.type.startsWith('image/')) {
            alert('Vui lòng chọn file hình ảnh hợp lệ (JPG, PNG, WEBP).');
            return;
        }
        if (file.size > 5 * 1024 * 1024) {
            alert('Dung lượng ảnh vượt quá 5MB. Vui lòng chọn ảnh nhỏ hơn.');
            return;
        }

        const originalPosterUrl = "{{ $event->poster_url }}";

        const reader = new FileReader();
        reader.onload = function(e) {
            imagePreviewEdit.src = e.target.result;
            fileNameEdit.textContent = file.name + ' (' + (file.size / (1024 * 1024)).toFixed(2) + ' MB)';
            uploadPromptEdit.classList.add('hidden');
            previewContainerEdit.classList.remove('hidden');

            // Update Mockups
            document.getElementById('mockupCardImageEdit').src = e.target.result;
            document.getElementById('mockupThumbSquareEdit').src = e.target.result;
            document.getElementById('mockupThumbCircleEdit').src = e.target.result;
        };
        reader.readAsDataURL(file);
    }

    function removeEditFile() {
        if (fileInputEdit) fileInputEdit.value = '';
        if (imagePreviewEdit) imagePreviewEdit.src = '#';
        if (previewContainerEdit) previewContainerEdit.classList.add('hidden');
        if (uploadPromptEdit) uploadPromptEdit.classList.remove('hidden');

        // Revert Mockups to original poster
        const originalPoster = "{{ $event->poster_url }}";
        document.getElementById('mockupCardImageEdit').src = originalPoster;
        document.getElementById('mockupThumbSquareEdit').src = originalPoster;
        document.getElementById('mockupThumbCircleEdit').src = originalPoster;
    }

    // Sync Title Realtime
    const titleInputEdit = document.getElementById('title');
    if (titleInputEdit) {
        titleInputEdit.addEventListener('input', function() {
            const val = this.value.trim() || 'Tên Concert Của Bạn';
            document.getElementById('mockupCardTitleEdit').textContent = val;
            document.getElementById('mockupThumbTitleEdit').textContent = val;
        });
    }
</script>
@endpush

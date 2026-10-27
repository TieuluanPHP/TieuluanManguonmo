@extends('layouts.app', ['title' => 'Đăng ký làm Đối tác Ban tổ chức - MEGATIX'])

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    
    <div class="text-center mb-10">
        <div class="inline-flex w-16 h-16 rounded-3xl bg-gradient-to-tr from-purple-600 via-fuchsia-600 to-indigo-600 items-center justify-center shadow-xl shadow-fuchsia-500/20 mb-4">
            <i class="fa-solid fa-handshake text-white text-3xl"></i>
        </div>
        <h1 class="text-3xl font-extrabold text-white tracking-tight">Hợp Tác Cùng MEGATIX</h1>
        <p class="mt-2 text-sm text-slate-400">
            Nâng cấp tài khoản thành Ban Tổ Chức (Organizer) để tự do đăng bán vé concert, tự sinh sơ đồ ghế và nhận 100% doanh thu.
        </p>
    </div>

    @if($profile && $profile->status === 'pending')
        <div class="bg-amber-950/40 border border-amber-500/40 rounded-3xl p-8 text-center space-y-4 shadow-xl">
            <div class="w-12 h-12 rounded-full bg-amber-500/20 text-amber-400 flex items-center justify-center mx-auto text-xl">
                <i class="fa-solid fa-hourglass-half"></i>
            </div>
            <h3 class="text-lg font-bold text-amber-300">Hồ Sơ Đang Chờ Xét Duyệt</h3>
            <p class="text-sm text-slate-300 max-w-md mx-auto">
                Yêu cầu đăng ký của đơn vị <strong>{{ $profile->company_name }}</strong> (SĐT: {{ $profile->phone }}) đã được chuyển đến Quản trị viên (Admin). Chúng tôi sẽ liên hệ và phê duyệt tài khoản trong thời gian sớm nhất!
            </p>
            <div class="pt-2">
                <a href="{{ route('home') }}" class="inline-flex items-center px-5 py-2.5 rounded-xl bg-slate-800 text-slate-300 hover:text-white text-xs font-semibold">
                    <i class="fa-solid fa-arrow-left mr-2"></i> Quay về Trang chủ
                </a>
            </div>
        </div>
    @else
        @if($profile && $profile->status === 'rejected')
            <div class="mb-6 p-4 rounded-2xl bg-rose-950/50 border border-rose-500/40 text-rose-300 text-sm">
                <p class="font-bold flex items-center"><i class="fa-solid fa-circle-xmark mr-2 text-rose-400"></i> Yêu cầu trước đó đã bị từ chối:</p>
                <p class="mt-1 text-xs text-rose-200/80">{{ $profile->notes ?? 'Chưa đáp ứng đủ tiêu chuẩn đối tác.' }}</p>
                <p class="mt-2 text-xs">Vui lòng cập nhật lại thông tin chính xác bên dưới để gửi yêu cầu mới.</p>
            </div>
        @endif

        <div class="bg-slate-900/90 rounded-3xl p-8 border border-slate-800 shadow-2xl">
            <form action="{{ route('organizer.request.post') }}" method="POST" class="space-y-6">
                @csrf

                <div>
                    <label for="company_name" class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-1.5">
                        Tên Công ty / Đơn vị tổ chức sự kiện <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" id="company_name" name="company_name" required
                        value="{{ old('company_name', $profile->company_name ?? '') }}"
                        class="w-full px-4 py-3 rounded-xl bg-slate-950 border {{ $errors->has('company_name') ? 'border-rose-500' : 'border-slate-800' }} text-white text-sm focus:outline-none focus:ring-2 focus:ring-fuchsia-500"
                        placeholder="Ví dụ: Công ty TNHH Giải trí & Sự kiện Âm nhạc Việt">
                    @error('company_name')
                        <p class="mt-1 text-xs text-rose-400">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="phone" class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-1.5">
                        Số điện thoại liên hệ chính thức <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" id="phone" name="phone" required
                        value="{{ old('phone', $profile->phone ?? '') }}"
                        class="w-full px-4 py-3 rounded-xl bg-slate-950 border {{ $errors->has('phone') ? 'border-rose-500' : 'border-slate-800' }} text-white text-sm focus:outline-none focus:ring-2 focus:ring-fuchsia-500"
                        placeholder="Ví dụ: 0912345678">
                    @error('phone')
                        <p class="mt-1 text-xs text-rose-400">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="notes" class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-1.5">
                        Giới thiệu kinh nghiệm tổ chức sự kiện (Tùy chọn)
                    </label>
                    <textarea id="notes" name="notes" rows="3"
                        class="w-full px-4 py-3 rounded-xl bg-slate-950 border border-slate-800 text-white text-sm focus:outline-none focus:ring-2 focus:ring-fuchsia-500"
                        placeholder="Đã từng tổ chức các concert/liveshow quy mô nào...">{{ old('notes', $profile->notes ?? '') }}</textarea>
                </div>

                <div class="p-4 rounded-2xl bg-indigo-950/40 border border-indigo-500/30 text-xs text-indigo-300 space-y-1">
                    <p class="font-bold flex items-center text-indigo-200"><i class="fa-solid fa-sparkles text-indigo-400 mr-2"></i> Cam kết nền tảng MEGATIX:</p>
                    <p>• <strong>0% phí hoa hồng sàn:</strong> Đối tác nhận trọn vẹn 100% doanh thu bán vé.</p>
                    <p>• <strong>Sinh sơ đồ ghế tự động:</strong> Hỗ trợ thiết lập concert ngồi hàng nghìn ghế chỉ trong 30 giây.</p>
                    <p>• <strong>Khóa ghế chống đặt trùng tuyệt đối:</strong> Bảo vệ uy tín thương hiệu của Ban tổ chức.</p>
                </div>

                <button type="submit" class="w-full py-4 px-6 rounded-xl text-sm font-bold text-white bg-gradient-to-r from-purple-600 via-fuchsia-600 to-indigo-600 hover:from-purple-500 hover:to-indigo-500 shadow-xl shadow-fuchsia-600/30 transition-all">
                    <i class="fa-solid fa-paper-plane mr-2"></i> Gửi Đơn Đăng Ký Đối Tác
                </button>
            </form>
        </div>
    @endif

</div>
@endsection

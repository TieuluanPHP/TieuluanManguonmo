@extends('layouts.app', ['title' => 'Đăng ký tài khoản mới - MEGATIX'])

@section('content')
<div class="min-h-[calc(100vh-16rem)] flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8 bg-slate-900/90 backdrop-blur-xl p-8 sm:p-10 rounded-3xl border border-slate-800 shadow-2xl">
        <div class="text-center">
            <div class="inline-flex w-14 h-14 rounded-2xl bg-gradient-to-tr from-fuchsia-600 via-pink-600 to-indigo-600 items-center justify-center shadow-lg shadow-fuchsia-500/30 mb-4">
                <i class="fa-solid fa-user-plus text-white text-2xl"></i>
            </div>
            <h2 class="text-3xl font-extrabold text-white tracking-tight">Đăng Ký Tài Khoản</h2>
            <p class="mt-2 text-sm text-slate-400">
                Đã có tài khoản? 
                <a href="{{ route('login') }}" class="font-semibold text-fuchsia-400 hover:text-fuchsia-300 transition-colors">
                    Đăng nhập tại đây
                </a>
            </p>
        </div>

        <form class="mt-8 space-y-6" action="{{ route('register.post') }}" method="POST">
            @csrf
            
            <div class="space-y-4">
                <div>
                    <label for="name" class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-1.5">
                        Tên tài khoản mong muốn
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <i class="fa-solid fa-user"></i>
                        </div>
                        <input id="name" name="name" type="text" required 
                            value="{{ old('name') }}"
                            class="block w-full pl-10 pr-4 py-3 bg-slate-950/80 border {{ $errors->has('name') ? 'border-rose-500' : 'border-slate-800' }} rounded-xl text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-fuchsia-500 focus:border-transparent text-sm transition-all"
                            placeholder="Ví dụ: myharuto, nguyenvan_a">
                    </div>
                    @error('name')
                        <p class="mt-1.5 text-xs text-rose-400 flex items-center"><i class="fa-solid fa-circle-exclamation mr-1"></i> {{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password" class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-1.5">
                        Mật khẩu (Tối thiểu 6 ký tự)
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <i class="fa-solid fa-key"></i>
                        </div>
                        <input id="password" name="password" type="password" required
                            class="block w-full pl-10 pr-4 py-3 bg-slate-950/80 border {{ $errors->has('password') ? 'border-rose-500' : 'border-slate-800' }} rounded-xl text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-fuchsia-500 focus:border-transparent text-sm transition-all"
                            placeholder="••••••••">
                    </div>
                    @error('password')
                        <p class="mt-1.5 text-xs text-rose-400 flex items-center"><i class="fa-solid fa-circle-exclamation mr-1"></i> {{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password_confirmation" class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-1.5">
                        Xác nhận lại mật khẩu
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <i class="fa-solid fa-shield-check"></i>
                        </div>
                        <input id="password_confirmation" name="password_confirmation" type="password" required
                            class="block w-full pl-10 pr-4 py-3 bg-slate-950/80 border border-slate-800 rounded-xl text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-fuchsia-500 focus:border-transparent text-sm transition-all"
                            placeholder="••••••••">
                    </div>
                </div>
            </div>

            <div class="p-4 rounded-xl bg-slate-950/60 border border-slate-800/80 text-xs text-slate-400 space-y-1">
                <p class="flex items-center text-slate-300 font-semibold"><i class="fa-solid fa-circle-info text-fuchsia-400 mr-1.5"></i> Quyền lợi thành viên:</p>
                <p>• Nhận ví nạp tiền nội bộ để mua vé tức thì, không lo nghẽn mạng.</p>
                <p>• Nhận mã vé điện tử 8 ký tự lưu trực tiếp trong tài khoản.</p>
                <p>• Có thể gửi yêu cầu nâng cấp lên tài khoản Ban tổ chức bất kỳ lúc nào.</p>
            </div>

            <div>
                <button type="submit" class="w-full flex justify-center py-3.5 px-4 rounded-xl text-sm font-bold text-white bg-gradient-to-r from-fuchsia-600 via-pink-600 to-indigo-600 hover:from-fuchsia-500 hover:to-indigo-500 shadow-lg shadow-fuchsia-600/30 hover:shadow-fuchsia-600/50 transition-all duration-200">
                    <i class="fa-solid fa-user-check mr-2 text-base"></i> Hoàn tất đăng ký & Đăng nhập
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

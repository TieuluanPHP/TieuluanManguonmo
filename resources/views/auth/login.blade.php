@extends('layouts.app', ['title' => 'Đăng nhập tài khoản - MEGATIX'])

@section('content')
<div class="min-h-[calc(100vh-16rem)] flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8 bg-slate-900/90 backdrop-blur-xl p-8 sm:p-10 rounded-3xl border border-slate-800 shadow-2xl">
        <div class="text-center">
            <div class="inline-flex w-14 h-14 rounded-2xl bg-gradient-to-tr from-fuchsia-600 via-pink-600 to-indigo-600 items-center justify-center shadow-lg shadow-fuchsia-500/30 mb-4">
                <i class="fa-solid fa-lock text-white text-2xl"></i>
            </div>
            <h2 class="text-3xl font-extrabold text-white tracking-tight">Đăng Nhập</h2>
            <p class="mt-2 text-sm text-slate-400">
                Chưa có tài khoản? 
                <a href="{{ route('register') }}" class="font-semibold text-fuchsia-400 hover:text-fuchsia-300 transition-colors">
                    Đăng ký ngay tại đây
                </a>
            </p>
        </div>

        <form class="mt-8 space-y-6" action="{{ route('login.post') }}" method="POST">
            @csrf
            
            <div class="space-y-4">
                <div>
                    <label for="name" class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-1.5">
                        Tên đăng nhập
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <i class="fa-solid fa-user"></i>
                        </div>
                        <input id="name" name="name" type="text" autocomplete="username" required 
                            value="{{ old('name') }}"
                            class="block w-full pl-10 pr-4 py-3 bg-slate-950/80 border {{ $errors->has('name') ? 'border-rose-500' : 'border-slate-800' }} rounded-xl text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-fuchsia-500 focus:border-transparent text-sm transition-all"
                            placeholder="Nhập tên đăng nhập của bạn">
                    </div>
                    @error('name')
                        <p class="mt-1.5 text-xs text-rose-400 flex items-center"><i class="fa-solid fa-circle-exclamation mr-1"></i> {{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password" class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-1.5">
                        Mật khẩu
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <i class="fa-solid fa-key"></i>
                        </div>
                        <input id="password" name="password" type="password" autocomplete="current-password" required
                            class="block w-full pl-10 pr-4 py-3 bg-slate-950/80 border {{ $errors->has('password') ? 'border-rose-500' : 'border-slate-800' }} rounded-xl text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-fuchsia-500 focus:border-transparent text-sm transition-all"
                            placeholder="••••••••">
                    </div>
                    @error('password')
                        <p class="mt-1.5 text-xs text-rose-400 flex items-center"><i class="fa-solid fa-circle-exclamation mr-1"></i> {{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="flex items-center justify-between text-sm">
                <label class="flex items-center space-x-2 cursor-pointer">
                    <input type="checkbox" name="remember" class="w-4 h-4 rounded bg-slate-950 border-slate-700 text-fuchsia-600 focus:ring-fuchsia-500 focus:ring-offset-slate-900">
                    <span class="text-xs text-slate-400">Ghi nhớ đăng nhập</span>
                </label>
            </div>

            <div>
                <button type="submit" class="w-full flex justify-center py-3.5 px-4 rounded-xl text-sm font-bold text-white bg-gradient-to-r from-fuchsia-600 via-pink-600 to-indigo-600 hover:from-fuchsia-500 hover:to-indigo-500 shadow-lg shadow-fuchsia-600/30 hover:shadow-fuchsia-600/50 transition-all duration-200">
                    <i class="fa-solid fa-right-to-bracket mr-2 text-base"></i> Đăng nhập vào hệ thống
                </button>
            </div>
        </form>

        <!-- Quick Demo Switcher -->
        <div class="pt-6 border-t border-slate-800">
            <p class="text-xs font-semibold uppercase tracking-wider text-slate-500 text-center mb-3">
                <i class="fa-solid fa-bolt text-amber-400 mr-1"></i> Tài khoản Demo mẫu có sẵn:
            </p>
            <div class="grid grid-cols-3 gap-2">
                <button type="button" onclick="fillAccount('admin', 'password123')" class="px-2.5 py-1.5 rounded-lg bg-slate-800/80 hover:bg-rose-950/40 border border-slate-700 hover:border-rose-500/50 text-[11px] font-semibold text-rose-300 transition-colors">
                    Admin
                </button>
                <button type="button" onclick="fillAccount('organizer1', 'password123')" class="px-2.5 py-1.5 rounded-lg bg-slate-800/80 hover:bg-purple-950/40 border border-slate-700 hover:border-purple-500/50 text-[11px] font-semibold text-purple-300 transition-colors">
                    Ban tổ chức
                </button>
                <button type="button" onclick="fillAccount('customer1', 'password123')" class="px-2.5 py-1.5 rounded-lg bg-slate-800/80 hover:bg-indigo-950/40 border border-slate-700 hover:border-indigo-500/50 text-[11px] font-semibold text-indigo-300 transition-colors">
                    Khách hàng
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    function fillAccount(username, password) {
        document.getElementById('name').value = username;
        document.getElementById('password').value = password;
    }
</script>
@endsection

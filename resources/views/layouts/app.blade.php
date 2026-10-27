<!DOCTYPE html>
<html lang="vi" class="h-full bg-slate-950 text-slate-100">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'MEGATIX - Nền tảng Đặt vé Concert & Sự kiện Âm nhạc Đỉnh cao' }}</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS CDN with Plugins -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'system-ui', 'sans-serif'],
                    },
                    colors: {
                        brand: {
                            50: '#fdf4ff',
                            100: '#fae8ff',
                            500: '#d946ef',
                            600: '#c026d3',
                            700: '#a21caf',
                        },
                        accent: {
                            500: '#6366f1',
                            600: '#4f46e5',
                        }
                    }
                }
            }
        }
    </script>
    
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style>
        body {
            font-family: 'Plus Jakarta Sans', system-ui, sans-serif;
        }
        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }
        ::-webkit-scrollbar-track {
            background: #0f172a;
        }
        ::-webkit-scrollbar-thumb {
            background: #334155;
            border-radius: 4px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #475569;
        }
    </style>
</head>
<body class="flex flex-col min-h-screen bg-slate-950 text-slate-100 selection:bg-fuchsia-500 selection:text-white">

    <!-- Top Navigation Bar -->
    <header class="sticky top-0 z-50 backdrop-blur-xl bg-slate-950/80 border-b border-slate-800/80">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-20">
                
                <!-- Logo & Brand -->
                <div class="flex items-center space-x-8">
                    <a href="{{ route('home') }}" class="flex items-center space-x-3 group">
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-fuchsia-600 via-purple-600 to-indigo-600 flex items-center justify-center shadow-lg shadow-fuchsia-500/20 group-hover:scale-105 transition-transform duration-300">
                            <i class="fa-solid fa-compact-disc text-white text-xl animate-spin-slow"></i>
                        </div>
                        <div class="flex flex-col">
                            <span class="text-2xl font-extrabold tracking-wider bg-gradient-to-r from-fuchsia-400 via-pink-400 to-indigo-400 bg-clip-text text-transparent">
                                MEGATIX
                            </span>
                            <span class="text-[10px] uppercase tracking-widest text-slate-400 font-semibold -mt-1">
                                LIVE CONCERT PASS
                            </span>
                        </div>
                    </a>

                    <!-- Nav Links -->
                    <nav class="hidden md:flex items-center space-x-1">
                        <a href="{{ route('home') }}" class="px-4 py-2 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('home') ? 'text-white bg-slate-800/80' : 'text-slate-300 hover:text-white hover:bg-slate-800/50' }}">
                            <i class="fa-solid fa-fire mr-1.5 text-fuchsia-400"></i> Sự kiện Hot
                        </a>
                        @auth
                            <a href="{{ route('tickets.my') }}" class="px-4 py-2 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('tickets.my') ? 'text-white bg-slate-800/80' : 'text-slate-300 hover:text-white hover:bg-slate-800/50' }}">
                                <i class="fa-solid fa-ticket mr-1.5 text-indigo-400"></i> Vé của tôi
                            </a>
                            <a href="{{ route('wallet.index') }}" class="px-4 py-2 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('wallet.index') ? 'text-white bg-slate-800/80' : 'text-slate-300 hover:text-white hover:bg-slate-800/50' }}">
                                <i class="fa-solid fa-wallet mr-1.5 text-emerald-400"></i> Ví nạp tiền
                            </a>
                        @endauth
                    </nav>
                </div>

                <!-- Right Action Navigation -->
                <div class="flex items-center space-x-4">
                    @auth
                        <!-- Wallet Balance Badge -->
                        <a href="{{ route('wallet.index') }}" title="Bấm để nạp thêm tiền" class="hidden sm:flex items-center space-x-2 px-3.5 py-1.5 rounded-full bg-slate-900 border border-emerald-500/30 hover:border-emerald-500/60 transition-colors shadow-sm">
                            <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                            <span class="text-xs text-slate-400 font-medium">Số dư ví:</span>
                            <span class="text-sm font-bold text-emerald-400">
                                {{ number_format(Auth::user()->balance, 0, ',', '.') }} đ
                            </span>
                            <i class="fa-solid fa-circle-plus text-xs text-emerald-400"></i>
                        </a>

                        <!-- Role Specific Badges / Links -->
                        @if(Auth::user()->isAdmin())
                            <a href="{{ route('admin.dashboard') }}" class="px-3.5 py-1.5 rounded-lg text-xs font-bold uppercase tracking-wider bg-rose-500/10 border border-rose-500/30 text-rose-400 hover:bg-rose-500/20 transition-colors">
                                <i class="fa-solid fa-shield-halved mr-1"></i> Admin Panel
                            </a>
                        @elseif(Auth::user()->isOrganizer())
                            <a href="{{ route('organizer.dashboard') }}" class="px-3.5 py-1.5 rounded-lg text-xs font-bold uppercase tracking-wider bg-purple-500/10 border border-purple-500/30 text-purple-400 hover:bg-purple-500/20 transition-colors">
                                <i class="fa-solid fa-building-columns mr-1"></i> Ban tổ chức
                            </a>
                        @elseif(Auth::user()->isCustomer())
                            <a href="{{ route('organizer.request') }}" class="hidden lg:flex items-center text-xs font-medium text-slate-400 hover:text-fuchsia-400 transition-colors">
                                <i class="fa-solid fa-handshake mr-1.5 text-fuchsia-400"></i> Trở thành Đối tác
                            </a>
                        @endif

                        <!-- User Profile Menu -->
                        <div class="relative group">
                            <button type="button" class="flex items-center space-x-2.5 p-1.5 rounded-xl bg-slate-800/60 border border-slate-700/60 hover:border-slate-600 transition-colors">
                                <div class="w-8 h-8 rounded-lg bg-gradient-to-tr from-fuchsia-600 to-indigo-600 flex items-center justify-center font-bold text-white text-xs">
                                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                </div>
                                <span class="hidden md:inline-block text-sm font-semibold text-slate-200">
                                    {{ Auth::user()->name }}
                                </span>
                                <i class="fa-solid fa-chevron-down text-xs text-slate-400 pr-1"></i>
                            </button>

                            <!-- Dropdown -->
                            <div class="absolute right-0 mt-2 w-56 bg-slate-900 border border-slate-800 rounded-2xl shadow-2xl py-2 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 transform origin-top-right">
                                <div class="px-4 py-2.5 border-b border-slate-800/80">
                                    <p class="text-xs text-slate-400">Tài khoản</p>
                                    <p class="text-sm font-bold text-white truncate">{{ Auth::user()->name }}</p>
                                    <span class="inline-block mt-1 px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider
                                        {{ Auth::user()->isAdmin() ? 'bg-rose-500/20 text-rose-300' : (Auth::user()->isOrganizer() ? 'bg-purple-500/20 text-purple-300' : 'bg-slate-700 text-slate-300') }}">
                                        {{ Auth::user()->role }}
                                    </span>
                                </div>

                                <a href="{{ route('tickets.my') }}" class="flex items-center px-4 py-2.5 text-sm text-slate-300 hover:bg-slate-800 hover:text-white transition-colors">
                                    <i class="fa-solid fa-ticket w-5 text-indigo-400 mr-2"></i> Vé của tôi
                                </a>
                                <a href="{{ route('wallet.index') }}" class="flex items-center px-4 py-2.5 text-sm text-slate-300 hover:bg-slate-800 hover:text-white transition-colors">
                                    <i class="fa-solid fa-wallet w-5 text-emerald-400 mr-2"></i> Ví & Nạp tiền
                                </a>

                                @if(Auth::user()->isAdmin())
                                    <div class="border-t border-slate-800/80 my-1"></div>
                                    <a href="{{ route('admin.dashboard') }}" class="flex items-center px-4 py-2.5 text-sm text-rose-300 hover:bg-slate-800 transition-colors">
                                        <i class="fa-solid fa-gauge w-5 mr-2"></i> Bảng Quản trị Admin
                                    </a>
                                    <a href="{{ route('admin.taxonomies.index') }}" class="flex items-center px-4 py-2.5 text-sm text-fuchsia-300 hover:bg-slate-800 transition-colors">
                                        <i class="fa-solid fa-folder-plus w-5 mr-2"></i> Thêm Danh Mục
                                    </a>
                                @elseif(Auth::user()->isOrganizer())
                                    <div class="border-t border-slate-800/80 my-1"></div>
                                    <a href="{{ route('organizer.dashboard') }}" class="flex items-center px-4 py-2.5 text-sm text-purple-300 hover:bg-slate-800 transition-colors">
                                        <i class="fa-solid fa-calendar-plus w-5 mr-2"></i> Quản lý Concert
                                    </a>
                                @else
                                    <div class="border-t border-slate-800/80 my-1"></div>
                                    <a href="{{ route('organizer.request') }}" class="flex items-center px-4 py-2.5 text-sm text-fuchsia-300 hover:bg-slate-800 transition-colors">
                                        <i class="fa-solid fa-handshake w-5 mr-2"></i> Đăng ký làm Đối tác
                                    </a>
                                @endif

                                <div class="border-t border-slate-800/80 my-1"></div>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="w-full flex items-center px-4 py-2.5 text-sm text-rose-400 hover:bg-rose-500/10 transition-colors">
                                        <i class="fa-solid fa-arrow-right-from-bracket w-5 mr-2"></i> Đăng xuất
                                    </button>
                                </form>
                            </div>
                        </div>

                    @else
                        <!-- Guest Buttons -->
                        <a href="{{ route('login') }}" class="px-4 py-2 rounded-xl text-sm font-semibold text-slate-300 hover:text-white hover:bg-slate-800/60 transition-colors">
                            Đăng nhập
                        </a>
                        <a href="{{ route('register') }}" class="px-5 py-2.5 rounded-xl text-sm font-bold bg-gradient-to-r from-fuchsia-600 via-pink-600 to-indigo-600 hover:from-fuchsia-500 hover:to-indigo-500 text-white shadow-lg shadow-fuchsia-600/30 hover:shadow-fuchsia-600/50 transition-all duration-200 transform hover:-translate-y-0.5">
                            Đăng ký ngay
                        </a>
                    @endauth
                </div>

            </div>
        </div>
    </header>

    <!-- Flash Notifications -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full mt-4">
        @if(session('success'))
            <div class="flex items-center p-4 mb-4 rounded-2xl bg-emerald-950/60 border border-emerald-500/40 text-emerald-200 shadow-lg shadow-emerald-950/50">
                <i class="fa-solid fa-circle-check text-emerald-400 text-xl mr-3"></i>
                <div class="text-sm font-medium">{{ session('success') }}</div>
            </div>
        @endif

        @if(session('error'))
            <div class="flex items-center p-4 mb-4 rounded-2xl bg-rose-950/60 border border-rose-500/40 text-rose-200 shadow-lg shadow-rose-950/50">
                <i class="fa-solid fa-circle-exclamation text-rose-400 text-xl mr-3"></i>
                <div class="text-sm font-medium">{{ session('error') }}</div>
            </div>
        @endif

        @if(session('warning'))
            <div class="flex items-center p-4 mb-4 rounded-2xl bg-amber-950/60 border border-amber-500/40 text-amber-200 shadow-lg shadow-amber-950/50">
                <i class="fa-solid fa-triangle-exclamation text-amber-400 text-xl mr-3"></i>
                <div class="text-sm font-medium">{{ session('warning') }}</div>
            </div>
        @endif
    </div>

    <!-- Main Content Area -->
    <main class="flex-grow">
        @yield('content')
    </main>

    <!-- Global Footer -->
    <footer class="mt-20 border-t border-slate-900 bg-slate-950/60 backdrop-blur-md">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div class="space-y-4">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 rounded-lg bg-gradient-to-tr from-fuchsia-600 to-indigo-600 flex items-center justify-center text-white">
                            <i class="fa-solid fa-compact-disc"></i>
                        </div>
                        <span class="text-xl font-bold bg-gradient-to-r from-fuchsia-400 to-indigo-400 bg-clip-text text-transparent">
                            MEGATIX
                        </span>
                    </div>
                    <p class="text-sm text-slate-400">
                        Nền tảng công nghệ đặt vé Live Concert & Đại nhạc hội hàng đầu. Trải nghiệm chọn chỗ ngồi trực tiếp, thanh toán ví siêu tốc không lo nghẽn mạng.
                    </p>
                </div>

                <div>
                    <h3 class="text-sm font-semibold uppercase tracking-wider text-slate-300 mb-4">Về MEGATIX</h3>
                    <ul class="space-y-2 text-sm text-slate-400">
                        <li><a href="{{ route('home') }}" class="hover:text-fuchsia-400 transition-colors">Tất cả sự kiện</a></li>
                        <li><a href="#" class="hover:text-fuchsia-400 transition-colors">Điều khoản dịch vụ</a></li>
                        <li><a href="#" class="hover:text-fuchsia-400 transition-colors">Chính sách bảo mật</a></li>
                        <li><a href="#" class="hover:text-fuchsia-400 transition-colors">Quy định hoàn vé khi hủy show</a></li>
                    </ul>
                </div>

                <div>
                    <h3 class="text-sm font-semibold uppercase tracking-wider text-slate-300 mb-4">Đối Tác & BTC</h3>
                    <ul class="space-y-2 text-sm text-slate-400">
                        @auth
                            <li><a href="{{ route('organizer.request') }}" class="hover:text-fuchsia-400 transition-colors">Đăng ký làm Ban tổ chức</a></li>
                        @else
                            <li><a href="{{ route('login') }}" class="hover:text-fuchsia-400 transition-colors">Đăng nhập Ban tổ chức</a></li>
                        @endauth
                        <li><a href="#" class="hover:text-fuchsia-400 transition-colors">Giải pháp quản lý ghế ngồi</a></li>
                        <li><a href="#" class="hover:text-fuchsia-400 transition-colors">Đối soát 0% phí sàn</a></li>
                    </ul>
                </div>

                <div>
                    <h3 class="text-sm font-semibold uppercase tracking-wider text-slate-300 mb-4">Hỗ Trợ Khách Hàng</h3>
                    <div class="space-y-3 text-sm text-slate-400">
                        <p class="flex items-center"><i class="fa-solid fa-headset w-5 text-fuchsia-400 mr-2"></i> Hotline: 1900 8888 (8:00 - 22:00)</p>
                        <p class="flex items-center"><i class="fa-solid fa-envelope w-5 text-indigo-400 mr-2"></i> support@megatix.local</p>
                        <p class="flex items-center"><i class="fa-solid fa-location-dot w-5 text-rose-400 mr-2"></i> Hà Nội & TP. Hồ Chí Minh</p>
                    </div>
                </div>
            </div>

            <div class="border-t border-slate-900 mt-10 pt-6 flex flex-col sm:flex-row items-center justify-between text-xs text-slate-400">
                <p>&copy; {{ date('Y') }} MEGATIX LIVE. All rights reserved. Laravel Concert Booking Platform.</p>
                <p class="mt-2 sm:mt-0 flex items-center space-x-1">
                    <span>Designed with passion for Live Music</span>
                    <i class="fa-solid fa-heart text-fuchsia-500"></i>
                </p>
            </div>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>

<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function showLogin(): View
    {
        return view('auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'name' => ['required', 'string'],
            'password' => ['required', 'string'],
        ], [
            'name.required' => 'Vui lòng nhập tên đăng nhập.',
            'password.required' => 'Vui lòng nhập mật khẩu.',
        ]);

        $remember = $request->boolean('remember');

        if (Auth::attempt(['name' => $credentials['name'], 'password' => $credentials['password']], $remember)) {
            $request->session()->regenerate();

            $user = Auth::user();
            if ($user->isAdmin()) {
                return redirect()->intended(route('admin.dashboard'))->with('success', 'Chào mừng Admin quay trở lại!');
            }
            if ($user->isOrganizer()) {
                return redirect()->intended(route('organizer.dashboard'))->with('success', 'Chào mừng Ban tổ chức quay trở lại!');
            }

            return redirect()->intended(route('home'))->with('success', "Chào mừng bạn {$user->name} đã đăng nhập thành công!");
        }

        return back()->withErrors([
            'name' => 'Tên đăng nhập hoặc mật khẩu không chính xác.',
        ])->onlyInput('name');
    }

    public function showRegister(): View
    {
        return view('auth.register');
    }

    public function register(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'min:3', 'max:50', 'unique:users,name'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ], [
            'name.required' => 'Vui lòng nhập tên tài khoản.',
            'name.unique' => 'Tên tài khoản này đã được sử dụng.',
            'name.min' => 'Tên tài khoản phải có ít nhất 3 ký tự.',
            'password.required' => 'Vui lòng nhập mật khẩu.',
            'password.min' => 'Mật khẩu phải có ít nhất 6 ký tự.',
            'password.confirmed' => 'Xác nhận mật khẩu không trùng khớp.',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'password' => Hash::make($validated['password']),
            'role' => 'customer',
            'balance' => 0,
        ]);

        Auth::login($user);

        return redirect()->route('home')->with('success', 'Đăng ký tài khoản thành công! Bạn có thể nạp ví và mua vé ngay.');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('success', 'Đã đăng xuất tài khoản thành công.');
    }
}

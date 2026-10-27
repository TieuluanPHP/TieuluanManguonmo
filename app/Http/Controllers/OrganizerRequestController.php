<?php

namespace App\Http\Controllers;

use App\Models\OrganizerProfile;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class OrganizerRequestController extends Controller
{
    public function show(): View|RedirectResponse
    {
        $user = Auth::user();

        if ($user->isOrganizer()) {
            return redirect()->route('organizer.dashboard')->with('info', 'Bạn đã là Ban tổ chức của hệ thống.');
        }

        $profile = $user->organizerProfile;

        return view('organizer.request', compact('user', 'profile'));
    }

    public function submit(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'company_name' => ['required', 'string', 'min:3', 'max:150'],
            'phone' => ['required', 'string', 'regex:/^[0-9]{9,12}$/'],
            'notes' => ['nullable', 'string', 'max:500'],
        ], [
            'company_name.required' => 'Vui lòng nhập tên công ty hoặc đơn vị tổ chức.',
            'phone.required' => 'Vui lòng nhập số điện thoại liên hệ.',
            'phone.regex' => 'Số điện thoại không đúng định dạng (9 - 12 chữ số).',
        ]);

        $user = Auth::user();

        OrganizerProfile::updateOrCreate(
            ['user_id' => $user->id],
            [
                'company_name' => $validated['company_name'],
                'phone' => $validated['phone'],
                'notes' => $validated['notes'],
                'status' => 'pending',
            ]
        );

        return redirect()->route('organizer.request')->with('success', 'Yêu cầu đăng ký Đối tác đã được gửi đến Admin. Vui lòng chờ phê duyệt!');
    }
}

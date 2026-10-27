<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OrganizerProfile;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class OrganizerModerationController extends Controller
{
    public function index(): View
    {
        $profiles = OrganizerProfile::with('user')->latest()->paginate(15);

        return view('admin.organizers.index', compact('profiles'));
    }

    public function approve(int $id): RedirectResponse
    {
        $profile = OrganizerProfile::with('user')->findOrFail($id);

        DB::transaction(function () use ($profile) {
            $profile->update(['status' => 'approved']);
            $profile->user->update(['role' => 'organizer']);
        });

        return redirect()->back()->with('success', "Đã phê duyệt đối tác {$profile->company_name}. Tài khoản {$profile->user->name} đã được nâng cấp lên Ban tổ chức!");
    }

    public function reject(Request $request, int $id): RedirectResponse
    {
        $profile = OrganizerProfile::findOrFail($id);

        $reason = $request->input('notes', 'Thông tin đăng ký chưa đáp ứng tiêu chuẩn đối tác.');

        $profile->update([
            'status' => 'rejected',
            'notes' => $reason,
        ]);

        return redirect()->back()->with('success', "Đã từ chối đơn đăng ký của {$profile->company_name}.");
    }
}

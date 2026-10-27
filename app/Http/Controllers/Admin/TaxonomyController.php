<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Artist;
use App\Models\Category;
use App\Models\Venue;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class TaxonomyController extends Controller
{
    public function index(): View
    {
        $categories = Category::withCount('events')->get();
        $venues = Venue::withCount('events')->get();
        $artists = Artist::withCount('events')->get();

        return view('admin.taxonomies.index', compact('categories', 'venues', 'artists'));
    }

    public function storeCategory(Request $request): RedirectResponse
    {
        $request->validate(['name' => ['required', 'string', 'max:100', 'unique:categories,name']]);
        Category::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
        ]);

        return redirect()->back()->with('success', 'Đã thêm Thể loại mới thành công!');
    }

    public function storeVenue(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'address' => ['required', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:100'],
        ]);
        Venue::create($request->only('name', 'address', 'city'));

        return redirect()->back()->with('success', 'Đã thêm Địa điểm mới thành công!');
    }

    public function storeArtist(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'bio' => ['nullable', 'string'],
            'image_file' => ['nullable', 'file', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ], [
            'image_file.image' => 'File tải lên phải là hình ảnh hợp lệ (jpg, jpeg, png, webp).',
            'image_file.max' => 'Kích thước ảnh đại diện không được vượt quá 5MB.',
        ]);

        $imagePath = null;
        if ($request->hasFile('image_file')) {
            $imagePath = $request->file('image_file')->store('artists', 'public');
        }

        Artist::create([
            'name' => $request->name,
            'bio' => $request->bio,
            'image' => $imagePath,
        ]);

        return redirect()->back()->with('success', 'Đã thêm Nghệ sĩ mới thành công!');
    }
}

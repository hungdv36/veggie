<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Banner;


class BannerController extends Controller
{
    public function index()
    {
        $banners = Banner::orderBy('id', 'DESC')->get();

        return view('admin.banners.index', compact('banners'));
    }

    public function create()
    {
        return view('admin.banners.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'  => 'required',
            'image'  => 'required|image',
            'url'    => 'nullable|string',
            'status' => 'nullable|in:0,1',

        ]);

        // Lưu ảnh
     $imageName = time() . '.' . $request->image->extension();
$request->image->move(public_path('uploads/banners'), $imageName);


        // Lưu DB
      Banner::create([
    'title'  => $request->title,
    'image'  => $imageName,
    'url'    => $request->url,
    'status' => $request->status ?? 1,   // <-- đặt ở đây mới đúng
]);


        return redirect()
            ->route('admin.banners.index')
            ->with('success', 'Thêm banner thành công!');
    }

    public function edit($id)
    {
        $banner = Banner::findOrFail($id);
        return view('admin.banners.edit', compact('banner'));
    }

    public function update(Request $request, $id)
    {
        $banner = Banner::findOrFail($id);

        $request->validate([
            'title'  => 'required',
            'image'  => 'nullable|image',
            'url'    => 'nullable|string',
            'status' => 'required|in:0,1',
        ]);

        // Nếu có ảnh mới
        if ($request->hasFile('image')) {
            $imageName = time() . '.' . $request->image->extension();
            $request->image->move(public_path('uploads/banners'), $imageName);

            // xóa ảnh cũ
            if (file_exists(public_path('uploads/banners/' . $banner->image))) {
                unlink(public_path('uploads/banners/' . $banner->image));
            }

            $banner->image = $imageName;
        }

        $banner->title  = $request->title;
        $banner->url    = $request->url;
        $banner->status = $request->status;
        $banner->save();

        return redirect()
            ->route('admin.banners.index')
            ->with('success', 'Cập nhật banner thành công!');
    }

    public function destroy($id)
    {
        $banner = Banner::findOrFail($id);

        // Xóa ảnh
        if (file_exists(public_path('uploads/banners/' . $banner->image))) {
            unlink(public_path('uploads/banners/' . $banner->image));
        }

        $banner->delete();

        return redirect()
            ->route('admin.banners.index')
            ->with('success', 'Xóa banner thành công!');
    }
}
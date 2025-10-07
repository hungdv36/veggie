<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function ShowForm()
    {
        return view('admin.pages.categories-add');
    }

   public function addCategory(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
        'image' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
    ]);

    $imagePath = null;

    if ($request->hasFile('image')) {
        $file = $request->file('image');
        $fileName = now()->timestamp . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
        $imagePath = $file->storeAs('upload/categories', $fileName, 'public');
    }

    Category::create([
        'name' => $request->input('name'),
        'description' => $request->input('description'),
        'image' => $imagePath,
    ]);

    return redirect()
        ->route('admin.categories.add')
        ->with('success', 'Danh mục đã được thêm thành công!');
}

public function index()
{
    $categories = Category::all();
    return view('admin.pages.categories', compact('categories'));
}


}

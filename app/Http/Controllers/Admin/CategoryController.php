<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::orderBy('id', 'desc')->paginate(10);
        return view('admin.pages.category.categories', compact('categories'));
    }
    public function showFormAddCate()
    {
        $categories = Category::latest()->paginate(10);
        return view('admin.pages.category.categories-add', compact('categories'));
    }
    public function addCategory(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('assets/admin/img/category'), $fileName);
            $imagePath = $fileName; // 👈 chỉ lưu tên file để hiển thị
        }

        Category::create([
            'name' => $request->name,
            'description' => $request->description,
            'image' => $imagePath,
        ]);

        return redirect()->route('admin.categories.index')->with('success', 'Thêm danh mục thành công!');
    }
    public function updateCategory(Request $request)
    {
        try {
            $category = Category::findOrFail($request->category_id);
            if (!$category) {
                return response()->json([
                    'status' => false,
                    'message' => 'Danh mục không tồn tại'
                ], 404);
            }

            $category->name = $request->name;
            $category->description = $request->description;

            if ($request->hasFile("image")) {
                if ($category->image) {
                    // Delete old image
                    Storage::disk('public')->delete('category/' . $category->image);
                }

                $imagePath = $request->file('image');
                $fileName = now()->timestamp . '_' . uniqid() . '.' . $imagePath->getClientOriginalExtension();
                $imagePath->move(public_path('assets/admin/img/category'), $fileName);
                $category->image = $fileName;
            }
            $category->save();

            return response()->json([
                'status' => true,
                'message' => 'Cập nhật danh mục thành công',
                'category' => $category
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => 'Cập nhật danh mục thất bại'
            ], 500);
        }
    }
    public function deleteCategory(Request $request)
    {
        try {
            $category = Category::findOrFail($request->category_id);

            // Kiểm tra danh mục có sản phẩm nào không
            if ($category->products()->exists()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Không thể xóa danh mục vì có sản phẩm thuộc danh mục này'
                ], 400);
            }

            // Soft delete
            $category->delete();

            return response()->json([
                'status' => true,
                'message' => 'Xóa danh mục thành công'
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => 'Xóa danh mục thất bại: ' . $th->getMessage()
            ], 500);
        }
    }

    public function trash()
    {
        $categories = Category::onlyTrashed()->orderBy('id', 'desc')->paginate(10);
        return view('admin.pages.category.categories-trash', compact('categories'));
    }

    public function restoreCategory(Request $request)
    {
        try {
            $category = Category::onlyTrashed()->findOrFail($request->category_id);
            $category->restore();

            return response()->json(['status' => true, 'message' => 'Khôi phục danh mục thành công']);
        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'message' => 'Khôi phục thất bại'], 500);
        }
    }

    public function forceDeleteCategory(Request $request)
    {
        try {
            $category = Category::onlyTrashed()->findOrFail($request->category_id);

            if ($category->image) {
                $imagePath = public_path('assets/admin/img/category/' . $category->image);
                if (file_exists($imagePath)) {
                    unlink($imagePath); // Xóa file thật sự
                }
            }

            $category->forceDelete();

            return response()->json(['status' => true, 'message' => 'Xóa vĩnh viễn thành công']);
        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'message' => 'Xóa vĩnh viễn thất bại'], 500);
        }
    }
}

<?php


namespace App\Http\Controllers\Clients;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $query = trim($request->input('query')); // loại bỏ khoảng trắng thừa
        $products = collect();

        if ($query !== '') {
            $products = Product::query()
                ->with(['category', 'variants.color', 'variants.size'])
                ->where(function ($q) use ($query) {
                    $q->where('name', 'like', "%{$query}%")
                        ->orWhere('description', 'like', "%{$query}%")
                        ->orWhere('brand', 'like', "%{$query}%")
                        ->orWhereHas('category', function ($sub) use ($query) {
                            $sub->where('name', 'like', "%{$query}%");
                        })
                        ->orWhereHas('variants.color', function ($sub) use ($query) {
                            $sub->where('name', 'like', "%{$query}%");
                        })
                        ->orWhereHas('variants.size', function ($sub) use ($query) {
                            $sub->where('name', 'like', "%{$query}%");
                        });
                })
                ->orderByRaw("CASE WHEN name LIKE ? THEN 0 ELSE 1 END", ["%{$query}%"]) // ưu tiên kết quả gần khớp
                ->orderBy('created_at', 'desc')
                ->paginate(12);
        }

        return view('clients.pages.products-search', compact('products', 'query'));
    }
}

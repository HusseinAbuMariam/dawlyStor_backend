<?php


namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::query()
            ->with(['store:id,name,slug,category_id'])
            ->active()
            ->accepted()
            ->when($request->store_category, function ($q) use ($request) {
                $q->whereHas('store.category', function ($q) use ($request) {
                    $q->where('slug', $request->store_category);
                });
            })
            ->when($request->search, function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%');
            })
            ->latest()
            ->get();

        return successResponse(
            ProductResource::collection($products),
            'Products fetched successfully'
        );
    }

    public function show($store_slug, $product_slug)
    {
        $product = Product::with(['store', 'category', 'additions', 'options'])
            ->accepted()
            ->active()
            ->whereHas('store', function ($query) use ($store_slug) {
                $query->where('slug', $store_slug);
            })
            ->where('slug', $product_slug)
            ->firstOrFail();

        return successResponse(
            ProductResource::make($product),
            'Product fetched successfully'
        );
    }
}

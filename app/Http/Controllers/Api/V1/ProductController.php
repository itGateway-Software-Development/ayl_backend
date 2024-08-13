<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProductController extends Controller
{
    public function index() {
        $products = Product::with('media', 'series')->get();

        return response()->json(['products' => ProductResource::collection($products)]);
    }

    public function show(Product $product) {

        return response()->json(['product' => new ProductResource($product->load('media'))]);
    }

    public function getRandomProducts() {
        $products = Product::with('media', 'series')->inRandomOrder()->take(4)->get();

        return response()->json(['products' => ProductResource::collection($products)]);
    }

    public function searchProducts(Request $request) {
        $keyword = $request->search;

        if (isset($keyword)) {
            $products = Product::where('name', 'like', "%$keyword%")
                                ->orWhere('product_info', 'like', "%$keyword%")
                                ->orWhereHas('series', function($query) use ($keyword) {
                                    $query->where('name', 'like', "%$keyword%");
                                })
                                ->get();
        } else {
            $products = [];
        }

        return response()->json(['products' => ProductResource::collection($products)]);
    }
}

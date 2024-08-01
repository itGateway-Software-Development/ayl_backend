<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProductController extends Controller
{
    public function index() {
        $products = Product::with('media')->get();

        return response()->json(['products' => ProductResource::collection($products)]);
    }

    public function show(Product $product) {

        return response()->json(['product' => new ProductResource($product->load('media'))]);
    }
}

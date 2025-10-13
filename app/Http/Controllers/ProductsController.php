<?php

namespace App\Http\Controllers;

use App\Models\Product;

class ProductsController extends Controller
{
    public function list()
    {
        $products = Product::query()->paginate(10);

        return response()->json($products);
    }
}

<?php

namespace App\Http\Repositories;

use App\Http\Controllers\ResponseTrait;
use App\Models\PublishedProduct;

use Illuminate\Support\Facades\Cache;

class PublishedProductRepository
{

    use ResponseTrait;

    public function index($per_page = 10)
    {

        $products = PublishedProduct::where('is_active', 1)->orderBy('id')->paginate($per_page);

        $metaData = [
            "current_page" => $products->currentPage(),
            "per_page" => $products->perPage(),
            "last_page" => $products->lastPage(),
            "total" => $products->total(),
            "current_page_record" => $products->count(),
        ];

        // Log::info('Products Data from Database');

        return [
            'products' => $products->items(),
            'meta' => $metaData
        ];
    }

    public function get($id){
        $product = PublishedProduct::find($id);
        if (!$product) {
            return $this->ErrRes(404, null, 'Product not found');
        }
        Cache::put('product_'.$id, $product, 600);
        return $this->Res(200, $product, 'Product data successfully retrieved');
    }

    public function search($request){
        $request->validate([
            'search' => 'required|string',
            'per_page' => 'integer|min:1',
            'page' => 'integer|min:1',
        ]);

        $search = $request->search;
        $per_page = $request->per_page ?? 10;

        $products = PublishedProduct::where('product_name', 'ILIKE', '%'.$search.'%')->orWhere('combination_string', 'ILIKE', '%'.$search.'%')->paginate($per_page);

        $metaData = [
            "current_page" => $products->currentPage(),
            "per_page" => $products->perPage(),
            "last_page" => $products->lastPage(),
            "total" => $products->total(),
            "current_page_record" => $products->count(),
        ];

        return $this->Res(200, [
            'products' => $products->items(),
            'meta' => $metaData
        ], "Products Data Successfully Recieved.");
    }

    public function changeStatus($request){
        $product = PublishedProduct::where('product_code', $request->product_code)->first();
        if (!$product) {
            return $this->ErrRes(404, null, 'Product not found');
        }

        $product->is_active = $request->status;
        $product->save();

        return $this->Res(200, $product, 'Product status updated successfully');
    }
}
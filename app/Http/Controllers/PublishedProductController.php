<?php

namespace App\Http\Controllers;

use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

use App\Models\PublishedProduct;
use App\http\Repositories\PublishedProductRepository;

class PublishedProductController extends Controller
{
    use ResponseTrait;

    private PublishedProductRepository $publishedProductRepository;
    public function __construct(PublishedProductRepository $publishedProductRepository)
    {
        $this->publishedProductRepository = $publishedProductRepository;
    }

    public function index(Request $request)
    {
        $request->validate([
            'page' => 'integer|min:1',
            'per_page' => 'integer|min:1',
        ]);
        $page = $request->page ?? 1;
        $per_page = $request->per_page ?? 10;

        if (Cache::has('published_products_' . $page . '_' . $per_page)) {
            $cachedData = Cache::get('published_products_' . $page . '_' . $per_page);
            $products = $cachedData['products'];
            $metaData = $cachedData['meta'];

            return $this->Res(200, [
                'products' => $products,
                'meta' => $metaData
            ], "Products Data Successfully Recieved from Cache.");
        }

        $data = $this->publishedProductRepository->index($per_page);
        Cache::put('published_products_' . $page . '_' . $per_page, $data, 600);
        $products = $data['products'];
        $metaData = $data['meta'];

        return $this->Res(200, [
            'products' => $products,
            'meta' => $metaData
        ], "Products Data Successfully Recieved from Database.");
    }

    public function get($id)
    {
        if(Cache::has('product_' . $id)){
            $product = Cache::get('product_' . $id);
            return $this->Res(200, $product, 'Product data successfully retrieved from Cache');
        }
        return $this->publishedProductRepository->get($id);
    }

    public function search(Request $request)
    {
        return $this->publishedProductRepository->search($request);
    }

    public function changeStatus(Request $request)
    {
        $request->validate([
            'id' => 'required|integer',
            'status' => 'required|boolean',
        ]);

        return $this->publishedProductRepository->changeStatus($request);
    }

}

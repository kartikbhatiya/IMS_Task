<?php

namespace App\Http\Repositories;

use App\Http\Controllers\ResponseTrait;
use Illuminate\Http\Request;
use App\http\Requests\StoreDraftProductRequest;

use App\Models\DraftProduct;
use App\Models\Molecule;
use App\Models\Category;
use App\Models\User;

use Illuminate\Validation\ValidationException;
use Exception;

class DraftProductRepository
{

    use ResponseTrait;

    public function index()
    {
        $products = DraftProduct::paginate(5);

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

    public function store($request)
    {
        $molecules = explode(',', $request['molecules']);
        $existingIds = Molecule::whereIn('id', $molecules)->pluck('id')->toArray();

        $missingIds = array_diff($molecules, $existingIds);

        if(count($missingIds) > 0){
            return $this->ErrRes(404, $missingIds, 'Molecule IDs not found');
        }

        try {
            $data = $request->validated();
            $data['created_by'] = auth()->user()->id;
            $molecules = Molecule::whereIn('id', $existingIds)->pluck('name')->toArray();
            $data['combination_string'] = implode('+', $molecules);
            $product = DraftProduct::create($data);

            $product->molecules()->attach($existingIds);

            return $this->Res(201, $product, 'Product created successfully');
        } catch (ValidationException $e) {
            return $this->ErrRes(422, $e->errors(), 'Validation Error');
        } catch (Exception $e) {
            return $this->ErrRes(500, $e, 'Internal server error');
        }
    }
}

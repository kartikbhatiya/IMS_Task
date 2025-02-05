<?php

namespace App\Http\Repositories;

use App\Http\Controllers\ResponseTrait;
use App\http\Requests\StoreDraftProductRequest;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

use App\Models\ProductMolecules;
use App\Models\DraftProduct;
use App\Models\Molecule;
use App\Models\Category;
use App\Models\User;


use Illuminate\Validation\ValidationException;
use Exception;
use Illuminate\Http\Exceptions\HttpResponseException;

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
        try {
            $data = $request->validated();
            $moleculesData = Molecule::whereIn('id', $data['molecules'])->pluck('name')->toArray();
            $data['combination_string'] = implode('+', $moleculesData);

            DB::beginTransaction();
            $product = DraftProduct::create($data);

            $product_code = $request->input('product_code');
    
            $productMolecules = [];
    
            foreach ($data['molecules'] as $molecule_id) {
                $productMolecules[] = [
                    'product_code' => $product_code,
                    'molecule_id' => $molecule_id,
                    'created_by' => $request->input('created_by')
                ];
            }
    
            if(!ProductMolecules::insert($productMolecules)){
                throw new Exception('Failed to create product molecules.');
            }

            DB::commit();
            return $this->Res(201, $product, 'Product created successfully');
        } catch (Exception $e) {
            DB::rollBack();
            return $this->ErrRes(500, $e, 'Internal server error');
        }
    }

    public function updateData($request)
    {
        try {
            $data = $request->validated();
            $product = DraftProduct::findOrFail($data['id']);

            DB::beginTransaction();

            $existingData = $product->toArray();

            $changedData = array_diff_assoc($data, $existingData);

            if (!empty($changedData)) {
                $product->update($changedData);
            }

            DB::commit();
            return $this->Res(200, $product, 'Product updated successfully');
        } catch (Exception $e) {
            DB::rollBack();
            return $this->ErrRes(500, $e, 'Internal server error');
        }
    }

    public function updateMolecules($request){
        try {
            $data = $request->validated();
            $product = DraftProduct::findOrFail($data['id']);
            
            $existingMolecules = ProductMolecules::where('product_code', $product->product_code)->pluck('molecule_id')->toArray();
            
            $newMolecules = $data['molecules'];

            $moleculesToAdd = array_diff($newMolecules, $existingMolecules); // Molecules Exists in newMolecules but not in existingMolecules
            $moleculesToRemove = array_diff($existingMolecules, $newMolecules); // Molecules Exists in existingMolecules but not in newMolecules
            
            if(empty($moleculesToAdd) && empty($moleculesToRemove)){
                return $this->Res(200, $product, 'No changes found in Molecules');
            }

            DB::beginTransaction();
            
            // Remove molecules
            ProductMolecules::where('product_code', $product->product_code)
                ->whereIn('molecule_id', $moleculesToRemove)
                ->update(['is_deleted' =>  1, 'deleted_by' => auth()->user()->id, 'deleted_at' => now()]);

            // Add new molecules
            $productMolecules = [];
            foreach ($moleculesToAdd as $molecule_id) {
                $productMolecules[] = [
                    'product_code' => $product->product_code,
                    'molecule_id' => $molecule_id,
                    'created_by' => $request->input('updated_by')
                ];
            }

            if (!empty($productMolecules)) {
                ProductMolecules::insert($productMolecules);
            }

            $molecules = Molecule::where('id', $newMolecules)->pluck('name')->toArray();

            $product->combination_string = implode('+', $molecules);
            $product->updated_by = $request->input('updated_by');
            $product->save();

            DB::commit();
            return $this->Res(200, $product, 'Product updated successfully');
        } catch (Exception $e) {
            DB::rollBack();
            return $this->ErrRes(500, $e, 'Internal server error');
        }
    }
}

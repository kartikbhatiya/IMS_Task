<?php

namespace App\Http\Repositories;

use App\Http\Controllers\ResponseTrait;
use Illuminate\Http\Request;
use App\http\Requests\StoreMoleculeRequest;
use App\http\Requests\UpdateMoleculeRequest;
use App\Models\DraftProduct;
use App\Models\Category;
use App\Models\Molecule;
use App\Models\User;

use Exception;
use Illuminate\Validation\ValidationException;

class MoleculeRepository
{

    use ResponseTrait;

    public function index($per_page)
    {
        $molecules = Molecule::OrderBy('id')->paginate($per_page);

        $metaData = [
            "current_page" => $molecules->currentPage(),
            "per_page" => $molecules->perPage(),
            "last_page" => $molecules->lastPage(),
            "total" => $molecules->total(),
            "current_page_record" => $molecules->count(),
        ];

        return $this->Res(200, [
            'molecules' => $molecules->items(),
            'meta' => $metaData
        ], "Molecules Data Successfully Retrieved.");
    }

    public function store($request)
    {
        try {
            $data = $request->validated();
            $data['created_by'] = auth()->user()->id;
            // dump($data);
            $molecule = Molecule::create($data);

            return $this->Res(201, $molecule, 'Molecule created successfully');
        }catch (ValidationException $e) {
            dump($e);
            return $this->ErrRes(422, $e->errors(), 'Validation Error');
        }
        catch (Exception $e) {
            return $this->ErrRes(500, $e, 'Internal server error');
        }
    }

    public function get($id)
    {
        try {
            $molecule = Molecule::find($id);

            if (!$molecule) {
                return $this->ErrRes(404, [], 'Molecule not found');
            }

            return $this->Res(200, $molecule, 'Molecule retrieved successfully');
        } catch (Exception $e) {
            dump($e);
            return $this->ErrRes(500, $e, 'Internal server error');
        }
    }

    public function update($request)
    {
        try {
            $data['name'] = $request['name'];
            $data['slug'] = $request['slug'];
            $molecule = Molecule::find($request->id);

            // if (!$molecule) {
            //     return $this->ErrRes(404, [], 'Molecule not found');
            // }

            $molecule->update($data);
            $molecule->updated_by = auth()->user()->id;
            $molecule->updated_at = now();
            $molecule->save();

            return $this->Res(200, $molecule, 'Molecule updated successfully');
        } catch (Exception $e) {
            dump($e);
            return $this->ErrRes(500, $e, 'Internal server error');
        }
    }

    public function delete($id)
    {
        try {
            $molecule = Molecule::where([['id', $id],['is_deleted', 0]])->first();

            if (!$molecule) {
                return $this->ErrRes(404, [], 'Molecule not found Or already deleted');
            }

            $molecule->is_deleted = 1;
            $molecule->deleted_by = auth()->user()->id;
            $molecule->deleted_at = now();
            $molecule->save();

            return $this->Res(200, [], 'Molecule deleted successfully');
        } catch (Exception $e) {
            dump($e);
            return $this->ErrRes(500, $e, 'Internal server error');
        }
    }
}

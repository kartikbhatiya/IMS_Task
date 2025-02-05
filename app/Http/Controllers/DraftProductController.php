<?php

namespace App\Http\Controllers;

use App\http\Repositories\DraftProductRepository;

use Illuminate\Validation\ValidationException;

use App\Models\DraftProduct;
use App\Http\Requests\StoreDraftProductRequest;
use App\Http\Requests\UpdateDraftProductRequest;
use App\Http\Requests\UpdateDraftProductMoleculeRequest;

class DraftProductController extends Controller
{
    private DraftProductRepository $draftProductRepository;
    public function __construct(DraftProductRepository $draftProductRepository)
    {
        $this->draftProductRepository = $draftProductRepository;
    }

    public function index()
    {
        return $this->draftProductRepository->index();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreDraftProductRequest $request)
    {
        try {
            $request->validated();
            return $this->draftProductRepository->store($request);
        } catch (ValidationException $e) {
            return $this->ErrRes(422, $e->errors(), 'Validation Error');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function updateData(UpdateDraftProductRequest $request)
    {

        try {
            $request->validated();
            return $this->draftProductRepository->updateData($request);
        } catch (ValidationException $e) {
            return $this->ErrRes(422, $e->errors(), 'Validation Error');
        }
        
    }

    public function updateMolecules(UpdateDraftProductMoleculeRequest $request)
    {
        try {
            $request->validated();
            return $this->draftProductRepository->updateMolecules($request);
        } catch (ValidationException $e) {
            return $this->ErrRes(422, $e->errors(), 'Validation Error');
        }
        
    }
}

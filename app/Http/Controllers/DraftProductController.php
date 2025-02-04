<?php

namespace App\Http\Controllers;

use App\http\Repositories\DraftProductRepository;

use App\Models\DraftProduct;
use App\Http\Requests\StoreDraftProductRequest;
use App\Http\Requests\UpdateDraftProductRequest;

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
        return $this->draftProductRepository->store($request);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDraftProductRequest $request, DraftProduct $draftProduct)
    {
        //
    }

}

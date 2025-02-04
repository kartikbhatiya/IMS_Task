<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests\StoreMoleculeRequest;
use App\Http\Requests\UpdateMoleculeRequest;
use App\Http\Repositories\MoleculeRepository;

class MoleculeController extends Controller
{
    private MoleculeRepository $moleculeRepository;

    public function __construct(MoleculeRepository $moleculeRepository)
    {
        $this->moleculeRepository = $moleculeRepository;
    }

    public function index()
    {
        return $this->moleculeRepository->index();
    }

    public function get($id)
    {
        return $this->moleculeRepository->get($id);
    }

    public function store(StoreMoleculeRequest $request)
    {
        return $this->moleculeRepository->store($request);
    }

    public function update(UpdateMoleculeRequest $request)
    {
        return $this->moleculeRepository->update($request);
    }

    public function delete($id)
    {
        return $this->moleculeRepository->delete($id);
    }

}

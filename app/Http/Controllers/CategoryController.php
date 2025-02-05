<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Category;

class CategoryController extends Controller
{
    use ResponseTrait;
    
    public function index(){
        return $this->Res(200,Category::all(), "All Categories retrieved successfully");
    }

    public function get($id){
        return $this->Res(200, Category::find($id), "Category fetched successfully");
    }

    public function store(Request $request){
        $request->validate([
            'name' => 'required|string|unique:categories,name',
        ]);

        $data = $request->all();
        $data['created_by'] = auth()->user()->id;

        $category = Category::create($data);
        return $this->Res(201, $category, "Category created successfully");
    }
}

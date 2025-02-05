<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use app\Models\User;
use app\Models\Category;

class DraftProduct extends Model
{
    use HasFactory;

    protected $table = 'draft_products';

    protected $fillable = [
        'product_name',
        'product_code',
        'manufacturer_name',
        'mrp',
        'combination_string',
        'category_id',
        'is_banned',
        'is_active',
        'is_discontinued',
        'is_assured',
        'is_refrigerated',
        'is_deleted',
        'is_published',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $casts = [];

    public function category(){
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function molecules()
    {
        return $this->belongsToMany(Molecule::class, 'product_molecules', 'product_code', 'molecule_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by'); 
    }
    
    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by'); 
    }
    
    public function deletedBy()
    {
        return $this->belongsTo(User::class, 'deleted_by'); 
    }
}
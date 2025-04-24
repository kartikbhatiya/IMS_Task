<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PublishedProduct extends Model
{
    use HasFactory;

    protected $table = 'publish_products';

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
        'created_by',
        'updated_by',
        'deleted_by',
    ];


    public function category()
    {
        return $this->belongsTo(Category::class);
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
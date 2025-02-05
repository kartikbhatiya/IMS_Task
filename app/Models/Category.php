<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use app\Models\User;
use app\Models\DraftProduct;

class Category extends Model
{
    use HasFactory;

    protected $table = 'categories';

    protected $fillable = [
        'name',
        'is_deleted',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    public function draftProducts()
    {
        return $this->hasMany(DraftProduct::class);
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
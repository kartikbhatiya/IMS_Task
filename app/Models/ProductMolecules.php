<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductMolecules extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_code',
        'molecule_id',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    public function molecule()
    {
        return $this->belongsTo(Molecule::class, 'molecule_id');
    }

    public function product()
    {
        return $this->belongsTo(DraftProduct::class, 'product_code');
    }

    public function moleculesForProduct()
    {
        return $this->hasMany(Molecule::class, 'molecule_id');
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

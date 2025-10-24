<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name',
        'description',
    ];

    /**
     * Get the SORs for the product.
     */
    public function sors()
    {
        return $this->hasMany(Sor::class, 'product_id');
    }
}

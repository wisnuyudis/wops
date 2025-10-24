<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = [
        'name',
        'description',
    ];

    /**
     * Get the SORs for the customer.
     */
    public function sors()
    {
        return $this->hasMany(Sor::class, 'customer_id');
    }
}

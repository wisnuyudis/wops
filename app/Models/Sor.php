<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sor extends Model
{
    protected $fillable = [
        'sor',
        'customer_id',
        'product_id',
        'init_date',
        'description',
        'status',
    ];

    protected $casts = [
        'init_date' => 'date',
    ];

    /**
     * Get the customer that owns the SOR.
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Get the product that owns the SOR.
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the daily activities for the SOR.
     */
    public function dailyActivities()
    {
        return $this->hasMany(DailyActivity::class, 'sor_id');
    }
}

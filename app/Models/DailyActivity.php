<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DailyActivity extends Model
{
    protected $fillable = [
        'user_id',
        'date',
        'sor_id',
        'action',
        'cust_name',
        'pic',
        'product',
        'job_type_id',
        'job_item_id',
        'objective',
        'result_of_issue',
        'status',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    /**
     * Get the user that owns the daily activity.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the SOR that owns the daily activity.
     */
    public function sor()
    {
        return $this->belongsTo(Sor::class);
    }

    /**
     * Get the job type that owns the daily activity.
     */
    public function jobType()
    {
        return $this->belongsTo(JobType::class);
    }

    /**
     * Get the job item that owns the daily activity.
     */
    public function jobItem()
    {
        return $this->belongsTo(JobItem::class);
    }
}

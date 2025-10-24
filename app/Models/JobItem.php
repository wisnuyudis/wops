<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobItem extends Model
{
    protected $fillable = [
        'name',
        'description',
    ];

    /**
     * Get the daily activities for the job item.
     */
    public function dailyActivities()
    {
        return $this->hasMany(DailyActivity::class, 'job_item_id');
    }
}

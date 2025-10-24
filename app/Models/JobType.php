<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobType extends Model
{
    protected $fillable = [
        'name',
        'description',
    ];

    /**
     * Get the daily activities for the job type.
     */
    public function dailyActivities()
    {
        return $this->hasMany(DailyActivity::class, 'job_type_id');
    }
}

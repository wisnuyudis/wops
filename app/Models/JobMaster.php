<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobMaster extends Model
{
    protected $table = 'jobs_master';
    
    protected $fillable = [
        'name',
        'description',
    ];

    /**
     * Get the users for the job.
     */
    public function users()
    {
        return $this->hasMany(User::class, 'job_id');
    }
}

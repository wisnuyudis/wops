<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WeeklyProgress extends Model
{
    protected $table = 'weekly_progress';
    
    protected $fillable = [
        'user_id',
        'year',
        'week_number',
        'last_week_status',
        'p1',
        'p2',
        'p3',
    ];

    /**
     * Get the user that owns the weekly progress.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'username',
        'password',
        'name',
        'job_id',
        'role',
        'status',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    /**
     * Get the job that the user belongs to.
     */
    public function job()
    {
        return $this->belongsTo(JobMaster::class, 'job_id');
    }

    /**
     * Get the daily activities for the user.
     */
    public function dailyActivities()
    {
        return $this->hasMany(DailyActivity::class);
    }

    /**
     * Get the weekly progress for the user.
     */
    public function weeklyProgress()
    {
        return $this->hasMany(WeeklyProgress::class);
    }
}

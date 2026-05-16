<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Shift extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'shift_name',
        'start_time',
        'end_time',
        'grace_time_minutes',
        'half_day_minutes_threshold'
    ];

    /**
     * Get the staff members assigned to this shift.
     */
    public function staff(): HasMany
    {
        return $this->hasMany(StaffDetail::class);
    }
}

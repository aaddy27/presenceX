<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AttendanceLog extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'staff_id',
        'date',
        'punch_in',
        'punch_out',
        'status',
        'productive_hours'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'date' => 'date',
        'productive_hours' => 'decimal:2',
    ];

    /**
     * Get the staff that owns the attendance log.
     */
    public function staff(): BelongsTo
    {
        return $this->belongsTo(StaffDetail::class, 'staff_id');
    }

    /**
     * Get the break logs for the attendance log.
     */
    public function breakLogs(): HasMany
    {
        return $this->hasMany(BreakLog::class);
    }
}

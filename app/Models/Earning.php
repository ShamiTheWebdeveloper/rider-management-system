<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Earning extends Model
{
    use HasFactory;

    /**
     * Allowed platform choices for an Uber/Careem driver.
     *
     * @var array<int, string>
     */
    public const array PLATFORMS = [
        'Uber',
        'Careem',
        'InDrive',
        'Yango',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'amount',
        'platform',
        'date',
        'notes',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'date' => 'date',
        ];
    }

    /**
     * Get the user that owns the earning record.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Expense extends Model
{
    use HasFactory;

    /**
     * Allowed expense categories for driver operational costs.
     *
     * @var array<int, string>
     */
    public const array CATEGORIES = [
        'Fuel',
        'Maintenance',
        'Challan/Fines',
        'Tolls',
        'Car Wash',
        'Meals',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'amount',
        'category',
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
     * Get the user that owns the expense record.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

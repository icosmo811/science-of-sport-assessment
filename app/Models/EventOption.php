<?php

namespace App\Models;

use Database\Factories\EventOptionFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EventOption extends Model
{
    /** @use HasFactory<EventOptionFactory> */
    use HasFactory;

    protected $fillable = [
        'category',
        'name',
        'price',
        'golfer_count',
        'description',
        'benefits',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'golfer_count' => 'integer',
            'benefits' => 'array',
            'sort_order' => 'integer',
        ];
    }

    public function entry(): BelongsTo
    {
        return $this->belongsTo(Entry::class);
    }
}

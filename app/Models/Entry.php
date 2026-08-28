<?php

namespace App\Models;

use Database\Factories\EntryFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Entry extends Model
{
    /** @use HasFactory<EntryFactory> */
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'tagline',
        'event_date',
        'location',
        'overview',
        'sponsorship_benefits',
        'player_benefits',
        'hero_image_url',
        'published_at',
    ];

    protected function casts(): array
    {
        return [
            'event_date' => 'date',
            'sponsorship_benefits' => 'array',
            'player_benefits' => 'array',
            'published_at' => 'datetime',
        ];
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function eventOptions(): HasMany
    {
        return $this->hasMany(EventOption::class);
    }
}

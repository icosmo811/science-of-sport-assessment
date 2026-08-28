<?php

namespace Database\Factories;

use App\Models\Entry;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Entry>
 */
class EntryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'author_id' => User::factory(),
            'title' => fake()->sentence(4),
            'slug' => fake()->unique()->slug(4),
            'tagline' => fake()->sentence(3),
            'event_date' => fake()->dateTimeBetween('-1 year', '+1 year'),
            'location' => fake()->city(),
            'overview' => fake()->paragraphs(3, true),
            'sponsorship_benefits' => fake()->sentences(3),
            'player_benefits' => fake()->sentences(3),
            'hero_image_url' => null,
            'published_at' => now(),
        ];
    }
}

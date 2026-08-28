<?php

namespace Database\Factories;

use App\Models\Entry;
use App\Models\EventOption;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<EventOption>
 */
class EventOptionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'entry_id' => Entry::factory(),
            'category' => fake()->randomElement(['sponsorship', 'golf', 'social']),
            'name' => fake()->words(3, true),
            'price' => fake()->randomFloat(2, 10, 15000),
            'golfer_count' => fake()->numberBetween(1, 12),
            'description' => fake()->paragraph(),
            'benefits' => fake()->sentences(3),
            'sort_order' => fake()->numberBetween(1, 10),
        ];
    }
}

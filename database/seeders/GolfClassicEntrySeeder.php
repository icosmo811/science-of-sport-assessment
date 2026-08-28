<?php

namespace Database\Seeders;

use App\Models\Entry;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GolfClassicEntrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::transaction(function (): void {
            $admin = User::query()
                ->where('email', 'admin@assessment.test')
                ->firstOrFail();

            $entry = Entry::query()->firstOrNew([
                'slug' => 'golf-classic-tournament-2025',
            ]);

            $entry->forceFill([
                'author_id' => $admin->id,
                'title' => 'Golf Classic Tournament 2025',
                'tagline' => 'Empowering Students Through Sports',
                'event_date' => '2025-11-10',
                'location' => 'Calabasas Country Club',
                'overview' => 'The Golf Classic brings supporters together to help expand educational sports experiences for students throughout Los Angeles County. Funds raised through the event support the continued growth of Science of Sport programs in local schools.',
                'sponsorship_benefits' => [
                    'Brand activation at a featured hole or event experience.',
                    'Digital promotion and event signage.',
                    'Reserved practice area access.',
                    'Custom-branded golf carts.',
                    'Contests and VIP benefits.',
                    'Expedited registration.',
                    'Sponsor recognition throughout the event.',
                    'Two raffle tickets for each player.',
                ],
                'player_benefits' => [
                    'Gift bag.',
                    'Food and beverages throughout the day.',
                ],
                'hero_image_url' => null,
                'published_at' => $entry->published_at ?? now(),
            ]);

            $entry->save();

            $entry->eventOptions()->delete();

            $entry->eventOptions()->createMany([
                [
                    'category' => 'sponsorship',
                    'name' => 'Title Sponsor',
                    'price' => 15000,
                    'golfer_count' => 12,
                    'description' => 'Lead sponsorship with premium event visibility and three foursomes for invited guests.',
                    'benefits' => [
                        'Prominent name and logo placement.',
                        'VIP table during the post-golf event.',
                        'Custom signature drink opportunity.',
                        'Three included foursomes.',
                        'Branded item placement in player gift bags.',
                        'Dedicated activation hole.',
                        'Recognition during and after the event.',
                    ],
                    'sort_order' => 1,
                ],
                [
                    'category' => 'sponsorship',
                    'name' => 'Champion',
                    'price' => 8500,
                    'golfer_count' => 8,
                    'description' => 'A prominent sponsorship designed to shape the atmosphere of the event throughout the day.',
                    'sort_order' => 2,
                ],
                [
                    'category' => 'sponsorship',
                    'name' => 'All Star',
                    'price' => 5000,
                    'golfer_count' => 4,
                    'description' => 'Includes one tailored hole activation or on-course brand experience.',
                    'sort_order' => 3,
                ],
                [
                    'category' => 'sponsorship',
                    'name' => 'MVP',
                    'price' => 3000,
                    'golfer_count' => 4,
                    'description' => 'Includes one off-course experience sponsorship opportunity.',
                    'sort_order' => 4,
                ],
                [
                    'category' => 'golf',
                    'name' => 'Foursome',
                    'price' => 1800,
                    'golfer_count' => 4,
                    'sort_order' => 5,
                ],
                [
                    'category' => 'golf',
                    'name' => 'Single',
                    'price' => 450,
                    'golfer_count' => 1,
                    'sort_order' => 6,
                ],
                [
                    'category' => 'social',
                    'name' => '19th Hole Attendee',
                    'price' => 45,
                    'golfer_count' => null,
                    'sort_order' => 7,
                ],
            ]);
        });
    }
}

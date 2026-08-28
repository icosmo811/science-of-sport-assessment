<?php

namespace Tests\Feature\Services;

use App\Models\Entry;
use App\Models\EventOption;
use App\Models\User;
use App\Services\EntryService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EntryServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_creates_an_entry_with_its_author_and_options(): void
    {
        $author = User::factory()->create();

        $entry = app(EntryService::class)->create(
            $author,
            $this->validData(),
        );

        $this->assertTrue($entry->author->is($author));
        $this->assertCount(2, $entry->eventOptions);

        $this->assertDatabaseHas('entries', [
            'id' => $entry->id,
            'author_id' => $author->id,
            'slug' => 'assessment-event',
        ]);

        $this->assertDatabaseCount('event_options', 2);
    }

    public function test_it_updates_an_entry_and_replaces_its_options(): void
    {
        $author = User::factory()->create();

        $entry = Entry::factory()
            ->for($author, 'author')
            ->create();

        $oldOption = EventOption::factory()
            ->for($entry)
            ->create();

        $data = $this->validData();
        $data['title'] = 'Updated assessment event';
        $data['slug'] = 'updated-assessment-event';
        $data['event_options'] = [
            [
                'category' => 'golf',
                'name' => 'Updated Foursome',
                'price' => 2000,
                'golfer_count' => 4,
                'description' => 'Updated option.',
                'benefits' => ['Four player registrations.'],
                'sort_order' => 1,
            ],
        ];

        $updatedEntry = app(EntryService::class)->update($entry, $data);

        $this->assertSame('Updated assessment event', $updatedEntry->title);
        $this->assertSame($author->id, $updatedEntry->author_id);
        $this->assertCount(1, $updatedEntry->eventOptions);
        $this->assertSame('Updated Foursome', $updatedEntry->eventOptions->first()->name);

        $this->assertModelMissing($oldOption);

        $this->assertDatabaseHas('entries', [
            'id' => $entry->id,
            'slug' => 'updated-assessment-event',
        ]);
    }

    public function test_it_deletes_an_entry_and_its_options(): void
    {
        $entry = Entry::factory()->create();

        $options = EventOption::factory()
            ->count(2)
            ->for($entry)
            ->create();

        app(EntryService::class)->delete($entry);

        $this->assertModelMissing($entry);

        foreach ($options as $option) {
            $this->assertModelMissing($option);
        }
    }

    /**
     * @return array<string, mixed>
     */
    private function validData(): array
    {
        return [
            'title' => 'Assessment event',
            'slug' => 'assessment-event',
            'tagline' => 'Supporting students through sports',
            'event_date' => '2026-11-10',
            'location' => 'Calabasas Country Club',
            'overview' => 'An event created for the technical assessment.',
            'sponsorship_benefits' => [
                'Brand visibility.',
                'Event recognition.',
            ],
            'player_benefits' => [
                'Gift bag.',
                'Food and beverages.',
            ],
            'hero_image_url' => null,
            'published_at' => null,
            'event_options' => [
                [
                    'category' => 'sponsorship',
                    'name' => 'Title Sponsor',
                    'price' => 15000,
                    'golfer_count' => 12,
                    'description' => 'Primary sponsorship.',
                    'benefits' => ['Premium visibility.'],
                    'sort_order' => 1,
                ],
                [
                    'category' => 'golf',
                    'name' => 'Foursome',
                    'price' => 1800,
                    'golfer_count' => 4,
                    'description' => null,
                    'benefits' => null,
                    'sort_order' => 2,
                ],
            ],
        ];
    }
}

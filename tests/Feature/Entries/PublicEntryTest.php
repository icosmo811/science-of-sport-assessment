<?php

namespace Tests\Feature\Entries;

use App\Models\Entry;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicEntryTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_published_entry_can_be_viewed_publicly(): void
    {
        $entry = Entry::factory()->create([
            'title' => 'Public Golf Classic',
            'slug' => 'public-golf-classic',
            'published_at' => now()->subMinute(),
        ]);

        $response = $this->get(
            route('entries.public.show', $entry),
        );

        $response
            ->assertOk()
            ->assertSee('Public Golf Classic');
    }

    public function test_a_draft_entry_cannot_be_viewed_publicly(): void
    {
        $entry = Entry::factory()->create([
            'published_at' => null,
        ]);

        $this->get(route('entries.public.show', $entry))
            ->assertNotFound();
    }

    public function test_a_future_entry_cannot_be_viewed_publicly(): void
    {
        $entry = Entry::factory()->create([
            'published_at' => now()->addDay(),
        ]);

        $this->get(route('entries.public.show', $entry))
            ->assertNotFound();
    }

    public function test_home_redirects_to_the_golf_classic(): void
    {
        $this->get('/')
            ->assertRedirect('/events/golf-classic-tournament-2025');
    }
}

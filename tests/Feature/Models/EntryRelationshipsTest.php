<?php

namespace Tests\Feature\Models;

use App\Models\Entry;
use App\Models\EventOption;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EntryRelationshipsTest extends TestCase
{
    use RefreshDatabase;

    public function test_entry_relations_work_in_both_directions(): void
    {
        $author = User::factory()->create();

        $entry = Entry::factory()
            ->for($author, 'author')
            ->create();

        $options = EventOption::factory()
            ->count(2)
            ->for($entry)
            ->create();

        $entry->refresh();

        $this->assertTrue($entry->author->is($author));
        $this->assertCount(2, $entry->eventOptions);

        foreach ($options as $option) {
            $this->assertTrue($option->entry->is($entry));
        }
    }

    public function test_deleting_an_entry_deletes_its_event_options(): void
    {
        $entry = Entry::factory()->create();

        $options = EventOption::factory()
            ->count(2)
            ->for($entry)
            ->create();

        $entry->delete();

        $this->assertModelMissing($entry);

        foreach ($options as $option) {
            $this->assertModelMissing($option);
        }
    }

    public function test_deleting_an_author_preserves_the_entry(): void
    {
        $author = User::factory()->create();

        $entry = Entry::factory()
            ->for($author, 'author')
            ->create();

        $author->delete();

        $entry->refresh();

        $this->assertDatabaseHas('entries', [
            'id' => $entry->id,
            'author_id' => null,
        ]);

        $this->assertNull($entry->author_id);

        $this->assertNull($entry->author);
    }
}

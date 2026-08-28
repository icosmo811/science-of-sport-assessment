<?php

namespace Tests\Feature\Assessment;

use App\Enums\UserRole;
use App\Models\Entry;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AssessmentAcceptanceTest extends TestCase
{
    use RefreshDatabase;

    public function test_seeded_assessment_data_is_complete_and_publicly_visible(): void
    {
        $this->seed();

        $this->assertDatabaseCount('users', 2);
        $this->assertDatabaseCount('entries', 1);
        $this->assertDatabaseCount('event_options', 7);

        $admin = User::query()
            ->where('email', 'admin@assessment.test')
            ->firstOrFail();

        $editor = User::query()
            ->where('email', 'editor@assessment.test')
            ->firstOrFail();

        $this->assertSame(UserRole::ADMIN, $admin->role);
        $this->assertSame(UserRole::EDITOR, $editor->role);
        $this->assertTrue(Hash::check('Assessment2026!', $admin->password));
        $this->assertTrue(Hash::check('Assessment2026!', $editor->password));

        $entry = Entry::query()
            ->with('eventOptions')
            ->where('slug', 'golf-classic-tournament-2025')
            ->firstOrFail();

        $this->assertSame('Golf Classic Tournament 2025', $entry->title);
        $this->assertSame('2025-11-10', $entry->event_date->format('Y-m-d'));
        $this->assertSame('Calabasas Country Club', $entry->location);
        $this->assertCount(8, $entry->sponsorship_benefits);
        $this->assertCount(2, $entry->player_benefits);
        $this->assertNotNull($entry->published_at);

        $this->assertSame([
            'Title Sponsor' => '15000.00',
            'Champion' => '8500.00',
            'All Star' => '5000.00',
            'MVP' => '3000.00',
            'Foursome' => '1800.00',
            'Single' => '450.00',
            '19th Hole Attendee' => '45.00',
        ], $entry->eventOptions->pluck('price', 'name')->all());

        $this->get(route('entries.public.show', $entry))
            ->assertOk()
            ->assertSeeText('Golf Classic Tournament 2025')
            ->assertSeeText('Calabasas Country Club')
            ->assertSeeText('Title Sponsor')
            ->assertSeeText('$15,000.00')
            ->assertSeeText('Champion')
            ->assertSeeText('$8,500.00')
            ->assertSeeText('All Star')
            ->assertSeeText('$5,000.00')
            ->assertSeeText('MVP')
            ->assertSeeText('$3,000.00')
            ->assertSeeText('Foursome')
            ->assertSeeText('$1,800.00')
            ->assertSeeText('Single')
            ->assertSeeText('$450.00')
            ->assertSeeText('19th Hole Attendee')
            ->assertSeeText('$45.00');
    }
}

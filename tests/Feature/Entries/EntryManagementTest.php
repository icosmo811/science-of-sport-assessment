<?php

namespace Tests\Feature\Entries;

use App\Enums\UserRole;
use App\Models\Entry;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EntryManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_cannot_access_entry_management(): void
    {
        $response = $this->get(route('entries.index'));

        $response->assertRedirect(route('login'));
    }

    public function test_admin_can_view_the_entry_list_and_create_form(): void
    {
        $admin = User::factory()->create([
            'role' => UserRole::ADMIN,
        ]);

        Entry::factory()->create([
            'title' => 'Visible assessment entry',
        ]);

        $this->actingAs($admin)
            ->get(route('entries.index'))
            ->assertOk()
            ->assertSee('Visible assessment entry');

        $this->actingAs($admin)
            ->get(route('entries.create'))
            ->assertOk()
            ->assertSee('Create entry');
    }

    public function test_admin_can_create_an_entry(): void
    {
        $admin = User::factory()->create([
            'role' => UserRole::ADMIN,
        ]);

        $response = $this->actingAs($admin)
            ->post(route('entries.store'), $this->validPayload());

        $entry = Entry::query()
            ->where('slug', 'automated-assessment-event')
            ->firstOrFail();

        $response->assertRedirect(route('entries.edit', $entry));

        $this->assertSame($admin->id, $entry->author_id);
        $this->assertCount(2, $entry->eventOptions);
        $this->assertSame(
            ['Brand visibility.', 'Event recognition.'],
            $entry->sponsorship_benefits,
        );
    }

    public function test_editor_can_update_an_entry(): void
    {
        $editor = User::factory()->create([
            'role' => UserRole::EDITOR,
        ]);

        $entry = Entry::factory()
            ->for($editor, 'author')
            ->create();

        $payload = $this->validPayload();
        $payload['title'] = 'Updated through HTTP';
        $payload['slug'] = $entry->slug;

        $response = $this->actingAs($editor)
            ->put(route('entries.update', $entry), $payload);

        $response->assertRedirect(route('entries.edit', $entry));

        $this->assertDatabaseHas('entries', [
            'id' => $entry->id,
            'title' => 'Updated through HTTP',
        ]);

        $this->assertDatabaseCount('event_options', 2);
    }

    public function test_editor_cannot_delete_an_entry(): void
    {
        $editor = User::factory()->create([
            'role' => UserRole::EDITOR,
        ]);

        $entry = Entry::factory()->create();

        $this->actingAs($editor)
            ->delete(route('entries.destroy', $entry))
            ->assertForbidden();

        $this->assertModelExists($entry);
    }

    public function test_admin_can_delete_an_entry(): void
    {
        $admin = User::factory()->create([
            'role' => UserRole::ADMIN,
        ]);

        $entry = Entry::factory()->create();

        $this->actingAs($admin)
            ->delete(route('entries.destroy', $entry))
            ->assertRedirect(route('entries.index'));

        $this->assertModelMissing($entry);
    }

    public function test_entry_pagination_can_be_loaded_with_ajax(): void
    {
        $editor = User::factory()->create([
            'role' => UserRole::EDITOR,
        ]);

        Entry::factory()->count(11)->create();

        $response = $this->actingAs($editor)->get(
            route('entries.index', ['page' => 2]),
            [
                'Accept' => 'application/json',
                'X-Requested-With' => 'XMLHttpRequest',
            ],
        );

        $response
            ->assertOk()
            ->assertJsonStructure(['html']);

        $this->assertStringContainsString(
            'pagination',
            $response->json('html'),
        );
    }

    /**
     * @return array<string, mixed>
     */
    private function validPayload(): array
    {
        return [
            'title' => 'Automated assessment event',
            'slug' => 'automated-assessment-event',
            'tagline' => 'Supporting students through sports',
            'event_date' => '2026-11-10',
            'location' => 'Calabasas Country Club',
            'overview' => 'Entry created through an HTTP feature test.',
            'sponsorship_benefits_text' => "Brand visibility.\nEvent recognition.",
            'player_benefits_text' => "Gift bag.\nFood and beverages.",
            'hero_image_url' => null,
            'published_at' => null,
            'event_options' => [
                [
                    'category' => 'sponsorship',
                    'name' => 'Title Sponsor',
                    'price' => 15000,
                    'golfer_count' => 12,
                    'description' => 'Primary sponsorship.',
                    'benefits_text' => "Premium visibility.\nEvent recognition.",
                    'sort_order' => 1,
                ],
                [
                    'category' => 'golf',
                    'name' => 'Foursome',
                    'price' => 1800,
                    'golfer_count' => 4,
                    'description' => null,
                    'benefits_text' => '',
                    'sort_order' => 2,
                ],
            ],
        ];
    }

    public function test_dashboard_redirects_authenticated_users_to_entries(): void
    {
        $editor = User::factory()->create([
            'role' => UserRole::EDITOR,
        ]);

        $this->actingAs($editor)
            ->get(route('dashboard'))
            ->assertRedirect('/entries');
    }
}

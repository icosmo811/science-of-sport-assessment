<?php

namespace Tests\Feature\Policies;

use App\Enums\UserRole;
use App\Models\Entry;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Gate;
use Tests\TestCase;

class EntryPolicyTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_manage_entries(): void
    {
        $admin = User::factory()->create([
            'role' => UserRole::ADMIN,
        ]);

        $entry = Entry::factory()->create();

        $this->assertTrue(Gate::forUser($admin)->allows('viewAny', Entry::class));
        $this->assertTrue(Gate::forUser($admin)->allows('view', $entry));
        $this->assertTrue(Gate::forUser($admin)->allows('create', Entry::class));
        $this->assertTrue(Gate::forUser($admin)->allows('update', $entry));
        $this->assertTrue(Gate::forUser($admin)->allows('delete', $entry));
    }

    public function test_editor_can_manage_entries_except_deleting_them(): void
    {
        $editor = User::factory()->create([
            'role' => UserRole::EDITOR,
        ]);

        $entry = Entry::factory()->create();

        $this->assertTrue(Gate::forUser($editor)->allows('viewAny', Entry::class));
        $this->assertTrue(Gate::forUser($editor)->allows('view', $entry));
        $this->assertTrue(Gate::forUser($editor)->allows('create', Entry::class));
        $this->assertTrue(Gate::forUser($editor)->allows('update', $entry));
        $this->assertFalse(Gate::forUser($editor)->allows('delete', $entry));
    }

    public function test_entries_cannot_be_restored_or_force_deleted(): void
    {
        $admin = User::factory()->create([
            'role' => UserRole::ADMIN,
        ]);

        $entry = Entry::factory()->create();

        $this->assertFalse(Gate::forUser($admin)->allows('restore', $entry));
        $this->assertFalse(Gate::forUser($admin)->allows('forceDelete', $entry));
    }
}

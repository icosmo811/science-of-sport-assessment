<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\Entry;
use App\Models\User;

class EntryPolicy
{
    public function viewAny(User $user): bool
    {
        return in_array($user->role, [
            UserRole::ADMIN,
            UserRole::EDITOR,
        ], true);
    }

    public function view(User $user, Entry $entry): bool
    {
        return in_array($user->role, [
            UserRole::ADMIN,
            UserRole::EDITOR,
        ], true);
    }

    public function create(User $user): bool
    {
        return in_array($user->role, [
            UserRole::ADMIN,
            UserRole::EDITOR,
        ], true);
    }

    public function update(User $user, Entry $entry): bool
    {
        return in_array($user->role, [
            UserRole::ADMIN,
            UserRole::EDITOR,
        ], true);
    }

    public function delete(User $user, Entry $entry): bool
    {
        return $user->role === UserRole::ADMIN;
    }

    public function restore(User $user, Entry $entry): bool
    {
        return false;
    }

    public function forceDelete(User $user, Entry $entry): bool
    {
        return false;
    }
}

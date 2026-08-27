<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Database\Seeder;

class AssessmentUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $editor = User::firstOrNew([
            'email' => 'editor@assessment.test',
        ]);

        $editor->forceFill([
            'name' => 'Assessment Editor',
            'password' => 'Assessment2026!',
            'role' => UserRole::EDITOR,
        ]);

        $editor->save();

        $admin = User::firstOrNew([
            'email' => 'admin@assessment.test',
        ]);

        $admin->forceFill([
            'name' => 'Assessment Admin',
            'password' => 'Assessment2026!',
            'role' => UserRole::ADMIN,
        ]);

        $admin->save();
    }
}

<?php

namespace App\Http\Requests;

use App\Models\Entry;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Unique;

class StoreEntryRequest extends EntryRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('create', Entry::class) ?? false;
    }

    protected function uniqueSlugRule(): Unique
    {
        return Rule::unique('entries', 'slug');
    }
}

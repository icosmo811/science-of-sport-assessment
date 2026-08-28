<?php

namespace App\Http\Requests;

use App\Models\Entry;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Unique;

class UpdateEntryRequest extends EntryRequest
{
    public function authorize(): bool
    {
        $entry = $this->route('entry');

        return $entry instanceof Entry
            && ($this->user()?->can('update', $entry) ?? false);
    }

    protected function uniqueSlugRule(): Unique
    {
        $entry = $this->route('entry');

        return Rule::unique('entries', 'slug')
            ->ignore($entry instanceof Entry ? $entry : null);
    }
}

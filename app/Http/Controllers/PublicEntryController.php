<?php

namespace App\Http\Controllers;

use App\Models\Entry;
use Illuminate\View\View;

class PublicEntryController extends Controller
{
    public function __invoke(Entry $entry): View
    {
        abort_if(
            $entry->published_at === null
            || $entry->published_at->isFuture(),
            404,
        );

        $entry->load([
            'eventOptions' => fn ($query) => $query->orderBy('sort_order'),
        ]);

        return view('entries.public-show', compact('entry'));
    }
}

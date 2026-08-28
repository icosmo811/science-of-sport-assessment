<?php

namespace App\Services;

use App\Models\Entry;
use App\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class EntryService
{
    /**
     * @param  array<string, mixed>  $data
     */
    public function create(User $author, array $data): Entry
    {
        return DB::transaction(function () use ($author, $data): Entry {
            $eventOptions = Arr::pull($data, 'event_options', []);

            $entry = new Entry;
            $entry->forceFill([
                'author_id' => $author->id,
            ]);
            $entry->fill($data);
            $entry->save();

            $entry->eventOptions()->createMany($eventOptions);

            return $entry->load('author', 'eventOptions');
        });
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public function update(Entry $entry, array $data): Entry
    {
        return DB::transaction(function () use ($entry, $data): Entry {
            $eventOptions = Arr::pull($data, 'event_options', []);

            $entry->fill($data);
            $entry->save();

            $entry->eventOptions()->delete();
            $entry->eventOptions()->createMany($eventOptions);

            return $entry->load('author', 'eventOptions');
        });
    }

    public function delete(Entry $entry): void
    {
        DB::transaction(function () use ($entry): void {
            $entry->delete();
        });
    }
}

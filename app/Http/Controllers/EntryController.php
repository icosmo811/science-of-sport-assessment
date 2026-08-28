<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEntryRequest;
use App\Http\Requests\UpdateEntryRequest;
use App\Models\Entry;
use App\Models\User;
use App\Services\EntryService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class EntryController extends Controller
{
    public function __construct(
        private readonly EntryService $entryService,
    ) {}

    public function index(Request $request): View|JsonResponse
    {
        Gate::authorize('viewAny', Entry::class);

        $entries = Entry::query()
            ->with('author')
            ->withCount('eventOptions')
            ->latest()
            ->paginate(10);

        if ($request->ajax()) {
            return response()->json([
                'html' => view(
                    'entries.partials.table',
                    compact('entries'),
                )->render(),
            ]);
        }

        return view('entries.index', compact('entries'));
    }

    public function create(): View
    {
        Gate::authorize('create', Entry::class);

        return view('entries.create');
    }

    public function store(StoreEntryRequest $request): RedirectResponse
    {
        /** @var User $author */
        $author = $request->user();

        $entry = $this->entryService->create(
            $author,
            $request->validated(),
        );

        return redirect()
            ->route('entries.edit', $entry)
            ->with('status', 'Entry created successfully.');
    }

    public function edit(Entry $entry): View
    {
        Gate::authorize('update', $entry);

        $entry->load('eventOptions');

        return view('entries.edit', compact('entry'));
    }

    public function update(
        UpdateEntryRequest $request,
        Entry $entry,
    ): RedirectResponse {
        $entry = $this->entryService->update(
            $entry,
            $request->validated(),
        );

        return redirect()
            ->route('entries.edit', $entry)
            ->with('status', 'Entry updated successfully.');
    }

    public function destroy(Entry $entry): RedirectResponse
    {
        Gate::authorize('delete', $entry);

        $this->entryService->delete($entry);

        return redirect()
            ->route('entries.index')
            ->with('status', 'Entry deleted successfully.');
    }
}

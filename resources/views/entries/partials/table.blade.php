<div class="overflow-x-auto">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                    Entry
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                    Event date
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                    Author
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                    Options
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                    Status
                </th>
                <th class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider text-gray-500">
                    Actions
                </th>
            </tr>
        </thead>

        <tbody class="divide-y divide-gray-200 bg-white">
            @forelse ($entries as $entry)
                <tr>
                    <td class="px-6 py-4">
                        <p class="font-medium text-gray-900">
                            {{ $entry->title }}
                        </p>
                        <p class="text-sm text-gray-500">
                            {{ $entry->slug }}
                        </p>
                    </td>

                    <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-600">
                        {{ $entry->event_date->format('M j, Y') }}
                    </td>

                    <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-600">
                        {{ $entry->author?->name ?? 'Deleted user' }}
                    </td>

                    <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-600">
                        {{ $entry->event_options_count }}
                    </td>

                    <td class="whitespace-nowrap px-6 py-4">
                        @if ($entry->published_at)
                            <span class="rounded-full bg-green-100 px-2 py-1 text-xs font-semibold text-green-700">
                                Published
                            </span>
                        @else
                            <span class="rounded-full bg-gray-100 px-2 py-1 text-xs font-semibold text-gray-700">
                                Draft
                            </span>
                        @endif
                    </td>

                    <td class="whitespace-nowrap px-6 py-4 text-right text-sm font-medium">
                        @if ($entry->published_at)
                            <a
                                href="{{ route('entries.public.show', $entry) }}"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="mr-3 text-emerald-600 hover:text-emerald-900"
                            >
                                View
                            </a>
                        @endif
                        <a
                            href="{{ route('entries.edit', $entry) }}"
                            class="text-indigo-600 hover:text-indigo-900"
                        >
                            Edit
                        </a>

                        @can('delete', $entry)
                            <form
                                method="POST"
                                action="{{ route('entries.destroy', $entry) }}"
                                class="ml-3 inline"
                                onsubmit="return confirm('Delete this entry?');"
                            >
                                @csrf
                                @method('DELETE')

                                <button
                                    type="submit"
                                    class="text-red-600 hover:text-red-900"
                                >
                                    Delete
                                </button>
                            </form>
                        @endcan
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center text-sm text-gray-500">
                        No entries have been created.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if ($entries->hasPages())
    <div class="pagination border-t border-gray-200 px-6 py-4">
        {{ $entries->links() }}
    </div>
@endif
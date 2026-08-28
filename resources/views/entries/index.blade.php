<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                {{ __('Entries') }}
            </h2>

            <a
                href="{{ route('entries.create') }}"
                class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-500"
            >
                Create entry
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            @if (session('status'))
                <div class="mb-6 rounded-md bg-green-50 p-4 text-sm text-green-700">
                    {{ session('status') }}
                </div>
            @endif

            <div
                id="entries-table"
                class="overflow-hidden bg-white shadow-sm sm:rounded-lg"
                aria-live="polite"
            >
                @include('entries.partials.table')
            </div>

            <p
                id="entries-error"
                class="mt-4 hidden rounded-md bg-red-50 p-4 text-sm text-red-700"
                role="alert"
            >
                The entries could not be loaded. Please try again.
            </p>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const container = document.getElementById('entries-table');
                const errorMessage = document.getElementById('entries-error');

                container.addEventListener('click', async (event) => {
                    const link = event.target.closest('.pagination a');

                    if (!link) {
                        return;
                    }

                    event.preventDefault();
                    errorMessage.classList.add('hidden');
                    container.setAttribute('aria-busy', 'true');

                    try {
                        const response = await fetch(link.href, {
                            headers: {
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest',
                            },
                        });

                        if (!response.ok) {
                            throw new Error('Unable to load entries.');
                        }

                        const data = await response.json();

                        container.innerHTML = data.html;
                        window.history.pushState({}, '', link.href);
                    } catch (error) {
                        errorMessage.classList.remove('hidden');
                    } finally {
                        container.removeAttribute('aria-busy');
                    }
                });
            });
        </script>
    @endpush
</x-app-layout>
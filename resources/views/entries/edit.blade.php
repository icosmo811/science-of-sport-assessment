<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            Edit entry
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-5xl sm:px-6 lg:px-8">
            @if (session('status'))
                <div class="mb-6 rounded-md bg-green-50 p-4 text-sm text-green-700">
                    {{ session('status') }}
                </div>
            @endif

            <form
                method="POST"
                action="{{ route('entries.update', $entry) }}"
                class="space-y-6"
            >
                @csrf
                @method('PUT')

                @include('entries.partials.form', [
                    'entry' => $entry,
                    'submitLabel' => 'Save changes',
                ])
            </form>
        </div>
    </div>
</x-app-layout>
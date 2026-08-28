<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            Create entry
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-5xl sm:px-6 lg:px-8">
            <form
                method="POST"
                action="{{ route('entries.store') }}"
                class="space-y-6"
            >
                @csrf

                @include('entries.partials.form', [
                    'entry' => null,
                    'submitLabel' => 'Create entry',
                ])
            </form>
        </div>
    </div>
</x-app-layout>
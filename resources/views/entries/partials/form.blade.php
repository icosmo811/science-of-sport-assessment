@php
    $eventOptions = old('event_options');

    if ($eventOptions === null) {
        $eventOptions = $entry
            ? $entry->eventOptions->map(fn ($option) => [
                'category' => $option->category,
                'name' => $option->name,
                'price' => $option->price,
                'golfer_count' => $option->golfer_count,
                'description' => $option->description,
                'benefits' => $option->benefits,
                'sort_order' => $option->sort_order,
            ])->all()
            : [[
                'category' => 'golf',
                'name' => '',
                'price' => '',
                'golfer_count' => '',
                'description' => '',
                'benefits' => [],
                'sort_order' => 0,
            ]];
    }
@endphp

@if ($errors->any())
    <div class="rounded-md bg-red-50 p-4 text-sm text-red-700">
        <p class="font-semibold">Please correct the following errors:</p>

        <ul class="mt-2 list-disc space-y-1 pl-5">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="space-y-6 bg-white p-6 shadow-sm sm:rounded-lg">
    <h3 class="text-lg font-semibold text-gray-900">
        Event information
    </h3>

    <div class="grid gap-6 md:grid-cols-2">
        <div>
            <x-input-label for="title" value="Title" />
            <x-text-input
                id="title"
                name="title"
                type="text"
                class="mt-1 block w-full"
                :value="old('title', $entry?->title)"
                required
            />
        </div>

        <div>
            <x-input-label for="slug" value="Slug" />
            <x-text-input
                id="slug"
                name="slug"
                type="text"
                class="mt-1 block w-full"
                :value="old('slug', $entry?->slug)"
                required
            />
        </div>

        <div class="md:col-span-2">
            <x-input-label for="tagline" value="Tagline" />
            <x-text-input
                id="tagline"
                name="tagline"
                type="text"
                class="mt-1 block w-full"
                :value="old('tagline', $entry?->tagline)"
            />
        </div>

        <div>
            <x-input-label for="event_date" value="Event date" />
            <x-text-input
                id="event_date"
                name="event_date"
                type="date"
                class="mt-1 block w-full"
                :value="old('event_date', $entry?->event_date?->format('Y-m-d'))"
                required
            />
        </div>

        <div>
            <x-input-label for="location" value="Location" />
            <x-text-input
                id="location"
                name="location"
                type="text"
                class="mt-1 block w-full"
                :value="old('location', $entry?->location)"
                required
            />
        </div>

        <div class="md:col-span-2">
            <x-input-label for="overview" value="Overview" />

            <textarea
                id="overview"
                name="overview"
                rows="5"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                required
            >{{ old('overview', $entry?->overview) }}</textarea>
        </div>

        <div>
            <x-input-label
                for="sponsorship_benefits_text"
                value="Sponsorship benefits"
            />

            <textarea
                id="sponsorship_benefits_text"
                name="sponsorship_benefits_text"
                rows="7"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                required
            >{{ old('sponsorship_benefits_text', implode("\n", $entry?->sponsorship_benefits ?? [])) }}</textarea>

            <p class="mt-1 text-xs text-gray-500">
                Enter one benefit per line.
            </p>
        </div>

        <div>
            <x-input-label
                for="player_benefits_text"
                value="Player benefits"
            />

            <textarea
                id="player_benefits_text"
                name="player_benefits_text"
                rows="7"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                required
            >{{ old('player_benefits_text', implode("\n", $entry?->player_benefits ?? [])) }}</textarea>

            <p class="mt-1 text-xs text-gray-500">
                Enter one benefit per line.
            </p>
        </div>

        <div>
            <x-input-label for="hero_image_url" value="Hero image URL or path" />
            <x-text-input
                id="hero_image_url"
                name="hero_image_url"
                type="text"
                class="mt-1 block w-full"
                :value="old('hero_image_url', $entry?->hero_image_url)"
            />
        </div>

        <div>
            <x-input-label for="published_at" value="Published at" />
            <x-text-input
                id="published_at"
                name="published_at"
                type="datetime-local"
                class="mt-1 block w-full"
                :value="old(
                    'published_at',
                    $entry?->published_at?->format('Y-m-d\TH:i'),
                )"
            />
        </div>
    </div>
</div>

<div class="space-y-6 bg-white p-6 shadow-sm sm:rounded-lg">
    <div>
        <h3 class="text-lg font-semibold text-gray-900">
            Event options
        </h3>

        <p class="mt-1 text-sm text-gray-500">
            At least one option is required.
        </p>
    </div>

    <div class="space-y-6">
        @foreach ($eventOptions as $index => $option)
            @php
                $optionBenefits = $option['benefits'] ?? [];

                if (! is_array($optionBenefits)) {
                    $optionBenefits = [];
                }
            @endphp

            <fieldset class="rounded-md border border-gray-200 p-4">
                <legend class="px-2 text-sm font-semibold text-gray-700">
                    Option {{ $loop->iteration }}
                </legend>

                <div class="grid gap-4 md:grid-cols-2">
                    <div>
                        <x-input-label
                            :for="'event_options_'.$index.'_category'"
                            value="Category"
                        />

                        <select
                            id="event_options_{{ $index }}_category"
                            name="event_options[{{ $index }}][category]"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            required
                        >
                            @foreach (['sponsorship', 'golf', 'social'] as $category)
                                <option
                                    value="{{ $category }}"
                                    @selected(($option['category'] ?? '') === $category)
                                >
                                    {{ ucfirst($category) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <x-input-label
                            :for="'event_options_'.$index.'_name'"
                            value="Name"
                        />

                        <x-text-input
                            :id="'event_options_'.$index.'_name'"
                            name="event_options[{{ $index }}][name]"
                            type="text"
                            class="mt-1 block w-full"
                            :value="$option['name'] ?? ''"
                            required
                        />
                    </div>

                    <div>
                        <x-input-label
                            :for="'event_options_'.$index.'_price'"
                            value="Price"
                        />

                        <x-text-input
                            :id="'event_options_'.$index.'_price'"
                            name="event_options[{{ $index }}][price]"
                            type="number"
                            min="0"
                            step="0.01"
                            class="mt-1 block w-full"
                            :value="$option['price'] ?? ''"
                            required
                        />
                    </div>

                    <div>
                        <x-input-label
                            :for="'event_options_'.$index.'_golfer_count'"
                            value="Golfer count"
                        />

                        <x-text-input
                            :id="'event_options_'.$index.'_golfer_count'"
                            name="event_options[{{ $index }}][golfer_count]"
                            type="number"
                            min="1"
                            class="mt-1 block w-full"
                            :value="$option['golfer_count'] ?? ''"
                        />
                    </div>

                    <div>
                        <x-input-label
                            :for="'event_options_'.$index.'_sort_order'"
                            value="Sort order"
                        />

                        <x-text-input
                            :id="'event_options_'.$index.'_sort_order'"
                            name="event_options[{{ $index }}][sort_order]"
                            type="number"
                            min="0"
                            class="mt-1 block w-full"
                            :value="$option['sort_order'] ?? 0"
                            required
                        />
                    </div>

                    <div class="md:col-span-2">
                        <x-input-label
                            :for="'event_options_'.$index.'_description'"
                            value="Description"
                        />

                        <textarea
                            id="event_options_{{ $index }}_description"
                            name="event_options[{{ $index }}][description]"
                            rows="3"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        >{{ $option['description'] ?? '' }}</textarea>
                    </div>

                    <div class="md:col-span-2">
                        <x-input-label
                            :for="'event_options_'.$index.'_benefits'"
                            value="Benefits"
                        />

                        <textarea
                            id="event_options_{{ $index }}_benefits"
                            name="event_options[{{ $index }}][benefits_text]"
                            rows="4"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        >{{ implode("\n", $optionBenefits) }}</textarea>

                        <p class="mt-1 text-xs text-gray-500">
                            Enter one benefit per line.
                        </p>
                    </div>
                </div>
            </fieldset>
        @endforeach
    </div>
</div>

<div class="flex items-center justify-end gap-4">
    <a
        href="{{ route('entries.index') }}"
        class="text-sm font-semibold text-gray-600 hover:text-gray-900"
    >
        Cancel
    </a>

    <button
        type="submit"
        class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-500"
    >
        {{ $submitLabel }}
    </button>
</div>
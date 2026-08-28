<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Unique;

abstract class EntryRequest extends FormRequest
{
    protected function prepareForValidation(): void
    {
        $data = [];

        if ($this->has('sponsorship_benefits_text')) {
            $data['sponsorship_benefits'] = $this->lines(
                $this->input('sponsorship_benefits_text'),
            );
        }

        if ($this->has('player_benefits_text')) {
            $data['player_benefits'] = $this->lines(
                $this->input('player_benefits_text'),
            );
        }

        $eventOptions = $this->input('event_options');

        if (is_array($eventOptions)) {
            foreach ($eventOptions as &$eventOption) {
                if (
                    is_array($eventOption)
                    && array_key_exists('benefits_text', $eventOption)
                ) {
                    $eventOption['benefits'] = $this->lines(
                        $eventOption['benefits_text'],
                    );

                    unset($eventOption['benefits_text']);
                }
            }

            unset($eventOption);

            $data['event_options'] = $eventOptions;
        }

        $this->merge($data);
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'slug' => [
                'required',
                'string',
                'max:255',
                $this->uniqueSlugRule(),
            ],
            'tagline' => ['nullable', 'string', 'max:255'],
            'event_date' => ['required', 'date'],
            'location' => ['required', 'string', 'max:255'],
            'overview' => ['required', 'string'],
            'sponsorship_benefits' => ['required', 'array'],
            'sponsorship_benefits.*' => ['required', 'string', 'max:500'],
            'player_benefits' => ['required', 'array'],
            'player_benefits.*' => ['required', 'string', 'max:500'],
            'hero_image_url' => ['nullable', 'string', 'max:2048'],
            'published_at' => ['nullable', 'date'],

            'event_options' => ['required', 'array', 'min:1'],
            'event_options.*' => [
                'array:category,name,price,golfer_count,description,benefits,sort_order',
            ],
            'event_options.*.category' => [
                'required',
                'string',
                Rule::in(['sponsorship', 'golf', 'social']),
            ],
            'event_options.*.name' => ['required', 'string', 'max:255'],
            'event_options.*.price' => ['required', 'numeric', 'min:0', 'max:99999999.99'],
            'event_options.*.golfer_count' => ['nullable', 'integer', 'min:1', 'max:65535'],
            'event_options.*.description' => ['nullable', 'string'],
            'event_options.*.benefits' => ['nullable', 'array'],
            'event_options.*.benefits.*' => ['required', 'string', 'max:500'],
            'event_options.*.sort_order' => ['required', 'integer', 'min:0', 'max:65535'],
        ];
    }

    abstract protected function uniqueSlugRule(): Unique;

    /**
     * @return list<string>
     */
    private function lines(mixed $value): array
    {
        if (! is_string($value)) {
            return [];
        }

        $lines = preg_split('/\R/', $value) ?: [];

        return array_values(array_filter(
            array_map('trim', $lines),
            fn (string $line): bool => $line !== '',
        ));
    }
}

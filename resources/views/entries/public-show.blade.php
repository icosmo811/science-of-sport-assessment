<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ $entry->title }} | {{ config('app.name') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link
            href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap"
            rel="stylesheet"
        >

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>

    <body class="bg-slate-950 font-sans text-slate-100 antialiased">
        <header class="border-b border-white/10 bg-slate-950/90">
            <div class="mx-auto flex max-w-7xl items-center justify-between px-6 py-5">
                <a
                    href="{{ route('entries.public.show', $entry) }}"
                    class="text-lg font-bold tracking-wide text-white"
                >
                    Science of Sport
                </a>

                @auth
                    <a
                        href="{{ route('entries.index') }}"
                        class="rounded-full border border-white/20 px-5 py-2 text-sm font-semibold text-white hover:bg-white/10"
                    >
                        Manage entries
                    </a>
                @else
                    <a
                        href="{{ route('login') }}"
                        class="rounded-full border border-white/20 px-5 py-2 text-sm font-semibold text-white hover:bg-white/10"
                    >
                        Staff login
                    </a>
                @endauth
            </div>
        </header>

        <main>
            <section class="relative overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-br from-emerald-900 via-slate-950 to-sky-950"></div>
                <div class="absolute -right-32 -top-32 h-96 w-96 rounded-full bg-emerald-400/20 blur-3xl"></div>
                <div class="absolute -bottom-32 left-10 h-80 w-80 rounded-full bg-sky-500/20 blur-3xl"></div>

                <div class="relative mx-auto max-w-7xl px-6 py-24 lg:py-32">
                    <p class="mb-4 text-sm font-bold uppercase tracking-[0.3em] text-emerald-300">
                        {{ $entry->tagline }}
                    </p>

                    <h1 class="max-w-4xl text-4xl font-extrabold tracking-tight text-white sm:text-6xl">
                        {{ $entry->title }}
                    </h1>

                    <div class="mt-8 flex flex-wrap gap-4 text-sm font-semibold">
                        <span class="rounded-full bg-white/10 px-5 py-3 backdrop-blur">
                            {{ $entry->event_date->format('F j, Y') }}
                        </span>

                        <span class="rounded-full bg-white/10 px-5 py-3 backdrop-blur">
                            {{ $entry->location }}
                        </span>
                    </div>
                </div>
            </section>

            <section class="bg-white text-slate-900">
                <div class="mx-auto grid max-w-7xl gap-12 px-6 py-20 lg:grid-cols-3">
                    <div class="lg:col-span-2">
                        <p class="text-sm font-bold uppercase tracking-widest text-emerald-700">
                            About the event
                        </p>

                        <h2 class="mt-3 text-3xl font-bold">
                            Sports experiences that create opportunity
                        </h2>

                        <p class="mt-6 text-lg leading-8 text-slate-600">
                            {{ $entry->overview }}
                        </p>
                    </div>

                    <aside class="rounded-2xl bg-slate-100 p-8">
                        <h2 class="text-xl font-bold">Player benefits</h2>

                        <ul class="mt-5 space-y-3 text-slate-700">
                            @foreach ($entry->player_benefits as $benefit)
                                <li class="flex gap-3">
                                    <span class="font-bold text-emerald-600">✓</span>
                                    <span>{{ $benefit }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </aside>
                </div>
            </section>

            <section class="bg-slate-100 text-slate-900">
                <div class="mx-auto max-w-7xl px-6 py-20">
                    <div class="max-w-3xl">
                        <p class="text-sm font-bold uppercase tracking-widest text-emerald-700">
                            Opportunities
                        </p>

                        <h2 class="mt-3 text-3xl font-bold">
                            Sponsorships and registrations
                        </h2>

                        <p class="mt-4 text-slate-600">
                            Choose an option that fits your participation in the event.
                        </p>
                    </div>

                    <div class="mt-12 grid gap-6 md:grid-cols-2 xl:grid-cols-3">
                        @foreach ($entry->eventOptions as $option)
                            <article class="flex flex-col rounded-2xl bg-white p-7 shadow-sm ring-1 ring-slate-200">
                                <span class="text-xs font-bold uppercase tracking-widest text-emerald-700">
                                    {{ $option->category }}
                                </span>

                                <h3 class="mt-3 text-2xl font-bold">
                                    {{ $option->name }}
                                </h3>

                                <p class="mt-3 text-3xl font-extrabold text-slate-900">
                                    ${{ number_format((float) $option->price, 2) }}
                                </p>

                                @if ($option->golfer_count)
                                    <p class="mt-2 text-sm text-slate-500">
                                        Includes {{ $option->golfer_count }}
                                        {{ Str::plural('golfer', $option->golfer_count) }}
                                    </p>
                                @endif

                                @if ($option->description)
                                    <p class="mt-5 leading-7 text-slate-600">
                                        {{ $option->description }}
                                    </p>
                                @endif

                                @if ($option->benefits)
                                    <ul class="mt-5 space-y-2 text-sm text-slate-600">
                                        @foreach ($option->benefits as $benefit)
                                            <li class="flex gap-2">
                                                <span class="text-emerald-600">✓</span>
                                                <span>{{ $benefit }}</span>
                                            </li>
                                        @endforeach
                                    </ul>
                                @endif
                            </article>
                        @endforeach
                    </div>
                </div>
            </section>

            <section class="bg-white text-slate-900">
                <div class="mx-auto max-w-7xl px-6 py-20">
                    <div class="rounded-3xl bg-emerald-950 p-10 text-white lg:p-14">
                        <div class="max-w-3xl">
                            <h2 class="text-3xl font-bold">
                                Sponsorship benefits
                            </h2>

                            <ul class="mt-8 grid gap-4 md:grid-cols-2">
                                @foreach ($entry->sponsorship_benefits as $benefit)
                                    <li class="flex gap-3 text-emerald-50">
                                        <span class="font-bold text-emerald-300">✓</span>
                                        <span>{{ $benefit }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </section>
        </main>

        <footer class="border-t border-white/10 bg-slate-950">
            <div class="mx-auto max-w-7xl px-6 py-8 text-sm text-slate-400">
                Science of Sport · Golf Classic
            </div>
        </footer>
    </body>
</html>
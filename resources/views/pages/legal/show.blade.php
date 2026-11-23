@extends('layouts.public')
@section('title', $title.' | '.(setting('general.name') ?? __('Mosque')))
@section('content')

    <div class="bg-emerald-900 text-white py-16">
        <div class="max-w-4xl mx-auto px-6">
            <h1 class="text-4xl font-bold tracking-tight mb-3">{{ $title }}</h1>
            <p class="text-emerald-200 text-sm">{{ __('Last updated') }}: {{ \Illuminate\Support\Carbon::parse($updatedAt)->translatedFormat('j F Y') }}</p>
        </div>
    </div>

    <div class="max-w-4xl mx-auto px-6 py-12">
        <article class="space-y-4 text-neutral-700 leading-relaxed [&_h2]:mt-10 [&_h2]:mb-3 [&_h2]:text-2xl [&_h2]:font-bold [&_h2]:text-neutral-900 [&_h3]:mt-6 [&_h3]:mb-2 [&_h3]:text-lg [&_h3]:font-semibold [&_h3]:text-neutral-900 [&_ul]:list-disc [&_ul]:ps-6 [&_ul]:space-y-1.5 [&_a]:text-emerald-700 [&_a]:underline [&_a:hover]:text-emerald-900 [&_table]:w-full [&_table]:text-sm [&_th]:bg-neutral-100 [&_th]:p-2 [&_th]:text-start [&_td]:p-2 [&_td]:border-t [&_td]:border-neutral-200 [&_td]:align-top">
            @include($contentView, [
                'mosqueName' => setting('general.name') ?? __('Mosque'),
                'mosqueAddress' => setting('general.address'),
                'mosqueEmail' => setting('general.email'),
                'sessionCookie' => config('session.cookie'),
                'sessionLifetime' => config('session.lifetime'),
            ])

            <div class="mt-10 rounded-xl border border-emerald-200 bg-emerald-50 p-5">
                <button type="button" data-cc="show-preferencesModal" class="rounded-lg bg-emerald-700 px-4 py-2 text-sm font-medium text-white transition-colors hover:bg-emerald-800">
                    {{ __('Change cookie settings') }}
                </button>
            </div>
        </article>
    </div>

@endsection

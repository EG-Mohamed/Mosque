@props(['category' => 'media', 'provider' => null])

<div data-consent-placeholder="{{ $category }}" class="absolute inset-0 z-10 flex flex-col items-center justify-center gap-3 bg-neutral-100 p-6 text-center">
    <x-heroicon-o-shield-check class="h-10 w-10 text-emerald-600" />
    <p class="max-w-md text-sm text-neutral-600">
        {{ $provider ? __('This content is provided by :provider, which may set cookies and process your data.', ['provider' => $provider]) : __('This content is provided by a third party, which may set cookies and process your data.') }}
    </p>
    <div class="flex flex-wrap items-center justify-center gap-2">
        <button type="button" data-consent-accept="{{ $category }}" class="rounded-lg bg-emerald-700 px-4 py-2 text-sm font-medium text-white transition-colors hover:bg-emerald-800">
            {{ __('Load content') }}
        </button>
        <button type="button" data-cc="show-preferencesModal" class="rounded-lg border border-neutral-300 px-4 py-2 text-sm font-medium text-neutral-700 transition-colors hover:bg-white">
            {{ __('Cookie settings') }}
        </button>
    </div>
</div>

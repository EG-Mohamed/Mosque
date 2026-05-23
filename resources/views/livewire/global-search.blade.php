<div class="relative"
     x-data="{ open: false }"
     @click.away="open = false"
     @keydown.escape.window="open = false">

    <div class="relative">
        <div class="pointer-events-none absolute inset-y-0 start-0 flex items-center ps-3">
            <div wire:loading wire:target="query">
                <svg class="w-4 h-4 text-emerald-500 animate-spin" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                </svg>
            </div>
            <div wire:loading.remove wire:target="query">
                <svg class="w-4 h-4 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M21 21l-4.35-4.35M17 11A6 6 0 1 1 5 11a6 6 0 0 1 12 0z"/>
                </svg>
            </div>
        </div>

        <input type="search"
               wire:model.live.debounce.300ms="query"
               @focus="open = true"
               @input="open = $event.target.value.length >= 2"
               placeholder="{{ __('Search news & khutab...') }}"
               autocomplete="off"
               class="w-full lg:w-56 xl:w-72 ps-9 pe-3 py-1.5 text-sm bg-neutral-50 border border-neutral-200 rounded-md
                      placeholder-neutral-400 text-neutral-700
                      focus:outline-none focus:ring-2 focus:ring-emerald-400 focus:border-emerald-400
                      transition-all duration-150">
    </div>

    <div x-show="open && $wire.query.length >= 2"
         x-transition:enter="transition ease-out duration-100"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-75"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         class="absolute top-full mt-2 start-0 w-80 lg:w-96 bg-white rounded-lg shadow-lg border border-neutral-200 z-50 overflow-hidden"
         @click="open = false"
         x-cloak>

        @php $results = $this->results; @endphp

        @if($results['news']->isNotEmpty() || $results['khutab']->isNotEmpty())
            <div class="max-h-96 overflow-y-auto divide-y divide-neutral-100">

                @if($results['news']->isNotEmpty())
                    <div>
                        <div class="px-3 py-1.5 text-xs font-semibold text-neutral-400 uppercase tracking-wide bg-neutral-50">
                            {{ __('News') }}
                        </div>
                        @foreach($results['news'] as $result)
                            <a href="{{ $result['url'] }}"
                               class="flex flex-col px-3 py-2.5 hover:bg-emerald-50 transition-colors group">
                                <span class="text-sm font-medium text-neutral-800 group-hover:text-emerald-700 leading-snug">
                                    {{ $result['title'] }}
                                </span>
                                @if($result['snippetHtml'])
                                    <span class="text-xs text-neutral-500 mt-0.5 leading-relaxed line-clamp-2">
                                        {!! $result['snippetHtml'] !!}
                                    </span>
                                @endif
                            </a>
                        @endforeach
                    </div>
                @endif

                @if($results['khutab']->isNotEmpty())
                    <div>
                        <div class="px-3 py-1.5 text-xs font-semibold text-neutral-400 uppercase tracking-wide bg-neutral-50">
                            {{ __('Khutba') }}
                        </div>
                        @foreach($results['khutab'] as $result)
                            <a href="{{ $result['url'] }}"
                               class="flex flex-col px-3 py-2.5 hover:bg-emerald-50 transition-colors group">
                                <span class="text-sm font-medium text-neutral-800 group-hover:text-emerald-700 leading-snug">
                                    {{ $result['title'] }}
                                </span>
                                @if($result['snippetHtml'])
                                    <span class="text-xs text-neutral-500 mt-0.5 leading-relaxed line-clamp-2">
                                        {!! $result['snippetHtml'] !!}
                                    </span>
                                @endif
                            </a>
                        @endforeach
                    </div>
                @endif

            </div>
        @else
            <div wire:loading.remove wire:target="query"
                 class="px-4 py-6 text-center text-sm text-neutral-400">
                {{ __('No results found') }}
            </div>
            <div wire:loading wire:target="query"
                 class="px-4 py-6 text-center text-sm text-neutral-400">
                {{ __('Search') }}...
            </div>
        @endif
    </div>
</div>

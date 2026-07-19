@php
    $isToday = $today && $pt->date->isSameDay($today->date);
    $isFriday = $pt->date->isFriday();
    $hasJummah = $isFriday && ($pt->jummah_adhan ?? null);
@endphp
<article class="overflow-hidden rounded-2xl border {{ $isToday ? 'border-emerald-300 bg-emerald-50/80 shadow-sm shadow-emerald-100/60' : ($isFriday ? 'border-amber-200 bg-amber-50/60 shadow-sm shadow-amber-100/60' : 'border-neutral-200 bg-white shadow-sm') }}">
    <div class="border-b {{ $isToday ? 'border-emerald-200/80 bg-emerald-100/70' : ($isFriday ? 'border-amber-200/80 bg-amber-100/70' : 'border-neutral-200 bg-neutral-50/80') }} px-4 py-3.5">
        <div class="flex items-start justify-between gap-3">
            <div class="min-w-0">
                <p class="text-sm font-semibold {{ $isToday ? 'text-emerald-900' : ($isFriday ? 'text-amber-900' : 'text-neutral-900') }}">
                    {{ \App\Support\LocalizedDate::date($pt->date) }}
                </p>
                <p class="mt-1 text-xs {{ $isToday ? 'text-emerald-700' : ($isFriday ? 'text-amber-700' : 'text-neutral-500') }}">
                    {{ \App\Support\LocalizedDate::weekday($pt->date) }}
                    <span class="mx-1 text-neutral-300">&middot;</span>
                    {{ \App\Support\LocalizedDate::hijri($pt->date) }}
                </p>
            </div>
            <div class="flex flex-wrap justify-end gap-2">
                @if($isToday)
                    <span class="inline-flex items-center rounded-full bg-emerald-600 px-2.5 py-1 text-[10px] font-semibold uppercase tracking-wide text-white">{{ __('Today') }}</span>
                @endif
                @if($isFriday)
                    <span class="inline-flex items-center rounded-full bg-amber-100 px-2.5 py-1 text-[10px] font-semibold uppercase tracking-wide text-amber-700 ring-1 ring-amber-200">{{ __('Friday') }}</span>
                @endif
            </div>
        </div>
    </div>

    <div class="grid grid-cols-2 gap-3 px-4 py-4">
        @foreach($tablePrayers as $prayer)
            @php
                $adhan = $prayer['key'] === 'sunrise' ? $pt->sunrise : $pt->{$prayer['key'].'_adhan'};
                $iqamah = $prayer['key'] === 'sunrise' ? null : $pt->{$prayer['key'].'_iqamah'};
            @endphp
            <div class="rounded-xl border border-neutral-200/80 bg-neutral-50/70 px-3 py-2.5">
                <p class="text-[11px] font-semibold uppercase tracking-wide text-neutral-500">{{ $prayer['label'] }}</p>
                <p class="mt-1 text-base font-semibold tabular-nums {{ $isToday ? 'text-emerald-800' : 'text-neutral-900' }}">
                    {{ \App\Support\LocalizedDate::time($adhan) ?? '—' }}
                </p>
                <p class="mt-1 text-xs tabular-nums {{ $iqamah ? ($isToday ? 'text-emerald-600' : 'text-neutral-500') : 'text-neutral-300' }}">
                    {{ $iqamah ? __('Iqamah: :time', ['time' => \App\Support\LocalizedDate::time($iqamah)]) : __('No iqamah') }}
                </p>
            </div>
        @endforeach
    </div>

    @if($hasJummah)
        <div class="border-t border-amber-200/80 bg-amber-50/80 px-4 py-4">
            <div class="flex items-center justify-between gap-3">
                <div>
                    <p class="text-[11px] font-semibold uppercase tracking-[0.22em] text-amber-600">{{ __("Jumu'ah") }}</p>
                    <p class="mt-1 text-sm text-amber-800">{{ __('Friday Prayer Schedule') }}</p>
                </div>
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-amber-100 text-amber-600">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                              d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
            </div>
            <div class="mt-3 grid grid-cols-1 gap-2 sm:grid-cols-3">
                <div class="rounded-xl bg-white/70 px-3 py-2.5 ring-1 ring-amber-200/70">
                    <p class="text-[10px] font-semibold uppercase tracking-wide text-amber-500">{{ __('Salah') }}</p>
                    <p class="mt-1 text-sm font-bold text-amber-800 tabular-nums">{{ \App\Support\LocalizedDate::time($pt->jummah_adhan) }}</p>
                </div>
                @if($pt->jummah_khutba_time ?? null)
                    <div class="rounded-xl bg-white/70 px-3 py-2.5 ring-1 ring-amber-200/70">
                        <p class="text-[10px] font-semibold uppercase tracking-wide text-amber-500">{{ __('Khutbah') }}</p>
                        <p class="mt-1 text-sm font-semibold text-amber-700 tabular-nums">{{ \App\Support\LocalizedDate::time($pt->jummah_khutba_time) }}</p>
                    </div>
                @endif
                @if($pt->jummah_iqamah ?? null)
                    <div class="rounded-xl bg-white/70 px-3 py-2.5 ring-1 ring-amber-200/70">
                        <p class="text-[10px] font-semibold uppercase tracking-wide text-amber-500">{{ __('Iqamah') }}</p>
                        <p class="mt-1 text-sm font-semibold text-amber-700 tabular-nums">{{ \App\Support\LocalizedDate::time($pt->jummah_iqamah) }}</p>
                    </div>
                @endif
            </div>
        </div>
    @endif
</article>

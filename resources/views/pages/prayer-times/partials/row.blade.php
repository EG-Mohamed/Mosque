@php
    $isToday = $today && $pt->date->isSameDay($today->date);
    $isFriday = $pt->date->isFriday();
    $hasJummah = $isFriday && ($pt->jummah_adhan ?? null);
@endphp
<tr class="align-top transition-colors {{ $isToday ? 'bg-emerald-50/80' : ($isFriday ? 'bg-amber-50/50' : 'bg-white hover:bg-neutral-50/80') }}">
    <td class="px-4 py-4 whitespace-nowrap">
        <div class="flex items-start gap-3">
            <div class="min-w-0">
                <p class="font-semibold {{ $isToday ? 'text-emerald-900' : ($isFriday ? 'text-amber-900' : 'text-neutral-900') }}">
                    {{ \App\Support\LocalizedDate::date($pt->date) }}
                </p>
                <p class="mt-1 text-xs {{ $isToday ? 'text-emerald-700' : ($isFriday ? 'text-amber-700' : 'text-neutral-500') }}">
                    {{ \App\Support\LocalizedDate::weekday($pt->date) }}
                </p>
                <p class="mt-0.5 text-[11px] {{ $isToday ? 'text-emerald-600' : ($isFriday ? 'text-amber-600' : 'text-neutral-400') }}">
                    {{ \App\Support\LocalizedDate::hijri($pt->date) }}
                </p>
            </div>
            <div class="flex flex-col gap-1">
                @if($isToday)
                    <span class="inline-flex items-center rounded-full bg-emerald-100 px-2 py-1 text-[10px] font-semibold uppercase tracking-wide text-emerald-700 ring-1 ring-emerald-200">{{ __('Today') }}</span>
                @endif
                @if($isFriday)
                    <span class="inline-flex items-center rounded-full bg-amber-100 px-2 py-1 text-[10px] font-semibold uppercase tracking-wide text-amber-700 ring-1 ring-amber-200">{{ __('Friday') }}</span>
                @endif
            </div>
        </div>
    </td>

    @foreach($tablePrayers as $prayer)
        @php
            $adhan = $prayer['key'] === 'sunrise' ? $pt->sunrise : $pt->{$prayer['key'].'_adhan'};
            $iqamah = $prayer['key'] === 'sunrise' ? null : $pt->{$prayer['key'].'_iqamah'};
        @endphp
        <td class="px-4 py-4 text-center tabular-nums">
            <p class="font-semibold {{ $isToday ? 'text-emerald-800' : 'text-neutral-800' }}">
                {{ \App\Support\LocalizedDate::time($adhan) ?? '—' }}
            </p>
            <p class="mt-1 text-[11px] {{ $iqamah ? ($isToday ? 'text-emerald-600' : 'text-neutral-500') : 'text-neutral-300' }}">
                {{ $iqamah ? __('Iqamah: :time', ['time' => \App\Support\LocalizedDate::time($iqamah)]) : __('No iqamah') }}
            </p>

            @if($prayer['key'] === 'dhuhr' && $hasJummah)
                <div class="mx-auto mt-3 max-w-[12rem] rounded-xl border border-amber-200/80 bg-amber-50/90 px-3 py-2 text-start shadow-sm shadow-amber-100/50">
                    <p class="text-[10px] font-semibold uppercase tracking-[0.2em] text-amber-600">{{ __("Jumu'ah") }}</p>
                    <div class="mt-2 space-y-1 text-[11px] text-amber-700">
                        <div class="flex items-center justify-between gap-2">
                            <span>{{ __('Salah') }}</span>
                            <span class="font-bold tabular-nums text-amber-800">{{ \App\Support\LocalizedDate::time($pt->jummah_adhan) }}</span>
                        </div>
                        @if($pt->jummah_khutba_time ?? null)
                            <div class="flex items-center justify-between gap-2">
                                <span>{{ __('Khutbah') }}</span>
                                <span class="font-semibold tabular-nums">{{ \App\Support\LocalizedDate::time($pt->jummah_khutba_time) }}</span>
                            </div>
                        @endif
                        @if($pt->jummah_iqamah ?? null)
                            <div class="flex items-center justify-between gap-2">
                                <span>{{ __('Iqamah') }}</span>
                                <span class="font-semibold tabular-nums">{{ \App\Support\LocalizedDate::time($pt->jummah_iqamah) }}</span>
                            </div>
                        @endif
                    </div>
                </div>
            @endif
        </td>
    @endforeach
</tr>

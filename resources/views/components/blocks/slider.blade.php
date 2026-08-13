@php
$data = $block['data'] ?? $data ?? [];
$slides = \App\Models\Slider::active()->get();
@endphp

@if($slides->isNotEmpty())
<section class="group relative overflow-hidden bg-emerald-950"
    x-data="{
        current: 0,
        total: {{ $slides->count() }},
        autoplay: null,
        touchStartX: null,
        touchEndX: null,
        start() {
            this.autoplay = setInterval(() => this.next(), 5000);
        },
        stop() {
            clearInterval(this.autoplay);
        },
        next() {
            this.current = (this.current + 1) % this.total;
        },
        prev() {
            this.current = (this.current - 1 + this.total) % this.total;
        },
        handleTouchStart(event) {
            this.touchStartX = event.touches[0].clientX;
            this.touchEndX = event.touches[0].clientX;
        },
        handleTouchMove(event) {
            this.touchEndX = event.touches[0].clientX;
        },
        handleTouchEnd() {
            if (this.touchStartX === null || this.touchEndX === null) {
                return;
            }

            const deltaX = this.touchStartX - this.touchEndX;

            if (Math.abs(deltaX) >= 50) {
                if (deltaX > 0) {
                    this.next();
                } else {
                    this.prev();
                }
            }

            this.touchStartX = null;
            this.touchEndX = null;
        }
    }"
    x-init="start()"
    @mouseenter="stop()"
    @mouseleave="start()"
    @touchstart.passive="handleTouchStart($event)"
    @touchmove.passive="handleTouchMove($event)"
    @touchend="handleTouchEnd()">

    <div class="relative flex h-[70vh] min-h-[32rem] items-center sm:h-[75vh]">
        @foreach($slides as $index => $slide)
            <div class="absolute inset-0 transition-opacity duration-700"
                 x-show="current === {{ $index }}"
                 x-transition:enter="transition-opacity duration-700"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition-opacity duration-700"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0">

                @if($slide->isVideo())
                    <video src="{{ Storage::url($slide->image) }}"
                           autoplay muted loop playsinline
                           class="absolute inset-0 w-full h-full object-cover"></video>
                @elseif($slide->image)
                    <img src="{{ Storage::url($slide->image) }}"
                         alt="{{ $slide->title }}"
                         class="absolute inset-0 w-full h-full object-cover">
                @endif
                <div class="absolute inset-0 bg-gradient-to-t from-emerald-950 via-emerald-950/50 to-emerald-950/20"></div>
            </div>
        @endforeach

        <div class="relative z-10 mx-auto flex h-full w-full max-w-7xl items-center px-6 py-20">
            @foreach($slides as $index => $slide)
                <div x-show="current === {{ $index }}"
                     x-transition:enter="transition duration-500 delay-200"
                     x-transition:enter-start="opacity-0 translate-y-4"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     class="mx-auto max-w-3xl text-center text-white">

                    @if($slide->title)
                        <h2 class="mb-4 text-3xl font-bold leading-tight md:text-4xl lg:text-5xl">
                            {{ $slide->title }}
                        </h2>
                    @endif

                    @if($slide->subtitle)
                        <p class="text-lg leading-relaxed text-emerald-100 md:text-xl">
                            {{ $slide->subtitle }}
                        </p>
                    @endif
                        @if($slide->button_text && $slide->button_url)
                            <div class="mt-8">
                                <a href="{{ $slide->button_url }}" target="_blank"
                                   class="inline-flex items-center px-8 py-3 bg-emerald-500 hover:bg-emerald-600 text-white font-semibold rounded-lg transition-colors duration-200">
                                    {{ $slide->button_text }}
                                </a>
                            </div>
                        @endif
                </div>
            @endforeach
        </div>

        @if($slides->count() > 1)
            <button @click="prev()"
                    class="absolute left-4 z-20 hidden h-12 w-12 items-center justify-center rounded-full border border-white/10 bg-black/30 text-white opacity-0 backdrop-blur-sm transition-all duration-200 hover:border-emerald-500/50 hover:bg-emerald-600/80 group-hover:opacity-100 lg:flex">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                </svg>
            </button>

            <button @click="next()"
                    class="absolute right-4 z-20 hidden h-12 w-12 items-center justify-center rounded-full border border-white/10 bg-black/30 text-white opacity-0 backdrop-blur-sm transition-all duration-200 hover:border-emerald-500/50 hover:bg-emerald-600/80 group-hover:opacity-100 lg:flex">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                </svg>
            </button>
        @endif
    </div>

    @if($slides->count() > 1)
        <div class="absolute bottom-6 left-0 right-0 z-20 flex justify-center gap-2">
            @foreach($slides as $index => $slide)
                <button @click="current = {{ $index }}"
                        class="w-2.5 h-2.5 rounded-full transition-all duration-300"
                        :class="current === {{ $index }} ? 'bg-emerald-400 w-6' : 'bg-white/40 hover:bg-white/70'">
                </button>
            @endforeach
        </div>
    @endif
</section>
@endif

@props([
    'eyebrow' => null,
    'title',
    'description' => null,
    'icon' => null,
])

<section class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-[#256b4b] via-[#2c7a56] to-[#40a072] text-white shadow-sm mb-5">
    <div class="absolute -right-10 -top-14 w-40 h-40 rounded-full bg-white/10"></div>
    <div class="absolute -right-4 -bottom-16 w-28 h-28 rounded-full bg-white/5"></div>

    <div class="relative px-5 py-4 sm:px-6 sm:py-4">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div class="min-w-0 flex items-center gap-3">
                @if($icon)
                    <span class="inline-flex shrink-0 items-center justify-center w-9 h-9 rounded-xl bg-white/15">
                        <i class="{{ $icon }} text-sm"></i>
                    </span>
                @endif
                <div class="min-w-0">
                    @if($eyebrow)
                        <span class="text-[11px] font-semibold uppercase tracking-wider text-emerald-100">
                            {{ $eyebrow }}
                        </span>
                    @endif
                    <h1 class="text-lg sm:text-xl font-bold tracking-tight leading-tight">
                        {{ $title }}
                    </h1>
                    @if($description)
                        <p class="text-xs text-emerald-100/90 max-w-xl truncate sm:whitespace-normal">
                            {{ $description }}
                        </p>
                    @endif
                </div>
            </div>
            @isset($actions)
                <div class="flex items-center gap-2 print:hidden shrink-0">
                    {{ $actions }}
                </div>
            @endisset
        </div>
    </div>
</section>

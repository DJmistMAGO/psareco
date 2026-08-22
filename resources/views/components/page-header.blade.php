@props([
    'eyebrow' => null,
    'title',
    'description' => null,
    'icon' => null,
])

<section  class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-[#256b4b] via-[#2c7a56] to-[#40a072] text-white shadow-sm mb-6">
    <div class="absolute -right-16 -top-20 w-64 h-64 rounded-full bg-white/10"></div>
    <div class="absolute -right-8 -bottom-24 w-48 h-48 rounded-full bg-white/5"></div>

    <div class="relative p-6 sm:p-7">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-5">
            <div class="min-w-0">
                @if($eyebrow || $icon)
                    <div class="flex items-center gap-2 mb-2">
                        @if($icon)
                            <span class="inline-flex items-center justify-center w-9 h-9 rounded-xl bg-white/15">
                                <i class="{{ $icon }} text-sm"></i>
                            </span>
                        @endif
                        @if($eyebrow)
                            <span class="text-xs font-semibold uppercase tracking-wider text-emerald-100">
                                {{ $eyebrow }}
                            </span>
                        @endif
                    </div>
                @endif
                <h1 class="text-2xl sm:text-3xl font-bold tracking-tight">
                    {{ $title }}
                </h1>
                @if($description)
                    <p class="mt-1 text-sm text-emerald-100 max-w-xl">
                        {{ $description }}
                    </p>
                @endif
            </div>
            @isset($actions)
                <div class="flex items-center gap-2 print:hidden">
                    {{ $actions }}
                </div>
            @endisset
        </div>
    </div>
</section>

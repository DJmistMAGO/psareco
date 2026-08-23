@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination Navigation"
        class="flex flex-col sm:flex-row items-center justify-between gap-4 px-5 py-4 bg-slate-50/60 border-t border-slate-100">

        <p class="text-xs text-slate-500 order-2 sm:order-1">
            Showing <span class="font-semibold text-slate-700">{{ $paginator->firstItem() }}</span>
            – <span class="font-semibold text-slate-700">{{ $paginator->lastItem() }}</span>
            of <span class="font-semibold text-slate-700">{{ $paginator->total() }}</span>
        </p>

        <div class="flex items-center gap-1.5 order-1 sm:order-2 bg-white border border-slate-200 rounded-full p-1 shadow-sm">
            {{-- Previous --}}
            @if ($paginator->onFirstPage())
                <span class="w-8 h-8 flex items-center justify-center rounded-full text-slate-300 cursor-not-allowed">
                    <i class="fa-solid fa-chevron-left text-[11px]"></i>
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}"
                    class="w-8 h-8 flex items-center justify-center rounded-full text-slate-500 hover:bg-emerald-50 hover:text-emerald-700 transition">
                    <i class="fa-solid fa-chevron-left text-[11px]"></i>
                </a>
            @endif

            {{-- Page numbers --}}
            @foreach ($elements as $element)
                @if (is_string($element))
                    <span class="w-8 h-8 flex items-center justify-center text-xs text-slate-300">{{ $element }}</span>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span class="w-8 h-8 flex items-center justify-center rounded-full bg-emerald-600 text-white text-xs font-semibold shadow-sm shadow-emerald-600/30">
                                {{ $page }}
                            </span>
                        @else
                            <a href="{{ $url }}"
                                class="w-8 h-8 flex items-center justify-center rounded-full text-slate-600 hover:bg-emerald-50 hover:text-emerald-700 text-xs font-medium transition">
                                {{ $page }}
                            </a>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next --}}
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}"
                    class="w-8 h-8 flex items-center justify-center rounded-full text-slate-500 hover:bg-emerald-50 hover:text-emerald-700 transition">
                    <i class="fa-solid fa-chevron-right text-[11px]"></i>
                </a>
            @else
                <span class="w-8 h-8 flex items-center justify-center rounded-full text-slate-300 cursor-not-allowed">
                    <i class="fa-solid fa-chevron-right text-[11px]"></i>
                </span>
            @endif
        </div>
    </nav>
@endif

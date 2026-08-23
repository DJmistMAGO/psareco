@if (session('success'))
    <div
        x-data="{ show: true, progress: 100 }"
        x-show="show"
        x-init="
            let interval = setInterval(() => {
                progress -= 2;
                if (progress <= 0) { clearInterval(interval); show = false; }
            }, 100);
        "
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 -translate-y-2 scale-95"
        x-transition:enter-end="opacity-100 translate-y-0 scale-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="mb-5 relative overflow-hidden bg-white border border-emerald-100 rounded-2xl shadow-md ring-1 ring-emerald-600/5"
    >
        <div class="flex items-start gap-3 p-4">
            <span class="shrink-0 inline-flex items-center justify-center w-9 h-9 rounded-xl bg-emerald-100 text-emerald-600">
                <i class="fas fa-check-circle text-sm"></i>
            </span>

            <div class="min-w-0 flex-1 pt-0.5">
                <h4 class="font-bold text-sm text-emerald-900">Success</h4>
                <p class="mt-0.5 text-xs text-emerald-700">
                    {{ session('success') }}
                </p>
            </div>

            <button @click="show = false" type="button" class="shrink-0 text-emerald-400 hover:text-emerald-600 hover:bg-emerald-50 rounded-lg transition-colors p-1.5">
                <i class="fas fa-times text-xs"></i>
            </button>
        </div>

        {{-- auto-dismiss progress bar --}}
        <div class="h-1 bg-emerald-100">
            <div class="h-full bg-emerald-500 transition-all duration-100 ease-linear" :style="`width: ${progress}%`"></div>
        </div>
    </div>
@endif

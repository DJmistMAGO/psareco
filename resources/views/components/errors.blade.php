@if ($errors->any())
    <div
        x-data="{ show: true }"
        x-show="show"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 -translate-y-2 scale-95"
        x-transition:enter-end="opacity-100 translate-y-0 scale-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="mb-5 bg-white border border-red-100 rounded-2xl shadow-md ring-1 ring-red-600/5"
    >
        <div class="flex items-start gap-3 p-4">
            <span class="shrink-0 inline-flex items-center justify-center w-9 h-9 rounded-xl bg-red-100 text-red-600">
                <i class="fas fa-exclamation-circle text-sm"></i>
            </span>

            <div class="min-w-0 flex-1 pt-0.5">
                <h4 class="font-bold text-sm text-red-900">
                    {{ $errors->count() > 1 ? $errors->count() . ' errors found' : 'Something went wrong' }}
                </h4>
                <ul class="mt-1 space-y-1 text-xs text-red-700 list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>

            <button @click="show = false" type="button" class="shrink-0 text-red-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors p-1.5">
                <i class="fas fa-times text-xs"></i>
            </button>
        </div>
    </div>
@endif

@if (session('success'))
    <div x-data="{ show: true }" x-show="show" x-transition class="mb-5 bg-emerald-50 border-l-4 border-emerald-600 rounded-r-xl p-4 text-emerald-900 shadow-sm relative" >
        <div class="flex items-start justify-between">
            <div class="flex items-start space-x-2">
                <i class="fas fa-check-circle text-emerald-600 mt-0.5 text-sm"></i>
                <div>
                    <h4 class="font-bold text-sm"> Success </h4>
                    <p class="mt-1 text-xs text-emerald-800">
                        {{ session('success') }}
                    </p>
                </div>
            </div>

            <button @click="show = false" type="button" class="text-emerald-500 hover:text-emerald-700 transition-colors p-1" >
                <i class="fas fa-times text-xs"></i>
            </button>
        </div>
    </div>
@endif

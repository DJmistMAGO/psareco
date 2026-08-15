@if ($errors->any())
    <div x-data="{ show: true }" x-show="show" x-transition class="mb-5 bg-red-50 border-l-4 border-red-500 rounded-r-xl p-4 text-red-800 shadow-sm relative" >
        <div class="flex items-start justify-between">
            <div class="flex items-start space-x-2">
                <i class="fas fa-exclamation-circle text-red-500 mt-0.5 text-sm"></i>
                <div>
                    <h4 class="font-bold text-sm">
                        Login Error
                    </h4>
                    <ul class="mt-1 space-y-1 text-xs text-red-700">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
            <button @click="show = false" type="button" class="text-red-400 hover:text-red-600 transition-colors p-1" >
                <i class="fas fa-times text-xs"></i>
            </button>
        </div>
    </div>
@endif

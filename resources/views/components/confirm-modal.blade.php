@props([
    'title' => 'Confirm System Action',
    'message' => 'Are you sure you want to perform this action in the resource portal?',
    'confirmText' => 'Confirm',
    'cancelText' => 'Cancel',
    'confirmClass' => 'bg-emerald-700 hover:bg-emerald-800 text-white',
    'icon' => 'sprout',
    'action' => '#',
    'method' => 'POST'
])

<div x-data="{ open: false, submitting: false }" class="inline-block">
    {{-- Trigger --}}
    <div @click="open = true" class="cursor-pointer">
        {{ $slot }}
    </div>

    <template x-teleport="body">
        <div x-show="open" x-cloak class="fixed inset-0 z-50 overflow-y-auto font-sans" aria-labelledby="modal-title" role="dialog" aria-modal="true" >
            <div x-show="open" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" ></div>

            <div class="flex min-h-full items-center justify-center p-4 text-center">
                <div x-show="open" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-lg border border-emerald-900/10" >
                    <form action="{{ $action }}" method="POST" x-on:submit="if (submitting) $event.preventDefault(); submitting = true;">
                        @csrf
                        @if(strtoupper($method) !== 'POST')
                            @method($method)
                        @endif

                        <div class="bg-gradient-to-r from-emerald-50 via-green-50/40 to-white px-6 py-4 border-b border-emerald-100 flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-emerald-100/80 text-emerald-800 shadow-inner">
                                    @if($icon === 'shield-alert')
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m0 3.75h.008v.008H12v-.008zM12 3s7.5 2.25 7.5 10.5c0 5-3.5 8-7.5 9.5-4-1.5-7.5-4.5-7.5-9.5C4.5 5.25 12 3 12 3z" />
                                        </svg>
                                    @else
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 21V11m0 0C10 7 4 6 4 6s1 6 7 5zm0 0c2-4 8-5 8-5s-1 6-7 5z" />
                                        </svg>
                                    @endif
                                </div>
                                <div>
                                    <h3 class="text-base font-semibold text-slate-800" id="modal-title">
                                        {{ $title }}
                                    </h3>
                                    <p class="text-[11px] font-medium tracking-wide uppercase text-emerald-700">Farmer Resource Management System</p>
                                </div>
                            </div>

                            <button type="button" @click="open = false" class="rounded-lg p-1.5 text-slate-400 hover:bg-emerald-100/60 hover:text-slate-600 transition-colors" >
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>

                        <div class="px-6 py-6 text-md leading-relaxed text-slate-600">
                            <p>{{ $message }}</p>
                        </div>

                        <div class="flex items-center justify-end gap-3 bg-slate-50/80 px-6 py-3.5 border-t border-slate-100">
                            <button type="button" @click="open = false" class="rounded-xl border border-slate-300 bg-white px-4 py-2 text-sm font-medium text-slate-700 shadow-sm hover:bg-slate-50 transition focus:outline-none focus:ring-2 focus:ring-emerald-500/20" >
                                {{ $cancelText }}
                            </button>

                            <button type="submit" class="rounded-xl px-4 py-2 text-sm font-semibold shadow-sm text-white transition focus:outline-none focus:ring-2 focus:ring-offset-1 {{ $confirmClass }}" >
                                {{ $confirmText }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </template>
</div>

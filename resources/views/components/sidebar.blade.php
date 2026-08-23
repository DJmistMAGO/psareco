<div x-show="mobileOpen" x-cloak x-transition:enter="transition-opacity ease-linear duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity ease-linear duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" @click="mobileOpen = false" class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm z-40 lg:hidden" ></div>


<aside :class="{ 'w-64': sidebarOpen, 'w-20': !sidebarOpen, '-translate-x-full lg:translate-x-0': !mobileOpen, 'translate-x-0': mobileOpen}"
    class="fixed lg:sticky inset-y-0 lg:top-0 left-0 z-50 bg-[#f2f8f4] flex flex-col justify-between border-r border-emerald-100/80 p-4 shrink-0 h-screen transition-all duration-300 ease-in-out">

    <div>
        <div class="relative mb-6">
    {{-- desktop toggle button --}}
    <button type="button" @click="sidebarOpen = !sidebarOpen"
        :class="sidebarOpen ? 'w-full' : 'w-10 mx-auto'"
        class="hidden lg:flex h-10 bg-[#3d8b68] hover:bg-[#327356] text-white rounded-xl items-center justify-center transition-all duration-300 shadow-lg ring-2 ring-white/80 z-[60] focus:outline-none"
        title="Toggle Navigation">
        <i class="fa-solid text-sm transition-transform duration-300" :class="sidebarOpen ? 'fa-angle-left' : 'fa-angle-right'"></i>
    </button>

    {{-- mobile close button --}}
    <button type="button" @click="mobileOpen = false" class="lg:hidden absolute right-0 top-0 w-9 h-9 flex items-center justify-center rounded-lg text-slate-500 hover:bg-emerald-100 hover:text-emerald-800 transition" title="Close Navigation">
        <i class="fa-solid fa-xmark"></i>
    </button>
</div>

        <div class="flex flex-col items-center text-center mb-8" >
            <div class="w-12 h-12 lg:w-14 lg:h-14 bg-white rounded-full flex items-center justify-center shadow-sm border border-emerald-100 mb-2 shrink-0 transition-all" >
                <div class="w-full h-full rounded-full flex items-center justify-center text-amber-400 font-bold">
                    <img src="{{ asset('assets/images/PSARECO_logo.png') }}" alt="PSARECO Logo" class="w-full h-full object-contain rounded-full" >
                </div>
            </div>

            <div x-show="sidebarOpen" x-transition class="space-y-0.5">
                <h1  class="font-bold text-emerald-950 text-sm tracking-tight"> PSARECO </h1>
                <p class="text-[11px] text-slate-500 font-medium" > Farm Resource System </p>
            </div>
        </div>

        <hr class="border-emerald-200/60 my-3">

        <nav class="space-y-1.5">

            @php
                $menu = [];

                if (auth()->user()->hasRole('admin')) {

                    $menu = [
                        [ 'route' => 'dashboard.index', 'active' => 'dashboard.*', 'icon' => 'fa-chart-line', 'title' => 'Dashboard', ],
                        [ 'route' => 'reports.index', 'active' => 'reports.*', 'icon' => 'fa-file-alt', 'title' => 'Reports', ],
                        [ 'route' => 'user-management.index', 'active' => 'user-management.*', 'icon' => 'fa-users-cog', 'title' => 'Users', ],
                    ];

                } elseif (auth()->user()->hasRole('officer')) {

                    $menu = [
                        [ 'route' => 'dashboard.index', 'active' => 'dashboard.*', 'icon' => 'fa-chart-line', 'title' => 'Dashboard', ],
                        [ 'route' => 'machinery.index', 'active' => 'machinery.*', 'icon' => 'fa-tractor', 'title' => 'Machinery Management', ],
                        [ 'route' => 'booking.calendar', 'active' => 'booking.*', 'icon' => 'fa-calendar-alt', 'title' => 'Calendar Schedule', ],
                        [ 'route' => 'officer.index-booking', 'active' => 'officer.*', 'icon' => 'fa-calendar-plus', 'title' => 'Machinery Bookings', ],
                        [ 'route' => 'inventory.index', 'active' => 'inventory.*', 'icon' => 'fa-boxes-stacked', 'title' => 'Inventory', ],
                        [ 'route' => 'sales.index', 'active' => 'sales.*', 'icon' => 'fa-shopping-cart', 'title' => 'Sales', ],
                        [ 'route' => 'reports.index', 'active' => 'reports.*', 'icon' => 'fa-file-alt', 'title' => 'Reports', ],
                    ];

                } elseif (auth()->user()->hasRole('farmer')) {

                    $menu = [
                        [ 'route' => 'dashboard.index', 'active' => 'dashboard.*', 'icon' => 'fa-chart-line', 'title' => 'Dashboard', ],
                        [ 'route' => 'booking.calendar', 'active' => 'booking.*', 'icon' => 'fa-calendar-alt', 'title' => 'Calendar Schedule', ],
                        [ 'route' => 'farmers.index', 'active' => 'farmers.*', 'icon' => 'fa-tractor', 'title' => 'Book Machinery', ],
                        [ 'route' => 'farmers.myBookings', 'active' => 'farmers.myBookings', 'icon' => 'fa-calendar-check', 'title' => 'Booking History', ],
                        [ 'route' => 'farmers.products', 'active' => 'farmers.products', 'icon' => 'fa-box', 'title' => 'Products', ],
                    ];
                }
            @endphp


            @foreach ($menu as $item)

                @php
                    $active = request()->routeIs($item['active'] ?? $item['route']);
                @endphp

                <a href="{{ route($item['route']) }}" title="{{ $item['title'] }}" class="flex items-center space-x-3 px-3 py-2.5 rounded-xl
                    transition-all duration-200 text-sm font-medium group {{ $active ? 'bg-[#3d8b68] text-white shadow-md' : 'text-slate-600 hover:bg-emerald-100/60 hover:text-emerald-900' }}" >
                    <i class="fa-solid {{ $item['icon'] }} w-5 text-center shrink-0 {{ $active ? 'text-white' : 'text-emerald-600 group-hover:text-emerald-700' }}"></i>
                    <span x-show="sidebarOpen" x-transition class="truncate" >
                        {{ $item['title'] }}
                    </span>
                </a>
            @endforeach
        </nav>
    </div>


    <div class="space-y-3 pt-4 border-t border-slate-200/60" >
        <div class="flex items-center space-x-3 p-1.5" >
            <div class="w-8 h-8 rounded-full bg-[#276447] text-white font-bold flex items-center justify-center text-xs shadow-sm shrink-0" >
                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
            </div>
            <div x-show="sidebarOpen" x-transition class="flex-1 min-w-0" >
                <p class="text-xs font-semibold text-slate-800 truncate" >
                    {{ auth()->user()->name }}
                </p>

                <p class="text-[10px] text-slate-500 truncate" >
                    {{ auth()->user()->email }}
                </p>
            </div>
        </div>

        <form method="POST" action="{{ route('logout') }}" >
            @csrf
            <button type="submit" class="w-full bg-[#fce8e6] hover:bg-[#f8d0cb] text-[#d9381e] font-medium py-2 px-3 rounded-xl flex items-center justify-center space-x-2 text-xs transition-all duration-200 active:scale-[0.98]" title="Logout"  >
                <i class="fa-solid fa-right-from-bracket rotate-180 shrink-0" ></i>
                <span x-show="sidebarOpen" x-transition >
                    Logout
                </span>
            </button>
        </form>
    </div>
</aside>

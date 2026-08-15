<div
    x-show="mobileOpen"
    x-cloak
    x-transition:enter="transition-opacity ease-linear duration-200"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition-opacity ease-linear duration-200"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    @click="mobileOpen = false"
    class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm z-40 lg:hidden">
</div>

<aside
    :class="{
        'w-64': sidebarOpen,
        'w-20': !sidebarOpen,
        '-translate-x-full lg:translate-x-0': !mobileOpen,
        'translate-x-0': mobileOpen
    }"
    class="fixed lg:static inset-y-0 left-0 z-50 bg-[#f2f8f4] flex flex-col justify-between border-r border-emerald-100/80 p-4 shrink-0 transition-all duration-300 ease-in-out relative">

    <div>
        <div class="relative mb-6 h-10">
            <button
                @click="sidebarOpen = !sidebarOpen; mobileOpen = !mobileOpen"
                class="absolute right-[-18px] top-1/2 -translate-y-1/2 w-10 h-10 bg-[#3d8b68] hover:bg-[#327356] text-white rounded-xl flex items-center justify-center transition-all duration-300 shadow-lg ring-2 ring-white/80 focus:outline-none"
                title="Toggle Navigation">
                <i class="fa-solid text-sm transition-transform duration-300"
                   :class="sidebarOpen ? 'fa-angle-left' : 'fa-angle-right'"></i>
            </button>
        </div>

        <div class="flex flex-col items-center text-center mb-8">
            <div class="w-12 h-12 lg:w-14 lg:h-14 bg-white rounded-full flex items-center justify-center shadow-sm border border-emerald-100 mb-2 shrink-0 transition-all">
                <div class="w-full h-full rounded-full flex items-center justify-center text-amber-400 font-bold">
                    <img src="{{ asset('assets/images/PSARECO_logo.png') }}" alt="PSARECO Logo">
                </div>
            </div>

            <div x-show="sidebarOpen" class="space-y-0.5 transition-all duration-200">
                <h1 class="font-bold text-emerald-950 text-sm tracking-tight">PSARECO</h1>
                <p class="text-[11px] text-slate-500 font-medium">Farm Resource System</p>
            </div>
        </div>

        <hr class="border-emerald-200/60 my-3">

        <nav class="space-y-1.5">

            @if (auth()->user()->hasRole('admin'))
                <a href="{{ route('dashboard') }}"
                   class="flex items-center space-x-3 px-3 py-2.5 rounded-xl transition-all duration-200 text-sm font-medium group {{ request()->routeIs('dashboard') ? 'bg-[#3d8b68] text-white shadow-md' : 'text-slate-600 hover:bg-emerald-100/60 hover:text-emerald-900' }}"
                   title="Dashboard">
                    <i class="fa-solid fa-chart-line w-5 text-center shrink-0 {{ request()->routeIs('dashboard') ? 'text-white' : 'text-emerald-600 group-hover:text-emerald-700' }}"></i>
                    <span x-show="sidebarOpen" class="truncate">Dashboard</span>
                </a>

                <a href="{{ route('scheduling') }}"
                   class="flex items-center space-x-3 px-3 py-2.5 rounded-xl transition-all duration-200 text-sm font-medium group {{ request()->routeIs('scheduling') ? 'bg-[#3d8b68] text-white shadow-md' : 'text-slate-600 hover:bg-emerald-100/60 hover:text-emerald-900' }}"
                   title="Scheduling">
                    <i class="fa-solid fa-calendar-alt w-5 text-center shrink-0 {{ request()->routeIs('scheduling') ? 'text-white' : 'text-emerald-600 group-hover:text-emerald-700' }}"></i>
                    <span x-show="sidebarOpen" class="truncate">Scheduling</span>
                </a>

                <a href="{{ route('inventory') }}"
                   class="flex items-center space-x-3 px-3 py-2.5 rounded-xl transition-all duration-200 text-sm font-medium group {{ request()->routeIs('inventory') ? 'bg-[#3d8b68] text-white shadow-md' : 'text-slate-600 hover:bg-emerald-100/60 hover:text-emerald-900' }}"
                   title="Inventory">
                    <i class="fa-solid fa-boxes-stacked w-5 text-center shrink-0 {{ request()->routeIs('inventory') ? 'text-white' : 'text-emerald-600 group-hover:text-emerald-700' }}"></i>
                    <span x-show="sidebarOpen" class="truncate">Inventory</span>
                </a>

                <a href="{{ route('sales') }}"
                   class="flex items-center space-x-3 px-3 py-2.5 rounded-xl transition-all duration-200 text-sm font-medium group {{ request()->routeIs('sales') ? 'bg-[#3d8b68] text-white shadow-md' : 'text-slate-600 hover:bg-emerald-100/60 hover:text-emerald-900' }}"
                   title="Sales">
                    <i class="fa-solid fa-shopping-cart w-5 text-center shrink-0 {{ request()->routeIs('sales') ? 'text-white' : 'text-emerald-600 group-hover:text-emerald-700' }}"></i>
                    <span x-show="sidebarOpen" class="truncate">Sales</span>
                </a>

                <a href="{{ route('reports') }}"
                   class="flex items-center space-x-3 px-3 py-2.5 rounded-xl transition-all duration-200 text-sm font-medium group {{ request()->routeIs('reports') ? 'bg-[#3d8b68] text-white shadow-md' : 'text-slate-600 hover:bg-emerald-100/60 hover:text-emerald-900' }}"
                   title="Reports">
                    <i class="fa-solid fa-file-alt w-5 text-center shrink-0 {{ request()->routeIs('reports') ? 'text-white' : 'text-emerald-600 group-hover:text-emerald-700' }}"></i>
                    <span x-show="sidebarOpen" class="truncate">Reports</span>
                </a>

                <a href="{{ route('users') }}"
                   class="flex items-center space-x-3 px-3 py-2.5 rounded-xl transition-all duration-200 text-sm font-medium group {{ request()->routeIs('users') ? 'bg-[#3d8b68] text-white shadow-md' : 'text-slate-600 hover:bg-emerald-100/60 hover:text-emerald-900' }}"
                   title="Users">
                    <i class="fa-solid fa-users-cog w-5 text-center shrink-0 {{ request()->routeIs('users') ? 'text-white' : 'text-emerald-600 group-hover:text-emerald-700' }}"></i>
                    <span x-show="sidebarOpen" class="truncate">Users</span>
                </a>

            @elseif (auth()->user()->hasRole('officer'))
                <a href="{{ route('dashboard') }}"
                   class="flex items-center space-x-3 px-3 py-2.5 rounded-xl transition-all duration-200 text-sm font-medium group {{ request()->routeIs('dashboard') ? 'bg-[#3d8b68] text-white shadow-md' : 'text-slate-600 hover:bg-emerald-100/60 hover:text-emerald-900' }}"
                   title="Dashboard">
                    <i class="fa-solid fa-chart-line w-5 text-center shrink-0 {{ request()->routeIs('dashboard') ? 'text-white' : 'text-emerald-600 group-hover:text-emerald-700' }}"></i>
                    <span x-show="sidebarOpen" class="truncate">Dashboard</span>
                </a>

                <a href="{{ route('scheduling') }}"
                   class="flex items-center space-x-3 px-3 py-2.5 rounded-xl transition-all duration-200 text-sm font-medium group {{ request()->routeIs('scheduling') ? 'bg-[#3d8b68] text-white shadow-md' : 'text-slate-600 hover:bg-emerald-100/60 hover:text-emerald-900' }}"
                   title="Scheduling">
                    <i class="fa-solid fa-calendar-alt w-5 text-center shrink-0 {{ request()->routeIs('scheduling') ? 'text-white' : 'text-emerald-600 group-hover:text-emerald-700' }}"></i>
                    <span x-show="sidebarOpen" class="truncate">Scheduling</span>
                </a>

                <a href="{{ route('inventory') }}"
                   class="flex items-center space-x-3 px-3 py-2.5 rounded-xl transition-all duration-200 text-sm font-medium group {{ request()->routeIs('inventory') ? 'bg-[#3d8b68] text-white shadow-md' : 'text-slate-600 hover:bg-emerald-100/60 hover:text-emerald-900' }}"
                   title="Inventory">
                    <i class="fa-solid fa-boxes-stacked w-5 text-center shrink-0 {{ request()->routeIs('inventory') ? 'text-white' : 'text-emerald-600 group-hover:text-emerald-700' }}"></i>
                    <span x-show="sidebarOpen" class="truncate">Inventory</span>
                </a>

                <a href="{{ route('sales') }}"
                   class="flex items-center space-x-3 px-3 py-2.5 rounded-xl transition-all duration-200 text-sm font-medium group {{ request()->routeIs('sales') ? 'bg-[#3d8b68] text-white shadow-md' : 'text-slate-600 hover:bg-emerald-100/60 hover:text-emerald-900' }}"
                   title="Sales">
                    <i class="fa-solid fa-shopping-cart w-5 text-center shrink-0 {{ request()->routeIs('sales') ? 'text-white' : 'text-emerald-600 group-hover:text-emerald-700' }}"></i>
                    <span x-show="sidebarOpen" class="truncate">Sales</span>
                </a>

                <a href="{{ route('reports') }}"
                   class="flex items-center space-x-3 px-3 py-2.5 rounded-xl transition-all duration-200 text-sm font-medium group {{ request()->routeIs('reports') ? 'bg-[#3d8b68] text-white shadow-md' : 'text-slate-600 hover:bg-emerald-100/60 hover:text-emerald-900' }}"
                   title="Reports">
                    <i class="fa-solid fa-file-alt w-5 text-center shrink-0 {{ request()->routeIs('reports') ? 'text-white' : 'text-emerald-600 group-hover:text-emerald-700' }}"></i>
                    <span x-show="sidebarOpen" class="truncate">Reports</span>
                </a>

            @elseif (auth()->user()->hasRole('farmer'))
                <a href="{{ route('scheduling') }}"
                   class="flex items-center space-x-3 px-3 py-2.5 rounded-xl transition-all duration-200 text-sm font-medium group {{ request()->routeIs('scheduling') ? 'bg-[#3d8b68] text-white shadow-md' : 'text-slate-600 hover:bg-emerald-100/60 hover:text-emerald-900' }}"
                   title="Book Machinery">
                    <i class="fa-solid fa-tractor w-5 text-center shrink-0 {{ request()->routeIs('scheduling') ? 'text-white' : 'text-emerald-600 group-hover:text-emerald-700' }}"></i>
                    <span x-show="sidebarOpen" class="truncate">Book Machinery</span>
                </a>

                <a href="{{ route('my-bookings') }}"
                   class="flex items-center space-x-3 px-3 py-2.5 rounded-xl transition-all duration-200 text-sm font-medium group {{ request()->routeIs('my-bookings') ? 'bg-[#3d8b68] text-white shadow-md' : 'text-slate-600 hover:bg-emerald-100/60 hover:text-emerald-900' }}"
                   title="My Bookings">
                    <i class="fa-solid fa-calendar-check w-5 text-center shrink-0 {{ request()->routeIs('my-bookings') ? 'text-white' : 'text-emerald-600 group-hover:text-emerald-700' }}"></i>
                    <span x-show="sidebarOpen" class="truncate">My Bookings</span>
                </a>

                <a href="{{ route('inventory') }}"
                   class="flex items-center space-x-3 px-3 py-2.5 rounded-xl transition-all duration-200 text-sm font-medium group {{ request()->routeIs('inventory') ? 'bg-[#3d8b68] text-white shadow-md' : 'text-slate-600 hover:bg-emerald-100/60 hover:text-emerald-900' }}"
                   title="Inventory">
                    <i class="fa-solid fa-box w-5 text-center shrink-0 {{ request()->routeIs('inventory') ? 'text-white' : 'text-emerald-600 group-hover:text-emerald-700' }}"></i>
                    <span x-show="sidebarOpen" class="truncate">Inventory</span>
                </a>
            @endif

        </nav>
    </div>

    <div class="space-y-3 pt-4 border-t border-slate-200/60">
        <div class="flex items-center space-x-3 p-1.5">
            <div class="w-8 h-8 rounded-full bg-[#276447] text-white font-bold flex items-center justify-center text-xs shadow-sm shrink-0">
                {{ auth()->user()->name[0] }}
            </div>
            <div x-show="sidebarOpen" class="flex-1 min-w-0 transition-all">
                <p class="text-xs font-semibold text-slate-800 truncate">{{ auth()->user()->name }}</p>
                <p class="text-[10px] text-slate-500 truncate">{{ auth()->user()->email }}</p>
            </div>
        </div>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="w-full bg-[#fce8e6] hover:bg-[#f8d0cb] text-[#d9381e] font-medium py-2 px-3 rounded-xl flex items-center justify-center space-x-2 text-xs transition-all duration-200 active:scale-[0.98]" title="Logout">
                <i class="fa-solid fa-right-from-bracket rotate-180 shrink-0"></i>
                <span x-show="sidebarOpen">Logout</span>
            </button>
        </form>
    </div>
</aside>

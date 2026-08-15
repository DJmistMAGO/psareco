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
                <!-- Top Toggle Bar -->
                <div class="relative mb-6 h-10">
                    <!-- Toggle Button floating outside the side edge -->
                    <button
                        @click="sidebarOpen = !sidebarOpen; mobileOpen = !mobileOpen"
                        class="absolute right-[-18px] top-1/2 -translate-y-1/2 w-10 h-10 bg-[#3d8b68] hover:bg-[#327356] text-white rounded-xl flex items-center justify-center transition-all duration-300 shadow-lg ring-2 ring-white/80 focus:outline-none"
                        title="Toggle Navigation">
                        <i class="fa-solid text-sm transition-transform duration-300"
                           :class="sidebarOpen ? 'fa-angle-left' : 'fa-angle-right'"></i>
                    </button>
                </div>

                <!-- Brand Logo & User Role -->
                <div class="flex flex-col items-center text-center mb-8">
                    <div class="w-12 h-12 lg:w-14 lg:h-14 bg-white rounded-full flex items-center justify-center shadow-sm border border-emerald-100 mb-2 shrink-0 transition-all">
                        <div class="w-full h-full  rounded-full flex items-center justify-center text-amber-400 font-bold">
                            <img src="{{ asset('assets/images/PSARECO_logo.png') }}" alt="PSARECO Logo">
                        </div>
                    </div>

                    <!-- Collapsible Details -->
                    <div x-show="sidebarOpen" class="space-y-0.5 transition-all duration-200">
                        <h1 class="font-bold text-emerald-950 text-sm tracking-tight">PSARECO</h1>
                        <p class="text-[11px] text-slate-500 font-medium">Farm Resource System</p>
                    </div>
                </div>

                <hr class="text-emerald-900 px-2 my-2">

                <!-- Navigation Links -->
                <nav class="space-y-1.5">
                    <a href="#" class="flex items-center space-x-3 px-3 py-2.5 text-slate-600 hover:text-emerald-700 hover:bg-emerald-50/80 rounded-xl transition-all font-medium text-sm group" title="Book Machinery">
                        <i class="fa-solid fa-tractor w-5 text-emerald-600 text-center shrink-0"></i>
                        <span x-show="sidebarOpen" class="truncate">Book Machinery</span>
                    </a>
                    <a href="#" class="flex items-center space-x-3 px-3 py-2.5 text-slate-600 hover:text-emerald-700 hover:bg-emerald-50/80 rounded-xl transition-all font-medium text-sm group" title="My Bookings">
                        <i class="fa-solid fa-calendar-check w-5 text-emerald-600 text-center shrink-0"></i>
                        <span x-show="sidebarOpen" class="truncate">My Bookings</span>
                    </a>
                    <a href="#" class="flex items-center space-x-3 px-3 py-2.5 text-slate-600 hover:text-emerald-700 hover:bg-emerald-50/80 rounded-xl transition-all font-medium text-sm group" title="Inventory">
                        <i class="fa-solid fa-box-archive w-5 text-emerald-600 text-center shrink-0"></i>
                        <span x-show="sidebarOpen" class="truncate">Inventory</span>
                    </a>
                </nav>
            </div>

            <div class="space-y-3 pt-4 border-t border-slate-200/60">
                <div class="flex items-center space-x-3 p-1.5">
                    <div class="w-8 h-8 rounded-full bg-[#276447] text-white font-bold flex items-center justify-center text-xs shadow-sm shrink-0">
                        {{ auth()->user()->name[0] }}
                    </div>
                    <div x-show="sidebarOpen" class="flex-1 min-w-0 transition-all">
                        <p class="text-xs font-semibold text-slate-800 truncate">{{ auth()->user()->name}}</p>
                        <p class="text-[10px] text-slate-500 truncate">{{ auth()->user()->email }}</p>
                    </div>
                </div>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full bg-[#fce8e6] hover:bg-[#fbdcd8] text-[#d9381e] font-medium py-2 px-3 rounded-xl flex items-center justify-center space-x-2 text-xs transition-colors" title="Logout">
                        <i class="fa-solid fa-right-from-bracket rotate-180 shrink-0"></i>
                        <span x-show="sidebarOpen">Logout</span>
                    </button>
                </form>
            </div>
        </aside>





{{-- <div class="sidebar" id="sidebar">

    <button id="sidebarToggle" type="button" class="btn btn-sm btn-success sidebar-toggle mb-3" title="Collapse sidebar" >
        <i class="fas fa-bars"></i>
        <span class="toggle-text">Menu</span>
    </button>


    <!-- Logo Section -->
    <div class="logo-section" style="text-align: center;">

        <img src="{{ asset('assets/images/PSARECO_logo.png') }}" alt="PSARECO Logo" class="logo-image"  style="display: inline-block; padding: 0; margin: auto;" >

        <div class="logo-content">

            <h5 style="  color: var(--primary);  font-weight: 700; margin: 0; font-size: 1rem; "> PSARECO </h5>
            <small class="text-muted d-block mb-2" style="font-size: 0.75rem;" >
                Farm Resource System
            </small>

            <span class="badge bg-primary uppercase mb-3" style="font-size: 0.75rem;">
                {{ auth()->user()->getRoleNames()->first() ?? 'User' }}
            </span>

        </div>

    </div>


    <!-- Navigation -->
    <nav class="sidebar-nav">


        @if (auth()->user()->hasRole('admin'))

            <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="fas fa-chart-line nav-icon"></i>
                <span class="nav-text">Dashboard</span>
            </a>

            <a href="{{ route('scheduling') }}" class="nav-link {{ request()->routeIs('scheduling') ? 'active' : '' }}">
                <i class="fas fa-calendar-alt nav-icon"></i>
                <span class="nav-text">Scheduling</span>
            </a>

            <a href="{{ route('inventory') }}" class="nav-link {{ request()->routeIs('inventory') ? 'active' : '' }}">
                <i class="fas fa-boxes nav-icon"></i>
                <span class="nav-text">Inventory</span>
            </a>

            <a href="{{ route('sales') }}" class="nav-link {{ request()->routeIs('sales') ? 'active' : '' }}">
                <i class="fas fa-shopping-cart nav-icon"></i>
                <span class="nav-text">Sales</span>
            </a>

            <a href="{{ route('reports') }}" class="nav-link {{ request()->routeIs('reports') ? 'active' : '' }}">
                <i class="fas fa-file-alt nav-icon"></i>
                <span class="nav-text">Reports</span>
            </a>

            <a href="{{ route('users') }}" class="nav-link {{ request()->routeIs('users') ? 'active' : '' }}">
                <i class="fas fa-users-cog nav-icon"></i>
                <span class="nav-text">Users</span>
            </a>

        @elseif (auth()->user()->hasRole('officer'))

            <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="fas fa-chart-line nav-icon"></i>
                <span class="nav-text">Dashboard</span>
            </a>

            <a href="{{ route('scheduling') }}" class="nav-link {{ request()->routeIs('scheduling') ? 'active' : '' }}">
                <i class="fas fa-calendar-alt nav-icon"></i>
                <span class="nav-text">Scheduling</span>
            </a>

            <a href="{{ route('inventory') }}" class="nav-link {{ request()->routeIs('inventory') ? 'active' : '' }}">
                <i class="fas fa-boxes nav-icon"></i>
                <span class="nav-text">Inventory</span>
            </a>

            <a href="{{ route('sales') }}" class="nav-link {{ request()->routeIs('sales') ? 'active' : '' }}">
                <i class="fas fa-shopping-cart nav-icon"></i>
                <span class="nav-text">Sales</span>
            </a>

            <a href="{{ route('reports') }}" class="nav-link {{ request()->routeIs('reports') ? 'active' : '' }}">
                <i class="fas fa-file-alt nav-icon"></i>
                <span class="nav-text">Reports</span>
            </a>


            @elseif (auth()->user()->hasRole('farmer'))

            <a href="{{ route('scheduling') }}" class="nav-link {{ request()->routeIs('scheduling') ? 'active' : '' }}">
                <i class="fas fa-tractor nav-icon"></i>
                <span class="nav-text">Book Machinery</span>
            </a>

            <a href="{{ route('my-bookings') }}" class="nav-link {{ request()->routeIs('my-bookings') ? 'active' : '' }}">
                <i class="fas fa-calendar-check nav-icon"></i>
                <span class="nav-text">My Bookings</span>
            </a>

            <a href="{{ route('inventory') }}" class="nav-link {{ request()->routeIs('inventory') ? 'active' : '' }}">
                <i class="fas fa-box nav-icon"></i>
                <span class="nav-text">Inventory</span>
            </a>

        @endif

    </nav>


    <hr class="sidebar-divider">


    <div class="user-section">

        <div class="d-flex align-items-center gap-2 mb-2">

            <div style=" width: 35px; height: 35px; border-radius: 50%; background: var(--primary); color: white; display: flex; align-items: center; justify-content: center; font-weight: bold; font-size: 1rem; flex-shrink: 0; " >
                {{ auth()->user()->name[0] }}
            </div>

            <div class="user-details">
                <div style=" font-weight: 600;  font-size: 0.85rem; color: #173b1a; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; ">
                    {{ auth()->user()->name }}
                </div>

                <small class="text-muted" style=" display: block; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; font-size: 0.75rem;" >
                    {{ auth()->user()->email }}
                </small>
            </div>

        </div>


        <form method="POST"  action="{{ route('logout') }}" style="margin: 0;">
            @csrf
            <button type="submit" class="btn btn-outline-danger btn-sm w-100 logout-btn" style="padding: 6px; font-size: 0.85rem;" >
                <i class="fas fa-sign-out-alt"></i>
                <span class="logout-text">Logout</span>
            </button>
        </form>

    </div>

</div>


<script>
document.addEventListener('DOMContentLoaded', function () {

    const sidebar = document.getElementById('sidebar');
    const toggleBtn = document.getElementById('sidebarToggle');
    const mainContent = document.querySelector('.main-content');

    const isCollapsed =
        localStorage.getItem('sidebarCollapsed') === 'true';


    function updateSidebar(collapsed) {

        if (collapsed) {

            sidebar.classList.add('collapsed');

            if (mainContent) {
                mainContent.classList.add('sidebar-collapsed');
            }

            toggleBtn.innerHTML =
                '<i class="fas fa-chevron-right"></i>';

            toggleBtn.title = 'Expand sidebar';

        } else {

            sidebar.classList.remove('collapsed');

            if (mainContent) {
                mainContent.classList.remove('sidebar-collapsed');
            }

            toggleBtn.innerHTML =
                '<i class="fas fa-bars"></i>' +
                '<span class="toggle-text">Menu</span>';

            toggleBtn.title = 'Collapse sidebar';
        }

    }


    // Restore saved state
    // updateSidebar(isCollapsed);


    // Toggle
    toggleBtn.addEventListener('click', function () {

        const collapsed =
            !sidebar.classList.contains('collapsed');

        updateSidebar(collapsed);

        localStorage.setItem(
            'sidebarCollapsed',
            collapsed
        );

    });

});
</script> --}}

<div class="sidebar" id="sidebar">

    <button id="sidebarToggle" type="button" class="btn btn-sm btn-success sidebar-toggle mb-3" title="Collapse sidebar" >
        <i class="fas fa-bars"></i>
        <span class="toggle-text">Menu</span>
    </button>


    <!-- Logo Section -->
    <div class="logo-section" style="text-align: center;">

        <img src="{{ asset('assets/images/PSARECO_logo.png') }}" alt="PSARECO Logo" class="logo-image"  style="display: inline-block; padding: 0;;" >

        <div class="logo-content">

            <h5 style="  color: var(--primary);  font-weight: 700; margin: 0; font-size: 1rem; "> PSARECO </h5>
            <small class="text-muted d-block mb-2" style="font-size: 0.75rem;" >
                Farm System
            </small>

            <span class="badge bg-primary"  style="font-size: 0.75rem;" >
                {{ auth()->user()->getRoleNames()->first() ?? 'User' }}
            </span>

        </div>

    </div>


    <!-- Navigation -->
    <nav class="sidebar-nav">

        {{-- Admin Navigation --}}
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

        {{-- Officer Navigation --}}
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

            {{-- Farmer Navigation --}}
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


    {{-- User Info & Logout --}}
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
</script>

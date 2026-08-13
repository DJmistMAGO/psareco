// ========================================
// PSARECO Enterprise Sidebar Navigation
// LIGHT THEME - Professional Icons
// Logout with Sign Out Icon
// ========================================

function loadSidebar() {
    const user = JSON.parse(localStorage.getItem("currentUser"));

    if (!user) {
        window.location.href = "/login";
        return;
    }

    const role = user.role;
    const currentPath = window.location.pathname;

    let menuItems = [];

    const dashboardItem = { href: "/dashboard", icon: "fas fa-chart-line", label: "Dashboard" };

    const adminItems = [
        { href: "/scheduling", icon: "fas fa-calendar-alt", label: "Machinery Scheduling" },
        { href: "/inventory", icon: "fas fa-boxes", label: "Inventory Management" },
        { href: "/sales", icon: "fas fa-shopping-cart", label: "Sales Transactions" },
        { href: "/reports", icon: "fas fa-file-alt", label: "Reports" },
        { href: "/users", icon: "fas fa-users-cog", label: "User Management" }
    ];

    const officerItems = [
        { href: "/scheduling", icon: "fas fa-calendar-alt", label: "Machinery Scheduling" },
        { href: "/inventory", icon: "fas fa-boxes", label: "Inventory Management" },
        { href: "/sales", icon: "fas fa-shopping-cart", label: "Sales Transactions" },
        { href: "/reports", icon: "fas fa-file-alt", label: "Reports" }
    ];

    const farmerItems = [
        { href: "/scheduling", icon: "fas fa-tractor", label: "Book Machinery" },
        { href: "/my-bookings", icon: "fas fa-calendar-check", label: "My Bookings" },
        { href: "/inventory", icon: "fas fa-box", label: "View Inventory" }
    ];

    // Build menu based on role
    if (role === "admin") {
        menuItems = [dashboardItem, ...adminItems];
    } else if (role === "officer") {
        menuItems = [dashboardItem, ...officerItems];
    } else if (role === "farmer") {
        menuItems = [...farmerItems];
    }

    // Get user display name
    const displayName = user.name || user.fullName || user.email.split('@')[0];
    const userInitial = displayName ? displayName.charAt(0).toUpperCase() : 'U';

    // Build sidebar HTML - LIGHT THEME
    let sidebarHtml = `
        <div class="logo-section">
            <img src="assets/PSARECO_logo.png" alt="PSARECO Logo" onerror="this.src='https://via.placeholder.com/70?text=PSARECO'">
            <h4>PSARECO</h4>
            <small>Farm Resource System</small>
            <div class="mt-2">
                <span class="badge" style="background: var(--primary); color: white;">${role.toUpperCase()}</span>
            </div>
        </div>
    `;

    // Add menu items with Font Awesome icons
    for (let item of menuItems) {
        const isActive = currentPath === item.href;
        const activeClass = isActive ? 'active' : '';
        sidebarHtml += `
            <a href="${item.href}" class="${activeClass}">
                <i class="${item.icon}"></i>
                <span>${item.label}</span>
            </a>
        `;
    }

    // Add spacer to push logout to bottom
    sidebarHtml += `<div style="flex: 1;"></div>`;

    // Add user info and logout at the bottom
    sidebarHtml += `
        <div class="user-section">
            <div class="user-info">
                <div class="user-avatar">
                    <span>${userInitial}</span>
                </div>
                <div class="user-details">
                    <div class="user-name">${displayName}</div>
                    <div class="user-email">${user.email}</div>
                </div>
            </div>
            <a href="#" onclick="logout(); return false;" class="logout-btn">
                <i class="fas fa-sign-out-alt"></i>
                <span>Logout</span>
            </a>
        </div>
    `;

    const sidebarElement = document.getElementById("sidebar");
    if (sidebarElement) {
        sidebarElement.innerHTML = sidebarHtml;
    }
}

// Make sure logout is available globally
window.logout = logout;
window.loadSidebar = loadSidebar;

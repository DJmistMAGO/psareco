// ========================================
// PSARECO Authentication Module
// Role-Based Access Control (RBAC)
// ========================================

// Initialize localStorage with empty data
function initializeSystemData() {
    // Users
    if (!localStorage.getItem("users")) {
        localStorage.setItem("users", JSON.stringify([]));
        console.log("System initialized - No users yet.");
    }

    // Inventory
    if (!localStorage.getItem("inventory")) {
        const defaultInventory = [
            { id: 1, name: "Urea Fertilizer", type: "Fertilizer", qty: 150, unit: "bags", price: 1250, reorderLevel: 30 },
            { id: 2, name: "Complete Fertilizer", type: "Fertilizer", qty: 80, unit: "bags", price: 1350, reorderLevel: 25 },
            { id: 3, name: "Pesticide A", type: "Pesticide", qty: 45, unit: "liters", price: 450, reorderLevel: 15 },
            { id: 4, name: "Herbicide B", type: "Pesticide", qty: 12, unit: "liters", price: 380, reorderLevel: 10 }
        ];
        localStorage.setItem("inventory", JSON.stringify(defaultInventory));
    }

    // Machinery
    if (!localStorage.getItem("machines")) {
        const defaultMachines = [
            { id: 1, name: "Hand Tractor", model: "HT-2000", status: "Available", dailyRate: 1500 },
            { id: 2, name: "Rice Harvester", model: "RH-3000", status: "Available", dailyRate: 3500 },
            { id: 3, name: "Thresher", model: "TH-150", status: "Available", dailyRate: 1200 },
            { id: 4, name: "Water Pump", model: "WP-5HP", status: "Available", dailyRate: 800 }
        ];
        localStorage.setItem("machines", JSON.stringify(defaultMachines));
    }

    // Bookings
    if (!localStorage.getItem("bookings")) {
        localStorage.setItem("bookings", JSON.stringify([]));
    }

    // Sales
    if (!localStorage.getItem("sales")) {
        localStorage.setItem("sales", JSON.stringify([]));
    }
}

// Login function - checks status
function login(email, password) {
    initializeSystemData();

    const users = JSON.parse(localStorage.getItem("users")) || [];
    console.log("Users found:", users.length);

    const user = users.find(u => u.email === email && u.password === password);

    if (!user) {
        return { success: false, message: "Invalid email or password" };
    }

    // Check if account is pending approval
    if (user.status === 'pending') {
        return { success: false, message: "⏳ Your account is pending approval.\n\nPlease wait for the administrator to activate your account." };
    }

    // Check if account is active
    if (user.status !== 'active') {
        return { success: false, message: "Your account is not active. Please contact administrator." };
    }

    // Store session
    const sessionUser = {
        id: user.id,
        name: user.name,
        email: user.email,
        role: user.role
    };
    localStorage.setItem("currentUser", JSON.stringify(sessionUser));

    return { success: true, user: sessionUser };
}

// Logout function
function logout() {
    localStorage.removeItem("currentUser");
    window.location.href = "/login";
}

// Check if authenticated
function isAuthenticated() {
    const user = JSON.parse(localStorage.getItem("currentUser"));
    return user !== null;
}

// Get current user
function getCurrentUser() {
    return JSON.parse(localStorage.getItem("currentUser"));
}

// Check role
function hasRole(allowedRoles) {
    const user = getCurrentUser();
    if (!user) return false;
    return allowedRoles.includes(user.role);
}

// Require authentication
function requireAuth() {
    if (!isAuthenticated()) {
        window.location.href = "/login";
        return false;
    }
    return true;
}

// Check if admin
function isAdmin() {
    const user = getCurrentUser();
    return user && user.role === 'admin';
}

// Check if staff (admin or officer)
function isStaff() {
    const user = getCurrentUser();
    return user && (user.role === 'admin' || user.role === 'officer');
}

// Get all users
function getAllUsers() {
    return JSON.parse(localStorage.getItem("users")) || [];
}

// Get pending users (waiting for approval)
function getPendingUsers() {
    const users = getAllUsers();
    return users.filter(u => u.status === 'pending');
}

// Get active users
function getActiveUsers() {
    const users = getAllUsers();
    return users.filter(u => u.status === 'active');
}

// Approve user (admin only)
function approveUser(userId) {
    const users = getAllUsers();
    const index = users.findIndex(u => u.id === userId);
    if (index !== -1 && users[index].status === 'pending') {
        users[index].status = 'active';
        localStorage.setItem("users", JSON.stringify(users));
        return { success: true, message: "User approved successfully!" };
    }
    return { success: false, message: "User not found or already approved" };
}

// Reject/Delete pending user
function rejectUser(userId) {
    let users = getAllUsers();
    const user = users.find(u => u.id === userId);
    if (user && user.status === 'pending') {
        users = users.filter(u => u.id !== userId);
        localStorage.setItem("users", JSON.stringify(users));
        return { success: true, message: "User rejected and removed." };
    }
    return { success: false, message: "User not found" };
}

// Update user role
function updateUserRole(userId, newRole) {
    const users = getAllUsers();
    const index = users.findIndex(u => u.id === userId);
    if (index !== -1) {
        users[index].role = newRole;
        localStorage.setItem("users", JSON.stringify(users));
        return true;
    }
    return false;
}

// Add new user (admin only) - automatically active
// *** UPDATED: Admin cannot create farmer accounts ***
function addUserByAdmin(name, email, password, role) {
    // Prevent admin from creating farmer accounts
    if (role === 'farmer') {
        return { success: false, message: "Farmers must register through the public registration page." };
    }

    const users = getAllUsers();

    // Check if email exists
    if (users.find(u => u.email === email)) {
        return { success: false, message: "Email already exists" };
    }

    const newId = users.length > 0 ? Math.max(...users.map(u => u.id)) + 1 : 1;

    const newUser = {
        id: newId,
        name: name,
        email: email,
        password: password,
        role: role,
        status: 'active',  // Admin-created users are active immediately
        registeredAt: new Date().toISOString()
    };

    users.push(newUser);
    localStorage.setItem("users", JSON.stringify(users));
    return { success: true, user: newUser };
}

// Delete user
function deleteUser(userId) {
    let users = getAllUsers();
    const currentUser = getCurrentUser();

    if (currentUser.id === userId) {
        return { success: false, message: "Cannot delete your own account" };
    }

    users = users.filter(u => u.id !== userId);
    localStorage.setItem("users", JSON.stringify(users));
    return { success: true };
}

// Initialize
initializeSystemData();

// Export for browser
window.login = login;
window.logout = logout;
window.isAuthenticated = isAuthenticated;
window.getCurrentUser = getCurrentUser;
window.hasRole = hasRole;
window.requireAuth = requireAuth;
window.isAdmin = isAdmin;
window.isStaff = isStaff;
window.getAllUsers = getAllUsers;
window.getPendingUsers = getPendingUsers;
window.getActiveUsers = getActiveUsers;
window.approveUser = approveUser;
window.rejectUser = rejectUser;
window.updateUserRole = updateUserRole;
window.addUserByAdmin = addUserByAdmin;
window.deleteUser = deleteUser;

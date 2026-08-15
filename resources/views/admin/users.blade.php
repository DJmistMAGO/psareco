@extends('layouts.app')

@section('title', 'User Management - PSARECO')

@section('content')

    <!-- ================= MAIN CONTENT AREA ================= -->
    <main class="flex-1 min-w-0 p-4 sm:p-6 lg:p-8 overflow-y-auto transition-all duration-300">

        <!-- Mobile Navigation Trigger Bar -->
        <div class="flex items-center justify-between mb-4 lg:hidden bg-white/60 backdrop-blur p-3 rounded-xl shadow-sm border border-emerald-100 print:hidden">
            <span class="font-bold text-emerald-950 text-sm">PSARECO System</span>
            <button @click="mobileOpen = !mobileOpen" class="p-2 text-emerald-800 hover:bg-emerald-100 rounded-lg focus:outline-none">
                <i class="fa-solid fa-bars text-lg"></i>
            </button>
        </div>

        <!-- Hero Header & Actions -->
        <section class="bg-gradient-to-r from-[#2c7a56] to-[#40a072] text-white rounded-2xl p-6 mb-6 shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h2 class="text-xl sm:text-2xl font-bold tracking-tight flex items-center gap-2">
                    <i class="fa-solid fa-users-gear"></i> User Management
                </h2>
                <p class="text-emerald-100 text-xs sm:text-sm mt-1">Manage system administrators, cooperative officers, and farmer account approvals</p>
            </div>

            <div class="flex items-center gap-2 print:hidden">
                <button onclick="exportUsers()" class="inline-flex items-center gap-2 bg-white text-emerald-950 hover:bg-emerald-50 font-semibold text-xs py-2 px-3.5 rounded-xl shadow-sm transition">
                    <i class="fa-solid fa-file-export"></i> Export CSV
                </button>
            </div>
        </section>

        <!-- Pending Farmer Approvals Alert Section -->
        <div id="pendingSection" class="hidden bg-amber-50/80 rounded-2xl shadow-sm border border-amber-200/80 overflow-hidden mb-6 print:hidden">
            <div class="bg-amber-500 text-white px-5 py-3.5 flex items-center justify-between">
                <div class="flex items-center gap-2 font-bold text-sm">
                    <i class="fa-solid fa-clock-rotate-left"></i> Pending Farmer Approvals
                </div>
                <span class="bg-amber-950/40 text-amber-100 font-extrabold text-xs px-2.5 py-0.5 rounded-full" id="pendingCount">0</span>
            </div>

            <div class="w-full overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="bg-amber-100/50 text-amber-950 uppercase text-[10px] tracking-wider font-semibold border-b border-amber-200/60">
                            <th class="py-2.5 px-4">ID</th>
                            <th class="py-2.5 px-4">Name</th>
                            <th class="py-2.5 px-4">Email</th>
                            <th class="py-2.5 px-4">Requested Role</th>
                            <th class="py-2.5 px-4">Registered Date</th>
                            <th class="py-2.5 px-4 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="pendingUsersTable" class="divide-y divide-amber-100 text-slate-700">
                        <tr>
                            <td colspan="6" class="text-center text-amber-700/60 py-6">No pending farmer registrations</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Register New Staff (Admin or Officer) Card -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100/80 p-5 mb-6 print:hidden">
            <div class="flex items-center justify-between pb-3 mb-4 border-b border-slate-100">
                <h3 class="font-bold text-slate-800 text-sm flex items-center gap-2">
                    <i class="fa-solid fa-user-plus text-emerald-600"></i> Register New Staff Account
                </h3>
                <span class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider">Internal User</span>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-12 gap-3 items-end">
                <!-- Full Name -->
                <div class="sm:col-span-3">
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Full Name <span class="text-red-500">*</span></label>
                    <input type="text" id="userName" placeholder="e.g. Juan Dela Cruz"
                        class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:bg-white transition">
                </div>

                <!-- Email Address -->
                <div class="sm:col-span-3">
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Email Address <span class="text-red-500">*</span></label>
                    <input type="email" id="userEmail" placeholder="name@psareco.org"
                        class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:bg-white transition">
                </div>

                <!-- Password -->
                <div class="sm:col-span-2">
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Password <span class="text-red-500">*</span></label>
                    <input type="password" id="userPassword" placeholder="••••••••"
                        class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:bg-white transition">
                </div>

                <!-- Role Selection -->
                <div class="sm:col-span-2">
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Assigned Role</label>
                    <select id="userRole" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:bg-white transition">
                        <option value="admin">Administrator</option>
                        <option value="officer">Cooperative Officer</option>
                    </select>
                </div>

                <!-- Submit Button -->
                <div class="sm:col-span-2">
                    <button onclick="addNewUser()" class="w-full inline-flex items-center justify-center gap-1.5 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-xs py-2 px-3.5 rounded-xl shadow-sm transition">
                        <i class="fa-solid fa-plus"></i> Register Staff
                    </button>
                </div>
            </div>
        </div>

        <!-- Active System Users Table -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100/80 overflow-hidden">
            <div class="p-5 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <h3 class="font-bold text-slate-800 text-sm flex items-center gap-2">
                    <i class="fa-solid fa-users text-emerald-600"></i> Active System Users
                </h3>

                <!-- Search Input Filter -->
                <div class="relative w-full sm:w-64 print:hidden">
                    <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                    <input type="text" id="searchUsersInput" onkeyup="filterActiveUsers()" placeholder="Search users..."
                        class="w-full pl-9 pr-3 py-1.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:bg-white transition">
                </div>
            </div>

            <!-- Active Users Table -->
            <div class="w-full overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="bg-[#ebf4ef] text-emerald-900 uppercase text-[10px] tracking-wider font-semibold">
                            <th class="py-2.5 px-4">User ID</th>
                            <th class="py-2.5 px-4">Name</th>
                            <th class="py-2.5 px-4">Email</th>
                            <th class="py-2.5 px-4">Role</th>
                            <th class="py-2.5 px-4">Status</th>
                            <th class="py-2.5 px-4 text-right print:hidden">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="usersTable" class="divide-y divide-slate-100 text-slate-700">
                        <tr>
                            <td colspan="6" class="text-center text-slate-400 py-6">Loading users list...</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

    </main>

@endsection

@push('scripts')
    <script>
        function loadUsersPage() {
            if (typeof requireAuth === 'function' && !requireAuth()) return;

            if (typeof getCurrentUser === 'function') {
                const user = getCurrentUser();
                if (user && user.role !== 'admin') {
                    alert('Access Denied. Administrator privileges required.');
                    window.location.href = '{{ route('dashboard') }}';
                    return;
                }
            }

            if (typeof loadSidebar === 'function') loadSidebar();
            loadPendingUsers();
            loadActiveUsers();
        }

        function loadPendingUsers() {
            const pendingUsers = typeof getPendingUsers === 'function' ? getPendingUsers() : [];
            const table = document.getElementById('pendingUsersTable');
            const section = document.getElementById('pendingSection');
            const pendingCount = document.getElementById('pendingCount');

            if (!section || !table) return;

            if (pendingUsers.length === 0) {
                section.classList.add('hidden');
                return;
            }

            section.classList.remove('hidden');
            if (pendingCount) pendingCount.innerText = pendingUsers.length;

            table.innerHTML = pendingUsers.map(u => `
                <tr class="hover:bg-amber-100/40 transition-colors">
                    <td class="py-3 px-4 font-mono text-slate-500">#${u.id}</td>
                    <td class="py-3 px-4 font-bold text-slate-800">${u.name}</td>
                    <td class="py-3 px-4 text-slate-600">${u.email}</td>
                    <td class="py-3 px-4">
                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-sky-100 text-sky-800 border border-sky-200">
                            <i class="fa-solid fa-seedling"></i> ${u.role ? u.role.toUpperCase() : 'FARMER'}
                        </span>
                    </td>
                    <td class="py-3 px-4 text-slate-500">${u.registeredAt ? new Date(u.registeredAt).toLocaleDateString() : '—'}</td>
                    <td class="py-3 px-4 text-right print:hidden">
                        <div class="inline-flex items-center justify-end gap-1.5">
                            <button onclick="approveUserAccount(${u.id})" class="inline-flex items-center gap-1 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-[11px] py-1 px-2.5 rounded-lg transition shadow-sm">
                                <i class="fa-solid fa-check"></i> Approve
                            </button>
                            <button onclick="rejectUserAccount(${u.id})" class="inline-flex items-center gap-1 bg-red-600 hover:bg-red-700 text-white font-semibold text-[11px] py-1 px-2.5 rounded-lg transition shadow-sm">
                                <i class="fa-solid fa-xmark"></i> Reject
                            </button>
                        </div>
                    </td>
                </tr>
            `).join('');
        }

        function loadActiveUsers() {
            const users = typeof getAllUsers === 'function' ? getAllUsers() : [];
            const activeUsers = users.filter(u => u.status === 'active' || !u.status);
            const currentUser = typeof getCurrentUser === 'function' ? getCurrentUser() : { id: 0 };
            const table = document.getElementById('usersTable');

            if (!table) return;

            if (activeUsers.length === 0) {
                table.innerHTML = '<tr><td colspan="6" class="text-center text-slate-400 py-6">No active users found</td></tr>';
                return;
            }

            table.innerHTML = activeUsers.map(u => {
                let roleBadge = 'bg-emerald-100 text-emerald-800 border-emerald-200';
                let roleIcon = '<i class="fa-solid fa-seedling"></i>';

                if (u.role === 'admin') {
                    roleBadge = 'bg-rose-100 text-rose-800 border-rose-200';
                    roleIcon = '<i class="fa-solid fa-crown"></i>';
                } else if (u.role === 'officer') {
                    roleBadge = 'bg-amber-100 text-amber-800 border-amber-200';
                    roleIcon = '<i class="fa-solid fa-clipboard-list"></i>';
                }

                const isSelf = currentUser && u.id === currentUser.id;

                return `
                    <tr class="hover:bg-slate-50/80 transition-colors">
                        <td class="py-3 px-4 font-mono text-slate-500">#${u.id}</td>
                        <td class="py-3 px-4 font-semibold text-slate-800 flex items-center gap-2">
                            ${u.name}
                            ${isSelf ? '<span class="px-2 py-0.5 rounded-full text-[9px] font-extrabold bg-slate-200 text-slate-700 uppercase">You</span>' : ''}
                        </td>
                        <td class="py-3 px-4 text-slate-600">${u.email}</td>
                        <td class="py-3 px-4">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-bold border ${roleBadge}">
                                ${roleIcon} ${(u.role || 'farmer').toUpperCase()}
                            </span>
                        </td>
                        <td class="py-3 px-4">
                            <span class="inline-flex items-center gap-1 text-emerald-700 font-semibold text-[11px]">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Active
                            </span>
                        </td>
                        <td class="py-3 px-4 text-right print:hidden">
                            ${!isSelf ? `
                                <div class="inline-flex items-center justify-end gap-1">
                                    <button onclick="changeUserRole(${u.id}, '${u.role}')" title="Change Role" class="p-1.5 text-amber-600 hover:text-amber-800 hover:bg-amber-50 rounded-lg transition">
                                        <i class="fa-solid fa-arrows-rotate"></i>
                                    </button>
                                    <button onclick="deleteUserAccount(${u.id})" title="Delete Account" class="p-1.5 text-red-600 hover:text-red-800 hover:bg-red-50 rounded-lg transition">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </div>
                            ` : '<span class="text-slate-400 text-[11px] italic">Current Account</span>'}
                        </td>
                    </tr>
                `;
            }).join('');
        }

        function filterActiveUsers() {
            const term = (document.getElementById('searchUsersInput')?.value || '').toLowerCase();
            const rows = document.querySelectorAll('#usersTable tr');
            rows.forEach(row => {
                const text = row.textContent?.toLowerCase() || '';
                row.style.display = text.includes(term) ? '' : 'none';
            });
        }

        function approveUserAccount(userId) {
            if (confirm('Approve this farmer account?')) {
                const result = typeof approveUser === 'function' ? approveUser(userId) : { success: true, message: 'User approved' };
                alert(result.message || 'Approved');
                if (result.success) { loadPendingUsers(); loadActiveUsers(); }
            }
        }

        function rejectUserAccount(userId) {
            if (confirm('Reject this farmer registration?')) {
                const result = typeof rejectUser === 'function' ? rejectUser(userId) : { success: true, message: 'User rejected' };
                alert(result.message || 'Rejected');
                if (result.success) { loadPendingUsers(); loadActiveUsers(); }
            }
        }

        function addNewUser() {
            const name = document.getElementById('userName')?.value;
            const email = document.getElementById('userEmail')?.value;
            const password = document.getElementById('userPassword')?.value;
            const role = document.getElementById('userRole')?.value;

            if (!name || !email || !password) { alert('Please fill all required fields'); return; }
            if (password.length < 6) { alert('Password must be at least 6 characters'); return; }

            const result = typeof addUserByAdmin === 'function'
                ? addUserByAdmin(name, email, password, role)
                : { success: true, message: 'Staff user added' };

            if (result.success) {
                alert(`Staff registered successfully! ${name} can now log in as ${role.toUpperCase()}.`);
                if (document.getElementById('userName')) document.getElementById('userName').value = '';
                if (document.getElementById('userEmail')) document.getElementById('userEmail').value = '';
                if (document.getElementById('userPassword')) document.getElementById('userPassword').value = '';
                loadActiveUsers();
            } else {
                alert(result.message || 'Failed to add user');
            }
        }

        function changeUserRole(userId, currentRole) {
            const newRole = prompt(`Current role: ${currentRole}\nEnter new role (admin, officer):`, currentRole);
            if (newRole && ['admin', 'officer'].includes(newRole.toLowerCase())) {
                if (typeof updateUserRole === 'function' && updateUserRole(userId, newRole.toLowerCase())) {
                    alert(`Role updated to ${newRole.toUpperCase()}`);
                    loadActiveUsers();
                }
            } else if (newRole) {
                alert('Invalid role! Only admin or officer allowed.');
            }
        }

        function deleteUserAccount(userId) {
            if (confirm('Are you sure you want to delete this user?')) {
                const result = typeof deleteUser === 'function' ? deleteUser(userId) : { success: true, message: 'User deleted' };
                alert(result.message || 'Deleted');
                if (result.success) { loadPendingUsers(); loadActiveUsers(); }
            }
        }

        function exportUsers() {
            const users = typeof getAllUsers === 'function' ? getAllUsers() : [];
            let csv = 'ID,Name,Email,Role,Status,Registered Date\n';
            users.forEach(u => csv += `${u.id},"${u.name}","${u.email}",${u.role},${u.status || 'active'},${u.registeredAt || ''}\n`);

            const blob = new Blob([csv], { type: 'text/csv' });
            const a = document.createElement('a');
            a.href = URL.createObjectURL(blob);
            a.download = `users_export_${new Date().toISOString().split('T')[0]}.csv`;
            a.click();
            URL.revokeObjectURL(a.href);
        }

        document.addEventListener('DOMContentLoaded', loadUsersPage);
    </script>
@endpush

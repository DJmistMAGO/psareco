@extends('layouts.app')

@section('title', 'Users - PSARECO')

@section('content')
    <div class="d-flex">

        <div class="main-content" style="margin-left: 250px; flex: 1; padding: 30px;">
            @include('components.breadcrumb', [
                'title' => 'User Management',
                'icon' => 'fas fa-users-cog'
            ])

            <div class="card mb-4 border-warning" id="pendingSection">
                <div class="card-header" style="background: #f4a261; color: white;">
                    <i class="fas fa-clock"></i> Pending Farmer Approvals
                    <span class="badge bg-dark ms-2" id="pendingCount">0</span>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr><th>ID</th><th>Name</th><th>Email</th><th>Role</th><th>Registered Date</th><th>Action</th></tr>
                            </thead>
                            <tbody id="pendingUsersTable"><tr><td colspan="6" class="text-center text-muted py-4">No pending farmer registrations</td></tr></tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header" style="background: #2d6a4f; color: white;">
                    <i class="fas fa-user-plus"></i> Register New Staff (Admin or Officer)
                </div>
                <div class="card-body">
                    <div class="row g-2">
                        <div class="col-md-3"><input type="text" id="userName" class="form-control" placeholder="Full Name"></div>
                        <div class="col-md-3"><input type="email" id="userEmail" class="form-control" placeholder="Email"></div>
                        <div class="col-md-2"><input type="password" id="userPassword" class="form-control" placeholder="Password"></div>
                        <div class="col-md-2">
                            <select id="userRole" class="form-select">
                                <option value="admin">Administrator</option>
                                <option value="officer">Cooperative Officer</option>
                            </select>
                        </div>
                        <div class="col-md-2"><button class="btn btn-success w-100" onclick="addNewUser()"><i class="fas fa-plus"></i> Register</button></div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <span><i class="fas fa-users"></i> Active System Users</span>
                        <button class="btn btn-sm btn-outline-success" onclick="exportUsers()"><i class="fas fa-download"></i> Export</button>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead><tr><th>ID</th><th>Name</th><th>Email</th><th>Role</th><th>Status</th><th>Actions</th></tr></thead>
                            <tbody id="usersTable"></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    {{-- <script>
        function loadUsersPage() {
            if (!requireAuth()) return;
            const user = getCurrentUser();
            if (user.role !== 'admin') {
                alert('Access Denied. Administrator privileges required.');
                window.location.href = '{{ route('dashboard') }}';
                return;
            }
            if (typeof loadSidebar === 'function') loadSidebar();
            loadPendingUsers();
            loadActiveUsers();
        }

        function loadPendingUsers() {
            const pendingUsers = getPendingUsers();
            const table = document.getElementById('pendingUsersTable');
            const section = document.getElementById('pendingSection');
            const pendingCount = document.getElementById('pendingCount');

            if (pendingUsers.length === 0) {
                section.style.display = 'none';
                return;
            }

            section.style.display = 'block';
            pendingCount.innerText = pendingUsers.length;
            table.innerHTML = pendingUsers.map(u => `
                <tr class="table-warning">
                    <td>${u.id}</td>
                    <td><strong>${u.name}</strong></td>
                    <td>${u.email}</td>
                    <td><span class="badge badge-info">${u.role.toUpperCase()}</span></td>
                    <td><small>${new Date(u.registeredAt).toLocaleDateString()}</small></td>
                    <td>
                        <button class="btn btn-sm btn-success me-1" onclick="approveUserAccount(${u.id})">Approve</button>
                        <button class="btn btn-sm btn-danger" onclick="rejectUserAccount(${u.id})">Reject</button>
                    </td>
                </tr>
            `).join('');
        }

        function loadActiveUsers() {
            const users = getAllUsers();
            const activeUsers = users.filter(u => u.status === 'active');
            const currentUser = getCurrentUser();
            const table = document.getElementById('usersTable');

            if (activeUsers.length === 0) {
                table.innerHTML = '<tr><td colspan="6" class="text-center text-muted py-4">No active users</td></tr>';
                return;
            }

            table.innerHTML = activeUsers.map(u => {
                const roleClass = u.role === 'admin' ? 'badge-danger' : (u.role === 'officer' ? 'badge-warning' : 'badge-success');
                const roleIcon = u.role === 'admin' ? '<i class="fas fa-crown"></i>' : (u.role === 'officer' ? '<i class="fas fa-clipboard-list"></i>' : '<i class="fas fa-seedling"></i>');
                return `
                    <tr>
                        <td>${u.id}</td>
                        <td>${u.name} ${u.id === currentUser.id ? '<span class="badge badge-secondary">Current</span>' : ''}</td>
                        <td>${u.email}</td>
                        <td><span class="badge ${roleClass}">${roleIcon} ${u.role.toUpperCase()}</span></td>
                        <td><span class="badge badge-success">Active</span></td>
                        <td>${u.id !== currentUser.id ? `<button class="btn btn-sm btn-warning me-1" onclick="changeUserRole(${u.id}, '${u.role}')"><i class="fas fa-exchange-alt"></i></button><button class="btn btn-sm btn-danger" onclick="deleteUserAccount(${u.id})"><i class="fas fa-trash"></i></button>` : '<span class="text-muted">Current</span>'}</td>
                    </tr>
                `;
            }).join('');
        }

        function approveUserAccount(userId) {
            if (confirm('Approve this farmer?')) {
                const result = approveUser(userId);
                alert(result.message);
                if (result.success) { loadPendingUsers(); loadActiveUsers(); }
            }
        }

        function rejectUserAccount(userId) {
            if (confirm('Reject this farmer registration?')) {
                const result = rejectUser(userId);
                alert(result.message);
                if (result.success) { loadPendingUsers(); loadActiveUsers(); }
            }
        }

        function addNewUser() {
            const name = document.getElementById('userName').value;
            const email = document.getElementById('userEmail').value;
            const password = document.getElementById('userPassword').value;
            const role = document.getElementById('userRole').value;
            if (!name || !email || !password) { alert('Please fill all fields'); return; }
            if (password.length < 6) { alert('Password must be at least 6 characters'); return; }
            const result = addUserByAdmin(name, email, password, role);
            if (result.success) {
                alert(`User added successfully! ${name} can now login as ${role.toUpperCase()}.`);
                document.getElementById('userName').value = '';
                document.getElementById('userEmail').value = '';
                document.getElementById('userPassword').value = '';
                loadActiveUsers();
            } else { alert(result.message); }
        }

        function changeUserRole(userId, currentRole) {
            const newRole = prompt(`Current role: ${currentRole}\nEnter new role (admin, officer):`, currentRole);
            if (newRole && ['admin', 'officer'].includes(newRole.toLowerCase())) {
                if (updateUserRole(userId, newRole.toLowerCase())) {
                    alert(`Role changed to ${newRole}`);
                    loadActiveUsers();
                }
            } else if (newRole) alert('Invalid role. Only admin or officer allowed.');
        }

        function deleteUserAccount(userId) {
            if (confirm('Delete this user?')) {
                const result = deleteUser(userId);
                alert(result.message);
                if (result.success) { loadPendingUsers(); loadActiveUsers(); }
            }
        }

        function exportUsers() {
            const users = getAllUsers();
            let csv = 'ID,Name,Email,Role,Status,Registered Date\n';
            users.forEach(u => csv += `${u.id},${u.name},${u.email},${u.role},${u.status || 'active'},${u.registeredAt || ''}\n`);
            const blob = new Blob([csv], { type: 'text/csv' });
            const a = document.createElement('a'); a.href = URL.createObjectURL(blob); a.download = `users_${new Date().toISOString().split('T')[0]}.csv`; a.click(); URL.revokeObjectURL(blob);
        }

        document.addEventListener('DOMContentLoaded', loadUsersPage);
    </script> --}}
@endpush

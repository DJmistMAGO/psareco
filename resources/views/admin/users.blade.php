@extends('layouts.app')

@section('title', 'User Management - PSARECO')

@section('content')
    <main class="w-full min-w-0 p-4 sm:p-6 lg:p-8">
        <x-dashboard-header />

        <x-page-header eyebrow="PSARECO User Management" title="User Management" description="Manage system administrators, cooperative officers, and farmer account approvals" icon="fa-solid fa-users-gear" >
            <x-slot:actions>
                <button type="button" class="inline-flex items-center justify-center gap-2 px-5 py-3 rounded-xl bg-white text-emerald-700 text-sm font-bold shadow-sm hover:bg-emerald-50 transition" >
                    <a href="{{-- route('users.export') --}}" >
                        <i class="fa-solid fa-file-export"></i> Export CSV
                    </a>
                </button>
            </x-slot:actions>
        </x-page-header>



        <div class="bg-white rounded-2xl shadow-sm border border-slate-100/80 p-5 mb-6 print:hidden">
            <div class="flex items-center justify-between pb-3 mb-4 border-b border-slate-100">
                <h3 class="font-bold text-slate-800 text-sm flex items-center gap-2">
                    <i class="fa-solid fa-user-plus text-emerald-600"></i> Register New User Account
                </h3>
                <span class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider">Internal User</span>
            </div>

            <form action="{{ route('user-management.adduser') }}" method="POST">
                @csrf
                <div class="grid grid-cols-1 sm:grid-cols-12 gap-x-3 gap-y-4 items-start">

                    <div class="sm:col-span-3">
                        <label for="name" class="block text-xs font-semibold text-slate-600 mb-1">
                            Full Name <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input type="text" id="name" name="name" value="{{ old('name') }}" placeholder="e.g. Juan Dela Cruz" required class="w-full px-3 py-2 bg-slate-50 border rounded-xl text-xs text-slate-800 focus:outline-none transition {{ $errors->has('name') ? 'border-red-500 bg-red-50/30 focus:ring-2 focus:ring-red-400' : 'border-slate-200 focus:ring-2 focus:ring-emerald-500 focus:bg-white' }}" >
                        </div>
                        @error('name')
                            <p class="mt-1 text-red-500 text-[11px] font-medium flex items-center gap-1">
                                <i class="fa-solid fa-circle-info text-[10px]"></i> {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div class="sm:col-span-3">
                        <label for="email" class="block text-xs font-semibold text-slate-600 mb-1">
                            Email Address <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input type="email" id="email" name="email" value="{{ old('email') }}" placeholder="name@psareco.org" required class="w-full px-3 py-2 bg-slate-50 border rounded-xl text-xs text-slate-800 focus:outline-none transition {{ $errors->has('email') ? 'border-red-500 bg-red-50/30 focus:ring-2 focus:ring-red-400' : 'border-slate-200 focus:ring-2 focus:ring-emerald-500 focus:bg-white' }}" >
                        </div>
                        @error('email')
                            <p class="mt-1 text-red-500 text-[11px] font-medium flex items-center gap-1">
                                <i class="fa-solid fa-circle-info text-[10px]"></i> {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div class="sm:col-span-2">
                        <label for="password" class="block text-xs font-semibold text-slate-600 mb-1">
                            Password <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input type="password" id="password" name="password" placeholder="••••••••" required class="w-full px-3 py-2 bg-slate-50 border rounded-xl text-xs text-slate-800 focus:outline-none transition {{ $errors->has('password') ? 'border-red-500 bg-red-50/30 focus:ring-2 focus:ring-red-400' : 'border-slate-200 focus:ring-2 focus:ring-emerald-500 focus:bg-white' }}" >
                        </div>
                        @error('password')
                            <p class="mt-1 text-red-500 text-[11px] font-medium flex items-center gap-1">
                                <i class="fa-solid fa-circle-info text-[10px]"></i> {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div class="sm:col-span-2">
                        <label for="role" class="block text-xs font-semibold text-slate-600 mb-1">Assigned Role</label>
                        <select id="role" name="role" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:bg-white transition" required>
                            <option value="" disabled selected>Please select role</option>
                            <option value="officer" {{ old('role') == 'officer' ? 'selected' : '' }}>Cooperative Officer</option>
                            <option value="farmer" {{ old('role') == 'farmer' ? 'selected' : '' }}>Member Farmer</option>
                        </select>
                    </div>

                    <div class="sm:col-span-2 sm:mt-[21px]">
                        <button type="submit" class="w-full inline-flex items-center justify-center gap-1.5 bg-emerald-600 hover:bg-emerald-700 active:bg-emerald-800 text-white font-semibold text-xs py-2 px-3.5 rounded-xl shadow-sm hover:shadow transition-all duration-150 cursor-pointer" >
                            <i class="fa-solid fa-plus text-[11px]"></i> Register User
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <x-errors />
        <x-success />

        <div class="bg-white rounded-2xl shadow-sm border border-slate-100/80 overflow-hidden">
            <div class="p-5 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">

                <!-- Tab Switching Buttons -->
                <div class="flex items-center gap-2">
                    <button id="activeTabBtn" onclick="switchTab('active')" class="px-3 py-1.5 text-xs font-semibold rounded-xl bg-emerald-50 text-emerald-800 transition">
                        Active Users <span class="bg-emerald-500 text-white text-[10px] px-2 py-0.2 rounded-full font-bold"> {{ $activeUsers->count() }}</span>
                    </button>
                    <button id="pendingTabBtn" onclick="switchTab('pending')" class="px-3 py-1.5 text-xs font-semibold rounded-xl text-slate-500 hover:bg-slate-50 transition flex items-center gap-1.5">
                        Pending Approvals
                        {{-- @if($pendingUsers->count() > 0) --}}
                            <span class="bg-amber-500 text-white text-[10px] px-2 py-0.2 rounded-full font-bold">{{ $pendingUsers->count() }}</span>
                        {{-- @endif --}}
                    </button>
                    <button id="inactiveTabBtn" onclick="switchTab('inactive')" class="px-3 py-1.5 text-xs font-semibold rounded-xl text-slate-500 hover:bg-slate-50 transition flex items-center gap-1.5">
                        Inactive Users
                        {{-- @if($inactiveUsers->count() > 0) --}}
                            <span class="bg-red-500 text-white text-[10px] px-2 py-0.2 rounded-full font-bold">{{ $inactiveUsers->count() }}</span>
                        {{-- @endif --}}
                    </button>
                </div>

                <div class="relative w-full sm:w-64 print:hidden">
                    <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                    <input type="text" id="searchInput" onkeyup="filterUsers()" placeholder="Search users..."
                        class="w-full pl-9 pr-3 py-1.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:bg-white transition">
                </div>
            </div>

            <div id="activeSection" class="w-full overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-200 text-slate-500 uppercase text-[10px] tracking-wider font-semibold">
                            <th class="py-3 px-4">ID</th>
                            <th class="py-3 px-4">Name</th>
                            <th class="py-3 px-4">Email</th>
                            <th class="py-3 px-4">Role</th>
                            <th class="py-3 px-4">Status</th>
                            <th class="py-3 px-4 text-center print:hidden">Actions</th>
                        </tr>
                    </thead>

                    <tbody id="usersTable" class="divide-y divide-slate-100 text-slate-700">
                        @forelse($activeUsers as $user)
                            <tr class="group hover:bg-emerald-50/40 transition-colors duration-150">

                                <td class="py-3.5 px-4">
                                    <span class="font-mono text-[11px] text-slate-400">
                                        #{{ $user['id'] }}
                                    </span>
                                </td>

                                <td class="py-3.5 px-4">
                                    <div class="flex items-center gap-2">
                                        <span class="font-semibold text-slate-800">
                                            {{ $user['name'] }}
                                        </span>

                                        @if(auth()->id() === $user['id'])
                                            <span class="inline-flex items-center rounded-full bg-slate-100 border border-slate-200 px-2 py-0.5 text-[9px] font-bold uppercase tracking-wide text-slate-500">
                                                You
                                            </span>
                                        @endif
                                    </div>
                                </td>

                                <td class="py-3.5 px-4">
                                    <span class="text-slate-500">
                                        {{ $user['email'] }}
                                    </span>
                                </td>

                                <td class="py-3.5 px-4">
                                    <div class="flex flex-wrap gap-1.5">
                                        @foreach ($user['roles'] as $role)
                                            @php
                                                $badgeClass = match($role) {
                                                    'admin' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                                    'farmer' => 'bg-slate-50 text-slate-600 border-slate-200',
                                                    'officer' => 'bg-sky-50 text-sky-700 border-sky-200',
                                                    default => 'bg-slate-50 text-slate-600 border-slate-200',
                                                };
                                            @endphp

                                            <span class="inline-flex items-center rounded-full border px-2.5 py-1 text-[10px] font-semibold {{ $badgeClass }}">
                                                {{ ucfirst($role) }}
                                            </span>
                                        @endforeach
                                    </div>
                                </td>

                                <td class="py-3.5 px-4">
                                    <span class="inline-flex items-center gap-2 text-[11px] font-semibold text-emerald-700">
                                        <span class="relative flex h-2 w-2">
                                            <span class="absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-50"></span>
                                            <span class="relative inline-flex h-2 w-2 rounded-full bg-emerald-500"></span>
                                        </span>
                                        Active
                                    </span>
                                </td>

                                <td class="py-3.5 px-4 text-center print:hidden">
                                    @if(auth()->id() !== $user['id'])
                                        <x-confirm-modal
                                            title="Deactivate User"
                                            message="Are you sure you want to deactivate {{ $user['name'] }}? This action will disable their access to the system."
                                            confirm-text="Deactivate"
                                            cancel-text="Cancel"
                                            confirm-class="bg-red-600 hover:bg-red-700"
                                            :action="route('user-management.deactivateUser', $user['id'])"
                                            method="POST"
                                        >
                                            <button type="button" class="inline-flex items-center gap-1.5 rounded-lg border border-red-200 bg-white px-3 py-1.5 text-[11px] font-semibold text-red-600 shadow-sm transition-all hover:bg-red-50 hover:border-red-300" >
                                                <i class="fa-solid fa-user-slash text-[10px]"></i>
                                                Deactivate
                                            </button>
                                        </x-confirm-modal>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 text-[11px] italic text-slate-400">
                                            <i class="fa-solid fa-user-check text-[10px]"></i>
                                            Current Account
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-10 text-center">
                                    <div class="flex flex-col items-center justify-center gap-2">
                                        <div class="flex h-10 w-10 items-center justify-center rounded-full bg-slate-100 text-slate-400">
                                            <i class="fa-solid fa-users"></i>
                                        </div>

                                        <p class="text-xs font-semibold text-slate-500">
                                            No active users found
                                        </p>

                                        <p class="text-[11px] text-slate-400">
                                            There are currently no active user accounts.
                                        </p>
                                    </div>
                                </td>
                            </tr>

                        @endforelse
                    </tbody>
                </table>
            </div>

            <div id="pendingSection" class="w-full overflow-x-auto hidden">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-200 text-slate-500 uppercase text-[10px] tracking-wider font-semibold">
                            <th class="py-3 px-4">ID</th>
                            <th class="py-3 px-4">Name</th>
                            <th class="py-3 px-4">Email</th>
                            <th class="py-3 px-4">Requested Role</th>
                            <th class="py-3 px-4">Registered Date</th>
                            <th class="py-3 px-4 text-center print:hidden">Actions</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-100 text-slate-700">
                        @forelse($pendingUsers as $user)
                            <tr class="group hover:bg-amber-50/40 transition-colors duration-150">
                                <td class="py-3.5 px-4">
                                    <span class="font-mono text-[11px] text-slate-400">
                                        #{{ $user['id'] }}
                                    </span>
                                </td>

                                <td class="py-3.5 px-4">
                                    <span class="font-semibold text-slate-800">
                                        {{ $user['name'] }}
                                    </span>
                                </td>

                                <td class="py-3.5 px-4">
                                    <span class="text-slate-500">
                                        {{ $user['email'] }}
                                    </span>
                                </td>

                                <td class="py-3.5 px-4">
                                    <span class="inline-flex items-center gap-1.5 rounded-full border border-sky-200 bg-sky-50 px-2.5 py-1 text-[10px] font-semibold text-sky-700">
                                        <i class="fa-solid fa-seedling text-[9px]"></i>
                                        {{ ucfirst($user['roles'][0] ?? 'Farmer') }}
                                    </span>
                                </td>

                                <td class="py-3.5 px-4">
                                    <span class="text-slate-500">
                                        {{ $user['created_at'] ? $user['created_at']->format('M d, Y') : '—' }}
                                    </span>
                                </td>

                                <td class="py-3.5 px-4 text-center print:hidden">
                                    <div class="flex items-center justify-center gap-2">

                                        <x-confirm-modal
                                            title="Approve User"
                                            message="Are you sure you want to approve {{ $user['name'] }}? This action will grant them access to the system."
                                            confirm-text="Approve"
                                            cancel-text="Cancel"
                                            confirm-class="bg-emerald-600 hover:bg-emerald-700"
                                            :action="route('user-management.approveUser', $user['id'])"
                                            method="POST"
                                        >
                                            <button type="button" class="inline-flex items-center gap-1.5 rounded-lg bg-emerald-600 px-3 py-1.5 text-[11px] font-semibold text-white shadow-sm transition-all hover:bg-emerald-700 hover:shadow-md" >
                                                <i class="fa-solid fa-check text-[10px]"></i>
                                                Approve
                                            </button>
                                        </x-confirm-modal>

                                        <x-confirm-modal
                                            title="Reject User"
                                            message="Are you sure you want to reject {{ $user['name'] }}? This action will deny their registration request."
                                            confirm-text="Reject"
                                            cancel-text="Cancel"
                                            confirm-class="bg-red-600 hover:bg-red-700"
                                            :action="route('user-management.rejectUser', $user['id'])"
                                            method="POST"
                                        >
                                            <button type="button" class="inline-flex items-center gap-1.5 rounded-lg border border-red-200 bg-white px-3 py-1.5 text-[11px] font-semibold text-red-600 shadow-sm transition-all hover:bg-red-50 hover:border-red-300">
                                                <i class="fa-solid fa-xmark text-[10px]"></i>
                                                Reject
                                            </button>
                                        </x-confirm-modal>

                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-10 text-center">
                                    <div class="flex flex-col items-center justify-center gap-2">
                                        <div class="flex h-10 w-10 items-center justify-center rounded-full bg-amber-50 text-amber-500">
                                            <i class="fa-solid fa-user-clock"></i>
                                        </div>

                                        <p class="text-xs font-semibold text-slate-500">
                                            No pending registrations
                                        </p>

                                        <p class="text-[11px] text-slate-400">
                                            New registration requests will appear here.
                                        </p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div id="inactiveSection" class="w-full overflow-x-auto hidden">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-200 text-slate-500 uppercase text-[10px] tracking-wider font-semibold">
                            <th class="py-3 px-4">ID</th>
                            <th class="py-3 px-4">Name</th>
                            <th class="py-3 px-4">Email</th>
                            <th class="py-3 px-4">Role</th>
                            <th class="py-3 px-4">Registered Date</th>
                            <th class="py-3 px-4 text-center print:hidden">Actions</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-100 text-slate-700">

                        @forelse($inactiveUsers as $user)
                            <tr class="group hover:bg-red-50/30 transition-colors duration-150">
                                <td class="py-3.5 px-4">
                                    <span class="font-mono text-[11px] text-slate-400">
                                        #{{ $user['id'] }}
                                    </span>
                                </td>

                                <td class="py-3.5 px-4">
                                    <span class="font-semibold text-slate-800">
                                        {{ $user['name'] }}
                                    </span>
                                </td>

                                <td class="py-3.5 px-4">
                                    <span class="text-slate-500">
                                        {{ $user['email'] }}
                                    </span>
                                </td>

                                <td class="py-3.5 px-4">
                                    <span class="inline-flex items-center gap-1.5 rounded-full border border-sky-200 bg-sky-50 px-2.5 py-1 text-[10px] font-semibold text-sky-700">
                                        <i class="fa-solid fa-seedling text-[9px]"></i>
                                        {{ ucfirst($user['roles'][0] ?? 'Farmer') }}
                                    </span>
                                </td>

                                <td class="py-3.5 px-4">
                                    <span class="text-slate-500">
                                        {{ $user['created_at'] ? $user['created_at']->format('M d, Y') : '—' }}
                                    </span>
                                </td>

                                <td class="py-3.5 px-4 text-center print:hidden">
                                    <x-confirm-modal
                                        title="Reactivate User"
                                        message="Are you sure you want to reactivate {{ $user['name'] }}? This action will restore their access to the system."
                                        confirm-text="Reactivate"
                                        cancel-text="Cancel"
                                        confirm-class="bg-emerald-600 hover:bg-emerald-700"
                                        :action="route('user-management.reactivateUser', $user['id'])"
                                        method="POST"
                                    >
                                        <button type="button" class="inline-flex items-center gap-1.5 rounded-lg bg-emerald-50 border border-emerald-200 px-3 py-1.5 text-[11px] font-semibold text-emerald-700 shadow-sm transition-all hover:bg-emerald-600 hover:text-white hover:border-emerald-600 hover:shadow-md" >
                                            <i class="fa-solid fa-user-check text-[10px]"></i>
                                            Reactivate
                                        </button>
                                    </x-confirm-modal>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-10 text-center">
                                    <div class="flex flex-col items-center justify-center gap-2">
                                        <div class="flex h-10 w-10 items-center justify-center rounded-full bg-red-50 text-red-400">
                                            <i class="fa-solid fa-user-slash"></i>
                                        </div>

                                        <p class="text-xs font-semibold text-slate-500">
                                            No inactive users
                                        </p>

                                        <p class="text-[11px] text-slate-400">
                                            Deactivated accounts will appear here.
                                        </p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </main>
@endsection

@push('scripts')
    <script>
        function switchTab(tab) {
            const activeSection = document.getElementById('activeSection');
            const pendingSection = document.getElementById('pendingSection');
            const activeBtn = document.getElementById('activeTabBtn');
            const pendingBtn = document.getElementById('pendingTabBtn');
            const inactiveSection = document.getElementById('inactiveSection');
            const inactiveBtn = document.getElementById('inactiveTabBtn');

            if (tab === 'active') {
                activeSection.classList.remove('hidden');
                pendingSection.classList.add('hidden');
                inactiveSection.classList.add('hidden');
                activeBtn.className = 'px-3 py-1.5 text-xs font-semibold rounded-xl bg-emerald-50 text-emerald-800 transition';
                pendingBtn.className = 'px-3 py-1.5 text-xs font-semibold rounded-xl text-slate-500 hover:bg-slate-50 transition flex items-center gap-1.5';
                inactiveBtn.className = 'px-3 py-1.5 text-xs font-semibold rounded-xl text-slate-500 hover:bg-slate-50 transition flex items-center gap-1.5';
            } else if (tab === 'inactive') {
                inactiveSection.classList.remove('hidden');
                activeSection.classList.add('hidden');
                pendingSection.classList.add('hidden');
                inactiveBtn.className = 'px-3 py-1.5 text-xs font-semibold rounded-xl bg-red-50 text-red-800 transition flex items-center gap-1.5';
                activeBtn.className = 'px-3 py-1.5 text-xs font-semibold rounded-xl text-slate-500 hover:bg-slate-50 transition';
                pendingBtn.className = 'px-3 py-1.5 text-xs font-semibold rounded-xl text-slate-500 hover:bg-slate-50 transition flex items-center gap-1.5';
            } else {
                pendingSection.classList.remove('hidden');
                activeSection.classList.add('hidden');
                inactiveSection.classList.add('hidden');
                pendingBtn.className = 'px-3 py-1.5 text-xs font-semibold rounded-xl bg-amber-50 text-amber-800 transition flex items-center gap-1.5';
                activeBtn.className = 'px-3 py-1.5 text-xs font-semibold rounded-xl text-slate-500 hover:bg-slate-50 transition';
                inactiveBtn.className = 'px-3 py-1.5 text-xs font-semibold rounded-xl text-slate-500 hover:bg-slate-50 transition flex items-center gap-1.5';
            }
        }

        function filterUsers() {
            const term = (document.getElementById('searchInput')?.value || '').toLowerCase();
            const visibleTable = document.querySelector('div:not(.hidden) > table tbody');
            if (!visibleTable) return;

            visibleTable.querySelectorAll('tr').forEach(row => {
                const text = row.textContent?.toLowerCase() || '';
                row.style.display = text.includes(term) ? '' : 'none';
            });
        }
    </script>
@endpush

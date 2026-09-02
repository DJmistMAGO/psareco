@extends('layouts.app')

@section('title', 'User Management - PSARECO')

@section('content')
		<main class="w-full min-w-0 p-4 sm:p-6 lg:p-8">
				<x-dashboard-header />

				<x-page-header eyebrow="PSARECO User Management" title="User Management"
						description="Manage system administrators, cooperative officers, and farmer account approvals"
						icon="fa-solid fa-users-gear">
						<x-slot:actions>
								<button type="button" @click="$dispatch('open-add-user-modal')"
										class="inline-flex items-center justify-center gap-2 px-5 py-3 rounded-xl bg-emerald-600 text-white text-sm font-bold shadow-sm hover:bg-emerald-700 transition">
										<i class="fa-solid fa-user-plus"></i> Add User
								</button>
								<a href="{{ route('user-management.export') }}"
										class="inline-flex items-center justify-center gap-2 px-5 py-3 rounded-xl bg-white text-emerald-700 text-sm font-bold shadow-sm hover:bg-emerald-50 transition">
										<i class="fa-solid fa-file-export"></i> Export CSV
								</a>
						</x-slot:actions>
				</x-page-header>

				<x-errors />
				<x-success />

				{{-- Add User modal --}}
				<div x-data="{
	    open: {{ $errors->any() && old('_form') === 'add_user' ? 'true' : 'false' }},
	    selectedRole: '{{ old('role', 'officer') }}',
	    generatePassword() {
	        const chars = 'ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnopqrstuvwxyz23456789!@#$%';
	        let pw = '';
	        for (let i = 0; i < 12; i++) {
	            pw += chars.charAt(Math.floor(Math.random() * chars.length));
	        }
	        this.$refs.passwordInput.value = pw;
	        this.$refs.passwordInput.type = 'text';
	    }
	}" @open-add-user-modal.window="open = true" x-show="open" x-cloak
						class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 backdrop-blur-sm p-4">
						<div @click.outside="open = false"
								class="w-full max-w-md max-h-[90vh] overflow-y-auto bg-white rounded-2xl shadow-xl">
								<div class="flex items-center justify-between p-5 border-b border-slate-100">
										<h3 class="font-bold text-slate-800 text-sm flex items-center gap-2">
												<i class="fa-solid fa-user-plus text-emerald-600"></i> Register New User Account
										</h3>

								</div>

								<form action="{{ route('user-management.adduser') }}" method="POST" class="p-5">
										@csrf
										<input type="hidden" name="_form" value="add_user">

										<div class="mb-5">
												<label class="block text-xs font-semibold text-slate-600 mb-2">Assigned Role <span
																class="text-red-500">*</span></label>
												<input type="hidden" name="role" x-model="selectedRole">
												<div class="grid grid-cols-2 gap-2 rounded-xl bg-slate-50 border border-slate-200 p-1">
														<button type="button" @click="selectedRole = 'officer'"
																:class="selectedRole === 'officer' ? 'bg-white text-sky-700 shadow-sm border border-slate-200' :
																    'text-slate-500 hover:text-slate-700'"
																class="flex items-center justify-center gap-2 rounded-lg py-2.5 text-xs font-semibold transition">
																<i class="fa-solid fa-user-tie text-[10px]"></i> Officer
														</button>
														<button type="button" @click="selectedRole = 'farmer'"
																:class="selectedRole === 'farmer' ? 'bg-white text-emerald-700 shadow-sm border border-slate-200' :
																    'text-slate-500 hover:text-slate-700'"
																class="flex items-center justify-center gap-2 rounded-lg py-2.5 text-xs font-semibold transition">
																<i class="fa-solid fa-seedling text-[10px]"></i> Farmer
														</button>
												</div>
										</div>

										<div class="grid grid-cols-1 gap-y-4">
												<div>
														<label for="name" class="block text-xs font-semibold text-slate-600 mb-1">
																Full Name <span class="text-red-500">*</span>
														</label>
														<input type="text" id="name" name="name" value="{{ old('name') }}"
																placeholder="e.g. Juan Dela Cruz" required
																class="w-full px-3 py-2 bg-slate-50 border rounded-xl text-xs text-slate-800 focus:outline-none transition {{ $errors->has('name') ? 'border-red-500 bg-red-50/30 focus:ring-2 focus:ring-red-400' : 'border-slate-200 focus:ring-2 focus:ring-emerald-500 focus:bg-white' }}">
														@error('name')
																<p class="mt-1 text-red-500 text-[11px] font-medium flex items-center gap-1">
																		<i class="fa-solid fa-circle-info text-[10px]"></i> {{ $message }}
																</p>
														@enderror
												</div>

												<div>
														<label for="email" class="block text-xs font-semibold text-slate-600 mb-1">
																Email Address <span class="text-red-500">*</span>
														</label>
														<input type="email" id="email" name="email" value="{{ old('email') }}"
																placeholder="name@psareco.org" required
																class="w-full px-3 py-2 bg-slate-50 border rounded-xl text-xs text-slate-800 focus:outline-none transition {{ $errors->has('email') ? 'border-red-500 bg-red-50/30 focus:ring-2 focus:ring-red-400' : 'border-slate-200 focus:ring-2 focus:ring-emerald-500 focus:bg-white' }}">
														@error('email')
																<p class="mt-1 text-red-500 text-[11px] font-medium flex items-center gap-1">
																		<i class="fa-solid fa-circle-info text-[10px]"></i> {{ $message }}
																</p>
														@enderror
												</div>

												<div>
														<label for="contact_number" class="block text-xs font-semibold text-slate-600 mb-1">Contact Number</label>
														<input type="text" id="contact_number" name="contact_number" value="{{ old('contact_number') }}"
																placeholder="09xx-xxx-xxxx"
																class="w-full px-3 py-2 bg-slate-50 border rounded-xl text-xs text-slate-800 focus:outline-none transition {{ $errors->has('contact_number') ? 'border-red-500 bg-red-50/30 focus:ring-2 focus:ring-red-400' : 'border-slate-200 focus:ring-2 focus:ring-emerald-500 focus:bg-white' }}">
														@error('contact_number')
																<p class="mt-1 text-red-500 text-[11px] font-medium flex items-center gap-1"><i
																				class="fa-solid fa-circle-info text-[10px]"></i> {{ $message }}</p>
														@enderror
												</div>

												<div>
														<label for="password" class="block text-xs font-semibold text-slate-600 mb-1">
																Temporary Password <span class="text-red-500">*</span>
														</label>
														<div class="flex gap-2">
																<input type="password" id="password" name="password" x-ref="passwordInput" placeholder="••••••••"
																		required minlength="8"
																		class="w-full px-3 py-2 bg-slate-50 border rounded-xl text-xs text-slate-800 focus:outline-none transition {{ $errors->has('password') ? 'border-red-500 bg-red-50/30 focus:ring-2 focus:ring-red-400' : 'border-slate-200 focus:ring-2 focus:ring-emerald-500 focus:bg-white' }}">
																<button type="button" @click="generatePassword()"
																		class="shrink-0 px-3 py-2 rounded-xl border border-slate-200 text-xs font-semibold text-slate-600 hover:bg-slate-50 transition">
																		Generate
																</button>
														</div>
														<p class="mt-1 text-[10px] text-slate-400">User will be required to change this on first login.</p>
														@error('password')
																<p class="mt-1 text-red-500 text-[11px] font-medium flex items-center gap-1">
																		<i class="fa-solid fa-circle-info text-[10px]"></i> {{ $message }}
																</p>
														@enderror
												</div>

												<div>
														<label for="address" class="block text-xs font-semibold text-slate-600 mb-1">Address</label>
														<textarea id="address" name="address" rows="2" placeholder="Barangay / Municipality / Province"
														  class="w-full px-3 py-2 bg-slate-50 border rounded-xl text-xs text-slate-800 focus:outline-none transition {{ $errors->has('address') ? 'border-red-500 bg-red-50/30 focus:ring-2 focus:ring-red-400' : 'border-slate-200 focus:ring-2 focus:ring-emerald-500 focus:bg-white' }}">{{ old('address') }}</textarea>
														@error('address')
																<p class="mt-1 text-red-500 text-[11px] font-medium flex items-center gap-1"><i
																				class="fa-solid fa-circle-info text-[10px]"></i> {{ $message }}</p>
														@enderror
												</div>

												<div x-show="selectedRole === 'officer'">
														<label for="position" class="block text-xs font-semibold text-slate-600 mb-1">
																Position <span class="text-slate-400 font-normal">(Optional)</span>
														</label>
														<input type="text" id="position" name="position" value="{{ old('position') }}"
																placeholder="Officer / Staff"
																class="w-full px-3 py-2 bg-slate-50 border rounded-xl text-xs text-slate-800 focus:outline-none transition {{ $errors->has('position') ? 'border-red-500 bg-red-50/30 focus:ring-2 focus:ring-red-400' : 'border-slate-200 focus:ring-2 focus:ring-emerald-500 focus:bg-white' }}">
														@error('position')
																<p class="mt-1 text-red-500 text-[11px] font-medium flex items-center gap-1"><i
																				class="fa-solid fa-circle-info text-[10px]"></i> {{ $message }}</p>
														@enderror
												</div>
										</div>

										<div class="flex justify-end gap-2 mt-5 pt-4 border-t border-slate-100">
												<button type="button" @click="open = false"
														class="px-4 py-2 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-50 transition">
														Cancel
												</button>
												<button type="submit"
														class="inline-flex items-center gap-1.5 bg-emerald-600 hover:bg-emerald-700 active:bg-emerald-800 text-white font-semibold text-xs py-2 px-4 rounded-xl shadow-sm transition-all cursor-pointer">
														<i class="fa-solid fa-plus text-[11px]"></i> Register User
												</button>
										</div>
								</form>
						</div>
				</div>

				{{-- View / Edit User modal --}}
				<div x-data="{
	    open: false,
	    urlTemplate: '{{ route('user-management.updateUser', ['id' => '__ID__']) }}',
	    selectedUser: { id: null, name: '', email: '', contact_number: '', address: '', position: '', role: 'farmer', status: 'active', is_self: false, is_admin: false, created_at: '' },
	    get formAction() {
	        return this.urlTemplate.replace('__ID__', this.selectedUser.id);
	    }
	}" @open-view-user-modal.window="open = true; selectedUser = $event.detail.user"
						x-show="open" x-cloak
						class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 backdrop-blur-sm p-4" aria-modal="true"
						role="dialog">
						<div @click.outside="open = false"
								class="w-full max-w-md max-h-[90vh] overflow-y-auto bg-white rounded-2xl shadow-xl">
								<div class="flex items-center justify-between p-5 border-b border-slate-100">
										<h3 class="font-bold text-slate-800 text-sm flex items-center gap-2">
												<i class="fa-solid"
														:class="selectedUser.is_admin ? 'fa-shield-halved text-amber-600' : (selectedUser.role === 'officer' ?
														    'fa-user-tie text-sky-600' : 'fa-seedling text-emerald-600')"></i>
												<span x-text="selectedUser.is_admin ? 'Administrator Account' : 'Update User Account'"></span>
										</h3>
										<button type="button" @click="open = false" class="text-slate-400 hover:text-slate-600 transition">
												<i class="fa-solid fa-xmark"></i>
										</button>
								</div>

								<form :action="formAction" method="POST" class="p-5">
										@csrf
										@method('PUT')

										<div class="mb-5">
												<label class="block text-xs font-semibold text-slate-600 mb-2">Assigned Role</label>
												<input type="hidden" name="role" x-model="selectedUser.role">
												<div class="grid grid-cols-2 gap-2 rounded-xl bg-slate-50 border border-slate-200 p-1">
														<button type="button" @click="selectedUser.role = 'officer'" :disabled="selectedUser.is_admin"
																:class="selectedUser.role === 'officer' ? 'bg-white text-sky-700 shadow-sm border border-slate-200' :
																    'text-slate-500 hover:text-slate-700'"
																class="flex items-center justify-center gap-2 rounded-lg py-2.5 text-xs font-semibold transition disabled:opacity-60 disabled:cursor-not-allowed">
																<i class="fa-solid fa-user-tie text-[10px]"></i> Officer
														</button>
														<button type="button" @click="selectedUser.role = 'farmer'" :disabled="selectedUser.is_admin"
																:class="selectedUser.role === 'farmer' ? 'bg-white text-emerald-700 shadow-sm border border-slate-200' :
																    'text-slate-500 hover:text-slate-700'"
																class="flex items-center justify-center gap-2 rounded-lg py-2.5 text-xs font-semibold transition disabled:opacity-60 disabled:cursor-not-allowed">
																<i class="fa-solid fa-seedling text-[10px]"></i> Farmer
														</button>
												</div>
										</div>

										<div class="grid grid-cols-1 gap-y-4">
												<div>
														<label class="block text-xs font-semibold text-slate-600 mb-1">
																Full Name <span class="text-red-500">*</span>
														</label>
														<input type="text" name="name" x-model="selectedUser.name" required
																:disabled="selectedUser.is_admin"
																class="w-full px-3 py-2 bg-slate-50 border rounded-xl text-xs text-slate-800 focus:outline-none transition {{ $errors->has('name') ? 'border-red-500 bg-red-50/30 focus:ring-2 focus:ring-red-400' : 'border-slate-200 focus:ring-2 focus:ring-emerald-500 focus:bg-white' }} disabled:opacity-60 disabled:cursor-not-allowed">
												</div>

												<div>
														<label class="block text-xs font-semibold text-slate-600 mb-1">
																Email Address <span class="text-red-500">*</span>
														</label>
														<input type="email" name="email" x-model="selectedUser.email" required
																:disabled="selectedUser.is_admin"
																class="w-full px-3 py-2 bg-slate-50 border rounded-xl text-xs text-slate-800 focus:outline-none transition {{ $errors->has('email') ? 'border-red-500 bg-red-50/30 focus:ring-2 focus:ring-red-400' : 'border-slate-200 focus:ring-2 focus:ring-emerald-500 focus:bg-white' }} disabled:opacity-60 disabled:cursor-not-allowed">
												</div>

												<div>
														<label class="block text-xs font-semibold text-slate-600 mb-1">Contact Number</label>
														<input type="text" name="contact_number" x-model="selectedUser.contact_number"
																:disabled="selectedUser.is_admin"
																class="w-full px-3 py-2 bg-slate-50 border rounded-xl text-xs text-slate-800 focus:outline-none transition border-slate-200 focus:ring-2 focus:ring-emerald-500 focus:bg-white disabled:opacity-60 disabled:cursor-not-allowed">
												</div>

												<div>
														<label class="block text-xs font-semibold text-slate-600 mb-1">Account Status</label>
														<input type="hidden" name="status" :value="selectedUser.status">
														<button type="button"
																@click="selectedUser.status = selectedUser.status === 'active' ? 'inactive' : 'active'"
																:disabled="selectedUser.is_self || selectedUser.is_admin"
																class="w-full flex items-center justify-between rounded-xl border px-4 py-2.5 transition disabled:opacity-60 disabled:cursor-not-allowed"
																:class="selectedUser.status === 'active' ? 'bg-emerald-50 border-emerald-200' : 'bg-red-50 border-red-200'">
																<span class="flex items-center gap-2 text-xs font-semibold"
																		:class="selectedUser.status === 'active' ? 'text-emerald-700' : 'text-red-600'">
																		<span class="relative flex h-2 w-2">
																				<span class="absolute inline-flex h-full w-full rounded-full opacity-50"
																						:class="selectedUser.status === 'active' ? 'bg-emerald-400' : 'bg-red-400'"></span>
																				<span class="relative inline-flex h-2 w-2 rounded-full"
																						:class="selectedUser.status === 'active' ? 'bg-emerald-500' : 'bg-red-500'"></span>
																		</span>
																		<span x-text="selectedUser.status === 'active' ? 'Active' : 'Inactive'"></span>
																</span>
																<span class="relative inline-flex h-5 w-9 items-center rounded-full transition"
																		:class="selectedUser.status === 'active' ? 'bg-emerald-500' : 'bg-slate-300'">
																		<span class="inline-block h-3.5 w-3.5 transform rounded-full bg-white shadow transition"
																				:class="selectedUser.status === 'active' ? 'translate-x-[18px]' : 'translate-x-1'"></span>
																</span>
														</button>
														<p class="mt-1 text-[11px] text-slate-400" x-show="selectedUser.is_self && !selectedUser.is_admin">You
																can't change your own status here.</p>
												</div>

												<div>
														<label class="block text-xs font-semibold text-slate-600 mb-1">Address</label>
														<textarea name="address" x-model="selectedUser.address" rows="2" :disabled="selectedUser.is_admin"
														  class="w-full px-3 py-2 bg-slate-50 border rounded-xl text-xs text-slate-800 focus:outline-none transition border-slate-200 focus:ring-2 focus:ring-emerald-500 focus:bg-white disabled:opacity-60 disabled:cursor-not-allowed"></textarea>
												</div>

												<div x-show="selectedUser.role === 'officer'">
														<label class="block text-xs font-semibold text-slate-600 mb-1">
																Position <span class="text-slate-400 font-normal">(Optional)</span>
														</label>
														<input type="text" name="position" x-model="selectedUser.position" :disabled="selectedUser.is_admin"
																class="w-full px-3 py-2 bg-slate-50 border rounded-xl text-xs text-slate-800 focus:outline-none transition border-slate-200 focus:ring-2 focus:ring-emerald-500 focus:bg-white disabled:opacity-60 disabled:cursor-not-allowed">
												</div>
										</div>

										<template x-if="selectedUser.is_admin">
												<div class="mt-5 rounded-2xl border border-amber-100 bg-amber-50 p-4 flex items-center gap-3">
														<div class="w-9 h-9 rounded-xl flex items-center justify-center bg-amber-100 text-amber-600 shrink-0">
																<i class="fa-solid fa-shield-halved"></i>
														</div>
														<div>
																<p class="text-xs font-semibold text-amber-700">Protected Account</p>
																<p class="text-[11px] text-amber-600 mt-0.5">Administrator accounts can't be edited from User Management.
																</p>
														</div>
												</div>
										</template>

										<div class="flex justify-end gap-2 mt-5 pt-4 border-t border-slate-100">
												<button type="button" @click="open = false"
														class="px-4 py-2 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-50 transition">
														Close
												</button>
												<button type="submit" x-show="!selectedUser.is_admin"
														class="inline-flex items-center gap-1.5 bg-emerald-600 hover:bg-emerald-700 active:bg-emerald-800 text-white font-semibold text-xs py-2 px-4 rounded-xl shadow-sm transition-all cursor-pointer">
														<i class="fa-regular fa-floppy-disk text-[11px]"></i> Save Changes
												</button>
										</div>
								</form>
						</div>
				</div>

				<div class="bg-white rounded-2xl shadow-sm border border-slate-100/80 overflow-hidden">
						<div class="p-5 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">

								<div class="flex items-center gap-2">
										<button id="activeTabBtn" onclick="switchTab('active')"
												class="px-3 py-1.5 text-xs font-semibold rounded-xl bg-emerald-50 text-emerald-800 transition">
												Active Users <span class="bg-emerald-500 text-white text-[10px] px-2 py-0.2 rounded-full font-bold">
														{{ $activeUsers->count() }}</span>
										</button>
										<button id="inactiveTabBtn" onclick="switchTab('inactive')"
												class="px-3 py-1.5 text-xs font-semibold rounded-xl text-slate-500 hover:bg-slate-50 transition flex items-center gap-1.5">
												Deactivated Users
												<span
														class="bg-red-500 text-white text-[10px] px-2 py-0.2 rounded-full font-bold">{{ $inactiveUsers->count() }}</span>
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
												<tr
														class="bg-slate-50 border-b border-slate-200 text-slate-500 uppercase text-[10px] tracking-wider font-semibold">
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

																				@if (auth()->id() === $user['id'])
																						<span
																								class="inline-flex items-center rounded-full bg-slate-100 border border-slate-200 px-2 py-0.5 text-[9px] font-bold uppercase tracking-wide text-slate-500">
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
																								$badgeClass = match ($role) {
																								    'admin' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
																								    'farmer' => 'bg-slate-50 text-slate-600 border-slate-200',
																								    'officer' => 'bg-sky-50 text-sky-700 border-sky-200',
																								    default => 'bg-slate-50 text-slate-600 border-slate-200'
																								};
																						@endphp

																						<span
																								class="inline-flex items-center rounded-full border px-2.5 py-1 text-[10px] font-semibold {{ $badgeClass }}">
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
																		<div class="flex items-center justify-center gap-2">
																				<button type="button"
																						@click="$dispatch('open-view-user-modal', { user: {
                                                id: {{ $user['id'] }},
                                                name: @js($user['name']),
                                                email: @js($user['email']),
                                                contact_number: @js($user['contact_number'] ?? ''),
                                                address: @js($user['address'] ?? ''),
                                                position: @js($user['position'] ?? ''),
                                                role: @js($user['roles'][0] ?? 'farmer'),
                                                status: 'active',
                                                is_self: {{ auth()->id() === $user['id'] ? 'true' : 'false' }},
                                                is_admin: {{ in_array('admin', $user['roles'], true) ? 'true' : 'false' }},
                                                created_at: @js(optional($user['created_at'])->format('M d, Y'))
                                            } })"
																						class="inline-flex items-center gap-1.5 rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-[11px] font-semibold text-slate-600 shadow-sm transition-all hover:bg-slate-50 hover:border-slate-300">
																						<i class="fa-solid fa-eye text-[10px]"></i> View
																				</button>

																				@if (in_array('admin', $user['roles'], true))
																						<span class="inline-flex items-center gap-1.5 text-[11px] italic text-slate-400">
																								<i class="fa-solid fa-lock text-[10px]"></i>
																								Protected
																						</span>
																				@elseif(auth()->id() !== $user['id'])
																						<x-confirm-modal title="Deactivate User"
																								message="Are you sure you want to deactivate {{ $user['name'] }}? This action will disable their access to the system."
																								confirm-text="Deactivate" cancel-text="Cancel" confirm-class="bg-red-600 hover:bg-red-700"
																								:action="route('user-management.deactivateUser', $user['id'])" method="POST">
																								<button type="button"
																										class="inline-flex items-center gap-1.5 rounded-lg border border-red-200 bg-white px-3 py-1.5 text-[11px] font-semibold text-red-600 shadow-sm transition-all hover:bg-red-50 hover:border-red-300">
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
																		</div>
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

						<div id="inactiveSection" class="w-full overflow-x-auto hidden">
								<table class="w-full text-left border-collapse text-xs">
										<thead>
												<tr
														class="bg-slate-50 border-b border-slate-200 text-slate-500 uppercase text-[10px] tracking-wider font-semibold">
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
																		<span
																				class="inline-flex items-center gap-1.5 rounded-full border border-sky-200 bg-sky-50 px-2.5 py-1 text-[10px] font-semibold text-sky-700">
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
																				<button type="button"
																						@click="$dispatch('open-view-user-modal', { user: {
                                                id: {{ $user['id'] }},
                                                name: @js($user['name']),
                                                email: @js($user['email']),
                                                contact_number: @js($user['contact_number'] ?? ''),
                                                address: @js($user['address'] ?? ''),
                                                position: @js($user['position'] ?? ''),
                                                role: @js($user['roles'][0] ?? 'farmer'),
                                                status: 'inactive',
                                                is_self: false,
                                                is_admin: false,
                                                created_at: @js(optional($user['created_at'])->format('M d, Y'))
                                            } })"
																						class="inline-flex items-center gap-1.5 rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-[11px] font-semibold text-slate-600 shadow-sm transition-all hover:bg-slate-50 hover:border-slate-300">
																						<i class="fa-solid fa-eye text-[10px]"></i> View
																				</button>

																				<x-confirm-modal title="Reactivate User"
																						message="Are you sure you want to reactivate {{ $user['name'] }}? This action will restore their access to the system."
																						confirm-text="Reactivate" cancel-text="Cancel"
																						confirm-class="bg-emerald-600 hover:bg-emerald-700" :action="route('user-management.reactivateUser', $user['id'])" method="POST">
																						<button type="button"
																								class="inline-flex items-center gap-1.5 rounded-lg bg-emerald-50 border border-emerald-200 px-3 py-1.5 text-[11px] font-semibold text-emerald-700 shadow-sm transition-all hover:bg-emerald-600 hover:text-white hover:border-emerald-600 hover:shadow-md">
																								<i class="fa-solid fa-user-check text-[10px]"></i>
																								Reactivate
																						</button>
																				</x-confirm-modal>

																				<x-confirm-modal title="Delete User Permanently"
																						message="This will permanently remove {{ $user['name'] }} from the system. This action cannot be undone and the account data will be deleted."
																						confirm-text="Delete Permanently" cancel-text="Cancel"
																						confirm-class="bg-red-600 hover:bg-red-700" :action="route('user-management.deleteUser', $user['id'])" method="POST">
																						<button type="button"
																								class="inline-flex items-center gap-1.5 rounded-lg border border-red-200 bg-white px-3 py-1.5 text-[11px] font-semibold text-red-600 shadow-sm transition-all hover:bg-red-50 hover:border-red-300">
																								<i class="fa-solid fa-trash text-[10px]"></i>
																								Delete
																						</button>
																				</x-confirm-modal>
																		</div>
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
						const activeBtn = document.getElementById('activeTabBtn');
						const inactiveSection = document.getElementById('inactiveSection');
						const inactiveBtn = document.getElementById('inactiveTabBtn');

						if (tab === 'active') {
								activeSection.classList.remove('hidden');
								inactiveSection.classList.add('hidden');
								activeBtn.className = 'px-3 py-1.5 text-xs font-semibold rounded-xl bg-emerald-50 text-emerald-800 transition';
								inactiveBtn.className =
										'px-3 py-1.5 text-xs font-semibold rounded-xl text-slate-500 hover:bg-slate-50 transition flex items-center gap-1.5';
						} else {
								inactiveSection.classList.remove('hidden');
								activeSection.classList.add('hidden');
								inactiveBtn.className =
										'px-3 py-1.5 text-xs font-semibold rounded-xl bg-red-50 text-red-800 transition flex items-center gap-1.5';
								activeBtn.className = 'px-3 py-1.5 text-xs font-semibold rounded-xl text-slate-500 hover:bg-slate-50 transition';
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

@extends('layouts.app')

@section('title', 'Inventory Trash - PSARECO')

@section('content')
	<main class="w-full min-w-0 p-4 sm:p-6 lg:p-8" x-data="{ tab: 'products' }">
		<x-dashboard-header />
		<x-page-header eyebrow="PSARECO Archive" title="Archive"
			description="View and restore products or machineries that have been removed from your inventory."
			icon="fa-solid fa-box-archive">
		</x-page-header>

		<x-success />
		<x-errors />

		{{-- Stat cards ARE the tabs — no separate tab bar needed --}}
		<section class="mb-6">
			{{-- <div class="flex items-center gap-3 mb-4">
				<div class="w-10 h-10 rounded-xl bg-red-50 text-red-600 flex items-center justify-center">
					<i class="fa-solid fa-box-archive text-base"></i>
				</div>
				<div>
					<h3 class="text-sm font-bold text-slate-800">Archived Inventory</h3>
					<p class="text-xs text-slate-400">
						{{ $totalDeleted }} {{ Str::plural('item', $totalDeleted) }} removed from active stock
					</p>
				</div>
			</div> --}}

			<div class="grid gap-4 sm:grid-cols-2" role="tablist">
				<button type="button" role="tab" :aria-selected="tab === 'products'" @click="tab = 'products'"
					class="relative text-left rounded-2xl border p-4 flex items-center gap-3 transition-all"
					:class="tab === 'products'
					    ?
					    'bg-white border-red-200 shadow-md ring-1 ring-red-100' :
					    'bg-slate-50 border-slate-100 hover:border-red-100 hover:bg-white'">
					<div class="w-11 h-11 rounded-xl flex items-center justify-center shrink-0 transition-colors"
						:class="tab === 'products' ? 'bg-red-50 text-red-600' : 'bg-white border border-slate-200 text-slate-400'">
						<i class="fa-solid fa-box text-base"></i>
					</div>
					<div class="min-w-0">
						<p class="text-xs font-bold uppercase tracking-wide text-slate-400">Deleted Products</p>
						<h2 class="mt-0.5 text-xl font-bold text-slate-800">
							{{ $deletedInventories->total() }} {{ Str::plural('product', $deletedInventories->total()) }}
						</h2>
					</div>
					<div class="ml-auto self-start" x-show="tab === 'products'" x-cloak>
						<span class="w-2 h-2 rounded-full bg-red-500 block"></span>
					</div>
				</button>

				<button type="button" role="tab" :aria-selected="tab === 'machinery'" @click="tab = 'machinery'"
					class="relative text-left rounded-2xl border p-4 flex items-center gap-3 transition-all"
					:class="tab === 'machinery'
					    ?
					    'bg-white border-red-200 shadow-md ring-1 ring-red-100' :
					    'bg-slate-50 border-slate-100 hover:border-red-100 hover:bg-white'">
					<div class="w-11 h-11 rounded-xl flex items-center justify-center shrink-0 transition-colors"
						:class="tab === 'machinery' ? 'bg-red-50 text-red-600' : 'bg-white border border-slate-200 text-slate-400'">
						<i class="fa-solid fa-truck-ramp-box text-base"></i>
					</div>
					<div class="min-w-0">
						<p class="text-xs font-bold uppercase tracking-wide text-slate-400">Deleted Machinery</p>
						<h2 class="mt-0.5 text-xl font-bold text-slate-800">
							{{ $deletedMachineries->total() }} {{ $deletedMachineries->total() === 1 ? 'unit' : 'units' }}
						</h2>
					</div>
					<div class="ml-auto self-start" x-show="tab === 'machinery'" x-cloak>
						<span class="w-2 h-2 rounded-full bg-red-500 block"></span>
					</div>
				</button>
			</div>
		</section>

		{{-- PRODUCTS PANEL --}}
		<div x-show="tab === 'products'" x-cloak>
			@if ($deletedInventories->count())
				<section class="bg-white border border-slate-100 rounded-2xl shadow-sm overflow-hidden">
					<div class="hidden md:block overflow-x-auto">
						<table class="w-full text-sm">
							<thead>
								<tr class="bg-slate-50 border-b border-slate-100 text-left">
									<th class="px-5 py-3 text-[11px] font-bold uppercase tracking-wide text-slate-400">Product</th>
									<th class="px-5 py-3 text-[11px] font-bold uppercase tracking-wide text-slate-400">Type</th>
									<th class="px-5 py-3 text-[11px] font-bold uppercase tracking-wide text-slate-400 text-right">Stock</th>
									<th class="px-5 py-3 text-[11px] font-bold uppercase tracking-wide text-slate-400 text-right">Price</th>
									<th class="px-5 py-3 text-[11px] font-bold uppercase tracking-wide text-slate-400">Deleted</th>
									<th class="px-5 py-3 text-[11px] font-bold uppercase tracking-wide text-slate-400 text-right">Actions</th>
								</tr>
							</thead>
							<tbody class="divide-y divide-slate-100">
								@foreach ($deletedInventories as $item)
									@php
										$isFertilizer = $item->type === 'Fertilizer';
										$icon = $isFertilizer ? 'fa-leaf' : 'fa-bug';
										$iconBg = $isFertilizer ? 'bg-emerald-100' : 'bg-amber-100';
										$iconColor = $isFertilizer ? 'text-emerald-600' : 'text-amber-600';
										$stockFormatted = rtrim(rtrim(number_format($item->quantity, 2), '0'), '.');
									@endphp
									<tr class="hover:bg-slate-50/70 transition-colors">
										<td class="px-5 py-4">
											<div class="flex items-center gap-3">
												@if ($item->image_path)
													<img src="{{ asset('storage/' . $item->image_path) }}" alt="{{ $item->name }}"
														class="w-9 h-9 shrink-0 rounded-lg object-cover border border-slate-100">
												@else
													<div
														class="w-9 h-9 shrink-0 rounded-lg {{ $iconBg }} {{ $iconColor }} flex items-center justify-center">
														<i class="fa-solid {{ $icon }} text-sm"></i>
													</div>
												@endif
												<div class="min-w-0">
													<p class="font-semibold text-slate-800 truncate">{{ $item->name }}</p>
													<p class="text-[11px] text-slate-400">Added {{ $item->created_at?->format('M d, Y') ?? '—' }}</p>
												</div>
											</div>
										</td>
										<td class="px-5 py-4 text-slate-500">{{ $item->type }}</td>
										<td class="px-5 py-4 text-right">
											<span class="font-semibold text-slate-700">{{ $stockFormatted }}</span>
											<span class="text-xs text-slate-400">{{ $item->unit }}</span>
										</td>
										<td class="px-5 py-4 text-right">
											<span class="font-semibold text-slate-700">₱{{ number_format($item->price, 2) }}</span>
										</td>
										<td class="px-5 py-4">
											<p class="text-slate-600 font-medium">{{ $item->deleted_at?->format('M d, Y') }}</p>
											<p class="text-[11px] text-slate-400">{{ $item->deleted_at?->diffForHumans() }}</p>
										</td>
										<td class="px-5 py-4">
											<div class="flex items-center justify-end gap-2">
												<form action="{{ route('inventory.restoreProduct', $item->id) }}" method="POST">
													@csrf
													<button type="submit" title="Restore product"
														class="w-9 h-9 inline-flex items-center justify-center rounded-lg text-emerald-600 hover:bg-emerald-50 transition">
														<i class="fa-solid fa-rotate-left text-xs"></i>
													</button>
												</form>

												<x-confirm-modal title="Permanently Delete Product" :message="'Permanently delete ' . $item->name . '? This action cannot be undone.'" confirmText="Delete Permanently"
													confirmClass="bg-red-600 hover:bg-red-700 text-white" icon="triangle-exclamation" :action="route('inventory.forceDeleteProduct', $item->id)"
													method="DELETE">
													<button type="button" title="Delete permanently"
														class="w-9 h-9 inline-flex items-center justify-center rounded-lg text-red-500 hover:bg-red-50 transition">
														<i class="fa-solid fa-trash-can text-xs"></i>
													</button>
												</x-confirm-modal>
											</div>
										</td>
									</tr>
								@endforeach
							</tbody>
						</table>
					</div>

					<div class="md:hidden divide-y divide-slate-100">
						@foreach ($deletedInventories as $item)
							@php
								$isFertilizer = $item->type === 'Fertilizer';
								$icon = $isFertilizer ? 'fa-leaf' : 'fa-bug';
								$iconBg = $isFertilizer ? 'bg-emerald-100' : 'bg-amber-100';
								$iconColor = $isFertilizer ? 'text-emerald-600' : 'text-amber-600';
								$stockFormatted = rtrim(rtrim(number_format($item->quantity, 2), '0'), '.');
							@endphp
							<div class="p-4">
								<div class="flex items-start justify-between gap-3">
									<div class="flex items-center gap-3 min-w-0">
										@if ($item->image_path)
											<img src="{{ asset('storage/' . $item->image_path) }}" alt="{{ $item->name }}"
												class="w-9 h-9 shrink-0 rounded-lg object-cover border border-slate-100">
										@else
											<div
												class="w-9 h-9 shrink-0 rounded-lg {{ $iconBg }} {{ $iconColor }} flex items-center justify-center">
												<i class="fa-solid {{ $icon }} text-sm"></i>
											</div>
										@endif
										<div class="min-w-0">
											<p class="font-semibold text-slate-800 truncate">{{ $item->name }}</p>
											<p class="text-[11px] text-slate-400">{{ $item->type }}</p>
										</div>
									</div>
									<span class="shrink-0 text-[11px] text-slate-400 whitespace-nowrap">
										{{ $item->deleted_at?->diffForHumans() }}
									</span>
								</div>
								<div class="mt-3 grid grid-cols-2 gap-2 text-xs">
									<div>
										<span class="text-slate-400">Stock:</span>
										<span class="font-semibold text-slate-700">{{ $stockFormatted }} {{ $item->unit }}</span>
									</div>
									<div>
										<span class="text-slate-400">Price:</span>
										<span class="font-semibold text-slate-700">₱{{ number_format($item->price, 2) }}</span>
									</div>
									<div class="col-span-2">
										<span class="text-slate-400">Deleted:</span>
										<span class="text-slate-600 font-medium">{{ $item->deleted_at?->format('M d, Y h:i A') }}</span>
									</div>
								</div>
								<div class="mt-3 flex items-center justify-end gap-2">
									<form action="{{ route('inventory.restoreProduct', $item->id) }}" method="POST">
										@csrf
										<button type="submit"
											class="inline-flex items-center gap-2 px-3 py-2 rounded-lg bg-emerald-50 text-emerald-700 hover:bg-emerald-100 text-xs font-semibold transition">
											<i class="fa-solid fa-rotate-left"></i>
											Restore
										</button>
									</form>
									<x-confirm-modal title="Permanently Delete Product" :message="'Permanently delete ' . $item->name . '? This action cannot be undone.'" confirmText="Delete Permanently"
										confirmClass="bg-red-600 hover:bg-red-700 text-white" icon="triangle-exclamation" :action="route('inventory.forceDeleteProduct', $item->id)"
										method="DELETE">
										<button type="button"
											class="inline-flex items-center gap-2 px-3 py-2 rounded-lg bg-red-50 text-red-600 hover:bg-red-100 text-xs font-semibold transition">
											<i class="fa-solid fa-trash-can"></i>
											Delete
										</button>
									</x-confirm-modal>
								</div>
							</div>
						@endforeach
					</div>
				</section>

				@if ($deletedInventories->hasPages())
					<div class="mt-6 flex flex-col sm:flex-row items-center justify-between gap-3">
						<p class="text-xs text-slate-400 order-2 sm:order-1">
							Showing {{ $deletedInventories->firstItem() }}–{{ $deletedInventories->lastItem() }} of
							{{ $deletedInventories->total() }}
							{{ Str::plural('deleted product', $deletedInventories->total()) }}
						</p>
						<div class="order-1 sm:order-2">{{ $deletedInventories->onEachSide(1)->links() }}</div>
					</div>
				@endif
			@else
				<section class="bg-white border border-dashed border-slate-200 rounded-3xl p-10 sm:p-14 text-center">
					<div class="mx-auto w-20 h-20 rounded-3xl bg-slate-50 text-slate-400 flex items-center justify-center">
						<i class="fa-solid fa-box text-3xl"></i>
					</div>
					<h3 class="mt-5 text-lg font-bold text-slate-800">No deleted Products</h3>
					<p class="mt-2 text-sm text-slate-400 max-w-md mx-auto">
						Archived inventory products will appear here.
					</p>

					<a href="{{ route('inventory.index') }}"
						class="inline-flex items-center gap-2 mt-5 px-4 py-2.5 rounded-xl bg-emerald-600 text-white text-xs font-semibold hover:bg-emerald-700 transition">
						<i class="fa-solid fa-boxes-stacked"></i>
						Back to Inventory
					</a>
				</section>
			@endif
		</div>

		{{-- MACHINERY PANEL --}}
		<div x-show="tab === 'machinery'" x-cloak>
			@if ($deletedMachineries->count())
				<section class="bg-white border border-slate-100 rounded-2xl shadow-sm overflow-hidden">
					<div class="hidden md:block overflow-x-auto">
						<table class="w-full text-sm">
							<thead>
								<tr class="bg-slate-50 border-b border-slate-100 text-left">
									<th class="px-5 py-3 text-[11px] font-bold uppercase tracking-wide text-slate-400">Machinery</th>
									<th class="px-5 py-3 text-[11px] font-bold uppercase tracking-wide text-slate-400">Model / Serial</th>
									<th class="px-5 py-3 text-[11px] font-bold uppercase tracking-wide text-slate-400 text-right">Price</th>
									<th class="px-5 py-3 text-[11px] font-bold uppercase tracking-wide text-slate-400">Status</th>
									<th class="px-5 py-3 text-[11px] font-bold uppercase tracking-wide text-slate-400">Deleted</th>
									<th class="px-5 py-3 text-[11px] font-bold uppercase tracking-wide text-slate-400 text-right">Actions</th>
								</tr>
							</thead>
							<tbody class="divide-y divide-slate-100">
								@foreach ($deletedMachineries as $item)
									@php
										$statusClasses = match ($item->status) {
										    'Available' => 'bg-emerald-50 text-emerald-700',
										    'Reserved' => 'bg-blue-50 text-blue-700',
										    'In Use' => 'bg-amber-50 text-amber-700',
										    'Under Maintenance' => 'bg-orange-50 text-orange-700',
										    'Unavailable' => 'bg-red-50 text-red-700',
										    default => 'bg-slate-100 text-slate-600'
										};
									@endphp
									<tr class="hover:bg-slate-50/70 transition-colors">
										<td class="px-5 py-4">
											<div class="flex items-center gap-3">
												@if ($item->image_path)
													<img src="{{ asset('storage/' . $item->image_path) }}" alt="{{ $item->machinery_name }}"
														class="w-9 h-9 shrink-0 rounded-lg object-cover border border-slate-100">
												@else
													<div class="w-9 h-9 shrink-0 rounded-lg bg-slate-100 text-slate-400 flex items-center justify-center">
														<i class="fa-solid fa-truck-ramp-box text-sm"></i>
													</div>
												@endif
												<div class="min-w-0">
													<p class="font-semibold text-slate-800 truncate">{{ $item->machinery_name }}</p>
													<p class="text-[11px] text-slate-400">Added {{ $item->created_at?->format('M d, Y') ?? '—' }}</p>
												</div>
											</div>
										</td>
										<td class="px-5 py-4">
											<p class="text-slate-700 font-medium">{{ $item->model ?: '—' }}</p>
											<p class="text-[11px] text-slate-400">SN: {{ $item->serial_number ?: '—' }}</p>
										</td>
										<td class="px-5 py-4 text-right">
											<span class="font-semibold text-slate-700">₱{{ number_format($item->price, 2) }}</span>
										</td>
										<td class="px-5 py-4">
											<span
												class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-semibold {{ $statusClasses }}">
												<span class="w-1.5 h-1.5 rounded-full bg-current"></span>
												{{ $item->status ?: '—' }}
											</span>
										</td>
										<td class="px-5 py-4">
											<p class="text-slate-600 font-medium">{{ $item->deleted_at?->format('M d, Y') }}</p>
											<p class="text-[11px] text-slate-400">{{ $item->deleted_at?->diffForHumans() }}</p>
										</td>
										<td class="px-5 py-4">
											<div class="flex items-center justify-end gap-2">
												<form action="{{ route('machinery.restoreMachinery', $item->id) }}" method="POST">
													@csrf
													<button type="submit" title="Restore machinery"
														class="w-9 h-9 inline-flex items-center justify-center rounded-lg text-emerald-600 hover:bg-emerald-50 transition">
														<i class="fa-solid fa-rotate-left text-xs"></i>
													</button>
												</form>

												<x-confirm-modal title="Permanently Delete Machinery" :message="'Permanently delete ' . $item->machinery_name . '? This action cannot be undone.'" confirmText="Delete Permanently"
													confirmClass="bg-red-600 hover:bg-red-700 text-white" icon="triangle-exclamation" :action="route('machinery.forceDeleteMachinery', $item->id)"
													method="DELETE">
													<button type="button" title="Delete permanently"
														class="w-9 h-9 inline-flex items-center justify-center rounded-lg text-red-500 hover:bg-red-50 transition">
														<i class="fa-solid fa-trash-can text-xs"></i>
													</button>
												</x-confirm-modal>
											</div>
										</td>
									</tr>
								@endforeach
							</tbody>
						</table>
					</div>

					<div class="md:hidden divide-y divide-slate-100">
						@foreach ($deletedMachineries as $item)
							@php
								$statusClasses = match ($item->status) {
								    'Available' => 'bg-emerald-50 text-emerald-700',
								    'Reserved' => 'bg-blue-50 text-blue-700',
								    'In Use' => 'bg-amber-50 text-amber-700',
								    'Under Maintenance' => 'bg-orange-50 text-orange-700',
								    'Unavailable' => 'bg-red-50 text-red-700',
								    default => 'bg-slate-100 text-slate-600'
								};
							@endphp
							<div class="p-4">
								<div class="flex items-start justify-between gap-3">
									<div class="flex items-center gap-3 min-w-0">
										@if ($item->image_path)
											<img src="{{ asset('storage/' . $item->image_path) }}" alt="{{ $item->machinery_name }}"
												class="w-9 h-9 shrink-0 rounded-lg object-cover border border-slate-100">
										@else
											<div class="w-9 h-9 shrink-0 rounded-lg bg-slate-100 text-slate-400 flex items-center justify-center">
												<i class="fa-solid fa-truck-ramp-box text-sm"></i>
											</div>
										@endif
										<div class="min-w-0">
											<p class="font-semibold text-slate-800 truncate">{{ $item->machinery_name }}</p>
											<p class="text-[11px] text-slate-400">{{ $item->model ?: '—' }}</p>
										</div>
									</div>
									<span class="shrink-0 text-[11px] text-slate-400 whitespace-nowrap">
										{{ $item->deleted_at?->diffForHumans() }}
									</span>
								</div>
								<div class="mt-3 grid grid-cols-2 gap-2 text-xs">
									<div>
										<span class="text-slate-400">Price:</span>
										<span class="font-semibold text-slate-700">₱{{ number_format($item->price, 2) }}</span>
									</div>
									<div>
										<span class="text-slate-400">Serial:</span>
										<span class="text-slate-600 font-medium">{{ $item->serial_number ?: '—' }}</span>
									</div>
									<div class="col-span-2">
										<span class="text-slate-400">Status:</span>
										<span
											class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-semibold {{ $statusClasses }}">
											{{ $item->status ?: '—' }}
										</span>
									</div>
								</div>
								<div class="mt-3 flex items-center justify-end gap-2">
									<form action="{{ route('machinery.restoreMachinery', $item->id) }}" method="POST">
										@csrf
										<button type="submit"
											class="inline-flex items-center gap-2 px-3 py-2 rounded-lg bg-emerald-50 text-emerald-700 hover:bg-emerald-100 text-xs font-semibold transition">
											<i class="fa-solid fa-rotate-left"></i>
											Restore
										</button>
									</form>
									<x-confirm-modal title="Permanently Delete Machinery" :message="'Permanently delete ' . $item->machinery_name . '? This action cannot be undone.'" confirmText="Delete Permanently"
										confirmClass="bg-red-600 hover:bg-red-700 text-white" icon="triangle-exclamation" :action="route('machinery.forceDeleteMachinery', $item->id)"
										method="DELETE">
										<button type="button"
											class="inline-flex items-center gap-2 px-3 py-2 rounded-lg bg-red-50 text-red-600 hover:bg-red-100 text-xs font-semibold transition">
											<i class="fa-solid fa-trash-can"></i>
											Delete
										</button>
									</x-confirm-modal>
								</div>
							</div>
						@endforeach
					</div>
				</section>

				@if ($deletedMachineries->hasPages())
					<div class="mt-6 flex flex-col sm:flex-row items-center justify-between gap-3">
						<p class="text-xs text-slate-400 order-2 sm:order-1">
							Showing {{ $deletedMachineries->firstItem() }}–{{ $deletedMachineries->lastItem() }} of
							{{ $deletedMachineries->total() }}
							{{ Str::plural('deleted machinery unit', $deletedMachineries->total()) }}
						</p>
						<div class="order-1 sm:order-2">{{ $deletedMachineries->onEachSide(1)->links() }}</div>
					</div>
				@endif
			@else
				<section class="bg-white border border-dashed border-slate-200 rounded-3xl p-10 sm:p-14 text-center">
					<div class="mx-auto w-20 h-20 rounded-3xl bg-slate-50 text-slate-400 flex items-center justify-center">
						<i class="fa-solid fa-truck-ramp-box text-3xl"></i>
					</div>
					<h3 class="mt-5 text-lg font-bold text-slate-800">No deleted machinery</h3>
					<p class="mt-2 text-sm text-slate-400 max-w-md mx-auto">
						Archived machinery will appear here.
					</p>

					<a href="{{ route('inventory.index') }}"
						class="inline-flex items-center gap-2 mt-5 px-4 py-2.5 rounded-xl bg-emerald-600 text-white text-xs font-semibold hover:bg-emerald-700 transition">
						<i class="fa-solid fa-boxes-stacked"></i>
						Back to Inventory
					</a>
				</section>
			@endif
		</div>
	</main>
@endsection

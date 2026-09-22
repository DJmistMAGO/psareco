@extends('layouts.app')
@section('title', 'Notifications - PSARECO')
@section('content')

	<main class="w-full min-w-0 p-4 sm:p-6 lg:p-8"> <x-dashboard-header /> <x-page-header eyebrow="Notifications"
			title="Notification Center" description="Stay updated with activity across the PSARECO Enterprise System"
			icon="fa-regular fa-bell" />
		<div class="bg-white rounded-2xl shadow-sm border border-slate-100/80 overflow-hidden">
			<div class="px-5 py-5 sm:px-6 border-b border-slate-100">
				<div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
					<div>
						<div class="flex items-center gap-2">
							<h2 class="text-sm font-bold text-slate-700"> Notifications </h2>
							@if ($unreadCount > 0)
								<span
									class="inline-flex items-center justify-center min-w-5 h-5 px-1.5 rounded-full bg-emerald-100 text-emerald-700 text-[10px] font-bold">
									{{ $unreadCount }} </span>
							@endif
						</div>
						<p class="text-xs text-slate-400 mt-1"> {{ $totalCount }} total notification{{ $totalCount == 1 ? '' : 's' }}
						</p>
					</div>
					@if ($unreadCount > 0)
						<form action="{{ route('notifications.markAllAsRead') }}" method="POST"> @csrf <button type="submit"
								class="inline-flex items-center justify-center gap-2 px-3.5 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold transition-colors">
								<i class="fa-solid fa-check-double text-[10px]"></i> Mark All as Read </button> </form>
					@endif
					<div class="px-5 py-4 sm:px-6 border-b border-slate-100">
						<div class="grid grid-cols-3 gap-2 max-w-xl"> <a href="{{ route('notifications.index') }}"
								class="flex items-center justify-between gap-2 px-3 py-2.5 rounded-xl transition-colors {{ !request('filter') ? 'bg-emerald-600 text-white' : 'bg-slate-50 text-slate-500 hover:bg-slate-100' }}">
								<span class="text-xs font-semibold"> All </span> <span
									class="text-[10px] font-bold {{ !request('filter') ? 'text-white/80' : 'text-slate-400' }}"> {{ $totalCount }}
								</span> </a> <a href="{{ route('notifications.index', ['filter' => 'unread']) }}"
								class="flex items-center justify-between gap-2 px-3 py-2.5 rounded-xl transition-colors {{ request('filter') === 'unread' ? 'bg-emerald-600 text-white' : 'bg-slate-50 text-slate-500 hover:bg-slate-100' }}">
								<span class="text-xs font-semibold"> Unread </span> <span
									class="text-[10px] font-bold {{ request('filter') === 'unread' ? 'text-white/80' : 'text-slate-400' }}">
									{{ $unreadCount }} </span> </a> <a href="{{ route('notifications.index', ['filter' => 'read']) }}"
								class="flex items-center justify-between gap-2 px-3 py-2.5 rounded-xl transition-colors {{ request('filter') === 'read' ? 'bg-emerald-600 text-white' : 'bg-slate-50 text-slate-500 hover:bg-slate-100' }}">
								<span class="text-xs font-semibold"> Read </span> <span
									class="text-[10px] font-bold {{ request('filter') === 'read' ? 'text-white/80' : 'text-slate-400' }}">
									{{ $readCount }} </span> </a> </div>
					</div>
				</div>
			</div>

			<div class="divide-y divide-slate-100">
				@forelse ($notifications as $notification)
					@php
						$type = $notification->data['type'] ?? 'default';
						$icon = match ($type) {
						    'sale' => 'fa-solid fa-cart-shopping',
						    'booking' => 'fa-solid fa-calendar-check',
						    'inventory' => 'fa-solid fa-boxes-stacked',
						    'user' => 'fa-solid fa-user',
						    default => 'fa-regular fa-bell'
						};
						$isUnread = is_null($notification->read_at);
					@endphp <a href="{{ route('notifications.redirect', $notification->id) }}"
						class="group flex items-start gap-4 px-5 py-4 sm:px-6 transition-colors {{ $isUnread ? 'bg-emerald-50/50 hover:bg-emerald-50' : 'bg-white hover:bg-slate-50' }}">
						<div
							class="w-10 h-10 rounded-xl shrink-0 flex items-center justify-center {{ $isUnread ? 'bg-emerald-100 text-emerald-600' : 'bg-slate-100 text-slate-400' }}">
							<i class="{{ $icon }} text-sm"></i>
						</div>
						<div class="min-w-0 flex-1">
							<div class="flex items-start justify-between gap-3">
								<div class="min-w-0">
									<p class="text-sm font-semibold {{ $isUnread ? 'text-slate-800' : 'text-slate-600' }}">
										{{ $notification->data['title'] ?? 'Notification' }} </p>
									<p class="text-xs text-slate-500 mt-1 leading-relaxed"> {{ $notification->data['message'] ?? '' }} </p>
								</div>
								@if ($isUnread)
									<span class="w-2 h-2 rounded-full bg-emerald-500 shrink-0 mt-1.5"></span>
								@endif
							</div>
							<div class="flex flex-wrap items-center gap-x-2 gap-y-1 mt-2"> <span
									class="inline-flex items-center gap-1 text-[10px] text-slate-400"> <i class="fa-regular fa-clock"></i>
									{{ $notification->created_at->diffForHumans() }} </span> <span class="text-[10px] text-slate-300"> • </span>
								<span class="text-[10px] text-slate-400"> {{ $notification->created_at->format('M d, Y g:ia') }} </span>
								@if ($isUnread)
									<span class="text-[10px] text-slate-300"> • </span> <span class="text-[10px] font-semibold text-emerald-600">
										Unread </span>
								@endif
							</div>
						</div> <i
							class="fa-solid fa-chevron-right text-[10px] text-slate-300 group-hover:text-emerald-500 transition-colors mt-3 shrink-0"></i>
				</a> @empty <div class="flex flex-col items-center justify-center py-20 px-6">
						<div class="w-14 h-14 rounded-2xl bg-slate-100 text-slate-400 flex items-center justify-center mb-4"> <i
								class="fa-regular fa-bell-slash text-lg"></i> </div>
						<p class="text-sm font-semibold text-slate-600">
							@if (request('filter') === 'unread')
								No unread notifications
							@elseif (request('filter') === 'read')
								No read notifications
							@else
								No notifications yet
							@endif
						</p>
						<p class="text-xs text-slate-400 mt-1 text-center max-w-sm">
							@if (request('filter') === 'unread')
								You're all caught up. There are no unread notifications.
							@elseif (request('filter') === 'read')
								Notifications you have read will appear here.
							@else
								New system activity and updates will appear here.
							@endif
						</p>
					</div>
				@endforelse
			</div>
			@if ($notifications->total() > 0)
				<div class="px-5 py-4 sm:px-6 border-t border-slate-100">
					<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
						<p class="text-[10px] text-slate-400"> Showing <span class="font-semibold text-slate-500">
								{{ $notifications->firstItem() }} </span> to <span class="font-semibold text-slate-500">
								{{ $notifications->lastItem() }} </span> of <span class="font-semibold text-slate-500">
								{{ $notifications->total() }} </span> notifications </p>
						<div> {{ $notifications->onEachSide(1)->links() }} </div>
					</div>
				</div>
			@endif
		</div>
	</main>
@endsection

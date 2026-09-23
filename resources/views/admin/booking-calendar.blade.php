@extends('layouts.app')

@section('title', 'Booking Calendar - PSARECO')

@section('content')
	<main class="w-full min-w-0 p-4 sm:p-6 lg:p-8">
		<x-dashboard-header />

		<x-page-header eyebrow="PSARECO Booking Calendar" title="Booking Calendar"
			description="View your approved machinery booking schedule" icon="fa-solid fa-calendar" />

		<div class="bg-white rounded-none shadow-sm border border-slate-200 px-6 sm:px-12 lg:px-20 py-8 mb-6 print:hidden">
			<div id="calendar" class="w-full mx-auto"></div>
		</div>
	</main>

	{{-- Tooltip element, positioned via JS --}}
	<div id="booking-tooltip"
		class="hidden fixed z-50 w-64 bg-white rounded-xl shadow-lg border border-slate-200 p-4 pointer-events-none">
		<div class="flex items-center gap-2 mb-3">
			<div id="booking-tooltip-dot" class="w-2.5 h-2.5 rounded-full shrink-0"></div>
			<p id="booking-tooltip-machine" class="text-sm font-bold text-slate-800 truncate"></p>
		</div>
		<div class="space-y-2 text-xs">
			<div class="flex items-center gap-2 text-slate-500">
				<i class="fa-solid fa-user w-4 text-slate-400"></i>
				<span id="booking-tooltip-renter" class="text-slate-700 font-medium truncate"></span>
			</div>
			<div class="flex items-center gap-2 text-slate-500">
				<i class="fa-solid fa-calendar-days w-4 text-slate-400"></i>
				<span id="booking-tooltip-dates" class="text-slate-700 font-medium"></span>
			</div>
			<div class="flex items-center gap-2 text-slate-500">
				<i class="fa-solid fa-clock w-4 text-slate-400"></i>
				<span id="booking-tooltip-duration" class="text-slate-700 font-medium"></span>
			</div>
		</div>
	</div>
@endsection

@push('styles')
	<style>
		.fc {
			--fc-border-color: #e2e8f0;
			--fc-button-bg-color: #2c7a56;
			--fc-button-border-color: #2c7a56;
			--fc-button-hover-bg-color: #236345;
			--fc-button-hover-border-color: #236345;
			--fc-button-active-bg-color: #1b4d36;
			--fc-button-active-border-color: #1b4d36;
			--fc-event-bg-color: #2c7a56;
			--fc-event-border-color: #2c7a56;
		}

		.fc .fc-button {
			border-radius: 0 !important;
			font-weight: 500;
			text-transform: capitalize;
		}

		.fc .fc-toolbar-title {
			color: #2c7a56;
			font-weight: 700;
		}

		.fc-theme-standard td,
		.fc-theme-standard th,
		.fc-theme-standard .fc-scrollgrid {
			border-radius: 0 !important;
		}

		.fc .fc-highlight {
			background: rgba(64, 160, 114, 0.15) !important;
		}

		.fc-event {
			cursor: pointer;
		}
	</style>
@endpush

@push('scripts')
	<script>
		document.addEventListener('DOMContentLoaded', function() {

			const calendarDiv = document.getElementById('calendar');
			const tooltip = document.getElementById('booking-tooltip');
			const tooltipDot = document.getElementById('booking-tooltip-dot');
			const tooltipMachine = document.getElementById('booking-tooltip-machine');
			const tooltipRenter = document.getElementById('booking-tooltip-renter');
			const tooltipDates = document.getElementById('booking-tooltip-dates');
			const tooltipDuration = document.getElementById('booking-tooltip-duration');

			function showTooltip(event, jsEvent) {
				const props = event.extendedProps;

				tooltipDot.style.backgroundColor = event.backgroundColor || '#64748b';
				tooltipMachine.textContent = props.machineName;
				tooltipRenter.textContent = props.renterName;
				tooltipDates.textContent = props.startDate === props.endDate ?
					props.startDate :
					`${props.startDate} – ${props.endDate}`;
				tooltipDuration.textContent = props.totalDays === 1 ?
					'1 day' :
					`${props.totalDays} day${props.totalDays > 1 ? 's' : ''}`;

				tooltip.classList.remove('hidden');
				positionTooltip(jsEvent);
			}

			function positionTooltip(jsEvent) {
				const padding = 12;
				const tooltipRect = tooltip.getBoundingClientRect();
				let left = jsEvent.clientX + padding;
				let top = jsEvent.clientY + padding;

				// Keep tooltip within viewport
				if (left + tooltipRect.width > window.innerWidth) {
					left = jsEvent.clientX - tooltipRect.width - padding;
				}
				if (top + tooltipRect.height > window.innerHeight) {
					top = jsEvent.clientY - tooltipRect.height - padding;
				}

				tooltip.style.left = `${left}px`;
				tooltip.style.top = `${top}px`;
			}

			function hideTooltip() {
				tooltip.classList.add('hidden');
			}

			const calendar = new window.Calendar(calendarDiv, {
				plugins: [
					window.dayGridPlugin,
					window.timeGridPlugin,
					window.interactionPlugin
				],

				initialView: 'dayGridMonth',

				height: '520px',

				events: "{{ route('schedule.booking-calendar') }}",

				selectable: false,
				editable: false,

				headerToolbar: {
					left: 'prev,next today',
					center: 'title',
					right: 'dayGridMonth,timeGridWeek,timeGridDay'
				},

				eventDidMount: function(info) {
					info.el.addEventListener('mouseenter', (jsEvent) => showTooltip(info.event, jsEvent));
					info.el.addEventListener('mousemove', (jsEvent) => positionTooltip(jsEvent));
					info.el.addEventListener('mouseleave', hideTooltip);
				}
			});

			calendar.render();
		});
	</script>
@endpush

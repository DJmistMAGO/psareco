
@extends('layouts.app')

@section('title', 'Booking Calendar - PSARECO')

@section('content')
	<main class="w-full min-w-0 p-4 sm:p-6 lg:p-8">
		<x-dashboard-header />

		<x-page-header eyebrow="PSARECO Booking Calendar" title="Booking Calendar"
			description="View your approved machinery booking schedule" icon="fa-solid fa-calendar" />

		<div class="bg-white rounded-none shadow-sm border border-slate-200 px-3 sm:px-6 lg:px-12 xl:px-20 py-4 sm:py-6 lg:py-8 mb-6 print:hidden overflow-hidden">
			<div id="calendar" class="w-full mx-auto"></div>
		</div>
	</main>

	<div id="booking-tooltip"
		class="hidden fixed z-50 w-64 max-w-[calc(100vw-24px)] bg-white rounded-xl shadow-lg border border-slate-200 p-4 pointer-events-none">
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

		.fc .fc-daygrid-day-number {
			padding: 6px;
			font-size: 0.875rem;
		}

		.fc .fc-event {
			font-size: 0.75rem;
		}

		@media (max-width: 767px) {
			.fc .fc-toolbar {
				display: flex;
				flex-wrap: wrap;
				gap: 8px;
				margin-bottom: 12px;
			}

			.fc .fc-toolbar-chunk {
				display: flex;
				align-items: center;
			}

			.fc .fc-toolbar-title {
				font-size: 1rem;
				text-align: center;
			}

			.fc .fc-button {
				padding: 0.4rem 0.6rem;
				font-size: 0.75rem;
			}

			.fc .fc-toolbar-chunk:first-child {
				order: 1;
				width: 100%;
				justify-content: center;
			}

			.fc .fc-toolbar-chunk:nth-child(2) {
				order: 2;
				width: 100%;
				justify-content: center;
			}

			.fc .fc-toolbar-chunk:last-child {
				order: 3;
				width: 100%;
				justify-content: center;
			}

			.fc .fc-daygrid-day-number {
				padding: 4px;
				font-size: 0.7rem;
			}

			.fc .fc-col-header-cell-cushion {
				font-size: 0.65rem;
				padding: 4px 2px;
			}

			.fc .fc-event {
				font-size: 0.65rem;
				padding: 1px 2px;
				overflow: hidden;
				text-overflow: ellipsis;
				white-space: nowrap;
			}

			.fc .fc-daygrid-event {
				margin-top: 1px;
			}

			.fc .fc-more-link {
				font-size: 0.65rem;
			}
		}

		@media (max-width: 480px) {
			.fc .fc-toolbar-title {
				font-size: 0.9rem;
			}

			.fc .fc-button {
				padding: 0.35rem 0.5rem;
				font-size: 0.7rem;
			}

			.fc .fc-daygrid-day-number {
				font-size: 0.65rem;
			}

			.fc .fc-col-header-cell-cushion {
				font-size: 0.6rem;
			}

			.fc .fc-event {
				font-size: 0.6rem;
			}
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

			const isMobile = window.innerWidth < 768;

			function showTooltip(event, jsEvent) {
				if (isMobile) return;

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
				if (isMobile) return;

				const padding = 12;
				const tooltipRect = tooltip.getBoundingClientRect();
				let left = jsEvent.clientX + padding;
				let top = jsEvent.clientY + padding;

				if (left + tooltipRect.width > window.innerWidth) {
					left = jsEvent.clientX - tooltipRect.width - padding;
				}

				if (top + tooltipRect.height > window.innerHeight) {
					top = jsEvent.clientY - tooltipRect.height - padding;
				}

				left = Math.max(padding, left);
				top = Math.max(padding, top);

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

				initialView: window.innerWidth < 768 ? 'dayGridMonth' : 'dayGridMonth',

				height: window.innerWidth < 768 ? 'auto' : '520px',

				contentHeight: window.innerWidth < 768 ? 'auto' : undefined,

				events: "{{ route('schedule.booking-calendar') }}",

				selectable: false,
				editable: false,

				headerToolbar: {
					left: 'prev,next today',
					center: 'title',
					right: 'dayGridMonth,timeGridWeek,timeGridDay'
				},

				dayMaxEvents: window.innerWidth < 768 ? 2 : false,

				eventDidMount: function(info) {
					if (!isMobile) {
						info.el.addEventListener('mouseenter', (jsEvent) => {
							showTooltip(info.event, jsEvent);
						});

						info.el.addEventListener('mousemove', (jsEvent) => {
							positionTooltip(jsEvent);
						});

						info.el.addEventListener('mouseleave', hideTooltip);
					}
				}
			});

			calendar.render();

			window.addEventListener('resize', function() {
				calendar.updateSize();
			});
		});
	</script>
@endpush

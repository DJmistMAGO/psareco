@extends('layouts.app')

@section('title', 'Booking Calendar - PSARECO')

@section('content')
    <main class="w-full min-w-0 p-4 sm:p-6 lg:p-8">
        <x-dashboard-header />

        <x-page-header
            eyebrow="PSARECO Booking Calendar"
            title="Booking Calendar"
            description="View your approved machinery booking schedule"
            icon="fa-solid fa-calendar"
        />

        <div class="bg-white rounded-none shadow-sm border border-slate-200 px-6 sm:px-12 lg:px-20 py-8 mb-6 print:hidden">
            <div id="calendar" class="w-full mx-auto"></div>
        </div>
    </main>
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
    </style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {

    const calendarDiv = document.getElementById('calendar');

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
        }
    });

    calendar.render();
});
</script>
@endpush

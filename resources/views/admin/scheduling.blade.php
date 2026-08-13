@extends('layouts.app')

@section('title', 'Scheduling - PSARECO')

@section('content')
    <div class="d-flex">
        <div class="main-content" style="margin-left: 250px; flex: 1; padding: 30px;">
            @include('components.breadcrumb', [
                'title' => 'Machinery Scheduling',
                'icon' => 'fas fa-calendar-alt'
            ])

            {{-- <div id="overdueSection" style="display: none;" class="card mb-4 overdue-section">
                <div class="card-header" style="background: #e76f51; color: white;">
                    <i class="fas fa-exclamation-triangle"></i> Overdue Equipment
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead><tr><th>Machine</th><th>Farmer</th><th>Start Date</th><th>Return Date</th><th>Overdue Days</th><th>Action</th></tr></thead>
                            <tbody id="overdueTable"></tbody>
                        </table>
                    </div>
                </div>
            </div> --}}

            <div class="card mb-4" id="bookingForm">
                <div class="card-header"><i class="fas fa-calendar-plus"></i> Request Machine Booking</div>
                <div class="card-body">
                    <div class="row g-2">
                        <div class="col-md-4"><select id="bookingMachine" class="form-select" onchange="updateDailyRate()"><option value="">Select Machine</option></select></div>
                        <div class="col-md-3"><input type="date" id="bookingDate" class="form-control" min=""></div>
                        <div class="col-md-2"><input type="number" id="bookingDays" class="form-control" placeholder="Days" value="1" min="1"></div>
                        <div class="col-md-2"><input type="text" id="totalAmount" class="form-control" placeholder="Total" readonly></div>
                        <div class="col-md-1"><button class="btn btn-primary w-100" onclick="submitBooking()"><i class="fas fa-paper-plane"></i></button></div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span><i class="fas fa-tractor"></i> Machinery Fleet</span>
                    <span class="badge bg-secondary" id="totalMachinesCount">0</span>
                </div>
                <div class="card-body">
                    <div class="search-box mb-4" style="position: relative; display: flex; align-items: center;">
                        <i class="fas fa-search" style="position: absolute; left: 12px; color: #6c757d;"></i>
                        <input type="text" id="searchMachinery" class="form-control" placeholder="Search machinery..." onkeyup="filterMachinery()" style="padding-left: 35px;">
                    </div>
                    <div class="machinery-scroll-container"><div class="machinery-grid" id="machineryList">Loading...</div></div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    {{-- <script>
        document.addEventListener('DOMContentLoaded', function() {
            const today = new Date().toISOString().split('T')[0];
            document.getElementById('bookingDate').setAttribute('min', today);
            loadMachineryData();
            loadOverdueBookings();
        });
        function renderMachineryList() {
            const machines = getMachines();
            const container = document.getElementById('machineryList');
            const select = document.getElementById('bookingMachine');
            document.getElementById('totalMachinesCount').textContent = machines.length;

            container.innerHTML = machines.map(machine => `
                <div class="machinery-card">
                    <div class="machinery-img-container">
                        <img class="machinery-card-img" src="${machine.image || 'https://placehold.co/220x160/f5f5f5/2d6a4f?text=' + encodeURIComponent(machine.name)}" alt="${machine.name}">
                    </div>
                    <div class="card-body-custom">
                        <div class="machine-title">${machine.name}</div>
                        <div class="machine-model">${machine.model || 'Model N/A'}</div>
                        <div class="machine-rate">₱${Number(machine.dailyRate || 0).toLocaleString()} <small>/ day</small></div>
                        <span class="machine-status-badge ${machine.status === 'Operational' ? 'status-operational' : machine.status === 'Under Maintenance' ? 'status-maintenance' : 'status-outofservice'}">${machine.status}</span>
                        <div class="machine-meta"><span>Units: ${machine.totalUnits || 1}</span><span>Booked: ${machine.bookedUnits || 0}</span></div>
                    </div>
                </div>
            `).join('');

            select.innerHTML = '<option value="">Select Machine</option>' + machines.map(machine => `<option value="${machine.id}">${machine.name} - ₱${Number(machine.dailyRate || 0).toLocaleString()}</option>`).join('');
        }
    </script> --}}
@endpush

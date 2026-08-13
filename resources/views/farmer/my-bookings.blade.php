@extends('layouts.app')

@section('title', 'My Bookings - PSARECO')

@section('content')
    <div class="d-flex">
        <div class="sidebar" id="sidebar"></div>

        <div class="main-content">
            <h2 class="mb-4"><i class="fas fa-calendar-alt"></i> My Booking History</h2>

            <div id="overdueBanner" style="display: none;" class="overdue-alert">
                <i class="fas fa-exclamation-triangle fa-2x me-3" style="color: #e76f51;"></i>
                <div>
                    <h5 class="mb-1"><i class="fas fa-clock"></i> Overdue Equipment Return</h5>
                    <p id="overdueMessage" class="mb-0">You have overdue machinery. Please return immediately to avoid penalties.</p>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <i class="fas fa-list"></i> My Booking Requests
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Request Date</th>
                                    <th>Machine</th>
                                    <th>Start Date</th>
                                    <th>Duration</th>
                                    <th>Return Date</th>
                                    <th>Status / Return Status</th>
                                    <th>Total Amount</th>
                                </tr>
                            </thead>
                            <tbody id="bookingsTable"></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function loadMyBookingsPage() {
            if (!requireAuth()) return;
            if (typeof loadSidebar === 'function') loadSidebar();

            const user = getCurrentUser();
            const allBookings = getBookings();
            const myBookings = allBookings.filter(b => b.farmerId === user.id);

            const overdueList = getFarmerOverdueBookings(user.id);
            if (overdueList.length > 0) {
                const overdueBanner = document.getElementById('overdueBanner');
                const overdueMessage = document.getElementById('overdueMessage');
                const machineNames = overdueList.map(b => b.machineName).join(', ');
                overdueMessage.innerHTML = `<strong>You have ${overdueList.length} overdue booking(s):</strong> ${machineNames}. Please return the equipment immediately.`;
                overdueBanner.style.display = 'flex';
            } else {
                document.getElementById('overdueBanner').style.display = 'none';
            }

            const table = document.getElementById('bookingsTable');
            if (myBookings.length === 0) {
                table.innerHTML = '<tr><td colspan="7" class="text-center text-muted py-4"><i class="fas fa-calendar-times"></i> You have no bookings yet</td></tr>';
                return;
            }

            table.innerHTML = myBookings.sort((a, b) => new Date(b.createdAt) - new Date(a.createdAt)).map(b => {
                let statusClass = '';
                let statusIcon = '';
                let returnInfo = '';
                let rowClass = '';

                switch (b.status) {
                    case 'Pending': statusClass = 'badge-warning'; statusIcon = '<i class="fas fa-clock"></i> '; break;
                    case 'Confirmed': statusClass = 'badge-success'; statusIcon = '<i class="fas fa-check-circle"></i> '; break;
                    case 'Completed': statusClass = 'badge-info'; statusIcon = '<i class="fas fa-check-double"></i> '; break;
                    case 'Cancelled': statusClass = 'badge-danger'; statusIcon = '<i class="fas fa-times-circle"></i> '; break;
                    default: statusClass = 'badge-secondary'; statusIcon = '<i class="fas fa-question-circle"></i> '; break;
                }

                if (b.status === 'Confirmed') {
                    const returnStatus = getReturnStatus(b);
                    returnInfo = `<div class="return-info mt-1 ${returnStatus.class}">${returnStatus.text}</div>`;
                    if (returnStatus.overdue) rowClass = 'table-danger-row';
                } else if (b.status === 'Completed') {
                    returnInfo = '<div class="text-success"><i class="fas fa-check-circle"></i> Returned</div>';
                }

                const returnDateDisplay = b.returnDate ? new Date(b.returnDate).toLocaleDateString() : '—';
                return `
                    <tr class="${rowClass}">
                        <td>${new Date(b.createdAt).toLocaleDateString()}</td>
                        <td><strong>${b.machineName}</strong></td>
                        <td>${new Date(b.date).toLocaleDateString()}</td>
                        <td>${b.days} day(s)</td>
                        <td>${returnDateDisplay}</td>
                        <td>
                            <span class="${statusClass}">${statusIcon}${b.status}</span>
                            ${returnInfo}
                        </td>
                        <td>₱${(b.totalAmount || 0).toLocaleString()}</td>
                    </tr>
                `;
            }).join('');
        }

        document.addEventListener('DOMContentLoaded', loadMyBookingsPage);
    </script>
@endpush

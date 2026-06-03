{{-- resources/views/admin/vendor-availability/index.blade.php --}}
@extends('admin.layouts.app')

@section('title', 'Vendor Availability - Vendor Panel')
@section('page-title', 'My Availability Calendar')

@section('content')
    <div class="row">
        <!-- Calendar Overview -->
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-calendar-check me-2"></i>
                        Availability Calendar
                    </h5>
                    <div class="btn-group">
                        <button type="button" class="btn btn-outline-secondary btn-sm" onclick="changeView('month')">
                            <i class="fas fa-calendar-alt me-1"></i>Month
                        </button>
                        <button type="button" class="btn btn-outline-secondary btn-sm" onclick="changeView('week')">
                            <i class="fas fa-calendar-week me-1"></i>Week
                        </button>
                        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                            data-bs-target="#availabilityModal" onclick="resetForm()">
                            <i class="fas fa-plus me-1"></i>Set Date
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        Manage your availability calendar. Mark dates as unavailable when you're booked or not working.
                        Customers can see your availability when booking your services.
                    </div>

                    <!-- Calendar will be rendered here -->
                    <div id="calendar-container">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <button class="btn btn-outline-primary" onclick="previousMonth()">
                                <i class="fas fa-chevron-left"></i> Previous
                            </button>
                            <h4 id="currentMonthYear"></h4>
                            <button class="btn btn-outline-primary" onclick="nextMonth()">
                                Next <i class="fas fa-chevron-right"></i>
                            </button>
                        </div>
                        <div id="calendar" class="calendar-grid"></div>
                    </div>

                    <!-- Legend -->
                    <div class="mt-3">
                        <div class="row">
                            <div class="col-md-6">
                                <span class="badge bg-success me-2">
                                    <i class="fas fa-check me-1"></i>Available
                                </span>
                                <span class="badge bg-danger me-2">
                                    <i class="fas fa-times me-1"></i>Unavailable
                                </span>
                                <span class="badge bg-secondary me-2">
                                    <i class="fas fa-calendar me-1"></i>Not Set
                                </span>
                            </div>
                            <div class="col-md-6 text-end">
                                <small class="text-muted">Click on dates to quickly set availability</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Availability List -->
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-list me-2"></i>
                        Recent Settings
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="availabilityTable" class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-chart-pie me-2"></i>
                        Quick Stats
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6">
                            <div class="border-end">
                                <h4 class="text-success mb-0" id="availableDays">0</h4>
                                <small class="text-muted">Available Days</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <h4 class="text-danger mb-0" id="unavailableDays">0</h4>
                            <small class="text-muted">Unavailable Days</small>
                        </div>
                    </div>
                    <hr>
                    <div class="text-center">
                        <h5 class="text-primary mb-0" id="nextAvailable">-</h5>
                        <small class="text-muted">Next Available Date</small>
                    </div>
                </div>
            </div>

            <!-- Bulk Actions -->
            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-magic me-2"></i>
                        Bulk Actions
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Date Range:</label>
                        <div class="row">
                            <div class="col-6">
                                <input type="date" class="form-control form-control-sm" id="bulkStartDate"
                                    min="{{ date('Y-m-d') }}">
                            </div>
                            <div class="col-6">
                                <input type="date" class="form-control form-control-sm" id="bulkEndDate"
                                    min="{{ date('Y-m-d') }}">
                            </div>
                        </div>
                    </div>
                    <div class="d-grid gap-2">
                        <button class="btn btn-success btn-sm" onclick="bulkSetAvailable()">
                            <i class="fas fa-check me-1"></i>Set Available
                        </button>
                        <button class="btn btn-danger btn-sm" onclick="bulkSetUnavailable()">
                            <i class="fas fa-times me-1"></i>Set Unavailable
                        </button>
                        <button class="btn btn-warning btn-sm" onclick="bulkSetWeekends()">
                            <i class="fas fa-calendar-times me-1"></i>Block Weekends
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Availability Modal -->
    <div class="modal fade" id="availabilityModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">Set Availability</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="availabilityForm">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="date" class="form-label">Date *</label>
                            <input type="date" class="form-control" id="date" name="date" required
                                min="{{ date('Y-m-d') }}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Availability Status *</label>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="is_available" id="available"
                                    value="1" checked>
                                <label class="form-check-label text-success" for="available">
                                    <i class="fas fa-check-circle me-1"></i>Available for bookings
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="is_available" id="unavailable"
                                    value="0">
                                <label class="form-check-label text-danger" for="unavailable">
                                    <i class="fas fa-times-circle me-1"></i>Unavailable / Blocked
                                </label>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="notes" class="form-label">Notes (Optional)</label>
                            <textarea class="form-control" id="notes" name="notes" rows="3"
                                placeholder="Reason for unavailability, special instructions, etc."></textarea>
                            <small class="text-muted">These notes are for your reference only and won't be visible to
                                customers.</small>
                        </div>

                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <strong>Note:</strong> Setting a date as unavailable will prevent customers from booking your
                            services on that date.
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary" id="submitBtn">
                            <i class="fas fa-save me-2"></i>Save Availability
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Quick Set Modal -->
    <div class="modal fade" id="quickSetModal" tabindex="-1">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Quick Set: <span id="quickDate"></span></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center">
                    <p>Set availability for this date:</p>
                    <div class="d-grid gap-2">
                        <button class="btn btn-success" onclick="quickSetAvailability(1)">
                            <i class="fas fa-check me-2"></i>Available
                        </button>
                        <button class="btn btn-danger" onclick="quickSetAvailability(0)">
                            <i class="fas fa-times me-2"></i>Unavailable
                        </button>
                        <button class="btn btn-secondary" onclick="removeAvailability()">
                            <i class="fas fa-trash me-2"></i>Remove Setting
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        let availabilityTable;
        let editingAvailabilityId = null;
        let currentDate = new Date();
        let currentView = 'month';
        let availabilityData = {};
        let selectedQuickDate = null;

        $(document).ready(function() {
            // Initialize DataTable
            availabilityTable = $('#availabilityTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('admin.vendor-availability.data') }}',
                columns: [{
                        data: 'date',
                        name: 'date',
                        width: '40%'
                    },
                    {
                        data: 'is_available',
                        name: 'is_available',
                        width: '35%',
                        render: function(data) {
                            return data ?
                                '<span class="badge bg-success"><i class="fas fa-check"></i></span>' :
                                '<span class="badge bg-danger"><i class="fas fa-times"></i></span>';
                        }
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                        width: '25%'
                    }
                ],
                order: [
                    [0, 'desc']
                ],
                pageLength: 10,
                searching: false,
                lengthChange: false,
                info: false
            });

            // Load initial calendar
            loadCalendar();
            loadStats();

            // Form submission
            $('#availabilityForm').on('submit', function(e) {
                e.preventDefault();

                const formData = new FormData(this);
                const url = editingAvailabilityId ?
                    `{{ route('admin.vendor-availability.update', ':id') }}`.replace(':id',
                        editingAvailabilityId) :
                    '{{ route('admin.vendor-availability.store') }}';

                if (editingAvailabilityId) {
                    formData.append('_method', 'PUT');
                }

                $.ajax({
                    url: url,
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        if (response.success) {
                            toastr.success(response.message);
                            $('#availabilityModal').modal('hide');
                            availabilityTable.ajax.reload();
                            loadCalendar();
                            loadStats();
                            resetForm();
                        }
                    },
                    error: function(xhr) {
                        if (xhr.status === 422) {
                            const errors = xhr.responseJSON.errors;
                            for (let field in errors) {
                                toastr.error(errors[field][0]);
                            }
                        } else {
                            toastr.error('An error occurred. Please try again.');
                        }
                    }
                });
            });

            // Set minimum date for bulk actions
            $('#bulkStartDate, #bulkEndDate').on('change', function() {
                const startDate = $('#bulkStartDate').val();
                const endDate = $('#bulkEndDate').val();

                if (startDate && !endDate) {
                    $('#bulkEndDate').attr('min', startDate);
                } else if (endDate && !startDate) {
                    $('#bulkStartDate').attr('max', endDate);
                }
            });
        });

        function loadCalendar() {
            const year = currentDate.getFullYear();
            const month = currentDate.getMonth();

            // Update header
            const monthNames = ['January', 'February', 'March', 'April', 'May', 'June',
                'July', 'August', 'September', 'October', 'November', 'December'
            ];
            $('#currentMonthYear').text(`${monthNames[month]} ${year}`);

            // Load availability data for this month
            $.get('{{ route('admin.vendor-availability.data') }}', {
                start: new Date(year, month, 1).toISOString().split('T')[0],
                end: new Date(year, month + 1, 0).toISOString().split('T')[0]
            }).done(function(response) {
                availabilityData = {};
                if (response.data) {
                    response.data.forEach(item => {
                        availabilityData[item.date] = item.is_available;
                    });
                }
                renderCalendar();
            });
        }

        function renderCalendar() {
            const year = currentDate.getFullYear();
            const month = currentDate.getMonth();
            const today = new Date();

            const firstDay = new Date(year, month, 1);
            const lastDay = new Date(year, month + 1, 0);
            const startDate = new Date(firstDay);
            startDate.setDate(startDate.getDate() - firstDay.getDay());

            let calendarHTML = '<div class="row text-center mb-2">';
            const dayNames = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
            dayNames.forEach(day => {
                calendarHTML += `<div class="col fw-bold text-muted">${day}</div>`;
            });
            calendarHTML += '</div>';

            let currentWeekDate = new Date(startDate);

            for (let week = 0; week < 6; week++) {
                calendarHTML += '<div class="row mb-1">';

                for (let day = 0; day < 7; day++) {
                    const dateStr = currentWeekDate.toISOString().split('T')[0];
                    const isCurrentMonth = currentWeekDate.getMonth() === month;
                    const isToday = currentWeekDate.toDateString() === today.toDateString();
                    const isPast = currentWeekDate < today;

                    let classes = 'col calendar-day p-2 border';
                    let content = currentWeekDate.getDate();
                    let statusIcon = '';

                    if (!isCurrentMonth) {
                        classes += ' text-muted bg-light';
                    } else if (isPast) {
                        classes += ' text-muted';
                    } else {
                        classes += ' cursor-pointer';
                    }

                    if (isToday) {
                        classes += ' border-primary bg-primary bg-opacity-10';
                    }

                    // Add availability status
                    if (isCurrentMonth && !isPast) {
                        if (availabilityData.hasOwnProperty(dateStr)) {
                            if (availabilityData[dateStr]) {
                                statusIcon = '<i class="fas fa-check text-success"></i>';
                                classes += ' bg-success bg-opacity-10';
                            } else {
                                statusIcon = '<i class="fas fa-times text-danger"></i>';
                                classes += ' bg-danger bg-opacity-10';
                            }
                        }

                        if (!isPast) {
                            classes += ` onclick-date`;
                        }
                    }

                    calendarHTML += `
                <div class="${classes}" data-date="${dateStr}" onclick="${!isPast && isCurrentMonth ? 'openQuickSet(\'' + dateStr + '\')' : ''}">
                    <div class="d-flex justify-content-between align-items-center">
                        <span>${content}</span>
                        <span>${statusIcon}</span>
                    </div>
                </div>
            `;

                    currentWeekDate.setDate(currentWeekDate.getDate() + 1);
                }

                calendarHTML += '</div>';
            }

            $('#calendar').html(calendarHTML);
        }

        function previousMonth() {
            currentDate.setMonth(currentDate.getMonth() - 1);
            loadCalendar();
        }

        function nextMonth() {
            currentDate.setMonth(currentDate.getMonth() + 1);
            loadCalendar();
        }

        function openQuickSet(date) {
            selectedQuickDate = date;
            $('#quickDate').text(new Date(date).toLocaleDateString());
            $('#quickSetModal').modal('show');
        }

        function quickSetAvailability(isAvailable) {
            if (!selectedQuickDate) return;

            $.ajax({
                url: '{{ route('admin.vendor-availability.store') }}',
                method: 'POST',
                data: {
                    date: selectedQuickDate,
                    is_available: isAvailable,
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.success) {
                        toastr.success(response.message);
                        $('#quickSetModal').modal('hide');
                        availabilityTable.ajax.reload();
                        loadCalendar();
                        loadStats();
                    }
                },
                error: function(xhr) {
                    if (xhr.status === 422) {
                        const errors = xhr.responseJSON.errors;
                        for (let field in errors) {
                            toastr.error(errors[field][0]);
                        }
                    } else {
                        toastr.error('An error occurred. Please try again.');
                    }
                }
            });
        }

        function removeAvailability() {
            if (!selectedQuickDate) return;

            // Find and delete the availability setting for this date
            toastr.info('Remove availability functionality would be implemented here');
            $('#quickSetModal').modal('hide');
        }

        function loadStats() {
            // Load statistics
            $.get('{{ route('admin.vendor-availability.data') }}').done(function(response) {
                let available = 0;
                let unavailable = 0;
                let nextAvailable = null;

                if (response.data) {
                    const today = new Date().toISOString().split('T')[0];

                    response.data.forEach(item => {
                        if (item.date >= today) {
                            if (item.is_available) {
                                available++;
                                if (!nextAvailable || item.date < nextAvailable) {
                                    nextAvailable = item.date;
                                }
                            } else {
                                unavailable++;
                            }
                        }
                    });
                }

                $('#availableDays').text(available);
                $('#unavailableDays').text(unavailable);
                $('#nextAvailable').text(nextAvailable ? new Date(nextAvailable).toLocaleDateString() : 'Not set');
            });
        }

        function resetForm() {
            editingAvailabilityId = null;
            $('#availabilityForm')[0].reset();
            $('#modalTitle').text('Set Availability');
            $('#submitBtn').html('<i class="fas fa-save me-2"></i>Save Availability');
            $('input[name="is_available"][value="1"]').prop('checked', true);
        }

        function editAvailability(id) {
            editingAvailabilityId = id;

            $.get(`{{ route('admin.vendor-availability.show', ':id') }}`.replace(':id', id))
                .done(function(availability) {
                    $('#date').val(availability.date);
                    $('#notes').val(availability.notes);
                    $(`input[name="is_available"][value="${availability.is_available}"]`).prop('checked', true);

                    $('#modalTitle').text('Edit Availability');
                    $('#submitBtn').html('<i class="fas fa-save me-2"></i>Update Availability');

                    $('#availabilityModal').modal('show');
                })
                .fail(function() {
                    toastr.error('Failed to load availability details');
                });
        }

        function deleteAvailability(id) {
            if (confirm('Are you sure you want to delete this availability record?')) {
                $.ajax({
                    url: `{{ route('admin.vendor-availability.destroy', ':id') }}`.replace(':id', id),
                    method: 'DELETE',
                    success: function(response) {
                        if (response.success) {
                            toastr.success(response.message);
                            availabilityTable.ajax.reload();
                            loadCalendar();
                            loadStats();
                        }
                    },
                    error: function() {
                        toastr.error('Failed to delete availability record');
                    }
                });
            }
        }

        // Bulk Actions
        function bulkSetAvailable() {
            const startDate = $('#bulkStartDate').val();
            const endDate = $('#bulkEndDate').val();

            if (!startDate || !endDate) {
                toastr.warning('Please select both start and end dates');
                return;
            }

            if (confirm(`Set all dates from ${startDate} to ${endDate} as available?`)) {
                // Implementation for bulk set available
                toastr.info('Bulk set available functionality would be implemented here');
            }
        }

        function bulkSetUnavailable() {
            const startDate = $('#bulkStartDate').val();
            const endDate = $('#bulkEndDate').val();

            if (!startDate || !endDate) {
                toastr.warning('Please select both start and end dates');
                return;
            }

            if (confirm(`Set all dates from ${startDate} to ${endDate} as unavailable?`)) {
                // Implementation for bulk set unavailable
                toastr.info('Bulk set unavailable functionality would be implemented here');
            }
        }

        function bulkSetWeekends() {
            const startDate = $('#bulkStartDate').val();
            const endDate = $('#bulkEndDate').val();

            if (!startDate || !endDate) {
                toastr.warning('Please select both start and end dates');
                return;
            }

            if (confirm(`Set all weekends from ${startDate} to ${endDate} as unavailable?`)) {
                // Implementation for bulk set weekends
                toastr.info('Bulk set weekends functionality would be implemented here');
            }
        }

        function changeView(view) {
            currentView = view;
            // Update active button
            $('.btn-group .btn').removeClass('btn-outline-secondary').addClass('btn-outline-secondary');
            $(event.target).removeClass('btn-outline-secondary').addClass('btn-secondary');

            toastr.info(`Switched to ${view} view`);
            // Implementation for different calendar views would go here
        }
    </script>
@endpush

@push('styles')
    <style>
        .calendar-grid {
            font-size: 0.9rem;
        }

        .calendar-day {
            min-height: 50px;
            transition: all 0.2s ease;
            user-select: none;
        }

        .calendar-day.cursor-pointer:hover {
            background-color: rgba(13, 110, 253, 0.1) !important;
            transform: scale(1.02);
        }

        .calendar-day.onclick-date {
            cursor: pointer;
        }

        .border-primary {
            border-color: #0d6efd !important;
        }

        .bg-success.bg-opacity-10 {
            background-color: rgba(25, 135, 84, 0.1) !important;
        }

        .bg-danger.bg-opacity-10 {
            background-color: rgba(220, 53, 69, 0.1) !important;
        }

        .bg-primary.bg-opacity-10 {
            background-color: rgba(13, 110, 253, 0.1) !important;
        }

        #availabilityTable {
            font-size: 0.85rem;
        }

        .card {
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        }

        .btn-group .btn.active {
            background-color: #0d6efd;
            border-color: #0d6efd;
            color: white;
        }
    </style>
@endpush

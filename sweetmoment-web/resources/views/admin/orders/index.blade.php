@extends('admin.layouts.app')

@section('title', 'Orders Management - Admin Panel')
@section('page-title', 'Orders Management')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-shopping-cart me-2"></i>
                        Orders List
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="ordersTable" class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Customer</th>
                                    <th>Package</th>
                                    <th>Total Price</th>
                                    <th>Status</th>
                                    <th>Event Date</th>
                                    <th>Created</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Order Modal -->
    <div class="modal fade" id="orderModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Order Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="orderDetails">
                    <!-- Order details will be loaded here -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Order Status Modal -->
    <div class="modal fade" id="editOrderModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Update Order Status</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="orderForm">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="status" class="form-label">Order Status *</label>
                            <select class="form-select" id="status" name="status" required>
                                <option value="Unpaid">Unpaid</option>
                                <option value="Paid">Paid</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="payment_status" class="form-label">Payment Status</label>
                            <input type="text" class="form-control" id="payment_status" name="payment_status">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Update Order
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        let ordersTable;
        let editingOrderId = null;

        $(document).ready(function() {
            ordersTable = $('#ordersTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('admin.orders.data') }}',
                columns: [{
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'user_id',
                        name: 'user_id'
                    },
                    {
                        data: 'vendor_package_id',
                        name: 'vendor_package_id'
                    },
                    {
                        data: 'total_price',
                        name: 'total_price'
                    },
                    {
                        data: 'status',
                        name: 'status'
                    },
                    {
                        data: 'event_date',
                        name: 'event_date'
                    },
                    {
                        data: 'created_at',
                        name: 'created_at'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    }
                ],
                order: [
                    [0, 'desc']
                ]
            });

            $('#orderForm').on('submit', function(e) {
                e.preventDefault();
                const formData = new FormData(this);
                formData.append('_method', 'PUT');

                $.ajax({
                    url: `{{ route('admin.orders.update', ':id') }}`.replace(':id', editingOrderId),
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        if (response.success) {
                            toastr.success(response.message);
                            $('#editOrderModal').modal('hide');
                            ordersTable.ajax.reload();
                        }
                    },
                    error: function(xhr) {
                        toastr.error('Failed to update order');
                    }
                });
            });
        });

        function viewOrder(id) {
            $.get(`{{ route('admin.orders.show', ':id') }}`.replace(':id', id))
                .done(function(order) {
                    const details = `
                <div class="row">
                    <div class="col-md-6">
                        <h6>Customer Information</h6>
                        <table class="table table-borderless table-sm">
                            <tr><th>Name:</th><td>${order.name}</td></tr>
                            <tr><th>Email:</th><td>${order.user ? order.user.email : '-'}</td></tr>
                            <tr><th>Phone:</th><td>${order.phone}</td></tr>
                            <tr><th>Address:</th><td>${order.address}</td></tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h6>Order Information</h6>
                        <table class="table table-borderless table-sm">
                            <tr><th>Package:</th><td>${order.vendor_package ? order.vendor_package.name : '-'}</td></tr>
                            <tr><th>Vendor:</th><td>${order.vendor_package && order.vendor_package.vendor_profile ? order.vendor_package.vendor_profile.business_name : '-'}</td></tr>
                            <tr><th>Quantity:</th><td>${order.qty}</td></tr>
                            <tr><th>Total Price:</th><td>Rp ${order.total_price.toLocaleString()}</td></tr>
                            <tr><th>Status:</th><td><span class="badge bg-${order.status === 'Paid' ? 'success' : 'warning'}">${order.status}</span></td></tr>
                            <tr><th>Event Date:</th><td>${order.event_date || '-'}</td></tr>
                        </table>
                    </div>
                </div>
                ${order.notes ? `<div class="mt-3"><h6>Notes:</h6><p>${order.notes}</p></div>` : ''}
                ${order.transaction_id ? `<div class="mt-3"><h6>Transaction ID:</h6><p>${order.transaction_id}</p></div>` : ''}
            `;
                    $('#orderDetails').html(details);
                    $('#orderModal').modal('show');
                });
        }

        function editOrder(id) {
            editingOrderId = id;
            $.get(`{{ route('admin.orders.show', ':id') }}`.replace(':id', id))
                .done(function(order) {
                    $('#status').val(order.status);
                    $('#payment_status').val(order.payment_status);
                    $('#editOrderModal').modal('show');
                });
        }

        function deleteOrder(id) {
            if (confirm('Are you sure you want to delete this order?')) {
                $.ajax({
                    url: `{{ route('admin.orders.destroy', ':id') }}`.replace(':id', id),
                    method: 'DELETE',
                    success: function(response) {
                        if (response.success) {
                            toastr.success(response.message);
                            ordersTable.ajax.reload();
                        }
                    }
                });
            }
        }
    </script>
@endpush

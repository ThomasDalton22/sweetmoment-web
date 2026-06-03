{{-- resources/views/admin/vendor-packages/index.blade.php --}}
@extends('admin.layouts.app')

@section('title', 'Vendor Packages - ' . (Auth::user()->role === 'admin' ? 'Admin Panel' : 'Vendor Panel'))
@section('page-title', Auth::user()->role === 'admin' ? 'All Vendor Packages' : 'My Packages')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-box me-2"></i>
                        {{ Auth::user()->role === 'admin' ? 'All Vendor Packages' : 'My Packages' }}
                    </h5>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#packageModal"
                        onclick="resetForm()">
                        <i class="fas fa-plus me-2"></i>Add New Package
                    </button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="packagesTable" class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    @if (Auth::user()->role === 'admin')
                                        <th>Vendor</th>
                                    @endif
                                    <th>Package Name</th>
                                    <th>Price</th>
                                    <th>Status</th>
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

    <!-- Package Modal -->
    <div class="modal fade" id="packageModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">Add New Package</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="packageForm">
                    <div class="modal-body">
                        @if (Auth::user()->role === 'admin')
                            <div class="mb-3">
                                <label for="vendor_profile_id" class="form-label">Vendor *</label>
                                <select class="form-select" id="vendor_profile_id" name="vendor_profile_id" required>
                                    <option value="">Select Vendor</option>
                                    <!-- Will be populated via AJAX -->
                                </select>
                            </div>
                        @endif

                        <div class="row">
                            <div class="col-md-8 mb-3">
                                <label for="name" class="form-label">Package Name *</label>
                                <input type="text" class="form-control" id="name" name="name" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="price" class="form-label">Price *</label>
                                <input type="number" class="form-control" id="price" name="price" min="0"
                                    step="0.01" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="features" class="form-label">Features</label>
                            <textarea class="form-control" id="features" name="features" rows="4"
                                placeholder="List package features (one per line)"></textarea>
                        </div>

                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1"
                                checked>
                            <label class="form-check-label" for="is_active">Active Package</label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary" id="submitBtn">
                            <i class="fas fa-save me-2"></i>Save Package
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- View Package Modal -->
    <div class="modal fade" id="viewPackageModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Package Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="packageDetails">
                    <!-- Package details will be loaded here -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        let packagesTable;
        let editingPackageId = null;

        $(document).ready(function() {
            // Initialize DataTable
            const columns = [{
                    data: 'id',
                    name: 'id'
                },
                @if (Auth::user()->role === 'admin')
                    {
                        data: 'vendor_profile_id',
                        name: 'vendor_profile_id'
                    },
                @endif {
                    data: 'name',
                    name: 'name'
                },
                {
                    data: 'price',
                    name: 'price'
                },
                {
                    data: 'is_active',
                    name: 'is_active'
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
            ];

            packagesTable = $('#packagesTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('admin.vendor-packages.data') }}',
                columns: columns,
                order: [
                    [0, 'desc']
                ]
            });

            // Load vendors for admin
            @if (Auth::user()->role === 'admin')
                loadVendors();
            @endif

            // Form submission
            $('#packageForm').on('submit', function(e) {
                e.preventDefault();

                const formData = new FormData(this);
                const url = editingPackageId ?
                    `{{ route('admin.vendor-packages.update', ':id') }}`.replace(':id', editingPackageId) :
                    '{{ route('admin.vendor-packages.store') }}';

                if (editingPackageId) {
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
                            $('#packageModal').modal('hide');
                            packagesTable.ajax.reload();
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
        });

        @if (Auth::user()->role === 'admin')
            function loadVendors() {
                // Load vendors for admin dropdown
                $.get('{{ route('admin.vendor-profile.data') }}')
                    .done(function(data) {
                        const select = $('#vendor_profile_id');
                        select.empty().append('<option value="">Select Vendor</option>');
                        if (data.data && Array.isArray(data.data)) {
                            data.data.forEach(function(vendor) {
                                select.append(`<option value="${vendor.id}">${vendor.business_name}</option>`);
                            });
                        }
                    })
                    .fail(function() {
                        toastr.error('Failed to load vendors');
                    });
            }
        @endif

        function resetForm() {
            editingPackageId = null;
            $('#packageForm')[0].reset();
            $('#modalTitle').text('Add New Package');
            $('#submitBtn').html('<i class="fas fa-save me-2"></i>Save Package');
            $('#is_active').prop('checked', true);
        }

        function viewPackage(id) {
            $.get(`{{ route('admin.vendor-packages.show', ':id') }}`.replace(':id', id))
                .done(function(package) {
                    const features = package.features ?
                        package.features.split('\n').map(f => f.trim()).filter(f => f).map(f => `<li>${f}</li>`).join(
                            '') :
                        '<li>No features listed</li>';

                    const details = `
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr><th>Package Name:</th><td>${package.name}</td></tr>
                            <tr><th>Price:</th><td>Rp ${parseInt(package.price).toLocaleString()}</td></tr>
                            <tr><th>Status:</th><td>${package.is_active ? '<span class="badge bg-success">Active</span>' : '<span class="badge bg-danger">Inactive</span>'}</td></tr>
                            <tr><th>Created:</th><td>${new Date(package.created_at).toLocaleDateString()}</td></tr>
                            ${package.vendor_profile ? `<tr><th>Vendor:</th><td>${package.vendor_profile.business_name}</td></tr>` : ''}
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h6>Description:</h6>
                        <p>${package.description || 'No description provided'}</p>
                        <h6>Features:</h6>
                        <ul>${features}</ul>
                    </div>
                </div>
            `;
                    $('#packageDetails').html(details);
                    $('#viewPackageModal').modal('show');
                })
                .fail(function() {
                    toastr.error('Failed to load package details');
                });
        }

        function editPackage(id) {
            editingPackageId = id;

            $.get(`{{ route('admin.vendor-packages.show', ':id') }}`.replace(':id', id))
                .done(function(package) {
                    $('#name').val(package.name);
                    $('#description').val(package.description);
                    $('#price').val(package.price);
                    $('#features').val(package.features);
                    $('#is_active').prop('checked', package.is_active);

                    @if (Auth::user()->role === 'admin')
                        $('#vendor_profile_id').val(package.vendor_profile_id);
                    @endif

                    $('#modalTitle').text('Edit Package');
                    $('#submitBtn').html('<i class="fas fa-save me-2"></i>Update Package');

                    $('#packageModal').modal('show');
                })
                .fail(function() {
                    toastr.error('Failed to load package details');
                });
        }

        function deletePackage(id) {
            if (confirm('Are you sure you want to delete this package?')) {
                $.ajax({
                    url: `{{ route('admin.vendor-packages.destroy', ':id') }}`.replace(':id', id),
                    method: 'DELETE',
                    success: function(response) {
                        if (response.success) {
                            toastr.success(response.message);
                            packagesTable.ajax.reload();
                        }
                    },
                    error: function() {
                        toastr.error('Failed to delete package');
                    }
                });
            }
        }
    </script>
@endpush

@extends('admin.layouts.app')

@section('title', 'Vendor Profiles - Admin Panel')
@section('page-title', 'Vendor Profiles Management')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-store me-2"></i>
                        All Vendor Profiles
                    </h5>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#profileModal"
                        onclick="resetForm()">
                        <i class="fas fa-plus me-2"></i>Add New Profile
                    </button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="profilesTable" class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>User</th>
                                    <th>Business Name</th>
                                    <th>Category</th>
                                    <th>Location</th>
                                    <th>Rating</th>
                                    <th>Status</th>
                                    <th>Verified</th>
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

    <!-- Profile Modal -->
    <div class="modal fade" id="profileModal" tabindex="-1">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">Add New Vendor Profile</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="profileForm">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="user_id" class="form-label">User *</label>
                                <select class="form-select" id="user_id" name="user_id" required>
                                    <option value="">Select User</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="vendor_category_id" class="form-label">Category *</label>
                                <select class="form-select" id="vendor_category_id" name="vendor_category_id" required>
                                    <option value="">Select Category</option>
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-8 mb-3">
                                <label for="business_name" class="form-label">Business Name *</label>
                                <input type="text" class="form-control" id="business_name" name="business_name" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="location" class="form-label">Location</label>
                                <input type="text" class="form-control" id="location" name="location">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <label for="price_range_min" class="form-label">Min Price</label>
                                <input type="number" class="form-control" id="price_range_min" name="price_range_min"
                                    min="0" step="0.01">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="price_range_max" class="form-label">Max Price</label>
                                <input type="number" class="form-control" id="price_range_max" name="price_range_max"
                                    min="0" step="0.01">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="phone" class="form-label">Phone</label>
                                <input type="text" class="form-control" id="phone" name="phone">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="whatsapp" class="form-label">WhatsApp</label>
                                <input type="text" class="form-control" id="whatsapp" name="whatsapp">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="instagram" class="form-label">Instagram</label>
                                <input type="text" class="form-control" id="instagram" name="instagram"
                                    placeholder="@username">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="website" class="form-label">Website</label>
                                <input type="url" class="form-control" id="website" name="website"
                                    placeholder="https://example.com">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="status" class="form-label">Status *</label>
                                <select class="form-select" id="status" name="status" required>
                                    <option value="pending">Pending</option>
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="form-check mt-4">
                                    <input type="checkbox" class="form-check-input" id="is_verified" name="is_verified"
                                        value="1">
                                    <label class="form-check-label" for="is_verified">Verified Profile</label>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="form-check mt-4">
                                    <input type="checkbox" class="form-check-input" id="is_featured" name="is_featured"
                                        value="1">
                                    <label class="form-check-label" for="is_featured">Featured Profile</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary" id="submitBtn">
                            <i class="fas fa-save me-2"></i>Save Profile
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- View Profile Modal -->
    <div class="modal fade" id="viewProfileModal" tabindex="-1">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Profile Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="profileDetails">
                    <!-- Profile details will be loaded here -->
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
        let profilesTable;
        let editingProfileId = null;

        $(document).ready(function() {
            // Initialize DataTable
            profilesTable = $('#profilesTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('admin.vendor-profile.data') }}',
                columns: [{
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'user_id',
                        name: 'user_id'
                    },
                    {
                        data: 'business_name',
                        name: 'business_name'
                    },
                    {
                        data: 'vendor_category_id',
                        name: 'vendor_category_id'
                    },
                    {
                        data: 'location',
                        name: 'location'
                    },
                    {
                        data: 'rating',
                        name: 'rating'
                    },
                    {
                        data: 'status',
                        name: 'status'
                    },
                    {
                        data: 'is_verified',
                        name: 'is_verified'
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

            // Load users and categories
            loadUsers();
            loadCategories();

            // Form submission
            $('#profileForm').on('submit', function(e) {
                e.preventDefault();

                const formData = new FormData(this);
                const url = editingProfileId ?
                    `{{ route('admin.vendor-profile.update', ':id') }}`.replace(':id', editingProfileId) :
                    '{{ route('admin.vendor-profile.store') }}';

                if (editingProfileId) {
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
                            $('#profileModal').modal('hide');
                            profilesTable.ajax.reload();
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

        function loadUsers() {
            // Load vendors only
            $.get('/admin/users/data?role=vendor')
                .done(function(data) {
                    const select = $('#user_id');
                    select.empty().append('<option value="">Select User</option>');
                    data.data.forEach(function(user) {
                        select.append(`<option value="${user.id}">${user.name} (${user.email})</option>`);
                    });
                });
        }

        function loadCategories() {
            $.get('{{ route('admin.vendor-categories.data') }}')
                .done(function(data) {
                    const select = $('#vendor_category_id');
                    select.empty().append('<option value="">Select Category</option>');
                    data.data.forEach(function(category) {
                        select.append(`<option value="${category.id}">${category.name}</option>`);
                    });
                });
        }

        function resetForm() {
            editingProfileId = null;
            $('#profileForm')[0].reset();
            $('#modalTitle').text('Add New Vendor Profile');
            $('#submitBtn').html('<i class="fas fa-save me-2"></i>Save Profile');
        }

        function viewVendorProfile(id) {
            $.get(`{{ route('admin.vendor-profile.show', ':id') }}`.replace(':id', id))
                .done(function(profile) {
                    const stars = Array.from({
                            length: 5
                        }, (_, i) =>
                        i < profile.rating ? '<i class="fas fa-star text-warning"></i>' :
                        '<i class="far fa-star text-muted"></i>'
                    ).join('');

                    const details = `
                <div class="row">
                    <div class="col-md-6">
                        <h4>${profile.business_name}</h4>
                        <p class="text-muted">${profile.description || 'No description provided'}</p>
                        
                        <table class="table table-borderless">
                            <tr><th>Owner:</th><td>${profile.user ? profile.user.name : '-'}</td></tr>
                            <tr><th>Category:</th><td>${profile.vendor_category ? profile.vendor_category.name : '-'}</td></tr>
                            <tr><th>Location:</th><td>${profile.location || '-'}</td></tr>
                            <tr><th>Phone:</th><td>${profile.phone || '-'}</td></tr>
                            <tr><th>WhatsApp:</th><td>${profile.whatsapp || '-'}</td></tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <h6>Rating</h6>
                            <div>${stars} <span class="text-muted">(${profile.total_reviews || 0} reviews)</span></div>
                        </div>
                        
                        <div class="mb-3">
                            <h6>Price Range</h6>
                            <p>Rp ${profile.price_range_min ? profile.price_range_min.toLocaleString() : '0'} - Rp ${profile.price_range_max ? profile.price_range_max.toLocaleString() : '0'}</p>
                        </div>
                        
                        <div class="mb-3">
                            <h6>Social Media</h6>
                            <p>Instagram: ${profile.instagram || '-'}</p>
                            <p>Website: ${profile.website ? `<a href="${profile.website}" target="_blank">${profile.website}</a>` : '-'}</p>
                        </div>
                        
                        <div class="mb-3">
                            <h6>Status</h6>
                            <span class="badge bg-${profile.status === 'active' ? 'success' : profile.status === 'pending' ? 'warning' : 'danger'}">${profile.status}</span>
                            ${profile.is_verified ? '<span class="badge bg-primary ms-2">Verified</span>' : ''}
                            ${profile.is_featured ? '<span class="badge bg-warning ms-2">Featured</span>' : ''}
                        </div>
                    </div>
                </div>
            `;
                    $('#profileDetails').html(details);
                    $('#viewProfileModal').modal('show');
                });
        }

        function editVendorProfile(id) {
            editingProfileId = id;

            $.get(`{{ route('admin.vendor-profile.show', ':id') }}`.replace(':id', id))
                .done(function(profile) {
                    $('#user_id').val(profile.user_id);
                    $('#vendor_category_id').val(profile.vendor_category_id);
                    $('#business_name').val(profile.business_name);
                    $('#description').val(profile.description);
                    $('#price_range_min').val(profile.price_range_min);
                    $('#price_range_max').val(profile.price_range_max);
                    $('#location').val(profile.location);
                    $('#phone').val(profile.phone);
                    $('#whatsapp').val(profile.whatsapp);
                    $('#instagram').val(profile.instagram);
                    $('#website').val(profile.website);
                    $('#status').val(profile.status);
                    $('#is_verified').prop('checked', profile.is_verified);
                    $('#is_featured').prop('checked', profile.is_featured);

                    $('#modalTitle').text('Edit Vendor Profile');
                    $('#submitBtn').html('<i class="fas fa-save me-2"></i>Update Profile');

                    $('#profileModal').modal('show');
                });
        }

        function deleteVendorProfile(id) {
            if (confirm('Are you sure you want to delete this vendor profile?')) {
                $.ajax({
                    url: `{{ route('admin.vendor-profile.destroy', ':id') }}`.replace(':id', id),
                    method: 'DELETE',
                    success: function(response) {
                        if (response.success) {
                            toastr.success(response.message);
                            profilesTable.ajax.reload();
                        }
                    },
                    error: function() {
                        toastr.error('Failed to delete vendor profile');
                    }
                });
            }
        }
    </script>
@endpush

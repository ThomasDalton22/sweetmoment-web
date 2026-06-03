@extends('admin.layouts.app')

@section('title', 'Users Management - Admin Panel')
@section('page-title', 'Users Management')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-users me-2"></i>
                        Users List
                    </h5>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#userModal"
                        onclick="resetForm()">
                        <i class="fas fa-plus me-2"></i>Add New User
                    </button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="usersTable" class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Phone</th>
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

    <!-- User Modal -->
    <div class="modal fade" id="userModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">Add New User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="userForm">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">Name *</label>
                                <input type="text" class="form-control" id="name" name="name" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">Email *</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="username" class="form-label">Username *</label>
                                <input type="text" class="form-control" id="username" name="username" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="password" class="form-label">Password *</label>
                                <input type="password" class="form-control" id="password" name="password">
                                <small class="text-muted" id="passwordHelp">Leave blank to keep current password (for
                                    edit)</small>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="role" class="form-label">Role *</label>
                                <select class="form-select" id="role" name="role" required>
                                    <option value="">Select Role</option>
                                    <option value="user">User</option>
                                    <option value="vendor">Vendor</option>
                                    <option value="admin">Admin</option>
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="gender" class="form-label">Gender *</label>
                                <select class="form-select" id="gender" name="gender" required>
                                    <option value="">Select Gender</option>
                                    <option value="Laki-Laki">Laki-Laki</option>
                                    <option value="Perempuan">Perempuan</option>
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="phone" class="form-label">Phone</label>
                                <input type="text" class="form-control" id="phone" name="phone">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="address" class="form-label">Address *</label>
                            <textarea class="form-control" id="address" name="address" rows="3" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary" id="submitBtn">
                            <i class="fas fa-save me-2"></i>Save User
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- View User Modal -->
    <div class="modal fade" id="viewUserModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">User Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="userDetails">
                    <!-- User details will be loaded here -->
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
        let usersTable;
        let editingUserId = null;

        $(document).ready(function() {
            // Initialize DataTable
            usersTable = $('#usersTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('admin.users.data') }}',
                columns: [{
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'email',
                        name: 'email'
                    },
                    {
                        data: 'role',
                        name: 'role'
                    },
                    {
                        data: 'phone',
                        name: 'phone'
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

            // Form submission
            $('#userForm').on('submit', function(e) {
                e.preventDefault();

                const formData = new FormData(this);
                const url = editingUserId ?
                    `{{ route('admin.users.update', ':id') }}`.replace(':id', editingUserId) :
                    '{{ route('admin.users.store') }}';

                const method = editingUserId ? 'PUT' : 'POST';

                if (editingUserId) {
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
                            $('#userModal').modal('hide');
                            usersTable.ajax.reload();
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

        function resetForm() {
            editingUserId = null;
            $('#userForm')[0].reset();
            $('#modalTitle').text('Add New User');
            $('#submitBtn').html('<i class="fas fa-save me-2"></i>Save User');
            $('#password').prop('required', true);
            $('#passwordHelp').hide();
        }

        function viewUser(id) {
            $.get(`{{ route('admin.users.show', ':id') }}`.replace(':id', id))
                .done(function(user) {
                    const details = `
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr><th>Name:</th><td>${user.name}</td></tr>
                            <tr><th>Email:</th><td>${user.email}</td></tr>
                            <tr><th>Username:</th><td>${user.username}</td></tr>
                            <tr><th>Role:</th><td><span class="badge bg-primary">${user.role}</span></td></tr>
                            <tr><th>Gender:</th><td>${user.gender}</td></tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr><th>Phone:</th><td>${user.phone || '-'}</td></tr>
                            <tr><th>Address:</th><td>${user.address}</td></tr>
                            <tr><th>Email Verified:</th><td>${user.email_verified_at ? 'Yes' : 'No'}</td></tr>
                            <tr><th>Created:</th><td>${new Date(user.created_at).toLocaleDateString()}</td></tr>
                            <tr><th>Updated:</th><td>${new Date(user.updated_at).toLocaleDateString()}</td></tr>
                        </table>
                    </div>
                </div>
            `;
                    $('#userDetails').html(details);
                    $('#viewUserModal').modal('show');
                })
                .fail(function() {
                    toastr.error('Failed to load user details');
                });
        }

        function editUser(id) {
            editingUserId = id;

            $.get(`{{ route('admin.users.show', ':id') }}`.replace(':id', id))
                .done(function(user) {
                    $('#name').val(user.name);
                    $('#email').val(user.email);
                    $('#username').val(user.username);
                    $('#role').val(user.role);
                    $('#gender').val(user.gender);
                    $('#phone').val(user.phone);
                    $('#address').val(user.address);

                    $('#modalTitle').text('Edit User');
                    $('#submitBtn').html('<i class="fas fa-save me-2"></i>Update User');
                    $('#password').prop('required', false);
                    $('#passwordHelp').show();

                    $('#userModal').modal('show');
                })
                .fail(function() {
                    toastr.error('Failed to load user details');
                });
        }

        function deleteUser(id) {
            if (confirm('Are you sure you want to delete this user?')) {
                $.ajax({
                    url: `{{ route('admin.users.destroy', ':id') }}`.replace(':id', id),
                    method: 'DELETE',
                    success: function(response) {
                        if (response.success) {
                            toastr.success(response.message);
                            usersTable.ajax.reload();
                        }
                    },
                    error: function() {
                        toastr.error('Failed to delete user');
                    }
                });
            }
        }
    </script>
@endpush

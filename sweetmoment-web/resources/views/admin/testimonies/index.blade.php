@extends('admin.layouts.app')

@section('title', 'Testimonies Management - Admin Panel')
@section('page-title', 'Testimonies Management')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-star me-2"></i>
                        Customer Testimonies
                    </h5>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#testimonyModal"
                        onclick="resetForm()">
                        <i class="fas fa-plus me-2"></i>Add New Testimony
                    </button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="testimoniesTable" class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>User</th>
                                    <th>Testimony</th>
                                    <th>Rating</th>
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

    <!-- Testimony Modal -->
    <div class="modal fade" id="testimonyModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">Add New Testimony</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="testimonyForm">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-8 mb-3">
                                <label for="user" class="form-label">User Name *</label>
                                <input type="text" class="form-control" id="user" name="user" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="rating" class="form-label">Rating *</label>
                                <select class="form-select" id="rating" name="rating" required>
                                    <option value="">Select Rating</option>
                                    <option value="1">1 Star</option>
                                    <option value="2">2 Stars</option>
                                    <option value="3">3 Stars</option>
                                    <option value="4">4 Stars</option>
                                    <option value="5">5 Stars</option>
                                </select>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="testimony" class="form-label">Testimony *</label>
                            <textarea class="form-control" id="testimony" name="testimony" rows="4" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary" id="submitBtn">
                            <i class="fas fa-save me-2"></i>Save Testimony
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- View Testimony Modal -->
    <div class="modal fade" id="viewTestimonyModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Testimony Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="testimonyDetails">
                    <!-- Testimony details will be loaded here -->
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
        let testimoniesTable;
        let editingTestimonyId = null;

        $(document).ready(function() {
            testimoniesTable = $('#testimoniesTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('admin.testimonies.data') }}',
                columns: [{
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'user',
                        name: 'user'
                    },
                    {
                        data: 'testimony',
                        name: 'testimony'
                    },
                    {
                        data: 'rating',
                        name: 'rating',
                        orderable: false
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

            $('#testimonyForm').on('submit', function(e) {
                e.preventDefault();
                const formData = new FormData(this);
                const url = editingTestimonyId ?
                    `{{ route('admin.testimonies.update', ':id') }}`.replace(':id', editingTestimonyId) :
                    '{{ route('admin.testimonies.store') }}';

                if (editingTestimonyId) {
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
                            $('#testimonyModal').modal('hide');
                            testimoniesTable.ajax.reload();
                            resetForm();
                        }
                    },
                    error: function(xhr) {
                        if (xhr.status === 422) {
                            const errors = xhr.responseJSON.errors;
                            for (let field in errors) {
                                toastr.error(errors[field][0]);
                            }
                        }
                    }
                });
            });
        });

        function resetForm() {
            editingTestimonyId = null;
            $('#testimonyForm')[0].reset();
            $('#modalTitle').text('Add New Testimony');
            $('#submitBtn').html('<i class="fas fa-save me-2"></i>Save Testimony');
        }

        function viewTestimony(id) {
            $.get(`{{ route('admin.testimonies.show', ':id') }}`.replace(':id', id))
                .done(function(testimony) {
                    const stars = Array.from({
                            length: 5
                        }, (_, i) =>
                        i < testimony.rating ? '<i class="fas fa-star text-warning"></i>' :
                        '<i class="far fa-star text-muted"></i>'
                    ).join('');

                    const details = `
                <div class="text-center mb-4">
                    <h4>${testimony.user}</h4>
                    <div class="mb-2">${stars}</div>
                    <small class="text-muted">Posted on ${new Date(testimony.created_at).toLocaleDateString()}</small>
                </div>
                <div class="bg-light p-3 rounded">
                    <p class="mb-0">"${testimony.testimony}"</p>
                </div>
            `;
                    $('#testimonyDetails').html(details);
                    $('#viewTestimonyModal').modal('show');
                });
        }

        function editTestimony(id) {
            editingTestimonyId = id;
            $.get(`{{ route('admin.testimonies.show', ':id') }}`.replace(':id', id))
                .done(function(testimony) {
                    $('#user').val(testimony.user);
                    $('#rating').val(testimony.rating);
                    $('#testimony').val(testimony.testimony);
                    $('#modalTitle').text('Edit Testimony');
                    $('#submitBtn').html('<i class="fas fa-save me-2"></i>Update Testimony');
                    $('#testimonyModal').modal('show');
                });
        }

        function deleteTestimony(id) {
            if (confirm('Are you sure you want to delete this testimony?')) {
                $.ajax({
                    url: `{{ route('admin.testimonies.destroy', ':id') }}`.replace(':id', id),
                    method: 'DELETE',
                    success: function(response) {
                        if (response.success) {
                            toastr.success(response.message);
                            testimoniesTable.ajax.reload();
                        }
                    }
                });
            }
        }
    </script>
@endpush

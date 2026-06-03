@extends('admin.layouts.app')

@section('title', 'Banners Management - Admin Panel')
@section('page-title', 'Banners Management')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-image me-2"></i>
                        Banners List
                    </h5>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#bannerModal"
                        onclick="resetForm()">
                        <i class="fas fa-plus me-2"></i>Add New Banner
                    </button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="bannersTable" class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Image</th>
                                    <th>Title</th>
                                    <th>Subtitle</th>
                                    <th>Position</th>
                                    <th>Status</th>
                                    <th>Order</th>
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

    <!-- Banner Modal -->
    <div class="modal fade" id="bannerModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">Add New Banner</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="bannerForm" enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="title" class="form-label">Title *</label>
                                <input type="text" class="form-control" id="title" name="title" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="subtitle" class="form-label">Subtitle</label>
                                <input type="text" class="form-control" id="subtitle" name="subtitle">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="position" class="form-label">Position *</label>
                                <select class="form-select" id="position" name="position" required>
                                    <option value="">Select Position</option>
                                    <option value="hero">Hero</option>
                                    <option value="middle">Middle</option>
                                    <option value="bottom">Bottom</option>
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="order" class="form-label">Order</label>
                                <input type="number" class="form-control" id="order" name="order" min="0">
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="form-check mt-4">
                                    <input type="checkbox" class="form-check-input" id="is_active" name="is_active"
                                        value="1" checked>
                                    <label class="form-check-label" for="is_active">Active</label>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="link" class="form-label">Link URL</label>
                            <input type="url" class="form-control" id="link" name="link"
                                placeholder="https://example.com">
                        </div>

                        <div class="mb-3">
                            <label for="image" class="form-label">Banner Image *</label>
                            <input type="file" class="form-control" id="image" name="image" accept="image/*">
                            <small class="text-muted">Accepted formats: JPG, PNG, GIF (Max: 2MB)</small>
                            <div id="imagePreview" class="mt-2" style="display: none;">
                                <img id="previewImg" src="" alt="Preview" class="img-thumbnail"
                                    style="max-height: 200px;">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary" id="submitBtn">
                            <i class="fas fa-save me-2"></i>Save Banner
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- View Banner Modal -->
    <div class="modal fade" id="viewBannerModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Banner Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="bannerDetails">
                    <!-- Banner details will be loaded here -->
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
        let bannersTable;
        let editingBannerId = null;

        $(document).ready(function() {
            // Initialize DataTable
            bannersTable = $('#bannersTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('admin.banners.data') }}',
                columns: [{
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'image',
                        name: 'image',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'title',
                        name: 'title'
                    },
                    {
                        data: 'subtitle',
                        name: 'subtitle'
                    },
                    {
                        data: 'position',
                        name: 'position'
                    },
                    {
                        data: 'is_active',
                        name: 'is_active'
                    },
                    {
                        data: 'order',
                        name: 'order'
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
                    [6, 'asc']
                ] // Order by 'order' column
            });

            // Image preview
            $('#image').on('change', function() {
                const file = this.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        $('#previewImg').attr('src', e.target.result);
                        $('#imagePreview').show();
                    };
                    reader.readAsDataURL(file);
                } else {
                    $('#imagePreview').hide();
                }
            });

            // Form submission
            $('#bannerForm').on('submit', function(e) {
                e.preventDefault();

                const formData = new FormData(this);
                const url = editingBannerId ?
                    `{{ route('admin.banners.update', ':id') }}`.replace(':id', editingBannerId) :
                    '{{ route('admin.banners.store') }}';

                if (editingBannerId) {
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
                            $('#bannerModal').modal('hide');
                            bannersTable.ajax.reload();
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
            editingBannerId = null;
            $('#bannerForm')[0].reset();
            $('#modalTitle').text('Add New Banner');
            $('#submitBtn').html('<i class="fas fa-save me-2"></i>Save Banner');
            $('#image').prop('required', true);
            $('#imagePreview').hide();
        }

        function viewBanner(id) {
            $.get(`{{ route('admin.banners.show', ':id') }}`.replace(':id', id))
                .done(function(banner) {
                    const details = `
                <div class="row">
                    <div class="col-md-6">
                        <img src="${banner.image ? '/storage/' + banner.image : '/placeholder.jpg'}" 
                             alt="${banner.title}" class="img-fluid rounded mb-3">
                    </div>
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr><th>Title:</th><td>${banner.title}</td></tr>
                            <tr><th>Subtitle:</th><td>${banner.subtitle || '-'}</td></tr>
                            <tr><th>Position:</th><td><span class="badge bg-info">${banner.position}</span></td></tr>
                            <tr><th>Status:</th><td>${banner.is_active ? '<span class="badge bg-success">Active</span>' : '<span class="badge bg-danger">Inactive</span>'}</td></tr>
                            <tr><th>Order:</th><td>${banner.order || 0}</td></tr>
                            <tr><th>Link:</th><td>${banner.link ? `<a href="${banner.link}" target="_blank">${banner.link}</a>` : '-'}</td></tr>
                            <tr><th>Created:</th><td>${new Date(banner.created_at).toLocaleDateString()}</td></tr>
                        </table>
                    </div>
                </div>
            `;
                    $('#bannerDetails').html(details);
                    $('#viewBannerModal').modal('show');
                })
                .fail(function() {
                    toastr.error('Failed to load banner details');
                });
        }

        function editBanner(id) {
            editingBannerId = id;

            $.get(`{{ route('admin.banners.show', ':id') }}`.replace(':id', id))
                .done(function(banner) {
                    $('#title').val(banner.title);
                    $('#subtitle').val(banner.subtitle);
                    $('#position').val(banner.position);
                    $('#order').val(banner.order);
                    $('#link').val(banner.link);
                    $('#is_active').prop('checked', banner.is_active);

                    if (banner.image) {
                        $('#previewImg').attr('src', '/storage/' + banner.image);
                        $('#imagePreview').show();
                    }

                    $('#modalTitle').text('Edit Banner');
                    $('#submitBtn').html('<i class="fas fa-save me-2"></i>Update Banner');
                    $('#image').prop('required', false);

                    $('#bannerModal').modal('show');
                })
                .fail(function() {
                    toastr.error('Failed to load banner details');
                });
        }

        function deleteBanner(id) {
            if (confirm('Are you sure you want to delete this banner?')) {
                $.ajax({
                    url: `{{ route('admin.banners.destroy', ':id') }}`.replace(':id', id),
                    method: 'DELETE',
                    success: function(response) {
                        if (response.success) {
                            toastr.success(response.message);
                            bannersTable.ajax.reload();
                        }
                    },
                    error: function() {
                        toastr.error('Failed to delete banner');
                    }
                });
            }
        }
    </script>
@endpush

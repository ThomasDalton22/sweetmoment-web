{{-- resources/views/admin/portfolio-images/index.blade.php --}}
@extends('admin.layouts.app')

@section('title', 'Portfolio Images - ' . (Auth::user()->role === 'admin' ? 'Admin Panel' : 'Vendor Panel'))
@section('page-title', Auth::user()->role === 'admin' ? 'All Portfolio Images' : 'My Portfolio')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-images me-2"></i>
                        {{ Auth::user()->role === 'admin' ? 'All Portfolio Images' : 'My Portfolio Images' }}
                    </h5>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#imageModal"
                        onclick="resetForm()">
                        <i class="fas fa-plus me-2"></i>Add New Image
                    </button>
                </div>
                <div class="card-body">
                    @if (Auth::user()->role === 'vendor')
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            Upload high-quality images to showcase your work. Featured images will be highlighted in your
                            profile.
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table id="imagesTable" class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Image</th>
                                    @if (Auth::user()->role === 'admin')
                                        <th>Vendor</th>
                                    @endif
                                    <th>Caption</th>
                                    <th>Featured</th>
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

    <!-- Image Modal -->
    <div class="modal fade" id="imageModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">Add New Image</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="imageForm" enctype="multipart/form-data">
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
                                <label for="caption" class="form-label">Caption</label>
                                <input type="text" class="form-control" id="caption" name="caption"
                                    placeholder="Image caption or description">
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="form-check mt-4">
                                    <input type="checkbox" class="form-check-input" id="is_featured" name="is_featured"
                                        value="1">
                                    <label class="form-check-label" for="is_featured">
                                        <i class="fas fa-star text-warning me-1"></i>Featured Image
                                    </label>
                                    <small class="d-block text-muted">Featured images are highlighted in profile</small>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="image" class="form-label">Portfolio Image *</label>
                            <input type="file" class="form-control" id="image" name="image" accept="image/*"
                                required>
                            <small class="text-muted">Accepted formats: JPG, PNG, GIF (Max: 5MB). Recommended: High-quality
                                images at least 800x600px.</small>
                        </div>

                        <div id="imagePreview" class="mb-3" style="display: none;">
                            <label class="form-label">Image Preview:</label>
                            <br>
                            <img id="previewImg" src="" alt="Preview" class="img-thumbnail"
                                style="max-height: 300px; max-width: 100%;">
                        </div>

                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <strong>Tips for great portfolio images:</strong>
                            <ul class="mb-0 mt-2">
                                <li>Use high-resolution images (minimum 800x600px)</li>
                                <li>Ensure good lighting and clear focus</li>
                                <li>Show your best work and variety</li>
                                <li>Add descriptive captions to help customers understand your work</li>
                            </ul>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary" id="submitBtn">
                            <i class="fas fa-save me-2"></i>Save Image
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- View Image Modal -->
    <div class="modal fade" id="viewImageModal" tabindex="-1">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Image Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="imageDetails">
                    <!-- Image details will be loaded here -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bulk Actions (Admin Only) -->
    @if (Auth::user()->role === 'admin')
        <div class="modal fade" id="bulkActionsModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Bulk Actions</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <p>Select an action to perform on selected images:</p>
                        <div class="d-grid gap-2">
                            <button type="button" class="btn btn-warning" onclick="bulkSetFeatured()">
                                <i class="fas fa-star me-2"></i>Set as Featured
                            </button>
                            <button type="button" class="btn btn-secondary" onclick="bulkUnsetFeatured()">
                                <i class="far fa-star me-2"></i>Remove Featured
                            </button>
                            <button type="button" class="btn btn-danger" onclick="bulkDelete()">
                                <i class="fas fa-trash me-2"></i>Delete Selected
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection

@push('scripts')
    <script>
        let imagesTable;
        let editingImageId = null;
        let selectedImages = [];

        $(document).ready(function() {
            // Initialize DataTable
            const columns = [{
                    data: 'id',
                    name: 'id',
                    @if (Auth::user()->role === 'admin')
                        render: function(data, type, row) {
                            return `<input type="checkbox" class="image-checkbox" value="${data}"> ${data}`;
                        }
                    @endif
                },
                {
                    data: 'image',
                    name: 'image',
                    orderable: false,
                    searchable: false
                },
                @if (Auth::user()->role === 'admin')
                    {
                        data: 'vendor_profile_id',
                        name: 'vendor_profile_id'
                    },
                @endif {
                    data: 'caption',
                    name: 'caption'
                },
                {
                    data: 'is_featured',
                    name: 'is_featured'
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

            imagesTable = $('#imagesTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('admin.portfolio-images.data') }}',
                columns: columns,
                order: [
                    [0, 'desc']
                ],
                @if (Auth::user()->role === 'admin')
                    drawCallback: function() {
                        // Handle checkbox selection for bulk actions
                        $('.image-checkbox').on('change', function() {
                            const imageId = $(this).val();
                            if ($(this).is(':checked')) {
                                selectedImages.push(imageId);
                            } else {
                                selectedImages = selectedImages.filter(id => id !== imageId);
                            }

                            // Show/hide bulk actions button
                            if (selectedImages.length > 0) {
                                $('#bulkActionsBtn').show();
                            } else {
                                $('#bulkActionsBtn').hide();
                            }
                        });
                    }
                @endif
            });

            // Image preview
            $('#image').on('change', function() {
                const file = this.files[0];
                if (file) {
                    // Check file size (5MB = 5 * 1024 * 1024 bytes)
                    if (file.size > 5 * 1024 * 1024) {
                        toastr.error('File size must be less than 5MB');
                        $(this).val('');
                        $('#imagePreview').hide();
                        return;
                    }

                    // Check file type
                    if (!file.type.startsWith('image/')) {
                        toastr.error('Please select a valid image file');
                        $(this).val('');
                        $('#imagePreview').hide();
                        return;
                    }

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

            // Load vendors for admin
            @if (Auth::user()->role === 'admin')
                loadVendors();
            @endif

            // Form submission
            $('#imageForm').on('submit', function(e) {
                e.preventDefault();

                const formData = new FormData(this);
                const url = editingImageId ?
                    `{{ route('admin.portfolio-images.update', ':id') }}`.replace(':id', editingImageId) :
                    '{{ route('admin.portfolio-images.store') }}';

                if (editingImageId) {
                    formData.append('_method', 'PUT');
                }

                // Show loading state
                $('#submitBtn').prop('disabled', true).html(
                    '<i class="fas fa-spinner fa-spin me-2"></i>Uploading...');

                $.ajax({
                    url: url,
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        if (response.success) {
                            toastr.success(response.message);
                            $('#imageModal').modal('hide');
                            imagesTable.ajax.reload();
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
                    },
                    complete: function() {
                        $('#submitBtn').prop('disabled', false).html(
                            '<i class="fas fa-save me-2"></i>Save Image');
                    }
                });
            });
        });

        @if (Auth::user()->role === 'admin')
            function loadVendors() {
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
            editingImageId = null;
            $('#imageForm')[0].reset();
            $('#modalTitle').text('Add New Image');
            $('#submitBtn').html('<i class="fas fa-save me-2"></i>Save Image');
            $('#image').prop('required', true);
            $('#imagePreview').hide();
            $('#is_featured').prop('checked', false);
        }

        function viewImage(id) {
            $.get(`{{ route('admin.portfolio-images.show', ':id') }}`.replace(':id', id))
                .done(function(image) {
                    const details = `
                <div class="row">
                    <div class="col-md-8">
                        <div class="text-center">
                            <img src="/storage/${image.image}" alt="${image.caption || 'Portfolio Image'}" 
                                 class="img-fluid rounded shadow" style="max-height: 500px;">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <table class="table table-borderless">
                            <tr><th>Caption:</th><td>${image.caption || 'No caption'}</td></tr>
                            <tr><th>Featured:</th><td>${image.is_featured ? '<span class="badge bg-warning"><i class="fas fa-star me-1"></i>Featured</span>' : '<span class="badge bg-secondary">Regular</span>'}</td></tr>
                            <tr><th>Added:</th><td>${new Date(image.created_at).toLocaleDateString()}</td></tr>
                            ${image.vendor_profile ? `<tr><th>Vendor:</th><td>${image.vendor_profile.business_name}</td></tr>` : ''}
                        </table>
                        
                        <div class="mt-4">
                            <h6>Image Information:</h6>
                            <div class="small text-muted" id="imageInfo">
                                Loading image details...
                            </div>
                        </div>
                    </div>
                </div>
            `;
                    $('#imageDetails').html(details);

                    // Load image dimensions
                    const img = new Image();
                    img.onload = function() {
                        $('#imageInfo').html(`
                    <p>Dimensions: ${this.width} x ${this.height} pixels</p>
                    <p>Aspect Ratio: ${(this.width/this.height).toFixed(2)}:1</p>
                `);
                    };
                    img.src = `/storage/${image.image}`;

                    $('#viewImageModal').modal('show');
                })
                .fail(function() {
                    toastr.error('Failed to load image details');
                });
        }

        function editImage(id) {
            editingImageId = id;

            $.get(`{{ route('admin.portfolio-images.show', ':id') }}`.replace(':id', id))
                .done(function(image) {
                    $('#caption').val(image.caption);
                    $('#is_featured').prop('checked', image.is_featured);

                    @if (Auth::user()->role === 'admin')
                        $('#vendor_profile_id').val(image.vendor_profile_id);
                    @endif

                    if (image.image) {
                        $('#previewImg').attr('src', '/storage/' + image.image);
                        $('#imagePreview').show();
                    }

                    $('#modalTitle').text('Edit Image');
                    $('#submitBtn').html('<i class="fas fa-save me-2"></i>Update Image');
                    $('#image').prop('required', false);

                    $('#imageModal').modal('show');
                })
                .fail(function() {
                    toastr.error('Failed to load image details');
                });
        }

        function deleteImage(id) {
            if (confirm('Are you sure you want to delete this image? This action cannot be undone.')) {
                $.ajax({
                    url: `{{ route('admin.portfolio-images.destroy', ':id') }}`.replace(':id', id),
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.success) {
                            toastr.success(response.message);
                            imagesTable.ajax.reload();
                        }
                    },
                    error: function() {
                        toastr.error('Failed to delete image');
                    }
                });
            }
        }

        @if (Auth::user()->role === 'admin')
            // Bulk Actions (Admin Only)
            function bulkSetFeatured() {
                if (selectedImages.length === 0) {
                    toastr.warning('Please select images first');
                    return;
                }

                if (confirm(`Set ${selectedImages.length} images as featured?`)) {
                    // Implementation for bulk set featured
                    toastr.info('Bulk featured functionality would be implemented here');
                    $('#bulkActionsModal').modal('hide');
                }
            }

            function bulkUnsetFeatured() {
                if (selectedImages.length === 0) {
                    toastr.warning('Please select images first');
                    return;
                }

                if (confirm(`Remove featured status from ${selectedImages.length} images?`)) {
                    // Implementation for bulk unset featured
                    toastr.info('Bulk unfeatured functionality would be implemented here');
                    $('#bulkActionsModal').modal('hide');
                }
            }

            function bulkDelete() {
                if (selectedImages.length === 0) {
                    toastr.warning('Please select images first');
                    return;
                }

                if (confirm(
                        `Are you sure you want to delete ${selectedImages.length} images? This action cannot be undone.`)) {
                    // Implementation for bulk delete
                    toastr.info('Bulk delete functionality would be implemented here');
                    $('#bulkActionsModal').modal('hide');
                }
            }
        @endif
    </script>
@endpush

@push('styles')
    <style>
        #imagePreview img {
            border: 2px solid #dee2e6;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .table td {
            vertical-align: middle;
        }

        .image-thumbnail {
            transition: transform 0.2s ease;
            cursor: pointer;
        }

        .image-thumbnail:hover {
            transform: scale(1.05);
        }

        @if (Auth::user()->role === 'admin')
            #bulkActionsBtn {
                position: fixed;
                bottom: 20px;
                right: 20px;
                z-index: 1000;
                display: none;
            }
        @endif
    </style>
@endpush

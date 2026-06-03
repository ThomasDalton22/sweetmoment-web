@extends('admin.layouts.app')

@section('title', 'News Management - Admin Panel')
@section('page-title', 'News Management')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-newspaper me-2"></i>
                        News Articles
                    </h5>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#newsModal"
                        onclick="resetForm()">
                        <i class="fas fa-plus me-2"></i>Add New Article
                    </button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="newsTable" class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Image</th>
                                    <th>Title</th>
                                    <th>Description</th>
                                    <th>Likes</th>
                                    <th>Dislikes</th>
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

    <!-- News Modal -->
    <div class="modal fade" id="newsModal" tabindex="-1">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">Add New Article</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="newsForm" enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-8 mb-3">
                                <label for="title" class="form-label">Title *</label>
                                <input type="text" class="form-control" id="title" name="title" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="image" class="form-label">Featured Image *</label>
                                <input type="file" class="form-control" id="image" name="image" accept="image/*">
                                <small class="text-muted">Max: 2MB</small>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description *</label>
                            <textarea class="form-control" id="description" name="description" rows="3" required maxlength="255"></textarea>
                            <small class="text-muted">Brief description (max 255 characters)</small>
                        </div>

                        <div class="mb-3">
                            <label for="content" class="form-label">Content *</label>
                            <textarea id="content" name="content" required></textarea>
                        </div>

                        <div id="imagePreview" class="mb-3" style="display: none;">
                            <label class="form-label">Image Preview:</label>
                            <br>
                            <img id="previewImg" src="" alt="Preview" class="img-thumbnail"
                                style="max-height: 200px;">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary" id="submitBtn">
                            <i class="fas fa-save me-2"></i>Save Article
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- View News Modal -->
    <div class="modal fade" id="viewNewsModal" tabindex="-1">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Article Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="newsDetails">
                    <!-- News details will be loaded here -->
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
        let newsTable;
        let editingNewsId = null;

        $(document).ready(function() {
            // Initialize Summernote
            $('#content').summernote({
                height: 300,
                toolbar: [
                    ['style', ['style']],
                    ['font', ['bold', 'underline', 'clear']],
                    ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['table', ['table']],
                    ['insert', ['link', 'picture', 'video']],
                    ['view', ['fullscreen', 'codeview', 'help']]
                ]
            });

            // Initialize DataTable
            newsTable = $('#newsTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('admin.news.data') }}',
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
                        data: 'description',
                        name: 'description'
                    },
                    {
                        data: 'likes',
                        name: 'likes'
                    },
                    {
                        data: 'dislikes',
                        name: 'dislikes'
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

            // Character counter for description
            $('#description').on('input', function() {
                const remaining = 255 - this.value.length;
                $(this).next('.text-muted').text(`Brief description (${remaining} characters remaining)`);
            });

            // Form submission
            $('#newsForm').on('submit', function(e) {
                e.preventDefault();

                const formData = new FormData(this);
                formData.set('content', $('#content').summernote('code'));

                const url = editingNewsId ?
                    `{{ route('admin.news.update', ':id') }}`.replace(':id', editingNewsId) :
                    '{{ route('admin.news.store') }}';

                if (editingNewsId) {
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
                            $('#newsModal').modal('hide');
                            newsTable.ajax.reload();
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
            editingNewsId = null;
            $('#newsForm')[0].reset();
            $('#content').summernote('code', '');
            $('#modalTitle').text('Add New Article');
            $('#submitBtn').html('<i class="fas fa-save me-2"></i>Save Article');
            $('#image').prop('required', true);
            $('#imagePreview').hide();
        }

        function viewNews(id) {
            $.get(`{{ route('admin.news.show', ':id') }}`.replace(':id', id))
                .done(function(news) {
                    const details = `
                <div class="row">
                    <div class="col-md-4">
                        <img src="${news.image ? '/storage/' + news.image : '/placeholder.jpg'}" 
                             alt="${news.title}" class="img-fluid rounded mb-3">
                        <div class="d-flex justify-content-between">
                            <div class="text-center">
                                <i class="fas fa-thumbs-up text-success"></i>
                                <span class="ms-1">${news.likes}</span>
                            </div>
                            <div class="text-center">
                                <i class="fas fa-thumbs-down text-danger"></i>
                                <span class="ms-1">${news.dislikes}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <h4>${news.title}</h4>
                        <p class="text-muted">${news.description}</p>
                        <hr>
                        <div class="content">
                            ${news.content}
                        </div>
                        <hr>
                        <small class="text-muted">
                            Created: ${new Date(news.created_at).toLocaleDateString()}
                            ${news.updated_at !== news.created_at ? '| Updated: ' + new Date(news.updated_at).toLocaleDateString() : ''}
                        </small>
                    </div>
                </div>
            `;
                    $('#newsDetails').html(details);
                    $('#viewNewsModal').modal('show');
                })
                .fail(function() {
                    toastr.error('Failed to load article details');
                });
        }

        function editNews(id) {
            editingNewsId = id;

            $.get(`{{ route('admin.news.show', ':id') }}`.replace(':id', id))
                .done(function(news) {
                    $('#title').val(news.title);
                    $('#description').val(news.description);
                    $('#content').summernote('code', news.content);

                    if (news.image) {
                        $('#previewImg').attr('src', '/storage/' + news.image);
                        $('#imagePreview').show();
                    }

                    $('#modalTitle').text('Edit Article');
                    $('#submitBtn').html('<i class="fas fa-save me-2"></i>Update Article');
                    $('#image').prop('required', false);

                    $('#newsModal').modal('show');
                })
                .fail(function() {
                    toastr.error('Failed to load article details');
                });
        }

        function deleteNews(id) {
            if (confirm('Are you sure you want to delete this article?')) {
                $.ajax({
                    url: `{{ route('admin.news.destroy', ':id') }}`.replace(':id', id),
                    method: 'DELETE',
                    success: function(response) {
                        if (response.success) {
                            toastr.success(response.message);
                            newsTable.ajax.reload();
                        }
                    },
                    error: function() {
                        toastr.error('Failed to delete article');
                    }
                });
            }
        }
    </script>
@endpush

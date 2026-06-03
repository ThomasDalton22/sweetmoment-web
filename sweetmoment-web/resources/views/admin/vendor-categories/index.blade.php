@extends('admin.layouts.app')

@section('title', 'Vendor Categories - Admin Panel')
@section('page-title', 'Vendor Categories')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-tags me-2"></i>
                        Vendor Categories
                    </h5>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#categoryModal"
                        onclick="resetForm()">
                        <i class="fas fa-plus me-2"></i>Add New Category
                    </button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="categoriesTable" class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Icon</th>
                                    <th>Name</th>
                                    <th>Slug</th>
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

    <!-- Category Modal -->
    <div class="modal fade" id="categoryModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">Add New Category</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="categoryForm">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="name" class="form-label">Category Name *</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="icon" class="form-label">Icon Class</label>
                            <input type="text" class="form-control" id="icon" name="icon" placeholder="bi-heart">

                            <small class="text-muted">Bootstarap 5 icon class (e.g., bi bi-heart)</small>
                            {{-- References --}}
                            <a href="https://icons.getbootstrap.com/" target="_blank" class="btn btn-link p-0">Bootstrap
                                Icons</a>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary" id="submitBtn">
                            <i class="fas fa-save me-2"></i>Save Category
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        let categoriesTable;
        let editingCategoryId = null;

        $(document).ready(function() {
            categoriesTable = $('#categoriesTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('admin.vendor-categories.data') }}',
                columns: [{
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'icon',
                        name: 'icon',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'slug',
                        name: 'slug'
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
                ]
            });

            $('#categoryForm').on('submit', function(e) {
                e.preventDefault();
                const formData = new FormData(this);
                const url = editingCategoryId ?
                    `{{ route('admin.vendor-categories.update', ':id') }}`.replace(':id',
                        editingCategoryId) :
                    '{{ route('admin.vendor-categories.store') }}';

                if (editingCategoryId) {
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
                            $('#categoryModal').modal('hide');
                            categoriesTable.ajax.reload();
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
            editingCategoryId = null;
            $('#categoryForm')[0].reset();
            $('#modalTitle').text('Add New Category');
            $('#submitBtn').html('<i class="fas fa-save me-2"></i>Save Category');
        }

        function editCategory(id) {
            editingCategoryId = id;
            $.get(`{{ route('admin.vendor-categories.show', ':id') }}`.replace(':id', id))
                .done(function(category) {
                    $('#name').val(category.name);
                    $('#icon').val(category.icon);
                    $('#modalTitle').text('Edit Category');
                    $('#submitBtn').html('<i class="fas fa-save me-2"></i>Update Category');
                    $('#categoryModal').modal('show');
                });
        }

        function deleteCategory(id) {
            if (confirm('Are you sure you want to delete this category?')) {
                $.ajax({
                    url: `{{ route('admin.vendor-categories.destroy', ':id') }}`.replace(':id', id),
                    method: 'DELETE',
                    success: function(response) {
                        if (response.success) {
                            toastr.success(response.message);
                            categoriesTable.ajax.reload();
                        }
                    }
                });
            }
        }
    </script>
@endpush

@extends('admin.layouts.app')

@section('title', 'Reviews - ' . (Auth::user()->role === 'admin' ? 'Admin Panel' : 'Vendor Panel'))
@section('page-title', Auth::user()->role === 'admin' ? 'All Reviews' : 'My Reviews')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-star-half-alt me-2"></i>
                        {{ Auth::user()->role === 'admin' ? 'All Customer Reviews' : 'My Customer Reviews' }}
                    </h5>
                </div>
                <div class="card-body">
                    @if (Auth::user()->role === 'vendor')
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            These are reviews from customers who have used your services. You can view them but cannot edit
                            or delete them.
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table id="reviewsTable" class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Customer</th>
                                    @if (Auth::user()->role === 'admin')
                                        <th>Vendor</th>
                                    @endif
                                    <th>Rating</th>
                                    <th>Review</th>
                                    <th>Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- View Review Modal -->
    <div class="modal fade" id="viewReviewModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Review Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="reviewDetails">
                    <!-- Review details will be loaded here -->
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
        let reviewsTable;

        $(document).ready(function() {
            // Initialize DataTable
            const columns = [{
                    data: 'id',
                    name: 'id'
                },
                {
                    data: 'user_id',
                    name: 'user_id'
                },
                @if (Auth::user()->role === 'admin')
                    {
                        data: 'vendor_profile_id',
                        name: 'vendor_profile_id'
                    },
                @endif {
                    data: 'rating',
                    name: 'rating',
                    orderable: false
                },
                {
                    data: 'review',
                    name: 'review'
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

            reviewsTable = $('#reviewsTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('admin.reviews.data') }}',
                columns: columns,
                order: [
                    [0, 'desc']
                ]
            });
        });

        function viewReview(id) {
            $.get(`{{ route('admin.reviews.show', ':id') }}`.replace(':id', id))
                .done(function(review) {
                    const stars = Array.from({
                            length: 5
                        }, (_, i) =>
                        i < review.rating ? '<i class="fas fa-star text-warning"></i>' :
                        '<i class="far fa-star text-muted"></i>'
                    ).join('');

                    const details = `
                <div class="row">
                    <div class="col-md-4 text-center">
                        <h5>${review.user ? review.user.name : 'Anonymous'}</h5>
                        <div class="mb-2">${stars}</div>
                        <p class="h4 text-primary">${review.rating}/5</p>
                        <small class="text-muted">Posted on ${new Date(review.created_at).toLocaleDateString()}</small>
                        ${review.vendor_profile ? `<p class="mt-3"><strong>For:</strong> ${review.vendor_profile.business_name}</p>` : ''}
                    </div>
                    <div class="col-md-8">
                        <h6>Customer Review:</h6>
                        <div class="bg-light p-3 rounded">
                            <p class="mb-0">${review.review || 'No written review provided.'}</p>
                        </div>
                        ${review.order_id ? `<p class="mt-3"><small class="text-muted">Order ID: #${review.order_id}</small></p>` : ''}
                    </div>
                </div>
            `;
                    $('#reviewDetails').html(details);
                    $('#viewReviewModal').modal('show');
                })
                .fail(function() {
                    toastr.error('Failed to load review details');
                });
        }

        @if (Auth::user()->role === 'admin')
            function deleteReview(id) {
                if (confirm('Are you sure you want to delete this review? This action cannot be undone.')) {
                    $.ajax({
                        url: `{{ route('admin.reviews.destroy', ':id') }}`.replace(':id', id),
                        method: 'DELETE',
                        success: function(response) {
                            if (response.success) {
                                toastr.success(response.message);
                                reviewsTable.ajax.reload();
                            }
                        },
                        error: function() {
                            toastr.error('Failed to delete review');
                        }
                    });
                }
            }
        @endif
    </script>
@endpush

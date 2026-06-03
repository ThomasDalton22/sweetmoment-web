@extends('admin.layouts.app')

@section('title', 'My Profile - Vendor Panel')
@section('page-title', 'My Vendor Profile')

@section('content')
    <div class="row">
        <div class="col-12">
            @if (!$vendorProfile)
                <!-- Create Profile Card -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-store me-2"></i>
                            Create Your Vendor Profile
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            You don't have a vendor profile yet. Create one to start offering your services.
                        </div>
                        <button type="button" class="btn btn-primary btn-lg" data-bs-toggle="modal"
                            data-bs-target="#profileModal" onclick="resetForm()">
                            <i class="fas fa-plus me-2"></i>Create My Profile
                        </button>
                    </div>
                </div>
            @else
                <!-- Profile Overview Card -->
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-store me-2"></i>
                            My Profile Overview
                        </h5>
                        <button type="button" class="btn btn-primary" onclick="editProfile({{ $vendorProfile->id }})">
                            <i class="fas fa-edit me-2"></i>Edit Profile
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-8">
                                <h4>{{ $vendorProfile->business_name }}</h4>
                                <p class="text-muted mb-3">{{ $vendorProfile->description ?: 'No description provided' }}
                                </p>

                                <div class="row">
                                    <div class="col-md-6">
                                        <p><strong>Category:</strong> {{ $vendorProfile->vendorCategory->name ?? '-' }}</p>
                                        <p><strong>Location:</strong> {{ $vendorProfile->location ?: '-' }}</p>
                                        <p><strong>Phone:</strong> {{ $vendorProfile->phone ?: '-' }}</p>
                                        <p><strong>WhatsApp:</strong> {{ $vendorProfile->whatsapp ?: '-' }}</p>
                                    </div>
                                    <div class="col-md-6">
                                        <p><strong>Instagram:</strong> {{ $vendorProfile->instagram ?: '-' }}</p>
                                        <p><strong>Website:</strong>
                                            @if ($vendorProfile->website)
                                                <a href="{{ $vendorProfile->website }}"
                                                    target="_blank">{{ $vendorProfile->website }}</a>
                                            @else
                                                -
                                            @endif
                                        </p>
                                        <p><strong>Price Range:</strong>
                                            Rp {{ number_format($vendorProfile->price_range_min ?: 0) }} -
                                            Rp {{ number_format($vendorProfile->price_range_max ?: 0) }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="text-center">
                                    <h5>Profile Rating</h5>
                                    <div class="mb-2">
                                        @for ($i = 1; $i <= 5; $i++)
                                            <i
                                                class="fas fa-star {{ $i <= $vendorProfile->rating ? 'text-warning' : 'text-muted' }}"></i>
                                        @endfor
                                    </div>
                                    <p class="h4 text-primary">{{ number_format($vendorProfile->rating, 1) }}</p>
                                    <p class="text-muted">{{ $vendorProfile->total_reviews }} reviews</p>

                                    <div class="mt-3">
                                        <span
                                            class="badge bg-{{ $vendorProfile->status === 'active' ? 'success' : ($vendorProfile->status === 'pending' ? 'warning' : 'danger') }} mb-2">
                                            {{ ucfirst($vendorProfile->status) }}
                                        </span>
                                        @if ($vendorProfile->is_verified)
                                            <span class="badge bg-primary mb-2">Verified</span>
                                        @endif
                                        @if ($vendorProfile->is_featured)
                                            <span class="badge bg-warning mb-2">Featured</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Profile Modal -->
    <div class="modal fade" id="profileModal" tabindex="-1">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">{{ $vendorProfile ? 'Edit My Profile' : 'Create My Profile' }}
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="profileForm">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="vendor_category_id" class="form-label">Category *</label>
                                <select class="form-select" id="vendor_category_id" name="vendor_category_id" required>
                                    <option value="">Select Category</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}"
                                            {{ $vendorProfile && $vendorProfile->vendor_category_id == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="business_name" class="form-label">Business Name *</label>
                                <input type="text" class="form-control" id="business_name" name="business_name"
                                    value="{{ $vendorProfile->business_name ?? '' }}" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="3">{{ $vendorProfile->description ?? '' }}</textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <label for="price_range_min" class="form-label">Min Price</label>
                                <input type="number" class="form-control" id="price_range_min" name="price_range_min"
                                    value="{{ $vendorProfile->price_range_min ?? '' }}" min="0" step="0.01">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="price_range_max" class="form-label">Max Price</label>
                                <input type="number" class="form-control" id="price_range_max" name="price_range_max"
                                    value="{{ $vendorProfile->price_range_max ?? '' }}" min="0" step="0.01">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="location" class="form-label">Location</label>
                                <input type="text" class="form-control" id="location" name="location"
                                    value="{{ $vendorProfile->location ?? '' }}">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="phone" class="form-label">Phone</label>
                                <input type="text" class="form-control" id="phone" name="phone"
                                    value="{{ $vendorProfile->phone ?? '' }}">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="whatsapp" class="form-label">WhatsApp</label>
                                <input type="text" class="form-control" id="whatsapp" name="whatsapp"
                                    value="{{ $vendorProfile->whatsapp ?? '' }}">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="instagram" class="form-label">Instagram</label>
                                <input type="text" class="form-control" id="instagram" name="instagram"
                                    value="{{ $vendorProfile->instagram ?? '' }}" placeholder="@username">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="website" class="form-label">Website</label>
                                <input type="url" class="form-control" id="website" name="website"
                                    value="{{ $vendorProfile->website ?? '' }}" placeholder="https://example.com">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary" id="submitBtn">
                            <i class="fas fa-save me-2"></i>{{ $vendorProfile ? 'Update Profile' : 'Create Profile' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            // Form submission
            $('#profileForm').on('submit', function(e) {
                e.preventDefault();

                const formData = new FormData(this);
                const isUpdate = {{ $vendorProfile ? 'true' : 'false' }};
                const url = isUpdate ?
                    `{{ route('admin.vendor-profile.update', ':id') }}`.replace(':id',
                        {{ $vendorProfile->id ?? 0 }}) :
                    '{{ route('admin.vendor-profile.store') }}';

                if (isUpdate) {
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
                            setTimeout(() => {
                                window.location.reload();
                            }, 1500);
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
            $('#modalTitle').text('Create My Profile');
            $('#submitBtn').html('<i class="fas fa-save me-2"></i>Create Profile');
        }

        function editProfile(id) {
            $('#modalTitle').text('Edit My Profile');
            $('#submitBtn').html('<i class="fas fa-save me-2"></i>Update Profile');
            $('#profileModal').modal('show');
        }
    </script>
@endpush

@extends('layouts.user')
@section('container')

    <!-- Contact Section -->
    <section id="contact" class="contact section">

        <!-- Section Title -->
        <div class="container section-title" data-aos="fade-up">
            <h2>User / Chatlist</h2>
        </div><!-- End Section Title -->

        <div class="container" data-aos="fade-up" data-aos-delay="100">

            <div class="row gy-4">

                <!-- Loop untuk menampilkan semua vendor -->
                @foreach ($vendors as $vendor)
                    <div class="col-md-12">
                        <a href="{{ route('user.chatbox', $vendor->id) }}">
                            <div class="info-item d-flex align-items-center" data-aos="fade-up" data-aos-delay="200">
                                <i class="icon bi bi-person-circle flex-shrink-0"></i>
                                <div>
                                    <h3 style="color:#bba016">Vendor</h3> 
                                    <p style="color:#bba016">{{ $vendor->name }}</p> 
                                </div>
                            </div>
                        </a>
                    </div><!-- End Info Item -->
                @endforeach

            </div>

        </div>

    </section><!-- /Contact Section -->

@endsection

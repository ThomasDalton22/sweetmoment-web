@extends('layouts.vendor')
@section('container')

    <!-- Contact Section -->
    <section id="contact" class="contact section">

      <!-- Section Title -->
      <div class="container section-title" data-aos="fade-up">
        <h2>Vendor / Profile</h2>
        <p><span>{{ auth()->user()->name }}</span></p>
      </div><!-- End Section Title -->

      <div class="container" data-aos="fade-up" data-aos-delay="100">


        <div class="row gy-4">

          <div class="col-md-12">

            <div class="info-item d-flex align-items-center" data-aos="fade-up" data-aos-delay="200">
              <i class="icon bi bi-person-circle flex-shrink-0"></i>
              <div>
                <h3 style="color:#bba016">Nama</h3>
                <p style="color:#bba016">{{ $user->name }}</p>
              </div>
            </div>
          </div><!-- End Info Item -->
          <div class="col-md-12">
            <div class="info-item d-flex align-items-center" data-aos="fade-up" data-aos-delay="200">
              <i class="icon bi bi-geo-alt flex-shrink-0"></i>
              <div>
                <h3 style="color:#bba016">Alamat</h3>
                <p style="color:#bba016">{{ $user->address }}</p>
              </div>
            </div>
          </div><!-- End Info Item -->
          <div class="col-md-12">
            <div class="info-item d-flex align-items-center" data-aos="fade-up" data-aos-delay="200">
              <i class="icon bi bi-envelope flex-shrink-0"></i>
              <div>
                <h3 style="color:#bba016">Email</h3>
                <p style="color:#bba016">{{ $user->email }}</p>
              </div>
            </div>
          </div><!-- End Info Item -->
          <div class="col-md-12">
            <div class="info-item d-flex align-items-center" data-aos="fade-up" data-aos-delay="200">
              <i class="icon bi bi-lock flex-shrink-0"></i>
              <div>
                <h3 style="color:#bba016">Password</h3>
                <p style="color:#bba016">************</p>
              </div>
            </div>
          </div><!-- End Info Item -->
          <div class="text-center mt-3">
            <a href="{{ route('vendor.profile.edit', auth()->user()->id) }}" style="background: white; padding:10px">UBAH DATA VENDOR</a>
          </div>
        </div>
      </div>

    </section><!-- /Contact Section -->



    
@endsection
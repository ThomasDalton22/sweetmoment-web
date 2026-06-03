@extends('layouts.vendor')
@section('container')

    <!-- Contact Section -->
    <section id="contact" class="contact section">

      <!-- Section Title -->
      <div class="container section-title" data-aos="fade-up">
        <h2>Vendor / Chatlist</h2>
        <!-- <p><span>Mortekiano</span></p> -->
      </div><!-- End Section Title -->

      <div class="container" data-aos="fade-up" data-aos-delay="100">


        <div class="row gy-4">

                @foreach ($users as $user)
                    <div class="col-md-12">
                        <a href="{{ route('vendor.chatbox', $user->id) }}">
                            <div class="info-item d-flex align-items-center" data-aos="fade-up" data-aos-delay="200">
                                <i class="icon bi bi-person-circle flex-shrink-0"></i>
                                <div>
                                    <h3 style="color:#bba016">User</h3> 
                                    <p style="color:#bba016">{{ $user->name }}</p> 
                                </div>
                            </div>
                        </a>
                    </div><!-- End Info Item -->
                @endforeach



      </div>

    </section><!-- /Contact Section -->

@endsection
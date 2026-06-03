@extends('layouts.vendor')
@section('container')



<!-- Book A Table Section -->
<section id="book-a-table" class="book-a-table section">

  <!-- Section Title -->
  <div class="container section-title" data-aos="fade-up">
    <h2>Vendor/Customer</h2>
    <p>Penawaran Customer</p>
  </div><!-- End Section Title -->

  <div class="container">

    <div class="row g-0" data-aos="fade-up" data-aos-delay="100">

    <div class="container d-flex justify-content-center align-items-center reservation-form-bg" data-aos="fade-up" data-aos-delay="200" style="padding:2rem;">
  <form action="{{ route('user.riwayat.store') }}" method="post" role="form" class=" w-100">
  @csrf  
    <div class="row gy-4">
      <div class="col-12">
        <label for="name">Nama</label>
        <input type="text" class="form-control" name="name" id="name" placeholder="name" required="">
      </div>  
      <div class="col-12">
        <label for="address">Address</label>
        <input type="text" class="form-control" name="address" id="address" placeholder="address" required="">
      </div>  
      <div class="col-12">
        <label for="phone">Phone</label>
        <input type="number" class="form-control" name="phone" id="phone" placeholder="phone" required="">
      </div>
      <div class="col-12">
        <label for="qty">Qty</label>
        <input type="number" class="form-control" name="qty" id="qty" placeholder="qty" required="">
      </div>

    </div>
    <div class="text-center mt-3">
      <button class="publisher-btn text-warning" type="submit" style="width: 50%;  padding:1rem">Ajukan</button>
    </div>
  </form>
</div><!-- End Reservation Form -->



    </div>

  </div>

</section><!-- /Book A Table Section -->

    
@endsection
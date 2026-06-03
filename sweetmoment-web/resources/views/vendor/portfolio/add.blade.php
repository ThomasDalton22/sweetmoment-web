@extends('layouts.vendor')
@section('container')



<!-- Book A Table Section -->
<section id="book-a-table" class="book-a-table section">

  <!-- Section Title -->
  <div class="container section-title" data-aos="fade-up">
    <h2>Vendor/Portfolio</h2>
    <p>Portfolio Vendor</p>
  </div><!-- End Section Title -->

  <div class="container">

    <div class="row g-0" data-aos="fade-up" data-aos-delay="100">

    <div class="container d-flex justify-content-center align-items-center reservation-form-bg" data-aos="fade-up" data-aos-delay="200" style="padding:2rem;">
  <form action="{{ route('vendor.portfolio.store') }}" method="post" role="form" class=" w-100" enctype="multipart/form-data">
  @csrf  
    <div class="row gy-4">
      <div class="col-12">
        <label for="image">Image</label>
        <input type="file" class="form-control" name="image" id="image" required="">
      </div>  
      <div class="col-12">
        <label for="description">Description</label>
        <textarea class="form-control" name="description" id="description"></textarea>
      </div>  
    </div>
    <div class="text-center mt-3">
      <button class="publisher-btn text-warning" type="submit" style="width: 50%;  padding:1rem">Simpan</button>
    </div>
  </form>
</div><!-- End Reservation Form -->



    </div>

  </div>

</section><!-- /Book A Table Section -->

    
@endsection
@extends('layouts.user')
@section('container')

<!-- Book A Table Section -->
<section id="book-a-table" class="book-a-table section">

  <!-- Section Title -->
  <div class="container section-title" data-aos="fade-up">
    <h2>User/Testimony</h2>
    <p>Testimony</p>
  </div><!-- End Section Title -->

  <div class="container">

    <div class="row g-0" data-aos="fade-up" data-aos-delay="100">

    <div class="container d-flex justify-content-center align-items-center reservation-form-bg" data-aos="fade-up" data-aos-delay="200" style="padding:2rem;">
    <form action="{{ route('user.testimony.update') }}" method="post" role="form" class="w-100" enctype="multipart/form-data">
  @csrf  
  <div class="row gy-4">
    <div class="col-12">
      <label for="testimony">Testimony</label>
      <textarea class="form-control" name="testimony" id="testimony" required></textarea>
    </div>
    <div class="col-12">
      <label for="rating">Rating</label>
      <select class="form-control" name="rating" id="rating" required>
        <option value="1">1 Star</option>
        <option value="2">2 Stars</option>
        <option value="3">3 Stars</option>
        <option value="4">4 Stars</option>
        <option value="5">5 Stars</option>
      </select>
    </div>
  </div>
  <div class="text-center mt-3">
    <button class="publisher-btn text-warning" type="submit" style="width: 50%; padding:1rem">Simpan</button>
  </div>
</form>

</div><!-- End Reservation Form -->
    </div>

  </div>

</section><!-- /Book A Table Section -->

    
@endsection
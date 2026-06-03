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
  <form action="{{ route('vendor.customer.store') }}" method="post" role="form" class=" w-100">
  @csrf  
    <div class="row gy-4">
      <div class="col-12">
        <label for="jenispenawaran">Jenis Penawaran</label>
        <select id="jenispenawaran" class="form-control" name="jenispenawaran">
          <option value="weddingorganizer">Wedding Organizer</option>
          <option value="partyorganizer">Party Organizer</option>
        </select>
      </div>
      <div class="col-12">
        <label for="budget">Estimasi Budget</label>
        <input type="number" class="form-control" name="budget" id="budget" placeholder="Estimasi Budget" required="">
      </div>  
      <div class="form-group mt-3">
        <label for="catatan">Catatan</label>
        <textarea class="form-control" name="catatan" rows="5" placeholder="Ex: Catering 15 pcs, MUA 5 pcs" required=""></textarea>
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
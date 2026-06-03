@extends('layouts.user')
@section('container')



<!-- Book A Table Section -->
<section id="book-a-table" class="book-a-table section">

  <!-- Section Title -->
  <div class="container section-title" data-aos="fade-up">
    <h2>User/Riwayat</h2>
    <p>Order Paket</p>
  </div><!-- End Section Title -->

  <div class="container">

    <div class="row g-0" data-aos="fade-up" data-aos-delay="100">

    <div class="container d-flex justify-content-center align-items-center reservation-form-bg" data-aos="fade-up" data-aos-delay="200" style="padding:2rem;">
  <form action="{{ route('user.riwayat.store') }}" method="post" role="form" class=" w-100">
  @csrf  
    <div class="row gy-4">
      <div class="col-12">
        <label for="jenispemesanan">Jenis Pemesanan</label>
        <select class="form-control" name="jenispemesanan" id="jenispemesanan" required>    
            <option value="Wedding_Organizer">Wedding Organizer</option>
            <option value="Party_Organizer">Party Organizer</option>
        </select>
      </div>
      <div class="col-12">
            <label for="nama_vendor">Nama Vendor</label>
            <select class="form-control" name="nama_vendor" id="nama_vendor" required>
                <option value="">Pilih Nama Vendor</option>
                @foreach($vendors as $vendor)
                    <!-- Menggunakan nama vendor sebagai value -->
                    <option value="{{ $vendor->name }}">{{ $vendor->name }}</option>
                @endforeach
            </select>
      </div>
      <div class="col-12">
        <label for="tanggal_acara">Tanggal Acara</label>
        <input type="date" class="form-control" name="tanggal_acara" id="tanggal_acara" placeholder="tanggal acara" required="">
      </div>  
      <div class="col-12">
        <label for="catatan">Catatan</label>
        <textarea class="form-control" name="catatan" id="catatan" placeholder="catatan" required=""></textarea>
      </div>
      <div class="col-12">
        <label for="harga">Harga</label>
        <input type="number" class="form-control" name="harga" id="harga" placeholder="harga" required="">
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
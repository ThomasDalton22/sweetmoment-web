@extends('layouts.vendor')
@section('container')

<section id="book-a-table" class="book-a-table section">

  <div class="container">

    <!-- Menambahkan beberapa kelas CSS untuk penataan lebih rapi -->
    <div class="text-left mt-6 mb-3">
      <a href="{{ route('vendor.customer.add') }}" class="btn btn-primary">Tambah Penawaran</a>
    </div>

    <table class="table-fill">
        <thead>
            <tr>
                <th class="text-left">Jenis Penawaran</th>
                <th class="text-left">Catatan</th>
                <th class="text-left">Budget</th>
                <th class="text-left">Aksi</th>
            </tr>
        </thead>
        <tbody class="table-hover">
            @foreach($vendor_offers as $vendor_offer)
            <tr>
                <td class="text-left">{{$vendor_offer->jenispenawaran}}</td>
                <td class="text-left">{{$vendor_offer->catatan}}</td>
                <td class="text-left">{{$vendor_offer->budget}}</td>
                <td class="text-left">
                    <a href="{{route('vendor.customer.delete', $vendor_offer->id)}}" ><i class="bi bi-trash"></i><br></a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

  </div>

</section><!-- /Book A Table Section -->
@endsection

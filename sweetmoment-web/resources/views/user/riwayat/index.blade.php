@extends('layouts.user')

@section('container')

<section id="book-a-table" class="book-a-table section">

  <div class="container">
    <div class="text-left mt-6 mb-3">
      <a href="{{ route('user.riwayat.add') }}" class="btn btn-primary">Tambah Pesanan</a>
    </div>


    <table class="table-fill">
    <thead>
        <tr>
            <th class="text-left">Jenis Penawaran</th>
            <th class="text-left">Nama Vendor</th>
            <th class="text-left">Tanggal</th>
            <th class="text-left">Harga</th>
            <th class="text-left">Status</th>
        </tr>
    </thead>
    <tbody class="table-hover">
        @foreach($pembayarans as $pembayaran)
            <tr>
                <td class="text-left">{{ $pembayaran->jenispemesanan }}</td>
                <td class="text-left">{{ $pembayaran->nama_vendor }}</td>
                <td class="text-left">{{ $pembayaran->tanggal_acara }}</td>
                <td class="text-left">{{ number_format($pembayaran->harga, 0, ',', '.') }}</td>
                <td class="text-left">{{ $pembayaran->status }}</td>
            </tr>
        @endforeach
    </tbody>
</table>



  </div>

</section>


@endsection

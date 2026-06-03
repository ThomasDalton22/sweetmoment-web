@extends('layouts.vendor')

@section('container')

<section id="book-a-table" class="book-a-table section">

  <div class="container">
    <div class="text-left mt-6 mb-3">
      <!-- <a href="{{ route('vendor.riwayat.add') }}" class="btn btn-primary">Tambah Pesanan</a> -->
    </div>


        <table class="table-fill">
            <thead>
                <tr>
                    <th class="text-left">Jenis Penawaran</th>
                    <th class="text-left">Nama Pemesan</th>
                    <th class="text-left">Tanggal</th>
                    <th class="text-left">Harga</th>
                    <th class="text-left">Status</th>

                </tr>
            </thead>
            <tbody class="table-hover">
            @foreach($pembayarans as $pembayaran)
                <tr>
                    <td>{{ $pembayaran->jenispemesanan }}</td>
                    <td>{{ $pembayaran->nama_pemesan }}</td>
                    <td>{{ $pembayaran->tanggal_acara }}</td>
                    <td>{{ number_format($pembayaran->harga, 0, ',', '.') }}</td>
                    <td>{{ $pembayaran->status }}</td>

                </tr>
            @endforeach
        </tbody>
        </table>


  </div>

</section>

<script type="text/javascript">
    // Pastikan Midtrans Snap JS sudah terload
    document.addEventListener('DOMContentLoaded', function () {
        // Ambil semua tombol bayar
        var payButtons = document.querySelectorAll('.pay-button');

        // Tambahkan event listener untuk setiap tombol bayar
        payButtons.forEach(function (payButton) {
            payButton.addEventListener('click', function () {
                var snapToken = payButton.getAttribute('data-snap-token');

                if (snapToken) {
                    // Memulai transaksi dengan Snap Token
                    window.snap.pay(snapToken, {
                        onSuccess: function(result){
                            alert('Payment Success');
                            console.log(result);
                            // Anda bisa melakukan update status order atau pengolahan lain di sini
                        },
                        onPending: function(result){
                            alert('Waiting for payment confirmation');
                            console.log(result);
                        },
                        onError: function(result){
                            alert('Payment Failed');
                            console.log(result);
                            // Tangani error pembayaran
                        },
                        onClose: function(){
                            alert('Payment window closed');
                            console.log("Payment window closed");
                        }
                    });
                } else {
                    alert('Snap Token is not available');
                }
            });
        });
    });
</script>

@endsection

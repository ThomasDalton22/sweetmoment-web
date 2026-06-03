@extends('layouts.vendor')

@section('container')

<section id="book-a-table" class="book-a-table section">

  <div class="container">
    <div class="text-left mt-6 mb-3">
      <a href="{{ route('vendor.portfolio.add') }}" class="btn btn-primary">Tambah Porto</a>
    </div>
        <table class="table-fill">
            <thead>
                <tr>
                    <th class="text-left">No</th>
                    <th class="text-left">Gambar</th>
                    <th class="text-left">Keterangan</th>
                    <th class="text-left">Aksi</th>
                </tr>
            </thead>
            <tbody class="table-hover">
                @foreach($portfolios as $porfolio)
                <tr>
                    <td>{{$porfolio->id}}</td>
                    <td>
                                @if($porfolio->image)
                                    <img src="{{ asset($porfolio->image) }}" alt="Image" style="width: 100px; height: auto;">
                                @else
                                    No Image
                                @endif
                    </td>
                    <td>{{$porfolio->description}}</td>
                    <td class="text-left">
                        <a href="{{route('vendor.portfolio.delete', $porfolio->id)}}" ><i class="bi bi-trash"></i><br></a>
                    </td>
                </tr>
                @endforeach
        </tbody>
        </table>
  </div>

</section>

<script type="text/javascript">
    document.addEventListener('DOMContentLoaded', function () {
    
        var payButtons = document.querySelectorAll('.pay-button');

     
        payButtons.forEach(function (payButton) {
            payButton.addEventListener('click', function () {
                var snapToken = payButton.getAttribute('data-snap-token');

                if (snapToken) {
                   
                    window.snap.pay(snapToken, {
                        onSuccess: function(result){
                            alert('Payment Success');
                            console.log(result);
                          
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

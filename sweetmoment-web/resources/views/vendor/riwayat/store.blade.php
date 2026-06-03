<!-- resources/views/user/pembayaran/index.blade.php -->
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <script src="https://unpkg.com/feather-icons"></script>
    <link rel="icon" href="{{ asset('../images/cincin.png') }}" type="image/png">
    <title>Wedding Organizer - Surabaya</title>
    <script type="text/javascript" src="https://app.sandbox.midtrans.com/snap/snap.js"
            data-client-key="{{ config('midtrans.client_key') }}"></script>
</head>
<body>
    <div class="form-container">
        <h2>Pembayaran</h2>
    </div>

    <div class="row">
        <button id="pay-button">Bayar</button>
    </div>

    <script type="text/javascript">
        // Menggunakan Snap Token yang diterima dari server
        var snapToken = '{{ $snapToken }}';
        
        // Ketika tombol bayar diklik
        var payButton = document.getElementById('pay-button');
        payButton.addEventListener('click', function () {
            window.snap.pay(snapToken, {
                onSuccess: function (result) {
                    alert("Payment success!");
                    console.log(result);
                },
                onPending: function (result) {
                    alert("Waiting for payment!");
                    console.log(result);
                },
                onError: function (result) {
                    alert("Payment failed!");
                    console.log(result);
                },
                onClose: function () {
                    alert('You closed the popup without finishing the payment');
                }
            });
        });
    </script>
</body>
</html>

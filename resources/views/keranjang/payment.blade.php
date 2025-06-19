@extends('layouts.appnoslider')

@section('title')
    Pembayaran Pesanan
@endsection

@section('content')
<div class="container" style="min-height: 60vh; padding-top: 120px; padding-bottom: 40px;">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header text-center bg-success text-white">
                    <h4>Selesaikan Pembayaran Anda</h4>
                </div>
                <div class="card-body text-center">
                    <p>Terima kasih telah memesan. Silakan selesaikan pembayaran Anda.</p>
                    <table class="table table-bordered">
                        <tr>
                            <th>ID Pesanan</th>
                            <td>#{{ $pesanan->id }}</td>
                        </tr>
                        <tr>
                            <th>Total Pembayaran</th>
                            <td><strong>Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }}</strong></td>
                        </tr>
                    </table>

                    <button id="pay-button" class="btn btn-primary mt-3 px-5">Bayar Sekarang</button>
                    <p class="text-muted mt-2"><small>Anda akan diarahkan ke halaman pembayaran Midtrans.</small></p>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
<script type="text/javascript">
    var payButton = document.getElementById('pay-button');
    payButton.addEventListener('click', function () {
        snap.pay('{{ $snapToken }}', {
            onSuccess: function(result){
                console.log(result);
                // Di `PesananController` Anda, tidak ada route `user.pesanan`, jadi kita arahkan ke home dulu
                window.location.href = '/?payment_status=success';
            },
            onPending: function(result){
                console.log(result);
                window.location.href = '/?payment_status=pending';
            },
            onError: function(result){
                console.log(result);
                alert("Pembayaran Gagal!");
            },
            onClose: function(){
                alert('Anda menutup jendela pembayaran sebelum selesai.');
            }
        });
    });

    document.addEventListener("DOMContentLoaded", function() {
        payButton.click();
    });
</script>
@endsection

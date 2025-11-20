@extends('templates.app')

@section('content')
    <div class="card w-50 d-block mx-auto my-5 p-4">
        <div class="card-body">

            <h5 class="d-flex justify-content-center ">Selesaikan Pembayaran</h5>
            <img src="{{ asset('storage/' . $ticket['ticketPayment']['barcode']) }}" alt="" class="d-block mx-auto">

            <div class="d-flex justify-content-between">
                <p>{{ $ticket['quantity'] }} Tiket</p>
            </div>

            <div class="d-flex justify-content-between">
                <p>Harga Tiket</p>
                <p><b>Rp. {{ number_format($ticket['schedule']['price'], 0, ',', '.')}}<span class="text-secondary"> X
                            {{ $ticket['quantity'] }}</span></b></p>
            </div>

            <div class="d-flex justify-content-between">
                <p>Biaya Layanan</p>
                <p><b>Rp. 4.000<span class="text-secondary">
                            X {{ $ticket['quantity'] }}</span></b></p>
            </div>

            <div class="d-flex justify-content-between">
                <p>Promo</p>

                @if ($ticket['promo_id'] != NULL) {{-- jika promo nya bukan null (milih promo sebelumnya) --}}
                        <p><b>{{ $ticket['promo']['type'] == 'percent' ? $ticket['promo']['discount'] . '%' : 'Rp.' .
                    number_format($ticket['promo']['discount'], 0, ',', '.') }}</b></p>
                @else
                    <p><b>-</b></p>
                @endif

            </div>

            <hr>

            @php
                // harga keseluruhan dair total price yang uda dapat diskon promo ditambah biaya layanan 4000 di kali jumlah ticket
                $price = $ticket['total_price'] + (4000 * $ticket['quantity']);
            @endphp

            <div class="d-flex justify-content-end">
                <p><b>Rp. {{ number_format($price, 0, ',', '.') }}</b></p>
            </div>

            <form action="{{ route('tickets.payment.proof', $ticket->id) }}" method="POST">
                @csrf
                @method('PATCH')
                <button class="btn btn-primary btn-lg btn-block rounded-pill" style="background-color: black">Sudah
                    Dibayar</button>
            </form>

        </div>
    </div>
@endsection
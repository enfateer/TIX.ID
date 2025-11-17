@extends('templates.app')

@section('content')

    <div class="container card my-5 p-4" style="margin-bottom: 10% !important">
        <div class="card-body">

            <b>{{ $schedule['cinema']['name'] }}</b>
            <br>
            <b>{{ now()->format('d F, Y') }} || {{ $hour }}</b>
        </div>

        <div class="alert my-2 alert-secondary">
            <i class="fa-solid fa-info text-danger me-3"></i>Anak berusia 2 tahun ke atas wajib membeli tiket.
        </div>

        <div class="d-flex justify-content-center">
            <div class="row w-75">
                <div class="col-4">
                    <div style="width: 20px; height: 20px; background: #112646;"></div>Kursi Tersedia
                </div>
                <div class="col-4">
                    <div style="width: 20px; height: 20px; background: #eaeaea;"></div>Kursi Terjual
                </div>
                <div class="col-4">
                    <div style="width: 20px; height: 20px; background: blue;"></div>Kursi Dipilih
                </div>
            </div>
        </div>

        @php
            // membuat data a-h
            $row = range('A', 'H');
            // membuat data 1-18
            $col = range(1, 18);
        @endphp

        {{-- looping baris a - h --}}
        @foreach ($row as $baris)
            <div class="d-flex justify-content-center">
                {{-- looping angka Kursi --}}
                @foreach ($col as $nomorKursi)
                    @if($nomorKursi == 7)
                        <div style="width: 55px"></div>
                    @endif
                    <div style="background: #112646; color: white; text-align: center; padding-top: 10px; width: 45px; height: 45px; border-radius: 10px; margin: 10px 3px;
                                                                            cursor: pointer;"
                        onclick="selectedSeats('{{ $schedule->price }}', '{{ $baris }}', '{{ $nomorKursi }}', this)">
                        {{ $baris }}-{{ $nomorKursi }}
                    </div>

                @endforeach
            </div>
        @endforeach
    </div>
    </div>

    <div class="fixed-bottom w-100 bg-light  text-center pt-1">
        <b class="text-center">LAYAR BIOSKOP</b>
        <div class="row mt-2" style="border: 1px solid #eaeaea;">
            <div class="col-6 p-4 text-center" style="border: 1px solid #eaeaea;">
                <h5>Total Harga</h5>
                <h5 id="totalPrice">Rp. -</h5>
            </div>
            <div class="col-6 p-4 text-center" style="border: 1px solid #eaeaea;">
                <h5>Tempat Duduk</h5>
                <h5 id="seats">Belum Dipilih</h5>
            </div>
        </div>

        {{-- input hidde yang disembunyikan hanya untuk menuimpan nilai yang di perlukan js untk proses tambah dat ticket
        --}}
        <input type="hidden" name="user_id" id="user_id" value="{{ Auth::user()->id }}">
        <input type="hidden" name="schedule_id" id="schedule_id" value="{{ $schedule->id }}">
        <input type="hidden" name="hours" id="hours" value="{{ $hour }}">
        <div class="text-center p-2 w-100" style="cursor: pointer" id="btnOrder"><b>RINGKASAN ORDER</b></div>
    </div>
@endsection

@push('script')
    <script>
        // menyimpan data kursi yag dipilih
        let seats = [];
        let totalPrice = 0;
        function selectedSeats(price, baris, nomorKursi, element) {
            let seat = baris + "-" + nomorKursi;
            // cek apakah kursi ini sudah i pilih sbelum nya, 
            let indexSeat = seats.indexOf(seat);

            if (indexSeat == -1) {
                element.style.background = "blue";
                seats.push(seat);
            } else {
                element.style.background = "#112646";
                seats.splice(indexSeat, 1);
            }

            let totalPriceElement = document.querySelector("#totalPrice");
            let seatsElement = document.querySelector("#seats");

            totalPrice = price * (seats.length);
            totalPriceElement.innerHTML = "Rp. " + totalPrice;

            seatsElement.innerHTML = seats.join(", ")

            let btnOrder = document.querySelector("#btnOrder");
            // seats array isi nnya lebih dari sama dengan satu, aktifin btn ORDER
            if (seats.length >= 1) {
                btnOrder.style.background = '#112646';
                btnOrder.style.color = 'white';
                // buat agar ketika di klik mengatah ke createTicket
                btnOrder.onclick = createTicket;
            } else {
                btnOrder.style.background = '';
                btnOrder.style.color = '';
                btnOrder.onclick = null;
            }

        }

        function createTicket() {
            // AJAX (asynchronus jacascript and XML) : 
            // proses mengambil/menambahkan data dari/ ke database. hanya bisa di gunakan melalui JQuery (library yang penulisannya beruap javascript modern, gaya penulisan lebih singkat, $())
            $.ajax({
                url: "{{ route('tickets.store') }}", // route untuk proses data
                method: "POST", // http method sesuai urld
                data: {
                    // data yang mau di kirim ke route ( kl di html, inpt form)
                    _token: "{{ csrf_token() }}",
                    user_id: $('#user_id').val(), // value="" dari input id="user_id"
                    schedule_id: $('#schedule_id').val(),
                    hours: $('#hours').val(),
                    quantity: seats.length, // jummlah item array seats
                    total_price: totalPrice,
                    rows_of_seats: seats,
                    // fillable = value
                },
                success: function(response) { // kalau berhaisl, mau ngapain, data hasil di simpe di (response)
                    // console.log(response)
                    // redirect JS : window.location.href
                    // response : message dan data
                    let ticketId = response.data.id;
                    window.location.href = `/tickets/${ticketId}/order`;
                },
                error: function(message) { // kalau di server nya ada error mau ngapain
                    alert("terjadi kesalahan saat membuat ticket")
                }
            })
        }
    </script>
@endpush
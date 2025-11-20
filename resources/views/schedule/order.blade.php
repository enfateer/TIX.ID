@extends('templates.app')

@section('content')

    <div class="container my-5">
        <div class="card shadow-sm border-0 p-4" style="margin-bottom: 10% !important">
            <div class="card-body">

                <!-- Header -->
                <h5 class="fw-bold mb-4">RINGKASAN PEMESANAN</h5>

                <!-- Movie + Cinema Info -->
                <div class="d-flex gap-4 mb-4">
                    <img src="{{ asset('storage/' . $ticket['schedule']['movie']['poster']) }}" width="130"
                        class="rounded shadow-sm" alt="Poster Film">

                    <div class="d-flex flex-column">
                        <span class="text-warning fw-bold">{{ $ticket['schedule']['cinema']['name'] }}</span>
                        <span class="fw-bold fs-5">{{ $ticket['schedule']['movie']['title'] }}</span>

                        <table class="mt-3">
                            <tr>
                                <td class="text-secondary">Genre</td>
                                <td class="px-3">:</td>
                                <td><b>{{ $ticket['schedule']['movie']['genre'] }}</b></td>
                            </tr>
                            <tr>
                                <td class="text-secondary">Sutradara</td>
                                <td class="px-3">:</td>
                                <td><b>{{ $ticket['schedule']['movie']['director'] }}</b></td>
                            </tr>
                            <tr>
                                <td class="text-secondary">Durasi</td>
                                <td class="px-3">:</td>
                                <td><b>{{ $ticket['schedule']['movie']['duration'] }}</b></td>
                            </tr>
                            <tr>
                                <td class="text-secondary">Rating Usia</td>
                                <td class="px-3">:</td>
                                <td>
                                    <span class="badge bg-danger text-light px-2 py-1">
                                        {{ $ticket['schedule']['movie']['age_rating'] }}
                                    </span>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>

                <!-- Order ID -->
                <p class="text-secondary mb-1">
                    <b>NOMOR PESANAN : {{ $ticket->id }}</b>
                </p>

                <hr>

                <!-- Detail Pesanan -->
                <h6 class="fw-bold mb-3">Detail Pesanan</h6>

                <table class="mb-4">
                    <tr>
                        <td class="text-secondary">Kursi yang dipilih</td>
                        <td class="px-3">:</td>
                        <td><b>{{ implode(', ', $ticket['rows_of_seats']) }}</b></td>
                    </tr>
                    <tr>
                        <td class="text-secondary">Harga Ticket</td>
                        <td class="px-3">:</td>
                        <td>
                            Rp <b>{{ number_format($ticket['schedule']['price'], 0, ',', '.') }}</b>
                            <span class="text-secondary">x {{ $ticket['quantity'] }}</span>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-secondary">Biaya Layanan</td>
                        <td class="px-3">:</td>
                        <td>
                            Rp <b>4.000</b>
                            <span class="text-secondary">x {{ $ticket['quantity'] }}</span>
                        </td>
                    </tr>
                </table>

                <!-- Promo -->
                <label for="promo_id" class="fw-semibold mb-2">Gunakan Promo</label>
                <select name="promo_id" id="promo_id" class="form-select" onchange="selectPromo(this)">
                    @if (count($promos) < 1)
                        <option disabled hidden selected>Tidak ada promo aktif saat ini</option>
                    @else
                        <option disabled hidden selected>Pilih Promo</option>
                        @foreach ($promos as $promo)
                                <option value="{{ $promo->id }}">
                                    {{ $promo->promo_code }} -
                                    {{ $promo->type == 'percent'
                            ? $promo->discount . '%'
                            : 'Rp ' . number_format($promo->discount, 0, ',', '.') }}
                                </option>
                        @endforeach
                    @endif
                </select>

            </div>
        </div>
    </div>

    <!-- Bottom Button -->
    <input type="hidden" name="ticket_id" value="{{ $ticket->id }}" id="ticket_id">
    <div class="fixed-bottom w-100 p-2 text-center" style="background: #112646; color: white; cursor: pointer;"
        onclick="createQR()"><b>BAYAR SEKARANG</b></div>

@endsection

@push('script')
    <script>
        let promoId = null;
        function selectPromo(element) {
            // isi nilai promo dari value select
            promoId = element.value;
        }

        function createQR() {
            let data = {
                _token: "{{ csrf_token() }}",
                promo_id: promoId,
                ticket_id: $("#ticket_id").val()
            }
            $.ajax({
                url: "{{ route('tickets.payment') }}",
                type: "POST",
                data: data,
                success: function(response) {
                    const ticketId = response.data.ticket_id;
                    window.location.href = `/tickets/${ticketId}/payment`;
                }, 
                error: function(xhr) {
                    alert("Terjadi kesalahan saat mengirim data");
                    console.error(xhr.responseText);
                }
            })
        }
    </script>
@endpush
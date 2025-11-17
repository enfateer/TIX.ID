@extends('templates.app')

@section('content')
<div class="container my-5 card">
    <div class="card-body">
        <i class="fa-solid fa-location-dot me-3"></i>{{ $schedules[0]['cinema']['location'] }}
        <hr>

        @foreach ($schedules as $schedule)
            <div class="my-2">
                <div class="row g-4 align-items-start shadow-lg rounded-4 bg-white p-4">

                    {{-- Poster --}}
                    <div class="col-md-4 d-flex justify-content-center">
                        <div class="poster-wrapper overflow-hidden rounded-4 shadow-sm" style="max-height: 450px;">
                            <img src="{{ asset('storage/' . $schedule['movie']['poster']) }}"
                                 alt="{{ $schedule['title'] }}"
                                 class="img-fluid rounded-4 w-100 h-100 object-fit-cover">
                        </div>
                    </div>

                    {{-- Detail Film --}}
                    <div class="col-md-8">
                        <h2 class="fw-bold mb-3">{{ $schedule['movie']['title'] }}</h2>
                        <p class="text-muted mb-4">
                            {{ $schedule['movie']['genre'] }} • {{ $schedule['movie']['duration'] }} menit
                        </p>

                        <table class="table table-borderless small mb-3">
                            <tbody>
                                <tr>
                                    <td class="text-secondary" width="130">Sutradara</td>
                                    <td>{{ $schedule['movie']['director'] }}</td>
                                </tr>
                                <tr>
                                    <td class="text-secondary">Rating Usia</td>
                                    <td>
                                        <span class="badge bg-danger px-3 py-2 fs-6">
                                            {{ $schedule['movie']['age_rating'] }}+
                                        </span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="w-100 my-3">
                    <div class="d-flex justify-content-end">
                        <b>Rp. {{ number_format($schedule['price'], 0, ',', '.') }}</b>
                    </div>

                    <div class="d-flex gap-3 ps-3 my-2">
                        @foreach ($schedule['hours'] as $index => $hours)
                            {{-- this => mengirimkan element html ke js untuk dimanipulasi --}}
                            <button class="btn btn-outline-dark btn-sm px-3 py-2 rounded-pill"
                                    onclick="selectedHour('{{ $schedule->id }}', '{{ $index }}', this)">
                                {{ $hours }}
                            </button>
                        @endforeach
                    </div>
                </div>

                <hr>
            </div>
        @endforeach
    </div>
</div>

{{-- Tombol Beli Tiket --}}
<div class="w-100 fixed-bottom bg-light text-center py-2" id="wrapBtn">
    <a href="javascript:void(0)" id="btnTiket">Beli Tiket</a>
</div>
@endsection

@push('script')
<script>
    let btnBefore = null;

    function selectedHour(scheduleId, hourId, element) {
        // Reset tombol sebelumnya
        if (btnBefore) {
            btnBefore.style.background = '';
            btnBefore.style.color = '';
            btnBefore.style.borderColor = '';
        }

        // Gaya untuk tombol aktif
        element.style.background = '#112646';
        element.style.color = 'white';
        element.style.borderColor = '#112646';
        btnBefore = element;

        // Ubah tampilan tombol bawah
        const wrapBtn = document.querySelector("#wrapBtn");
        const btnTiket = document.querySelector("#btnTiket");

        wrapBtn.style.background = "#112646";
        wrapBtn.classList.remove("bg-light");
        btnTiket.style.color = "white";

        // Buat URL dinamis
        const url = "{{ route('schedules.seats', ['scheduleId' => ':scheduleId', 'hourId' => ':hourId']) }}"
            .replace(":scheduleId", scheduleId)
            .replace(":hourId", hourId);

        btnTiket.href = url;
    }
</script>
@endpush

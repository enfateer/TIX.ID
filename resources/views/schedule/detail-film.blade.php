@extends('templates.app')

@section('content')
    <div class="container py-5">
        <div class="w-75 mx-auto">

            {{-- Poster + Detail Film --}}
            <div class="row g-4 align-items-start shadow-lg rounded-4 bg-white p-4">
                {{-- Poster --}}
                <div class="col-md-4 d-flex justify-content-center">
                    <div class="poster-wrapper overflow-hidden rounded-4 shadow-sm" style="max-height: 450px;">
                        <img src="{{ asset('storage/' . $movie['poster']) }}" alt="{{ $movie['title'] }}"
                            class="img-fluid rounded-4 w-100 h-100 object-fit-cover">
                    </div>
                </div>

                {{-- Detail Film --}}
                <div class="col-md-8">
                    <h2 class="fw-bold mb-3">{{ $movie['title'] }}</h2>
                    <p class="text-muted mb-4">{{ $movie['genre'] }} • {{ $movie['duration'] }} menit</p>

                    <table class="table table-borderless small mb-3">
                        <tbody>
                            <tr>
                                <td class="text-secondary" width="130">Sutradara</td>
                                <td>{{ $movie['director'] }}</td>
                            </tr>
                            <tr>
                                <td class="text-secondary">Rating Usia</td>
                                <td>
                                    <span class="badge bg-danger px-3 py-2 fs-6">
                                        {{ $movie['age_rating'] }}+
                                    </span>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    {{-- Tombol CTA
                    <a href="#jadwal-tab-pane" class="btn btn-warning fw-semibold px-4 py-2 shadow-sm"
                        data-bs-toggle="tab">Lihat Jadwal</a> --}}
                </div>
            </div>

            {{-- Navigasi Sinopsis & Jadwal --}}
            <div class="mt-5">
                @php
                    // request('price') : mengambil request, mengambil href="?"
                    if (request()->get('price')) {
                        $activeTab = true;
                        // jika suadah pernah sortir price dan type nya ASC, ubah jadi DESC
                        if (request()->get('price') == 'ASC') {
                            $typePrice = 'DESC';
                        } else {
                            // kalau sebelum nya bukan ASC (Berarti DESC), type sortir jadi ASC
                            $typePrice = 'ASC';
                        }
                    } else {
                        $activeTab = false;
                        // kalau belum pernah sortir price, type sortir jadi ASC
                        $typePrice = 'ASC';
                    }
                @endphp
                <ul class="nav nav-underline justify-content-center border-bottom pb-2">
                    <li class="nav-item">
                        <button class="nav-link fw-semibold text-dark {{ $activeTab == false ? 'active' : '' }}"
                            data-bs-toggle="tab" data-bs-target="#sinopsis-tab-pane">Sinopsis</button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link fw-semibold text-dark {{ $activeTab == true ? 'active' : '' }}"
                            data-bs-toggle="tab" data-bs-target="#jadwal-tab-pane">Jadwal</button>
                    </li>
                </ul>

                <div class="tab-content mt-4" id="myTabContent">

                    {{-- SINOPSIS --}}
                    <div class="tab-pane fade {{ $activeTab == false ? 'show active' : '' }}" id="sinopsis-tab-pane"
                        role="tabpanel">
                        <div class="mx-auto" style="max-width: 700px; text-align: justify;">
                            <p class="text-secondary">{{ $movie['description'] }}</p>
                        </div>
                    </div>

                    {{-- JADWAL --}}
                    <div class="tab-pane fade {{ $activeTab == true ? 'show active' : '' }}" id="jadwal-tab-pane"
                        role="tabpanel">
                        <div class="d-flex justify-content-between align-items-center my-4">
                            <h5 class="fw-bold mb-0">Jadwal Tayang</h5>
                            <div class="dropdown">
                                <button class="btn btn-outline-secondary dropdown-toggle" type="button"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                    Sortir
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="?price={{ $typePrice }}">Harga</a></li>
                                    <li><a class="dropdown-item" href="#">Alfabet</a></li>
                                </ul>
                            </div>
                        </div>

                        {{-- Daftar Jadwal --}}
                        @foreach ($movie['schedules'] as $schedule)
                            <div class="mb-4 p-4 rounded-4 shadow-sm bg-white border">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <i class="fa-solid fa-building text-secondary me-2"></i>
                                        <span class="fw-semibold">{{ $schedule['cinema']['name'] }}</span>
                                        <div class="text-muted small">{{ $schedule['cinema']['location'] }}</div>
                                    </div>
                                    <div class="fw-bold text-primary">
                                        Rp {{ number_format($schedule['price'], 0, ',', '.') }}
                                    </div>
                                </div>

                                {{-- Jam Tayang --}}
                                <div class="d-flex flex-wrap gap-2 mt-3">
                                    @foreach ($schedule['hours'] as $index => $hours)
                                        {{-- this => mengirimkan element html ke js unuk di manipulasi --}}
                                        <button class="btn btn-outline-dark btn-sm px-3 py-2 rounded-pill"
                                            onclick="selectedHour('{{ $schedule->id }}', '{{ $index }}', this)">
                                            {{ $hours }}
                                        </button>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

        </div>
    </div>

    <div class="w-100 fixed-bottom bg-light text-center py-2" id="wrapBtn">
        {{-- javascript:void(0) => nonaktifkan href --}}
        <a href="javascript:void(0)" id="btnTiket">Beli Tiket</a>
    </div>

@endsection

@push('script')
    <script>
        let btnBefore = null;
        function selectedHour(scheduleId, hourId, element) {
            if (btnBefore) {
                btnBefore.style.background = '';
                btnBefore.style.color = '';
                btnBefore.style.borderColor = '';
            }
            element.style.background = '#112646';
            element.style.color = 'white';
            element.style.borderColor = '#112646';

            btnBefore = element;

            let wrapBtn = document.querySelector("#wrapBtn");
            let btnTiket = document.querySelector("#btnTiket");

            wrapBtn.style.background = "#112646";
            wrapBtn.classList.remove("bg-light");
            btnTiket.style.color = "white";


            let url = "{{ route('schedules.seats', ['scheduleId' => ':scheduleId', 'hourId' => ':hourId']) }}".replace(":scheduleId", scheduleId).replace(":hourId", hourId);

            btnTiket.href = url;
        }


    </script>
@endpush
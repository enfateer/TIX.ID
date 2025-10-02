@extends('templates.app')

@section('content')

<div class="container py-5">
    <div class="w-75 mx-auto">

        {{-- Poster + Detail --}}
        <div class="row g-4 align-items-start shadow-lg rounded bg-white p-4">
            <div class="col-md-4 d-flex justify-content-center">
                <div class="poster-wrapper shadow rounded">
                    <img src="{{ asset('storage/' . $movie['poster']) }}" 
                         alt="{{ $movie['title'] }}" 
                         class="img-fluid rounded">
                </div>
            </div>

            <div class="col-md-8">
                <h2 class="fw-bold mb-4">{{ $movie['title'] }}</h2>

                <table class="table table-borderless mb-4">
                    <tbody>
                        <tr>
                            <td width="130" class="text-secondary">Genre</td>
                            <td>{{ $movie['genre'] }}</td>
                        </tr>
                        <tr>
                            <td class="text-secondary">Durasi</td>
                            <td>{{ $movie['duration'] }} menit</td>
                        </tr>
                        <tr>
                            <td class="text-secondary">Sutradara</td>
                            <td>{{ $movie['director'] }}</td>
                        </tr>
                        <tr>
                            <td class="text-secondary">Rating Usia</td>
                            <td>
                                <span class="badge bg-danger px-3 py-2">
                                    {{ $movie['age_rating'] }}+
                                </span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Navigasi Sinopsis & Jadwal --}}
        <div class="d-flex justify-content-center flex-column align-items-center mt-5">
            <ul class="nav nav-underline">
                <li class="nav-item">
                    <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#sinopsis-tab-pane">Sinopsis</button>
                </li>
                <li class="nav-item">
                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#jadwal-tab-pane">Jadwal</button>
                </li>
            </ul>

            <div class="tab-content w-100 mt-4" id="myTabContent">
                {{-- Sinopsis --}}
                <div class="tab-pane fade show active" id="sinopsis-tab-pane" role="tabpanel" tabindex="0">
                    <div class="mt-3 w-75 mx-auto" style="text-align: justify">
                        {{ $movie['description'] }}
                    </div>
                </div>

                {{-- Jadwal Tayang --}}
                <div class="tab-pane fade" id="jadwal-tab-pane" role="tabpanel" tabindex="0">
                    @foreach ($movie['schedules'] as $schedule)
                        <div class="w-75 mx-auto mb-4 p-3 rounded shadow-sm bg-light">
                            {{-- Lokasi Bioskop --}}
                            <h6 class="mb-3">
                                <i class="fa-solid fa-building text-secondary me-2"></i> 
                                <b>{{ $schedule['cinema']['name'] }}</b>
                            </h6>

                            {{-- Jam Tayang --}}
                            <div class="d-flex flex-wrap gap-2">
                                @foreach ($schedule['hours'] as $hours)
                                    <button class="btn btn-outline-dark px-3 py-2">
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

@endsection

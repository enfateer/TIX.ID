@extends('templates.app')

@section('content')

<div class="container py-5">
    <div class="w-75 mx-auto">

        <div class="row g-4 align-items-start shadow-lg rounded bg-white p-4">
            {{-- Poster --}}
            <div class="col-md-4 d-flex justify-content-center">
                <div class="poster-wrapper shadow rounded">
                    <img src="{{ asset('storage/' . $movie['poster']) }}" 
                         alt="{{ $movie['title'] }}" 
                         class="img-fluid rounded">
                </div>
            </div>

            {{-- Detail Film --}}
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

                {{-- Tombol Aksi
                <div class="d-flex gap-3">
                    <a href="#" 
                       class="btn fw-bold text-dark px-4 py-2 shadow"
                       style="background: linear-gradient(45deg, #ffc107, #ff9800); border: none;">
                        BELI TIKET
                    </a>
                    <a href="#" class="btn btn-outline-dark px-4 py-2">TRAILER</a>
                </div> --}}
            </div>
        </div>

        {{-- Navigasi bawah --}}
        <div class="d-flex justify-content-center mt-5">
            <ul class="nav nav-underline">
                <li class="nav-item">
                    <a class="nav-link active" href="#">Sinopsis</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Jadwal</a>
                </li>
            </ul>
        </div>

    </div>
</div>

@endsection

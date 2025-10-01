@extends('templates.app')


@section('content')

    <div class="container my-5">

        <div class="row g-3">
            @foreach ($movies as $movie)
                <div class="col-6 col-md-4 col-lg-3">
                    <div class="card h-100">
                        <img src="{{ asset('storage/' . $movie['poster']) }}" class="card-img-top"
                            alt="{{ $movie['title'] }}" />
                        <div class="card-body text-center p-2 d-flex align-items-end">
                            <a href="{{ route('schedules.detail', $movie['id']) }}" class="btn btn-primary w-100 fw-bold">Beli Tiket</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

    </div>

@endsection
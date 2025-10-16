@extends('templates.app')


@section('content')

    <div class="container my-5">

        {{-- form search gunakan method get karna form nya manggil data bukan nyimpen data,
        actionnya koosng untuk di arahkan ke proses yang sama (tetap di sini) --}}

        <form action="" method="GET">
            @csrf
            <div class="row">
                <div class="col-10">
                    <input type="text" name="search_movie" placeholder="Cari Judul Film..." class="form-control">
                </div>
                <div class="col-2">
                    <button type="submit" class="btn btn-primary">Cari</button>
                </div>
            </div>
        </form>

        <div class="row mt-3">
            @foreach ($movies as $movie)
                <div class="col-6 col-md-4 col-lg-3">
                    <div class="card h-100">
                        <img src="{{ asset('storage/' . $movie['poster']) }}" class="card-img-top"
                            alt="{{ $movie['title'] }}" />
                        <div class="card-body text-center p-2 d-flex align-items-end">
                            <a href="{{ route('schedules.detail', $movie['id']) }}" class="btn btn-primary w-100 fw-bold">Beli
                                Tiket</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

    </div>

@endsection
@extends('templates.app')

@section('content')

    <div class="mt-5 w-75 d-block m-auto">

        @if(Session::get('failed'))
            <div class="alert alert-danger">{{Session::get('failed')}}</div>
        @endif

        {{-- <nav data-mdb-navbar-init class="navbar navbar-expand-lg bg-body-tertiary">
            <div class="container-fluid">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('staff.index')}}">Cinema</a></li>
                        <li class="breadcrumb-item"><a href="{{route('staff.index')}}">Data</a></li>
                        <li class="breadcrumb-item active" aria-current="page"><a href="#">Tambah</a></li>
                    </ol>
                </nav>
            </div>
        </nav> --}}

    </div>

    <div class="card w-75 mx-auto my-5">
        <h5 class="text-center my-3">Buat Data</h5>

        <form action="{{route('staff.store')}}" method="post">

            @csrf
            <div class="mb-3">
                <label for="promo_code" class="form-label">Promo Code</label>
                <input type="number" class="form-control  @error('promo_code')
                 is-invalid @enderror" id="promo_code" name="promo_code">

                @error('promo_code')
                    <small class="text-danger">{{$message}}</small>
                @enderror

            </div>

            <div class="mb-3">
                <label for="discount" class="form-label">Diskon</label>
                <input type="number" class="form-control @error('discount')
                 is-invalid @enderror" id="discount" name="discount" cols="30" rows="5"></input>

                @error('discount')
                    <small class="text-danger">{{$message}}</small>
                @enderror

            </div>

            <div class="mb-3">
                <label for="type" class="form-label">Type</label>
                <select class="form-select" id="type" name="type" required>
                    <option value="">Choice</option>
                    <option value="percent" {{ old('type', $data->type ?? '') == 'percent' ? 'selected' : '' }}>Percent
                    </option>
                    <option value="rupiah" {{ old('type', $data->type ?? '') == 'rupiah' ? 'selected' : '' }}>Rupiah</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary mt-2">Buat</button>

        </form>

    </div>

@endsection
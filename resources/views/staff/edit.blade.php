@extends('templates.app')

@section('content')

    <div class="mt-5 w-75 d-block m-auto">

        @if(Session::get('failed'))
            <div class="alert alert-danger">{{Session::get('failed')}}</div>
        @endif

    </div>

    <div class="card w-75 mx-auto my-5">
        <h5 class="text-center my-3">Edit Data Bioskop</h5>

        <form action="{{route('staff.update', $promo->id)}}" method="post">

            @csrf
            @method('PUT')
            <div class="mb-3">
                <label for="promo_code" class="form-label">Promo Code</label>
                <input type="text" class="form-control @error('promo_code')
                 is-invalid @enderror" id="promo_code" name="promo_code" value="{{$promo->promo_code}}">

                @error('promo_code')
                    <small class="text-danger">{{$message}}</small>
                @enderror

            </div>

            <div class="mb-3">

                <label for="discount" class="form-label">Diskon</label>
                <textarea class="form-control @error('discount')
                 is-invalid @enderror" id="discount" name="discount" cols="30" rows="5">{{$promo->discount}}</textarea>

                @error('discount')
                    <small class="text-danger">{{$message}}</small>
                @enderror

            </div>

            <div class="mb-3">

                <label for="type" class="form-label">Type</label>
                <select class="form-select" id="type" name="type" required>
                    <option value="">Choice</option>
                    <option value="percent" {{ old('type', $data->type ?? '') == 'percent' ? 'selected' : '' }}">Percent
                    </option>
                    <option value="rupiah" {{ old('type', $data->type ?? '') == 'rupiah' ? 'selected' : '' }}>Rupiah</option>
                </select>

                @error('type')
                    <small class="text-danger">{{$message}}</small>
                @enderror

            </div>

            <button type="submit" class="btn btn-primary">Update</button>

        </form>

    </div>

@endsection
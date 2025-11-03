@extends('templates.app')

@section('content')

    <div class="mt-5 w-75 d-block m-auto">

        @if(Session::get('failed'))
            <div class="alert alert-danger">{{Session::get('failed')}}</div>
        @endif
        
    </div>

    <div class="card w-75 mx-auto my-5">
        <h5 class="text-center my-3">Edit Data Bioskop</h5>

        <form action="{{route('admin.cinemas.update', $cinema->id)}}" method="post">

            @csrf
            @method('PUT')
            <div class="mb-3">
                <label for="name" class="form-label">Nama Bioskop</label>
                <input type="text" class="form-control @error('name')
                 is-invalid @enderror" id="name" name="name" value="{{$cinema->name}}">

                 @error('name')
                     <small class="text-danger">{{$message}}</small>
                 @enderror
                 
            </div>
            
            <div class="mb-3">
                <label for="location" class="form-label">Detail Lokasi</label>
                <textarea class="form-control @error('location')
                 is-invalid @enderror" id="location" name="location" cols="30" rows="5">{{$cinema->location}}</textarea>

                  @error('location')
                     <small class="text-danger">{{$message}}</small>
                  @enderror

            </div>

            <button type="submit" class="btn btn-primary">Update</button>

        </form>

    </div>

@endsection
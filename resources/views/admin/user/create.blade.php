@extends('templates.app')

@section('content')

    <div class="mt-5 w-75 d-block m-auto">

        @if(Session::get('failed'))
            <div class="alert alert-danger">{{Session::get('failed')}}</div>
        @endif

        <nav data-mdb-navbar-init class="navbar navbar-expand-lg bg-body-tertiary">
            <div class="container-fluid">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('admin.users.index')}}">Cinema</a></li>
                        <li class="breadcrumb-item"><a href="{{route('admin.users.index')}}">Data</a></li>
                        <li class="breadcrumb-item active" aria-current="page"><a href="#">Tambah</a></li>
                    </ol>
                </nav>
            </div>
        </nav>

    </div>

    <div class="card w-75 mx-auto my-5">
        <h5 class="text-center my-3">Buat Data User</h5>

        <form action="{{route('admin.users.store')}}" method="post">

            @csrf
            <div class="mb-3">
                <label for="name" class="form-label">Nama User</label>
                <input type="text" class="form-control @error('name')
                 is-invalid @enderror" id="name" name="name">

                @error('name')
                    <small class="text-danger">{{$message}}</small>
                @enderror

            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input class="form-control @error('email')
                 is-invalid @enderror" id="email" name="email" cols="30" rows="5"></input>

                @error('location')
                    <small class="text-danger">{{$message}}</small>
                @enderror

            </div>


            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control @error('password') is-invalid @enderror" id="password"
                    name="password">

                @error('password')
                    <small class="text-danger">{{$message}}</small>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary">Buat</button>

        </form>

    </div>

@endsection
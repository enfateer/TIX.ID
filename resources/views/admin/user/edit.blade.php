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
                        <li class="breadcrumb-item active" aria-current="page"><a href="#">Edit</a></li>
                    </ol>
                </nav>
            </div>
        </nav>

    </div>

    <div class="card w-75 mx-auto my-5">
        <h5 class="text-center my-3">Edit Data User</h5>

        <form action="{{route('admin.users.update', $user->id)}}" method="post">

            @csrf
            @method('PUT')
            <div class="mb-3">
                <label for="name" class="form-label">Nama User</label>
                <input type="text" class="form-control @error('name')
                 is-invalid @enderror" id="name" name="name" value="{{$user->name}}">

                 @error('name')
                     <small class="text-danger">{{$message}}</small>
                 @enderror
                 
            </div>

            
            <div class="mb-3">
                <label for="email" class="form-label">Name Email</label>
                <textarea class="form-control @error('email')
                 is-invalid @enderror" id="email" name="email" cols="30" rows="5">{{$user->email}}</textarea>

                  @error('location')
                     <small class="text-danger">{{$message}}</small>
                  @enderror

            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input class="form-control @error('password')
                 is-invalid @enderror" id="password" name="password"  type="password">

                  @error('location')
                     <small class="text-danger">{{$message}}</small>
                  @enderror

            </div>

            <button type="submit" class="btn btn-primary">Update</button>

        </form>

    </div>

@endsection
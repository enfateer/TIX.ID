@extends('templates.app')

@section('content')

    @if(Session::get('success'))
    <div class="alert alert-success alert-dismissible fade show alert-top right" role="alert">

        {{ Session::get('success') }}
        <button type="submit" class="btn-close" data-bs-dismiss="alert"
         aria-label="Close"></button>

    </div>
    @endif

    <div class="container mt-3">

        @if(Session::get('failed'))
            <div class="alert alert-danger">{{Session::get('failed')}}</div>
        @endif

        <div class="d-flex justify-content-end mb-3 mt-4">
            <a href="{{route('admin.users.create')}}" class="btn btn-success">Tambah Data</a>
        </div>

        <h5>Data Bioskop</h5>

        <table class="table my-3 table-bordered">

            <tr>
                <th></th>
                <th class="text-center">Nama Bioskop</th>
                <th class="text-center">Lokasi</th>
                <th class="text-center">Role</th>
                <th class="text-center">Aksi</th>
            </tr>

            @foreach ($users as $key => $user) 
            {{--  mengubah aarray muldi dimensi menjadi array asosiatif --}}
            
            <tr>
                <td class="text-center">{{$key + 1}}</td>
                    <td>{{$user->name}}</td>
                    <td>{{$user->email}}</td>

                <td>
                        @if ($user->role == 'admin')
                            <span class="badge bg-success">{{$user->role}}</span>
                        @elseif($user->role == 'staff')
                            <span class="badge bg-primary">{{$user->role}}</span>
                        @else
                            <span class="badge bg-warning ">{{$user->role}}</span>
                        @endif
                </td>

                    <td class="d-flex justify-content-center gap-2">
                    <a href="{{route('admin.users.edit', $user->id)}}" 
                    class="btn btn-info">Edit</a>

                    <form action="{{route('admin.users.delete', $user->id)}}" method="post">
                        @csrf
                        @method('DELETE')
                    <button class="btn btn-danger">Hapus</button>
                    </form>

                </td>
            </tr>
                
            @endforeach

        </table>

    </div>

@endsection
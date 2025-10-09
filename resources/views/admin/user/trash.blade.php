@extends('templates.app')

@section('content')
    <div class="d-flex justify-content-end">
        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
            Kembali
        </a>
    </div>

    <h3 class="my-3">Data Sampah</h3>
    <table class="table table-bordered">
        <tr>
            <th>#</th>
            <th>Bioskop</th>
            <th>Film</th>
            <th>Aksi</th>
        </tr>

        @foreach ($usersTrash as $key => $user)
            <tr>
                <td>{{ $key + 1 }}</td>

                {{-- aman kalau cinema atau movie null --}}
                <td>{{ $user->name ?? '-' }}</td>
                <td>{{ $user->email ?? '-' }}</td>


                {{-- <td>
                    <ul>
                        @foreach ($schedule->hours ?? [] as $hours)
                        <li>{{ $hours }}</li>
                        @endforeach
                    </ul>
                </td> --}}

                <td class="d-flex">
                    <div class="container my-3 d-flex gap-2">

                        <form action="{{ route('admin.users.restore', $user->id) }}" method="post">
                            @csrf
                            @method('PATCH')
                            <button class="btn btn-success btn-sm">Kembalikan</button>
                        </form>
                        <form action="{{ route('admin.users.delete_permanent', $user->id) }}" method="post">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger btn-sm" onclick="return confirm('Yakin hapus data ini?')">Hapus
                                Permanent</button>
                        </form>
                    </div>
                </td>
            </tr>
        @endforeach

    </table>
@endsection
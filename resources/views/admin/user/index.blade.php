@extends('templates.app')

@section('content')

    @if(Session::get('success'))
        <div class="alert alert-success alert-dismissible fade show alert-top right" role="alert">

            {{ Session::get('success') }}
            <button type="submit" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>

        </div>
    @endif

    <div class="container mt-3">

        @if(Session::get('failed'))
            <div class="alert alert-danger">{{Session::get('failed')}}</div>
        @endif

        <div class="d-flex justify-content-end mb-3 mt-4">
            <a href="{{ route('admin.users.trash') }}" class="btn btn-secondary me-2">Sampah Jadwal</a>
            <a href="{{route('admin.users.export')}}" class="btn btn-secondary me-2">Export (.xlsx)</a>
            <a href="{{route('admin.users.create')}}" class="btn btn-success">Tambah Data</a>
        </div>

        <h5>Data Pengguna</h5>

        <table id="usersTable" class="table my-3 table-bordered">
            <thead>
                <tr>
                    <th></th>
                    <th class="text-center">Nama</th>
                    <th class="text-center">Email</th>
                    <th class="text-center">Role</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
@endsection

@push('script')
    <script>
        $(function () {
            $('#usersTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('admin.users.datatables') }}",
                ordering: false,
                columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'name',
                    name: 'name'
                },
                {
                    data: 'email',
                    name: 'email'
                },
                {
                    data: 'role',
                    name: 'role',
                    orderable: false
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                }
                ]
            });
        });
    </script>
@endpush

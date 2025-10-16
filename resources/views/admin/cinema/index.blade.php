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
            <a href="{{ route('admin.cinemas.trash') }}" class="btn btn-secondary me-2">Sampah Bioskop</a>
            <a href="{{route('admin.cinemas.export')}}" class="btn btn-secondary me-2">Export (.xlsx)</a>
            <a href="{{route('admin.cinemas.create')}}" class="btn btn-success">Tambah Data</a>
        </div>

        <h5>Data Bioskop</h5>

        <table id="cinemasTable" class="table my-3 table-bordered">
            <thead>
                <tr>
                    <th class="text-center">No</th>
                    <th class="text-center">Nama Bioskop</th>
                    <th class="text-center">Lokasi</th>
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
            $('#cinemasTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('admin.cinemas.datatables') }}",
                columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'title',
                    name: 'title'
                },
                {
                    data: 'location',
                    name: 'location'
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

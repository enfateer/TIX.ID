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
            <div class="alert alert-danger">{{ Session::get('failed') }}</div>
        @endif

        <div class="d-flex justify-content-end mb-3 mt-4">
            <a href="{{ route('staff.trash') }}" class="btn btn-secondary me-2">Sampah Jadwal</a>
            <a href="{{route('staff.export')}}" class="btn btn-secondary me-2">Export (.xlsx)</a>
            <a href="{{ route('staff.create') }}" class="btn btn-success">Tambah Data</a>
        </div>

        <h5>Data Bioskop</h5>

        <table id="promosTable" class="table my-3 table-bordered">
            <thead>
                <tr>
                    <th class="text-center">No</th>
                    <th class="text-center">Promo Code</th>
                    <th class="text-center">Diskon</th>
                    <th class="text-center">Type</th>
                    <th class="text-center">Status</th>
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
            $('#promosTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('staff.datatables') }}",
                columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'promo_code',
                    name: 'promo_code',
                    orderable: false
                },
                {
                    data: 'discount',
                    name: 'discount',
                    orderable: false
                },
                {
                    data: 'type',
                    name: 'type',
                    orderable: false
                },
                {
                    data: 'status',
                    name: 'status',
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
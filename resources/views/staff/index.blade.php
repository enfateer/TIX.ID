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

        <table class="table my-3 table-bordered">
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
                @foreach ($promos as $key => $promo)
                    <tr>
                        <td class="text-center">{{ $key + 1 }}</td>
                        <td class="text-center">{{ $promo->promo_code }}</td>
                        <td class="text-center">
                            @if($promo->type == 'rupiah')
                                Rp {{ number_format($promo->discount, 0, ',', '.') }}
                            @elseif($promo->type == 'percent')
                                {{ $promo->discount }} %
                            @else
                                {{ $promo->discount }}
                            @endif
                        </td>
                        <td class="text-center">{{ ucfirst($promo->type) }}</td>
                        <td class="text-center">
                            @if ($promo->actived == 1)
                                <span class="badge bg-success">Aktif</span>
                            @else
                                <span class="badge bg-danger">Non Aktif</span>
                            @endif
                        </td>
                        <td class="d-flex justify-content-center gap-2">
                            <a href="{{ route('staff.edit', $promo->id) }}" class="btn btn-info">Edit</a>

                            <form action="{{ route('staff.delete', $promo->id) }}" method="post">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger">Hapus</button>
                            </form>

                            <form action="{{ route('staff.toggleStatus', $promo->id) }}" method="post">
                                @csrf
                                @method('PUT')
                                @if ($promo->actived == 1)
                                    <button type="submit" class="btn btn-warning">Non Aktif</button>
                                @endif
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

    </div>

@endsection
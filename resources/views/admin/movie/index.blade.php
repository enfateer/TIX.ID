@extends('templates.app')

@section('content')
    <div class="container mt-5">

        @if (Session::get('failed'))
            <div class="alert alert-danger w-100 d-flex justify-content-between align-items-center">
                {{ Session::get('failed') }}
                <button type="button" class="btn-close ms-2" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (Session::get('success'))
            <div class="d-flex justify-content-end">
                <div class="alert alert-success d-flex justify-content-between align-items-center">
                    {{ Session::get('success') }}
                    <button type="button" class="btn-close ms-2" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            </div>
        @endif

        <div class="d-flex justify-content-end">

            <a href="{{ route('admin.movies.trash') }}" class="btn btn-secondary me-2">Sampah Jadwal</a>

            <a href="{{route('admin.movies.export')}}" class="btn btn-secondary me-2">
                Export (.xlsx)

                <a href="{{route('admin.movies.create')}}" class="btn btn-success">
                    Tambah Data
                </a>

        </div>

        <h5 class="mt-3">Data Film</h5>

        <table class="table table-bordered">

            <tr>
                <th>#</th>
                <th>Poster</th>
                <th>Judul Film</th>
                <th>Status</th>
                <th class="text-center">Aksi</th>
            </tr>

            @foreach ($movies as $key => $item)
                <tr>

                    <td>{{$key + 1}}</td>

                    <td>
                        {{-- memunculkan gambar yang di upload asset('storage/'.$item->poster)}} --}}
                        <img src="{{asset('storage/' . $item->poster)}}" width="120">

                    </td>

                    <td>{{$item['title']}}</td>

                    <td>
                        @if ($item['actived'] == 1)
                            <span class="badge bg-success">Aktif</span>
                        @else
                            <span class="badge bg-danger">Non Aktif</span>
                        @endif
                    </td>

                    <td class="d-flex justify-content-center">

                        {{-- onclick = menjalankan fungsi javascript keetika komponen di klik --}}
                        <button class="btn btn-secondary me-2" onclick="showModal({{$item}})">Detail</button>
                        <a href="{{route('admin.movies.edit', $item['id'])}}" class="btn btn-primary">Edit</a>

                        <form action="{{route('admin.movies.delete', $item['id'])}}" method="post">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger mx-2">Hapus</button>
                        </form>

                        <form action="{{route('admin.movies.toggleStatus', $item['id'])}}" method="post">
                            @csrf
                            @method('PUT')
                            @if ($item['actived'] == 1)
                                <button type="submit" class="btn btn-warning">Non Aktif</button>
                                {{-- @else
                                <button type="submit" class="btn btn-success">Aktif</button> --}}
                            @endif
                        </form>

                    </td>
                </tr>
            @endforeach
        </table>


        <!-- Modal -->
        <div class="modal fade" id="modalDetail" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">

                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Detail Film</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body" id="modalDetailBody">
                        ...
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>

                </div>
            </div>
        </div>

    </div>

@endsection


@push('script')

    <script>
        function showModal(item) {
            // console.log(item);

            // menghubungkan fungsi php asset, dagabungkan dengan data
            // yang di ambil js (item)
            let image = "{{asset('storage/')}}" + "/" + item.poster;
            // backtip (``) membuat string yang bisa di enter
            let content = `

                            <div class="d-block mx-auto my-5">
                                    <img src="${image}" width="120px">
                            </div>

                                <ol>
                                    <li>Judul : ${item.title}</li>
                                    <li>Durasi : ${item.duration}</li>
                                    <li>Genre : ${item.genre}</li>
                                    <li>Sutradara : ${item.director}</li>
                                    <li>Usia Minimal : <span class="badge badge-danger">${item.age_rating}</span></li>
                                    <li>Sinopsis : ${item.description}</li>
                                </ol>`;

            // memanggil variable pada tanda '' pake ${}
            // memanggil element html ayang akan di simpan konten diatas -> document.querySelector
            // innerHTML = mengisi konten html
            document.querySelector("#modalDetailBody").innerHTML = content;
            // munculkan data
            new bootstrap.Modal(document.querySelector("#modalDetail")).show();
        }
    </script>

@endpush
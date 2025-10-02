@extends('templates.app')

@section('content')

    <div class="container my-5">
        <div class="d-flex justify-content-end">
            {{-- modal add di munculkan dengan botstrap karena tidak memerlukan
            data dinamis di mdoal --}}
            <a href="{{ route('staff.schedules.trash') }}" class="btn btn-secondary me-2">Sampah Jadwal</a>
            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalAdd">Tambah data</button>
        </div>
        @if (Session::get('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ Session::get('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        <h3 class="my-3">Data jadwal tayang</h3>
        <table class="table table-bordered">
            <tr>
                <th>#</th>
                <th>Bioskop</th>
                <th>Film</th>
                <th>Harga</th>
                <th>Jam Tayang</th>
                <th>Aksi</th>
            </tr>

            @foreach ($schedules as $key => $schedule)
                <tr>
                    <td>{{ $key + 1 }}</td>

                    {{-- aman kalau cinema atau movie null --}}
                    <td>{{ $schedule->cinema->name ?? '-' }}</td>
                    <td>{{ $schedule->movie->title ?? '-' }}</td>

                    <td>Rp. {{ number_format($schedule->price, 0, ',', '.') }}</td>
                    <td>
                        <ul>
                            @foreach ($schedule->hours ?? [] as $hours)
                                <li>{{ $hours }}</li>
                            @endforeach
                        </ul>
                    </td>

                    <td class="d-flex">
                        <div class="container my-3 d-flex gap-2">
                            <a href="{{ route('staff.schedules.edit', $schedule->id) }}" class="btn btn-primary btn-sm">Edit</a>

                            <form action="{{ route('staff.schedules.delete', $schedule->id) }}" method="post">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger btn-sm"
                                    onclick="return confirm('Yakin hapus data ini?')">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @endforeach

        </table>

        {{-- modal --}}
        <div class="modal fade" id="modalAdd" tabindex="-1" aria-labelledby="modalAddLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="modalAddLabel">Tambah Data Jadwal</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form method="POST" action="{{route('staff.schedules.store')}}">
                        @csrf
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="cinema_id" class="col-form-label">Bioskop</label>
                                <select name="cinema_id" id="cinema_id"
                                    class="form-select @error('cinema_id') is-invalid @enderror">
                                    <option disabled hidden selected>Pilih Bioskop</option>
                                    {{-- loop option sesuai data $cinemas --}}
                                    @foreach ($cinemas as $cinema)
                                        {{-- // yang di ambil idnya (value), yang di munculin name nay --}}
                                        <option value="{{$cinema['id']}}">{{$cinema['name']}}</option>

                                    @endforeach
                                </select>
                                @error('cinema_id')
                                    <small class="text-danger">{{$message}}</small>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="movie_id" class="col-form-label">Film:</label>
                                <select name="movie_id" id="movie_id" class="form-select @error('movie_id')
                                is-invalid @enderror">
                                    <option disabled hidden selected>Pilih Film</option>
                                    $@foreach ($movies as $movie)

                                        <option value="{{$movie['id']}}">{{$movie['title']}}</option>

                                    @endforeach
                                </select>
                                @error('movie_id')
                                    <small class="text-danger">{{$message}}</small>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="price" class="form-label">Harga</label>
                                <input type="number" name="price" id="price" class="form-control @error('price')
                                is-invalid  @enderror">
                                @error('price')
                                    <small class="text-danger">{{$message}}</small>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="hours" class="form-label">Jam Tayang:</label>
                                {{-- karna hours array, error validasi di ambil dengan : --}}
                                {{-- $errors->has() : jika dari error validasi ada error item hours --}}
                                @if($errors->has('hours.*'))
                                    <br>
                                    <small class="text-danger">{{$errors->first('hours.*')}}</small>
                                @endif
                                <input type="time" name="hours[]" id="hours" class="form-control
                                                                @if ($errors->has('hours.*')) is-invalid @endif">
                                {{-- sediakan tempat konten tambahaan dari js --}}
                                <div id="additionalInput"></div>
                                <span class="text-primary mt-2" style="cursor: pointer" onclick="addInput()">+ Tambah
                                    Jam</span>
                            </div>



                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary">Kirim</button>
                        </div>
                    </form>
                </div>
            </div>

        </div>



    </div>
@endsection

@push('script')
    <script>
        function addInput() {
            let content = ` <input type="time" name="hours[]" class="form-control mt-2">`;
            // tempat content akan di tambahkan
            let wrap = document.querySelector("#additionalInput");
            // karena nanti akan sellau bertambah, agar yang sebelum nya tidak hilang gunakan : +=
            wrap.innerHTML += content;
        }
    </script>

    {{-- pengecekan php, klo ada error validasi apapun $errors->any() --}}
    @if ($errors->any())
        <script>
            let modalAdd = document.querySelector("#modalAdd");
            // muncul kan dengan js
            new bootstrap.Modal(modalAdd).show();
        </script>
    @endif
@endpush
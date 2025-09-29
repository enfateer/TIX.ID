@extends('templates.app')

{{-- mengisi yield --}}
@section('content')

     <div class="container pt-5">
        <div class="w-75 d-block m-auto">

            Poster + Detail Film
            <div style="width: 150px; height: 200px;">
                <img src="https://image.tmdb.org/t/p/original/95MVDO5VyjbzqInMFx6P576Ds0x.jpg"
                    alt="Poster Fast And Furious" class="w-100 rounded">
            </div>

            <div class="mt-5 mt-4">
                <h5 class="fw-bold">Fast and Furious</h5>
                <table class="table table-borderless table-sm">

                    <tr>
                        <td><b class="text-secondary">Genre</b></td>
                        <td class="ps-3">Race, Family, Romance</td>
                    </tr>
                    <tr>
                        <td><b class="text-secondary">Durasi</b></td>
                        <td class="ps-3">107 Menit</td>
                    </tr>
                    <tr>
                        <td><b class="text-secondary">Sutradara</b></td>
                        <td class="ps-3">Justin Lin</td>
                    </tr>
                    <tr>
                        <td><b class="text-secondary">Rating Usia</b></td>
                        <td class="ps-3"><span class="badge bg-danger">18+</span></td>
                    </tr>

                </table>
            </div>

        </div>
    </div>

@endsection
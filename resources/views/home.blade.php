{{-- memanggil file templates --}}
@extends('templates.app')

{{-- mengisi link yield --}}
@section('content')
    @if (Session::get('success'))
        {{-- Auth::user() mengambil data pengguna yang login --}}
        {{-- format : Auth::user()->clumn_di_fillable --}}
        <div class="alert alert-success w-100">{{ Session::get('success') }} <b>
                Selamat datang, {{ Auth::user()->name }}</b> </div>
    @endif

    @if (Session::get('logout'))
        <div class="alert alert-warning">{{ Session::get('logout') }} </div>
    @endif
    <div class="dropdown">
        <button class="btn btn-light dropdown-toggle w-100 d-flex align-items-center" type="button" id="dropdownMenuButton"
            data-mdb-dropdown-init data-mdb-ripple-init aria-expanded="false">
            <i class="fa-solid fa-location-dot me-2"></i>Bogor
        </button>
        <ul class="dropdown-menu w-100" aria-labelledby="dropdownMenuButton">
            <li><a class="dropdown-item" href="#">Jakarta</a></li>
            <li><a class="dropdown-item" href="#">Bandung</a></li>
            <li><a class="dropdown-item" href="#">Depok</a></li>
        </ul>
    </div>

    <div id="carouselExampleIndicators" class="carousel slide" data-mdb-ride="carousel" data-mdb-carousel-init>
        <div class="carousel-indicators">
            <button type="button" data-mdb-target="#carouselExampleIndicators" data-mdb-slide-to="0" class="active"
                aria-current="true" aria-label="Slide 1"></button>
            <button type="button" data-mdb-target="#carouselExampleIndicators" data-mdb-slide-to="1"
                aria-label="Slide 2"></button>
            <button type="button" data-mdb-target="#carouselExampleIndicators" data-mdb-slide-to="2"
                aria-label="Slide 3"></button>
        </div>
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img src="https://wallpaperaccess.com/full/1495044.jpg" class="d-block w-100" style="height: 700px"
                    alt="Wild Landscape" />
            </div>
            <div class="carousel-item">
                <img src="https://media.gq.com/photos/646e3b9a29e926e42d9d858d/3:2/w_1686,h_1124,c_limit/fnf.jpg"
                    class="d-block w-100" style="height: 700px" alt="Camera" />
            </div>
            <div class="carousel-item">
                <img src="https://wallpapers.com/images/hd/fast-and-furious-first-cars-lskrrrvaclx3r5p7.jpg"
                    class="d-block w-100" style="height: 700px" alt="Exotic Fruits" />
            </div>
        </div>
        <button class="carousel-control-prev" type="button" data-mdb-target="#carouselExampleIndicators"
            data-mdb-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-mdb-target="#carouselExampleIndicators"
            data-mdb-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>

    <div class="d-flex justify-content-between container mt-4">
        <div class="d-flex align-items-center gap-2 ">
            <i class="fa-solid fa-clapperboard"></i>
            <h5 class="mt-2">Sedang Tayang</h5>
        </div>
        <div>
            <a class="btn btn-warning rounded-pill mt-4">Semua
                <i class="fa-solid fa-angle-right"></i>
            </a>
        </div>
    </div>

    <div class="d-flex gap-2 container">

        <button type="button" class="btn btn-outline-primary rounded-pill" data-mdb-ripple-init
            data-mdb-ripple-color="dark">Semua
            Film</button>
        <button type="button" class="btn btn-outline-secondary rounded-pill" data-mdb-ripple-init
            data-mdb-ripple-color="dark">XII</button>
        <button type="button" class="btn btn-outline-secondary rounded-pill" data-mdb-ripple-init
            data-mdb-ripple-color="dark">Cinepolis</button>
        <button type="button" class="btn btn-outline-secondary rounded-pill" data-mdb-ripple-init
            data-mdb-ripple-color="dark">Imax</button>
    </div>


    @foreach ($movies as $movie)

        <div class="mt-3 d-flex justify-content-center container gap-2">
            <div class="card" style="width: 15rem;">
                <img src="{{ asset('storage/' . $movie['poster']) }}" class="card-img-top" alt="{{asset($movie['title'])}}" />
                <div class="card-body text-center p-2">
                    <a href="{{ route('schedules.detail') }}" class="btn btn-primary w-100 fw-bold">Beli Tiket
                    </a>
                </div>
            </div>

    @endforeach

    </div>

    <footer class="bg-body-tertiary text-center text-lg-start mt-4">
        <!-- Copyright -->
        <div class="text-center p-3" style="background-color: rgba(0, 0, 0, 0.05);">
            © 2025 TixID:
            <a class="text-body" href="https://mdbootstrap.com/">MDBootstrap.com</a>
        </div>
        <!-- Copyright -->
    </footer>
@endsection
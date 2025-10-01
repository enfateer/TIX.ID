<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>TIXID</title>

  <!-- Font Awesome -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet" />
  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap" rel="stylesheet" />
  <!-- MDB -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/9.1.0/mdb.min.css" rel="stylesheet" />
  <script src="https://code.jquery.com/jquery-3.7.1.min.js" crossorigin="anonymous"></script>

  <style>
    body {
      background: #f5f6fa;
      font-family: 'Roboto', sans-serif;
    }

    /* Navbar Styling */
    .navbar {
      background: #fff !important;
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
      padding: 10px 0;
    }

    .navbar-brand img {
      height: 36px;
    }

    .nav-link {
      font-weight: 500;
      padding: 8px 16px;
      border-radius: 6px;
      transition: all 0.3s ease;
    }

    .nav-link:hover {
      background: #f1f1f1;
      color: #e91e63 !important;
    }

    .dropdown-menu {
      border-radius: 10px;
      padding: 8px 0;
      box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
    }

    .dropdown-item {
      padding: 10px 20px;
      transition: background 0.3s ease;
    }

    .dropdown-item:hover {
      background: #f7f7f7;
      color: #e91e63;
    }

    /* Button Styling */
    .btn-rounded {
      border-radius: 30px;
      padding: 6px 20px;
      font-weight: 500;
      transition: all 0.3s ease;
    }

    .btn-rounded:hover {
      transform: translateY(-2px);
    }

    /* Hi User */
    .user-greet {
      font-size: 0.9rem;
      font-weight: 600;
      margin-right: 10px;
      color: #444;
    }

    main {
      padding: 80px 0 40px;
    }
  </style>
</head>

<body>
  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg fixed-top">
    <div class="container">
      <!-- Brand -->
      <a class="navbar-brand me-3" href="{{ route('home') }}">
        <img
          src="https://play-lh.googleusercontent.com/FcRZx_UEXN2uc7uKM5EKGn7Jmb65c8VVELlmligxdfUcjKKIpzFX0SHXFePllD2g4ik"
          alt="TIXID Logo" />
      </a>

      <!-- Toggle button -->
      <button class="navbar-toggler" type="button" data-mdb-collapse-init data-mdb-target="#navbarMenu"
        aria-controls="navbarMenu" aria-expanded="false" aria-label="Toggle navigation">
        <i class="fas fa-bars"></i>
      </button>

      <!-- Collapsible wrapper -->
      <div class="collapse navbar-collapse" id="navbarMenu">
        <!-- Left links -->
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
          @if(Auth::check() && Auth::user()->role == 'admin')
            <li class="nav-item">
              <a class="nav-link" href="{{route('home')}}">Dashboard</a>
            </li>
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button"
                data-mdb-dropdown-init aria-expanded="false">
                Data Master
              </a>
              <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                <li><a class="dropdown-item" href="{{route('admin.cinemas.index')}}">Data Bioskop</a></li>
                <li><a class="dropdown-item" href="{{route('admin.movies.index')}}">Data Film</a></li>
                <li><a class="dropdown-item" href="{{route('admin.users.index')}}">Data Pengguna</a></li>
              </ul>
            </li>
          @elseif(Auth::check() && Auth::user()->role == 'staff')
            <li class="nav-item"><a class="nav-link" href="#">Jadwal Tiket</a></li>
            <li class="nav-item"><a class="nav-link" href="{{route('staff.index')}}">Promo</a></li>
          @else
            <li class="nav-item"><a class="nav-link" href="{{route('home')}}">Beranda</a></li>
            <li class="nav-item"><a class="nav-link" href="#">Bioskop</a></li>
            <li class="nav-item"><a class="nav-link" href="#">Tiket</a></li>
          @endif
        </ul>

        <!-- Right buttons -->
        <div class="d-flex align-items-center gap-2">
          @if (Auth::check())
            <span class="user-greet">👋 Hi, {{ Auth::user()->name }}</span>
            <a href="{{route('logout')}}" class="btn btn-danger btn-rounded">Logout</a>
          @else
            <a href="{{route('login')}}" class="btn btn-outline-dark btn-rounded">Login</a>
            <a href="{{route('sign_up')}}" class="btn btn-primary btn-rounded">Sign up</a>
          @endif
        </div>
      </div>
    </div>
  </nav>
  <!-- Navbar -->
web
  <main class="container">
    {{-- Konten dinamis --}}
    @yield('content')
  </main>

  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/9.1.0/mdb.umd.min.js"></script>

  @stack('script')
</body>

</html>
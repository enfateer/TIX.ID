@extends('templates.app')

@section('content')
    <div class="w-75 d-block mx-auto my-5">
        @if (Session::get('success'))
            <div class="alert alert-success">
                {{ Session::get('success') }}
            </div>
        @endif
        @if (Session::get('error'))
            <div class="alert alert-danger">
                {{ Session::get('error') }}
            </div>
        @endif
        <form method="POST" action="{{ route('login.auth') }}">
            @csrf
            <!-- Email input -->
            @error('email')
                <small class="text-danger">{{ $message }}</small>
            @enderror
            <div data-mdb-input-init class="form-outline mb-4">
                <input type="email" id="form3Example3" class="form-control" @error('email') is-invalid
            @enderror
                    name="email" />
                <label class="form-label" for="form3Example3">Email address</label>
            </div>

            <!-- Password input -->
            @error('password')
                <small class="text-danger">{{ $message }}</small>
            @enderror
            <div data-mdb-input-init class="form-outline mb-4">
                <input type="password" id="form3Example4"
                    class="form-control" @error('password') is-invalid
             @enderror" name="password" />
                <label class="form-label" for="form3Example4">Password</label>
            </div>

            <!-- Checkbox -->
            <div class="form-check d-flex justify-content-center mb-4">
                <input class="form-check-input me-2" type="checkbox" value="" id="form2Example33" checked />
                <label class="form-check-label" for="form2Example33">
                    Remember me
                </label>
            </div>

            <!-- Submit button -->
            <button data-mdb-ripple-init type="submit" class="btn btn-primary btn-block mb-4">Login</button>

            <!-- Register buttons -->
            <div class="text-center">
                <p>or sign up with:</p>
                <button data-mdb-ripple-init type="button" class="btn btn-secondary btn-floating mx-1">
                    <i class="fab fa-facebook-f"></i>
                </button>

                <button data-mdb-ripple-init type="button" class="btn btn-secondary btn-floating mx-1">
                    <i class="fab fa-google"></i>
                </button>

                <button data-mdb-ripple-init type="button" class="btn btn-secondary btn-floating mx-1">
                    <i class="fab fa-twitter"></i>
                </button>

                <button data-mdb-ripple-init type="button" class="btn btn-secondary btn-floating mx-1">
                    <i class="fab fa-github"></i>
                </button>
            </div>
        </form>
    </div>
@endsection
<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\CinemaController;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\PromoController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
})->name('home');

// Route::get('/sig-nup', function () {
//     return view('signup');
// })->name('sign_up');

// Route::get('/login', function () {
//     return view('login');
// })->name(name: 'login');

Route::get('/schedules', function () {
    return view('schedule.detail-film');
})->name('schedules.detail');


Route::get('/', [MovieController::class, 'home'])->name('home');
//httpmethod Route::
// 1. get -> menampilkan halaman
// 2. post -> mengambil data/ menambahkan data
// 3. patch/put -> mengubah data
// 4. delete -> menghapus data

Route::middleware('isGuest')->group(function () {
    Route::get('/login', function () {
        return view('login');
    })->name('login');
    Route::post('/login', [UserController::class, 'loginAuth'])->name('login.auth');

    Route::get('/sign-up', function () {
        return view('signup');
    })->name('sign_up');
    Route::post('/sign-up', [UserController::class, 'signUp'])->name('sign_up.add');
});


Route::get('/logout', [UserController::class, 'logout'])->name('logout');

// prefix() : awalan, menulis /admin satu kali untuk 16 routes CRUD
// middleware('isAdmin') : memanggil middleware yang akan digunakna
// middleware : authorization, pengaturan hak akses pengguna
Route::middleware('isAdmin')->prefix('/admin')->name('admin.')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');


    // BIOSKOP
    Route::prefix('/cinemas')->name('cinemas.')->group(function () {
        Route::get('/', [CinemaController::class, 'index'])->name('index');
        Route::get('/create', function () {
            return view('admin.cinema.create');
        })->name('create');

        Route::post('/store', [CinemaController::class, 'store'])->name('store');

        Route::get('/edit/{id}', [CinemaController::class, 'edit'])->name('edit');

        Route::put('/update/{id}', [CinemaController::class, 'update'])->name('update');

        Route::delete('/destroy/{id}', [CinemaController::class, 'destroy'])->name('delete');

    });

    // PENGGUNA
    Route::prefix('/users')->name('users.')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('index');
        Route::get('/create', function () {
            return view('admin.user.create');
        })->name('create');

        Route::post('/store', [UserController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [UserController::class, 'edit'])->name('edit');
        Route::put('/update/{id}', [UserController::class, 'update'])->name('update');
        Route::delete('/destroy/{id}', [UserController::class, 'destroy'])->name('delete');
    });

    Route::prefix('/movies')->name('movies.')->group(function () {
        Route::get('/', [MovieController::class, 'index'])->name('index');
        Route::get('/create', [MovieController::class, 'create'])->name('create');
        Route::post('/store', [MovieController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [MovieController::class, 'edit'])->name('edit');
        Route::put('/update/{id}', [MovieController::class, 'update'])->name('update');
        Route::delete('/destroy/{id}', [MovieController::class, 'destroy'])->name('delete');

        Route::put('{id}/toggle-status/', [MovieController::class, 'toggleStatus'])->name('toggleStatus');

        Route::get('/export', [MovieController::class, 'export'])->name('export');
    });

});


Route::middleware('isStaff')->prefix('/staff')->name('staff.')->group(function () {
    Route::get('/', [PromoController::class, 'index'])->name('index');
    Route::get('/create', [PromoController::class, 'create'])->name('create');
    Route::post('/store', [PromoController::class, 'store'])->name('store');
    Route::get('/edit/{id}', [PromoController::class, 'edit'])->name('edit');
    // Route::get('/promo/{id}', [PromoController::class, 'promo'])->name('promo');
    Route::put('/update/{id}', [PromoController::class, 'update'])->name('update');
    Route::delete('/destroy/{id}', [PromoController::class, 'destroy'])->name('delete');
    Route::put('{id}/toggle-status/', [PromoController::class, 'toggleStatus'])->name('toggleStatus');
    Route::get('/dashboard', function () {
        return view('staff.dashboard');
    })->name('dashboard');

});
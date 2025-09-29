<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\UserExport;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::all();
        return view('admin.user.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|min:3',
            'email' => 'required|email:dns',
            'password' => 'required|min:8'
        ], [
            'name.required' => 'Nama wajib diisi',
            'name.min' => 'Nama minimal 3 karakter',
            'email.required' => 'Email wajib diisi',
            'email.email' => 'Email wajib diisi dengan data yang valid',
        ]);

        $createUser = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'staff',
        ]);

        if ($createUser) {
            return redirect()->route('admin.users.index')->with('success', 'Data user berhasil ditambahkan');
        } else {
            return redirect()->route('admin.users.index', 'Data user gagal ditambahkan');
        }

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $user = User::find($id);
        return view('admin.user.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|min:3',
            'email' => 'required|email:dns',
            'password' => 'required|min:8'
        ], [
            'name.required' => 'Nama wajib diisi',
            'name.min' => 'Nama minimal 3 karakter',
            'email.required' => 'Email wajib diisi',
            'email.email' => 'Email wajib diisi dengan data yang valid',
            'password.required' => 'Password wajib di isi',
            'password.min' => 'Password wajib di isi min 8 karakter',
        ]);

        $updateUser = User::where('id', $id)->update([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'staff'
        ]);
        
        if ($updateUser) {
            return redirect()->route('admin.users.index')->with('success', 'Data user berhasil diubah');
        } else {
            return redirect()->route('admin.users.index', 'Data user gagal diubah');
            // return redirect()->back()->with('failed', 'Data user gagal di ubah')
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy( $id)
    {
        $deleteUser = User::where('id', $id)->delete();

        if ($deleteUser) {
            return redirect()->route('admin.users.index')->with('success', 'Data user berhasil dihapus');
        } else {
            return redirect()->back()->with('failed', 'Data user gagal dihapus');
        }
    }

    public function signUp(Request $request)
    {
        // (Request $request) : class untuk mengambil value dari folmulir
        // validasi
        $request->validate([

            // 'name_input => 'tipe validasi
            // required => wajib disi, min : minimal karakter (teks)
            'first_name' => 'required|min:3',
            'last_name' => 'required|min:3',

            // email:dns => emailnya valid, @gmail.com, @yahoo.com, @outlook.com
            'email' => 'required|email:dns',
            'password' => 'required|min:8'
        ],  
        [

            // pesan error custom
            // 'name_input_validasi' => 'pesan'
            'first_name.required' => 'Nama depan wajib di isi',
            'first_name.min' => 'Nama depan wajib di isi min 3 huruf',
            'last_name.required' => 'Nama belakang wajib di isi',
            'last_name.min' => 'Nama belakang wajib di isi min 3 huruf',
            'email.required' => 'Email wajib di isi',
            'email.email' => 'Email wajib diisi dengan data yang valid',
            'password.required' => 'Password wajib di isi',
            'password.min' => 'Password wajib di isi min 8 karakter',

        ]);

        // membuat data baru
        $createUser = User::create([

            // 'nama_column' => $request->nama_input,
            'name' => $request->first_name . " " . $request->last_name,
            'email' => $request->email,

            // hash : enkripsi data ( mengubah menjadi karakter acak) agar tidak ada yang
            // bisa menebak isi nya
            'password' => Hash::make($request->password),

            // pengguna tidak bisa memilih role (akses), jadi manual di tambahkan ' user '
            'role' => 'user'
        ]);

        if ($createUser) {
            // redirect() : memindahkan halaman, route() : nama routing yang di tuju
            // with() : mengirinkan session, biasa nya unutk notifikasi
            return redirect()->route('login')->with('success', 'Silahkan Login!');
        } else {
            // back() : kembali ke halaman sebelumnya
            return redirect()->back()->with(['error' => 'Gagal, Silahkan coba lagi!']);
        }

    }

    public function loginAuth(Request $request)
    {
    $request->validate([
        'email' => 'required',
        'password' => 'required'
    ], [
        'email.required' => 'Email wajib di isi',
        'password.required' => 'Password wajib di isi',
    ]);

    // mengambil data yang akan di verifikasi
    $data = $request->only('email', 'password');

    // Auth:: -> class laravel utnuk penanganan authentication
    // attempt() : method class auth unutk mencocokan email-pw atau usrnme-pw
    // jika cocok akan di simpan datanya ke session auth
    if(Auth::attempt($data)) {
        
        if(Auth::user()->role == 'admin') {
            return redirect()->route('admin.dashboard')->with('success', 'Login Berhasil!');
        } 
        elseif(Auth::user()->role == 'staff') {
            return redirect()->route('staff.dashboard')->with('success', 'Login Berhasil!');
        } else {
            return redirect()->route('home')->with('success', 'Login Berhasil!');
        }

        } else {
        return redirect()->back()->with('error', 'Login Gagal, pastikan email atau
        password benar');
    }
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('home')->with('logout', 'Logout Berhasil,
        silakan login kembali well');
        
    }

    public function export() 
    {
        return Excel::download(new UserExport, 'users.xlsx');
    }
}

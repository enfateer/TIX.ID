<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\MovieExport;

class MovieController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $movies = Movie::all();
        return view('admin.movie.index', compact('movies'));
    }

    public function home()
    {
        $movies = Movie::where('actived', 1)->get();
        return view('home', compact('movies'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.movie.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->all());   
        $request->validate(
            [
                'title' => 'required',
                'duration' => 'required',
                'genre' => 'required',
                'director' => 'required',
                'age_rating' => 'required|numeric',
                // mimes -> bentuk file yang di izin kan untuk upload
                'poster' => 'required|mimes:jpg,png,jpeg,webp,svg',
                'description' => 'required|min:10'
            ],
            [
                'title.required' => 'Judul wajib diisi',
                'duration.required' => 'Durasi wajib diisi',
                'genre.required' => 'Genre wajib diisi',
                'director.required' => 'Sutradara wajib diisi',
                'age_rating.required' => 'Minimal usia wajib diisi',
                'age_rating.numeric' => 'Minimal usia harus berupa angka',
                'poster.required' => 'Poster wajib diunggah',
                'poster.mimes' => 'Format poster harus berupa jpg, png, jpeg, webp, atau svg',
                'description.required' => 'Deskripsi wajib diisi',
                'description.min' => 'Deskripsi minimal 10 karakter'
            ]
        );

        // ambil file yang di upload = $request->file('name_input')
        $gambar = $request->file('poster');
        // buat nama baru di file nya, agar menghindari nama file yang sama
        // $gambar->getClientOriginalExtension() = mengambil ekstensi file (jpg, png, dll)
        $namaGambar = Str::random(5) . "-poster." . $gambar->getClientOriginalExtension();
        // simpan file ke storage, nma file gunakan namafile baru
        $path = $gambar->storeAs('poster', $namaGambar, 'public');

        $createData = Movie::create([
            'title' => $request->title,
            'duration' => $request->duration,
            'genre' => $request->genre,
            'director' => $request->director,
            'age_rating' => $request->age_rating,
            'poster' => $path,
            'description' => $request->description,
            'actived' => 1
        ]);
        if ($createData) {
            return redirect()->route('admin.movies.index')->with('success', 'Data film berhasil ditambahkan');
        } else {
            return redirect()->back()->with('error', 'Data film gagal ditambahkan');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Movie $movie)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $movie = Movie::find($id);
        return view('admin.movie.edit', compact('movie'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // dd($request->all());   
        $request->validate(
            [
                'title' => 'required',
                'duration' => 'required',
                'genre' => 'required',
                'director' => 'required',
                'age_rating' => 'required|numeric',
                // mimes -> bentuk file yang di izin kan untuk upload
                'poster' => 'mimes:jpg,png,jpeg,webp,svg',
                'description' => 'required|min:10'
            ],
            [
                'title.required' => 'Judul wajib diisi',
                'duration.required' => 'Durasi wajib diisi',
                'genre.required' => 'Genre wajib diisi',
                'director.required' => 'Sutradara wajib diisi',
                'age_rating.required' => 'Minimal usia wajib diisi',
                'age_rating.numeric' => 'Minimal usia harus berupa angka',
                // 'poster' => 'Poster wajib diunggah',
                'poster.mimes' => 'Format poster harus berupa jpg, png, jpeg, webp, atau svg',
                'description.required' => 'Deskripsi wajib diisi',
                'description.min' => 'Deskripsi minimal 10 karakter'
            ]
        );
        // data sebelum nya
        $movie = Movie::find($id);
        if ($request->file('poster')) {
            // storage_path : cek apakah file sebelum nya ada di folder storage/app/public
            $fileSebelumnya = storage_path('app/public/' . $movie['poster']);
            if (file_exists($fileSebelumnya)) {
                // hapus file sebelumnya
                unlink($fileSebelumnya);
            }

            // ambil file yang di upload = $request->file('name_input')
            $gambar = $request->file('poster');
            // buat nama baru di file nya, agar menghindari nama file yang sama
            // $gambar->getClientOriginalExtension() = mengambil ekstensi file (jpg, png, dll)
            $namaGambar = Str::random(5) . "-poster." . $gambar->getClientOriginalExtension();
            // simpan file ke storage, nma file gunakan namafile baru
            $path = $gambar->storeAs('poster', $namaGambar, 'public');

        }

        $updateData = Movie::where('id', $id)->update([
            'title' => $request->title,
            'duration' => $request->duration,
            'genre' => $request->genre,
            'director' => $request->director,
            'age_rating' => $request->age_rating,
            // ?? sebelum ?? (if), setelah ?? (else)
            // kalau ada $path (poster baru), ambil data baru. kalau tidak ada, ambil dari data $movie sebelumnya
            'poster' => $path ?? $movie['poster'],
            'description' => $request->description,
            'actived' => 1
        ]);
        if ($updateData) {
            return redirect()->route('admin.movies.index')->with('success', 'Data film berhasil di ubah');
        } else {
            return redirect()->back()->with('error', 'Data film gagal ditambahkan');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {

        $film = Movie::find($id);
        if ($film) {
            if ($film->poster && Storage::disk('public')->exists($film->poster)) {
                Storage::disk('public')->delete($film->poster);
            }
        }

        $deleteData = Movie::where('id', $id)->delete();
        if ($deleteData) {
            return redirect()->route('admin.movies.index')->with('success', 'Data film berhasil dihapus');
        } else {
            return redirect()->back()->with('failed', 'Data film gagal dihapus');
        }
    }

    public function toggleStatus($id)
    {
        $movie = Movie::find($id);
        if (!$movie) {
            return redirect()->back()->with('error', 'Data film tidak ditemukan');
        }

        $movie->actived = !$movie->actived;
        $movie->save();

        $statusBaru = $movie->actived ? 'diaktifkan' : 'dinonaktifkan';

        return redirect()->route('admin.movies.index')->with('success', "Film berhasil $statusBaru.");
    }

    public function export ()
    {
        // nama file akan di unduh
        $fileName = 'data-film.xlxs';
        // proses unduh file
        return Excel::download(new MovieExport, $fileName);
    }
    
}

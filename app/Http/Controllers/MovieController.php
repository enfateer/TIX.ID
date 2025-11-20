<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use App\Models\Schedule;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Exports\MovieExport;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;
use Maatwebsite\Excel\Facades\Excel;
use Nette\Utils\Json;

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

    public function chartData()
    {
        $movieActive = Movie::where('actived', 1)->count();
        $movieNonActive = Movie::where('actived', 0)->count();
        // karena chart hanya perlu jumlah, jadi hitung dengan count()
        $data = [$movieActive, $movieNonActive];
        return response()->json([
            'data' => $data
        ]);
    }

    public function home()
    {
        // mengurutkan => orderBy 
        $movies = Movie::where('actived', 1)->orderBy('created_at', 'DESC')->limit(4)->get();
        return view('home', compact('movies'));
    }

    public function homeAllMovies(Request $request)
    {
        // Ambil value input search name = "search_movie"
        $title = $request->search_movie;
        // cek jika inut search ada isi nya, maka cari data
        if ($title != "") {
            // LIKE : mencari data yang mengandung kata tertentu
            // % depan : mencari kaa belakang, % belakang : mencari kata depan,
            // %depan belakang : mencari kata di depan dan belakang
            $movies = Movie::where('title', 'LIKE', '%' . $title . '%')->where('actived', 1)->orderBy('created_at', 'DESC')->get();
        } else {
            $movies = Movie::where('actived', 1)->orderBy('created_at', 'DESC')->get();
        }
        return view('home_movies', compact('movies'));
    }

    public function movieSchedules($movie_id, Request $request)
    {
        // ambil data dari href="?price=ASC" tanda tanya
        $priceSort = $request->price;
        // ambil data  film beserta schedule dan bioskop pada shcedule
        // 'schedules.cinema' -> karena relasi cinema ada di schedules bukan movie
        // first() -> ambil satu data movie
        if ($priceSort) {
            // Karna price ada nya di schedules bukan movie, jadi urutkan datanya dari schdule (relasi)
            $movie = Movie::where('id', $movie_id)->with([
                'schedules' => function ($q) use ($priceSort) {
                    $q->orderBy('price', $priceSort);
                },
                'schedules.cinema'
            ])->first();
        } else {
            $movie = Movie::where('id', $movie_id)->with(['schedules', 'schedules.cinema'])->first();
        }

        return view('schedule.detail-film', compact('movie'));
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

        $schedule = Schedule::where('movie_id', $id)->count();
        if ($schedule) {
            return redirect()->route('admin.movies.index')->with('failed', 'Data film gagal dihapus, masih memiliki data jadwal');
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

    public function export()
    {
        // nama file akan di unduh
        $fileName = 'data-film.xlsx';
        // proses unduh file
        return Excel::download(new MovieExport, $fileName);
    }

    public function trash()
    {
        $moviesTrash = Movie::onlyTrashed()->get();
        return view('admin.movie.trash', compact('moviesTrash'));
    }

    public function restore($id)
    {
        $movies = Movie::onlyTrashed()->find($id);
        $movies->restore();
        return redirect()->route('admin.movies.index')->with('success', 'Data berhasil di kembalikan');
    }

    public function deletePermanent($id)
    {
        $movies = Movie::onlyTrashed()->find($id);
        if ($movies) {
            // Delete related tickets first
            foreach ($movies->schedules as $schedule) {
                $schedule->tickets()->forceDelete();
            }
            // Delete related schedules
            $movies->schedules()->forceDelete();
            // Delete poster file
            if ($movies->poster && Storage::disk('public')->exists($movies->poster)) {
                Storage::disk('public')->delete($movies->poster);
            }
            $movies->forceDelete();
        }
        return redirect()->route('admin.movies.trash')->with('success', 'Data berhasil di hapus permanen');
    }

    public function datatables()
    {
        $movies = Movie::query();
        return DataTables::of($movies)
            ->addIndexColumn()
            ->addColumn('poster_img', function ($item) {
                $url = asset('storage/' . $item->poster);
                return '<img src="' . $url . '" width="70">';
            })
            ->addColumn('activated_badge', function ($item) {
                if ($item->actived) {
                    return '<span class="badge bg-success">Aktif</span>';
                } else {
                    return '<span class="badge bg-danger">Non-Aktif</span>';
                }
            })
            ->addColumn('action', function ($item) {
                $btnDetail = '<button type="button" class="btn btn-secondary" onclick=\'showModal(' . json_encode($item) . ')\'>Detail</button>';
                $btnEdit = '<a href="' . route('admin.movies.edit', $item->id) . '" class="btn btn-primary">Edit</a>';
                $btnDelete = '<form action="' . route('admin.movies.delete', $item->id) . '" method="POST" style="display:inline-block">
                            ' . csrf_field() . method_field('DELETE') . '
                            <button type="submit" class="btn btn-danger">Hapus</button>
                          </form>';
                $btnNonAktif = '';
                if ($item->activated) {
                    $btnNonAktif = '<form action="' . route('admin.movies.non-activated', $item->id) . '" method="POST" style="display:inline-block">
                            ' . csrf_field() . method_field('PATCH') . '
                            <button type="submit" class="btn btn-warning">Non-Aktif</button>
                          </form>';
                }

                return '<div class="d-flex justify-content-center align-items-center gap-2">' . $btnDetail . $btnEdit . $btnDelete . $btnNonAktif . '</div>';
            })
            ->rawColumns(['poster_img', 'activated_badge', 'action'])
            ->make(true);
    }
}



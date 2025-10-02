<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use App\Models\Cinema;
use App\Models\Movie;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // data untuk select
        $cinemas = Cinema::all();
        $movies = Movie::all();
        // return view('staff.schedule.index', compact('cinemas', 'movies'));

        // with() : mengambil fungsi relasi danri model, unutk mengakses detail relasi
        // ga cuman primary nya aja
        $schedules = Schedule::with(['cinema', 'movie'])->get();
        return view('staff.schedule.index', compact('cinemas', 'movies', 'schedules'));
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
        $request->validate(
            [
                'cinema_id' => 'required',
                'movie_id' => 'required',
                'price' => 'required|numeric',
                // karena hours array, jadi yang di validasi item nya  => 'hours.*'
                'hours.*' => 'required'
            ],
            [
                'cinema_id.required' => 'Bioskop harus di pilih',
                'movie_id.required' => 'Film harus di pilih',
                'price.required' => 'Harga harus di isi',
                'price.numeric' => 'harga harus di isi dengan angka',
                'hours.*.required' => 'Jam harus di isi minimal satu data jam',
            ]
        );

        // ambil data jika sudah ada berdasarkan bioskop adn flm yang sama
        $schedules = Schedule::where('cinema_id', $request->cinema_id)->where('movie_id', $request->movie_id)->first();

        // jika ada data yang bioskop dan film nya sama
        if ($schedules) {
            // ambil data jam yang sebelum nya
            $hours = $schedules['hours'];
        } else {
            // kalau belum ada data, hours du buat kosong dulu
            $hours = [];
        }
        // gabungkan hours sebelum nya dengan hours baru dari input ($request->hours)
        $mergeHours = array_merge($hours, $request->hours);
        // jika ada jam yang sama, hilangkan duplikasi data
        // gunakan data jam ini untuk databsse
        $newHours = array_unique($mergeHours);


        $createData = Schedule::updateOrCreate(
            [
                'cinema_id' => $request->cinema_id,
                'movie_id' => $request->movie_id,
            ],[
                'price' => $request->price,
                'hours' => $newHours,
            ]
        );

        if ($createData) {
            return redirect()->route('staff.schedules.index')->with('success', 'berhasil menambahkan data!');
        } else {
            return redirect()->back()->with('error', 'Gagal, coba lagi.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Schedule $schedule)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Schedule $schedule, $id)
    {
        $schedule = Schedule::where('id', $id)->with(['cinema', 'movie'])->first();
        return view('staff.schedule.edit', compact('schedule'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Schedule $schedule, $id)
    {
        $request->validate([
            'price' => 'required|numeric',
            'hours.*' => 'required|date_format:H:i'
        ],[
            'price.required' => 'Harga harus di isi',
            'price.numeric' => 'Harga harus di isi dengan angka',
            'hours.*.required' => 'Jam harus di isi minimal satu data jam',
            'hours.*.date_format' => 'Format jam harus dengan format jam:menit'
        ]);

        $updateData = Schedule::where('id', $id)->update([
            'price' => $request->price,
            'hours' => $request->hours
        ]);

        if($updateData) {
            return redirect()->route('staff.schedules.index')->with('success', 'Data berhasil di ubah');
        } else {
            return redirect()->back()->with('error', 'Data gagal di ubah');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $deleteData = Schedule::where('id', $id)->delete();
        if ($deleteData) {
            return redirect()->route('staff.schedules.index')->with('success', 'Data bioskop berhasil dihapus');
        } else {
            return redirect()->back()->with('failed', 'Data bioskop gagal dihapus');
        }
    }

    public function trash()
    {
        $schedulesTrash = Schedule::with(['cinema', 'movie'])->onlyTrashed()->get();
        return view('staff.schedule.trash', compact('schedulesTrash'));
    }

    public function restore($id)
    {
        $schedules = Schedule::onlyTrashed()->find($id);
        $schedules->restore();
        return redirect()->route('staff.schedules.index')->with('success', 'Data berhasil di kembalikan');
    }

    public function deletePermanent ($id)
    {
        $schedules = Schedule::onlyTrashed()->find($id);
        $schedules->forceDelete();
        return redirect()->route('staff.schedules.trash')->with('success', 'Data berhasil di hapus permanen');
    }
}

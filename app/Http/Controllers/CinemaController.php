<?php

namespace App\Http\Controllers;

use App\Models\Cinema;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;
use App\Exports\CinemaExport;


class CinemaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $cinemas = Cinema::all();
        return view('admin.cinema.index', compact('cinemas'));
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
        // valisdasi
        $request->validate([
            'name' => 'required|min:3',
            'location' => 'required|min:10',
        ], [
            'name.required' => 'Nama bioskop wajib diisi',
            'name.min' => 'Nama bioskop minimal 3 karakter',
            'location.required' => 'Lokasi bioskop wajib diisi',
            'location.min' => 'Lokasi bioskop minimal 10 karakter',
        ]);
        // mengirim data ke databse
        $createCinema = Cinema::create([
            'name' => $request->name,
            'location' => $request->location,
        ]);
        // redirect \ perpindahan halaman
        if ($createCinema) {
            return redirect()->route('admin.cinemas.index')->with('success', 'Data bioskop berhasil ditambahkan');
        } else {
            return redirect()->back()->with('failed', 'Data bioskop gagal ditambahkan');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Cinema $cinema)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $cinema = Cinema::find($id);
        return view('admin.cinema.edit', compact('cinema'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // validasi
        $request->validate([
            'name' => 'required|min:3',
            'location' => 'required|min:10',
        ], [
            'name.required' => 'Nama bioskop wajib diisi',
            'name.min' => 'Nama bioskop minimal 3 karakter',
            'location.required' => 'Lokasi bioskop wajib diisi',
            'location.min' => 'Lokasi bioskop minimal 10 karakter',
        ]);
        // mengirim data ke databse
        $updateCinema = Cinema::where('id', $id)->update([
            'name' => $request->name,
            'location' => $request->location,
        ]);

        if ($updateCinema) {
            return redirect()->route('admin.cinemas.index')->with('success', 'Data bioskop berhasil diubah');
        } else {
            return redirect()->back()->with('failed', 'Data bioskop gagal diubah');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $schedule = Schedule::where('cinema_id', $id)->count();
        if($schedule){
            return redirect()->route('admin.cinemas.index')->with('failed', 'Data bioskop gagal dihapus, masih memiliki data jadwal');
        }

        $deleteData = Cinema::where('id', $id)->delete();
        if ($deleteData) {
            return redirect()->route('admin.cinemas.index')->with('success', 'Data bioskop berhasil dihapus');
        } else {
            return redirect()->back()->with('failed', 'Data bioskop gagal dihapus');
    }
}

public function export ()
    {
        return Excel::download(new CinemaExport, 'cinemas.xlsx');
    }

    public function trash() 
    {
        $cinemasTrash = Cinema::onlyTrashed()->get();
        return view('admin.cinema.trash', compact('cinemasTrash'));
    }

    public function restore($id)
    {
        $cinemas = Cinema::onlyTrashed()->find($id);
        $cinemas->restore();
        return redirect()->route('admin.cinemas.index')->with('success', 'Data berhasil di kembalikan');
    }

    public function deletePermanent($id)
    {
        $cinemas = Cinema::onlyTrashed()->find($id);
        $cinemas->forceDelete();
        return redirect()->route('admin.cinemas.trash')->with('success', 'Data berhasil di hapus permanen');
    }

     public function datatables()
    {
        $cinemas = Cinema::query();
        return DataTables::of($cinemas)
            ->addIndexColumn()
            ->addColumn('title', function ($item) {
                return $item->name;
            })
            ->addColumn('location', function ($item) {
                return $item->location;
            })
            ->addColumn('action', function ($item) {
                $btnEdit = '<a href="' . route('admin.cinemas.edit', $item->id) . '" class="btn btn-primary">Edit</a>';
                $btnDelete = '<form action="' . route('admin.cinemas.delete', $item->id) . '" method="POST" style="display:inline-block">
                            ' . csrf_field() . method_field('DELETE') . '
                            <button type="submit" class="btn btn-danger">Hapus</button>
                          </form>';
                return '<div class="d-flex justify-content-center align-items-center gap-2">' . $btnEdit . $btnDelete . '</div>';
            })
            ->filterColumn('title', function($query, $keyword) {
                $query->where('name', 'like', "%{$keyword}%");
            })
            ->filterColumn('location', function($query, $keyword) {
                $query->where('location', 'like', "%{$keyword}%");
            })
            ->rawColumns(['title', 'location', 'action'])
            ->make(true);
    }
}
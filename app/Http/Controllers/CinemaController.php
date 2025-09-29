<?php

namespace App\Http\Controllers;

use App\Models\Cinema;
use Illuminate\Http\Request;


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
        $deleteData = Cinema::where('id', $id)->delete();
        if ($deleteData) {
            return redirect()->route('admin.cinemas.index')->with('success', 'Data bioskop berhasil dihapus');
        } else {
            return redirect()->back()->with('failed', 'Data bioskop gagal dihapus');
    }
}
}
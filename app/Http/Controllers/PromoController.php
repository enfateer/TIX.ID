<?php

namespace App\Http\Controllers;

use App\Models\Promo;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PromoExport;

class PromoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $promos = Promo::all();
        return view('staff.index', compact('promos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('staff.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'promo_code' => 'required|min:5',
            'discount' => 'required',
            'type' => 'required'
        ],
    [
            'promo_code.required' => 'Kode promo wajib diisi',
            'promo_code.min' => 'Kode promo minimal 5 karakter',
            
            'discount.numeric' => 'Persentase diskon harus berupa angka',
            
            'type.required' => 'Tipe promo wajib diisi',
    ]);

        $createData = Promo::create([
            'promo_code' => $request->promo_code,
            'discount' => $request->discount,
            'type' => $request->type,
            'actived' => 1,
        ]);

        if ($createData) {
            return redirect()->route('staff.index')->with('success', 'Data promo berhasil ditambahkan');
        } else {
            return redirect()->back()->with('failed', 'Data promo gagal ditambahkan');
        }

    }

    /**
     * Display the specified resource.
     */
    public function show(Promo $promo)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $promo = Promo::find($id);
        return view('staff.edit', compact('promo'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'promo_code' => 'required|min:5',
            'discount' => 'required',
            'type' => 'required',
        ],
    [
            'promo_code.required' => 'Kode promo wajib diisi',
            'promo_code.min' => 'Kode promo minimal 5 karakter',
            // 'discount.required' => 'Persentase diskon wajib diisi',
            'discount.numeric' => 'Persentase diskon harus berupa angka',
            // 'discount.min' => 'Persentase diskon minimal 1%',
            // 'discount.max' => 'Persentase diskon maksimal 100%',
            'type.required' => 'Tipe promo wajib diisi',
    ]);

        $updatePromo = Promo::where('id', $id)->update([
            'promo_code' => $request->promo_code,
            'discount' => $request->discount,
            'type' => $request->type,
        ]);

        if ($updatePromo) {
            return redirect()->route('staff.index')->with('success', 'Data promo berhasil diupdate');
        } else {
            return redirect()->back()->with('failed', 'Data promo gagal diupdate');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $deletePromo = Promo::where('id', $id)->delete();
        if ($deletePromo) {
            return redirect()->route('staff.index')->with('success', 'Data promo berhasil dihapus');
        } else {
            return redirect()->back()->with('failed', 'Data promo gagal dihapus');  
    }
}

    public function toggleStatus($id)
    {
        $promo = Promo::find($id);
        if (!$promo) {
            return redirect()->back()->with('error', 'Data film tidak ditemukan');
        }

        $promo->actived = !$promo->actived;
        $promo->save();

        $statusBaru = $promo->actived ? 'diaktifkan' : 'dinonaktifkan';

        return redirect()->route('staff.index')->with('success', "Film berhasil $statusBaru.");
    }

    public function export ()
    {
        return Excel::download(new PromoExport, 'promos.xlsx');
    }

    public function trash()
    {
        $promosTrash = Promo::onlyTrashed()->get();
        return view('staff.trash', compact('promosTrash'));
    }

    public function restore($id)
    {
        $promos = Promo::withTrashed()->find($id);
        $promos->restore();
        return redirect()->route('staff.index')->with('success', 'Data berhasil di kembalikan');
    }

    public function deletePermanent($id)
    {
        $promos = Promo::onlyTrashed()->find($id);
        $promos->forceDetele();
        return redirect()->route('staff.trash')->with('success', ' Data berhasil di hapus permanen');
    }

}

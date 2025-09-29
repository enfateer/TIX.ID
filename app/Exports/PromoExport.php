<?php

namespace App\Exports;

use App\Models\Promo;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Carbon\Carbon;

class PromoExport implements FromCollection, WithHeadings, WithMapping
{
    private $key = 0;

    /**
     * Ambil semua data promo
     */
    public function collection()
    {
        return Promo::all();
    }

    /**
     * Judul kolom Excel
     */
    public function headings(): array
    {
        return [
            'No',
            'Kode Promo',
            'Diskon',
            'Type',
            'Status',
            'Dibuat',
        ];
    }

    /**
     * Mapping isi data promo ke dalam tabel Excel
     */
   public function map($promo): array
{   
    // mengcek apakh nilai discount di < 100 atau >= 100 
    // jika nilai diskon nya di bawah 100, maka di anggap persen (misalnya 10 = 10%) 
    // jika nilai diskon nya di atas 100, maka di anggap rupih (misalnya 2500 = Rp 2.500)
    $type = $promo->discount < 100 ? 'Percent' : 'Rupiah';

    // Format tampilan diskon berdasarkan tipe
    // Jika persen => tambahkan tada "%" di belakang angkanya
    // Jika rupiah > gunakan number_format agar rapih (Rp 1.000, Rp 2.500, dst.)
    $discount = $type === 'Percent'
        ? $promo->discount . ' %'
        : 'Rp ' . number_format($promo->discount, 0, ',', '.');

    // Kembalikan data dalam bentuk array untuk diexport ke file (Excel/CSV)
    return [
        ++$this->key, // Nomor urut (auto increment)
        $promo->promo_code, // Kode promo
        $discount, // Diskon yang sudah diformat
        $type, // Jenis diskon: Percent atau Rupiah
        $promo->actived == 1 ? 'Aktif' : 'Non Aktif', // Status promo
        Carbon::parse($promo->created_at)->format('d-m-Y'), // Tanggal dibuat, diformat dd-mm-yyyy
    ];
}

}

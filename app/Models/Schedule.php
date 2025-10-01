<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Schedule extends Model
{
    use SoftDeletes;

    protected $fillable = ['cinema_id', 'movie_id', 'hours', 'price'];

    // mrmsnggil relasi cinema, schedule mempunya FK cinema
    // karena one (cinema) to many (schedule) : nama tunggal
    public function cinema()
    {
        // untuk tabe yang memegang FK gunakan ini 
        return $this->belongsTo(Cinema::class);
    }

    public function movie()
    {
        return $this->belongsTo(Movie::class);
    }

    // casts : memastikan tipe data, agar json jadi array
    protected function casts () 
    {
        return [
            'hours' => 'array'
        ];
    }
    }

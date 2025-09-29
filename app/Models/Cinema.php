<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cinema extends Model
{
    // mendaftar soft delete
    use SoftDeletes;

    // mendaftar nama-column yang akan di isi, 
    // nama nama column selain id dan timestamps
    protected $fillable = ['name', 'location'];
}

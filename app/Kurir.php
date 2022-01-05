<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Kurir extends Model
{
    protected $table = 'kurir';
    protected $fillable = [
        'kode_kurir', 'nama_kurir', 'sedang_mengirim'
    ];
    public $timestamps = false;
}

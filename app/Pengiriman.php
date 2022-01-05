<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pengiriman extends Model
{
    protected $table = 'pengiriman';
    protected $fillable = [
        'lokasi_pengiriman', 'proses', 'tgl_kirim', 'tgl_selesai', 'id_kurir'
    ];
    public $timestamps = false;
}

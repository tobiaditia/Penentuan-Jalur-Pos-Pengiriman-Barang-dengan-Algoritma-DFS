<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pesanan extends Model
{
    protected $table = 'pesanan';
    protected $fillable = [
        'resi', 'id_pengiriman', 'id_barang', 'id_kurir', 'id_pembeli', 'area_tujuan', 'proses', 'tgl_kirim', 'tgl_selesai'
    ];
    public $timestamps = false;
}

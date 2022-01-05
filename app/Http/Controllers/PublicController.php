<?php

namespace App\Http\Controllers;

use App\Barang;
use App\Pembeli;
use App\Pengiriman;
use App\Pesanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PublicController extends Controller
{
    public function index()
    {
        $data = Barang::get();

        return view('public.index', compact('data'));
    }

    public function show($id)
    {
        $data = Barang::find($id);
        return view('public.show', compact('data'));
    }

    public function store(Request $request)
    {
        // dd($request);
        $id_pembeli = Pembeli::insertGetId([
            'nama_pembeli' => $request->nama_pembeli
        ]);

        Pesanan::create([
            'resi' => Str::random(10) . '' . (Pesanan::max('id') + 1),
            'id_barang' => $request->id_barang,
            'id_kurir' => 0,
            'id_pembeli' => $id_pembeli,
            'area_tujuan' => $request->select2Kelurahan,

        ]);

        $stok_barang   = Barang::select('stok')->where('id', $request->id_barang)->first()->stok;
        Barang::where('id', $request->id_barang)->update(
            ['stok' => $stok_barang - 1]
        );

        return redirect('/');
    }

    public function lacak()
    {
        return view('public.lacak');
    }

    public function getLacak($resi)
    {
        $cek_pesanan = DB::table('pesanan')->where(['resi' => $resi])->first();

        $data_pengiriman = Pengiriman::select('lokasi_pengiriman')
            ->where(['id' => $cek_pesanan->id_pengiriman])
            ->first();

        $data_arr_pengiriman = json_decode($data_pengiriman->lokasi_pengiriman, TRUE);
        $data_kab = $data_arr_pengiriman['kab'];
        $data_kec = $data_arr_pengiriman['kec'];
        $data_kel = $data_arr_pengiriman['kel'];

        return $this->generate_sort_location(
            $data_kab,
            $data_kec,
            $data_kel
        );
    }

    public function getNamaAreacode($areacode)
    {
        return DB::table('administrative_area2')->where('area_code', $areacode)->pluck('area_name')[0];
    }

    public function getKecamatan($id_kab)
    {
        if (empty($id_kab)) {
        } else {
            $data = DB::table("v_kecamatan")
                ->select("kecamatan_code", "kecamatan_name")
                ->where("kabupatenkota_code", $id_kab)
                ->get();
            return $data;
        }
    }

    public function getKelurahan($id_kab, $id_kec)
    {
        if (empty($id_kec)) {
        } else {
            $data = DB::table("v_kelurahan")
                ->select("area_code", "kelurahan_code", "kelurahan_name")
                ->where("kabupatenkota_code", $id_kab)
                ->where("kecamatan_code", $id_kec)
                ->get();
            return $data;
        }
    }

    private function generate_sort_location($data_kab, $data_kec, $data_kel)
    {
        $urutan = '';
        $urutan_array = [];
        $html = '';
        foreach ($data_kab as $key_data_kab => $val_data_kab) {
            $urutan .=
                ($val_data_kab == 1) ?
                '<b class="text-success">' . $this->getNamaAreacode($key_data_kab) . '</b> -> '
                : $this->getNamaAreacode($key_data_kab) . ' -> ';
            // array_push($urutan_array, $key_data_kab);
            $urutan_array[$key_data_kab] = ['nama' => $this->getNamaAreacode($key_data_kab), 'kirim' => $val_data_kab];

            $html .=
                ($val_data_kab == 1) ?
                '<li class="levelgreen">' . $this->getNamaAreacode($key_data_kab) . '<ul>'
                : '<li>' . $this->getNamaAreacode($key_data_kab) . '<ul>';

            foreach ($data_kec as $key_data_kec => $val_data_kec) {
                $parent_data_kec = substr($key_data_kec, 0, 4) . '000000';
                if ($key_data_kab == $parent_data_kec) {
                    $urutan .=
                        ($val_data_kec == 1) ?
                        '<b class="text-success">Kec. ' . $this->getNamaAreacode($key_data_kec) . '</b> -> '
                        :
                        'Kec. ' . $this->getNamaAreacode($key_data_kec) . ' -> ';
                    // array_push($urutan_array, $key_data_kec);
                    $urutan_array[$key_data_kec] = ['nama' => $this->getNamaAreacode($key_data_kec), 'kirim' => $val_data_kec];

                    $html .=
                        ($val_data_kec == 1) ?
                        '<li class="levelgreen">' . $this->getNamaAreacode($key_data_kec) . '<ul>'
                        : '<li>' . $this->getNamaAreacode($key_data_kec) . '<ul>';

                    foreach ($data_kel as $key_data_kel => $val_data_kel) {
                        $parent_data_kel = substr($key_data_kel, 0, 6) . '0000';
                        if ($key_data_kec == $parent_data_kel) {
                            $urutan .=
                                ($val_data_kel == 1) ?
                                '<b class="text-success">Kel. ' . $this->getNamaAreacode($key_data_kel) . '</b> -> '
                                : 'Kel. ' . $this->getNamaAreacode($key_data_kel) . ' -> ';
                            // array_push($urutan_array, $key_data_kel);
                            $urutan_array[$key_data_kel] = ['nama' => $this->getNamaAreacode($key_data_kel), 'kirim' => $val_data_kel];

                            $html .=
                                ($val_data_kel == 1) ?
                                '<li class="levelgreen">' . $this->getNamaAreacode($key_data_kel) . '</li>'
                                : '<li>' . $this->getNamaAreacode($key_data_kel) . '</li>';
                        }
                    }

                    $html .= '</ul></li>';
                }
            }

            $html .= '</ul></li>';
        }

        $urutan = substr($urutan, 0, -3);

        return ['html' => $html, 'rute' => $urutan, 'urutan_array' => $urutan_array];
    }
}

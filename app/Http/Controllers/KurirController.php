<?php

namespace App\Http\Controllers;

use App\Kurir;
use App\Pengiriman;
use App\Pesanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class KurirController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax())
            return DataTables::of(Kurir::query())
                ->addColumn('action', function ($data) {
                    $button = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $data->id . '" data-original-title="Edit" class="edit btn btn-info btn-sm edit-post"><i class="far fa-edit"></i></a>';
                    $button .= '&nbsp;&nbsp;';
                    $button .= '<button type="button" name="delete" id="' . $data->id . '" class="delete btn btn-danger btn-sm"><i class="far fa-trash-alt"></i></button>';
                    return $button;
                })
                ->addColumn('mengirim', function ($data) {
                    $button = '';
                    if ($data->sedang_mengirim == 1) {
                        $button .= '<button disabled href="javascript:void(0)" class="btn btn-success btn-sm">Sedang Mengirim</button>';
                        $button .= '&nbsp;&nbsp;';
                        $button .= '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $data->id . '" class="btn btn-warning btn-sm lihat-pesanan">Lihat Pesanan</a>';
                        $button .= '&nbsp;&nbsp;';
                        $button .= '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $data->id . '" class="btn btn-warning btn-sm lihat-rute">Konfirmasi Rute</a>';
                    } else {
                        $button .= '<a href="/kurir/tambah-pengiriman/' . $data->id . '" class="btn btn-primary btn-sm">Tambahkan Pengiriman</a>';
                    }
                    return $button;
                })
                ->escapeColumns(['action', 'megirim'])
                ->addIndexColumn()
                ->make(true);
        return view('kurir.index');
    }

    public function getMaxId()
    {
        return response()->json(['new_id' => Kurir::max('id') + 1]);
    }

    public function tambahPengiriman($id)
    {
        $data = Kurir::find($id);
        $data_barang = DB::table('v_pesanan_belum_dikirim')->get();
        return view('kurir.tambahPengiriman', ['data' => $data, 'data_barang' => $data_barang]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_kurir' => 'required',
            'nama_kurir' => 'required',
            'sedang_mengirim' => 'required',
        ]);

        $post = Kurir::updateOrCreate(
            ['id' => $request->id],
            [
                'kode_kurir' => $request->kode_kurir,
                'nama_kurir' => $request->nama_kurir,
                'sedang_mengirim' => $request->sedang_mengirim,
            ]
        );


        return response()->json($post);
    }

    public function storePengiriman(Request $request)
    {
        // dd($request);

        Kurir::find($request->id_kurir)->update(
            ['sedang_mengirim' => 1,]
        );

        Pesanan::whereIn('id', $request->to)->update([
            'id_kurir' => $request->id_kurir,
            'proses' => 1,
            'tgl_kirim' => date('Y-m-d H:i:s'),
        ]);

        $data = Pesanan::where(['id_kurir' => $request->id_kurir, 'proses' => 1])->get();

        $list_kode_kab = [];
        $list_kode_kec = [];
        $list_kode_kel = [];

        foreach ($data as $key_data) {
            $kode_kab = substr($key_data->area_tujuan, 0, 4) . '000000';
            $kode_kec = substr($key_data->area_tujuan, 0, 6) . '0000';
            $kode_kel = $key_data->area_tujuan;

            // if (!in_array($kode_kab, $list_kode_kab)) array_push($list_kode_kab, $kode_kab);
            // if (!in_array($kode_kec, $list_kode_kec)) array_push($list_kode_kec, $kode_kec);
            // if (!in_array($kode_kel, $list_kode_kel)) array_push($list_kode_kel, ['id' => $kode_kel, 'kirim' => 0]);

            if (!in_array($kode_kab, $list_kode_kab)) $list_kode_kab[$kode_kab] = 0;
            if (!in_array($kode_kec, $list_kode_kec)) $list_kode_kec[$kode_kec] = 0;
            if (!in_array($kode_kel, $list_kode_kel)) $list_kode_kel[$kode_kel] = 0;
        }

        $data_semua_kode = ['kab' => $list_kode_kab, 'kec' => $list_kode_kec, 'kel' => $list_kode_kel];

        $id_pengiriman = Pengiriman::insertGetId([
            'lokasi_pengiriman' => json_encode($data_semua_kode),
            'proses' => 1,
            'tgl_kirim' => date('Y-m-d H:i:s'),
            'id_kurir' => $request->id_kurir,
        ]);

        Pesanan::where(['id_kurir' => $request->id_kurir, 'proses' => 1])->update([
            'id_pengiriman' => $id_pengiriman
        ]);

        return redirect('/kurir');
    }

    public function lihatPesanan($id)
    {
        // $data = DB::table('v_pesanan_sedang_dikirim')
        //     ->where(['id_kurir' => $id])
        //     ->groupBy('area_tujuan')
        //     ->get();

        $data = DB::table('v_pesanan_sedang_dikirim')
            ->where(['id_kurir' => $id])
            ->get();

        return response()->json($data);
    }

    public function lihatRute($id)
    {
        $data = DB::table('pengiriman')
            ->select('lokasi_pengiriman')
            ->where(['id_kurir' => $id, 'proses' => 1])
            ->first();
        $data_lokasi = json_decode($data->lokasi_pengiriman, TRUE);
        $data_kab = $data_lokasi['kab'];
        $data_kec = $data_lokasi['kec'];
        $data_kel = $data_lokasi['kel'];

        return $this->generate_sort_location(
            $data_kab,
            $data_kec,
            $data_kel
        );
    }

    public function cek_rute($t)
    {
        # code...
    }

    public function getNamaAreacode($areacode)
    {
        return DB::table('administrative_area2')->where('area_code', $areacode)->pluck('area_name')[0];
    }

    public function edit($id)
    {
        $kurir = Kurir::find($id);

        return response()->json($kurir);
    }

    public function destroy($id)
    {
        $kurir = Kurir::find($id)->delete();

        return response()->json($kurir);
    }

    public function imKurir()
    {
        $data = Kurir::all();
        return view('kurir.im_kurir', ['data' => $data]);
    }

    public function imKurirId($id)
    {
        $data_kurir = Kurir::find($id);
        $data_pengiriman = Pengiriman::select('id', 'lokasi_pengiriman')
            ->where(['id_kurir' => $id, 'proses' => 1])
            ->first();

        if (!empty($data_pengiriman)) {
            $data_arr_pengiriman = json_decode($data_pengiriman->lokasi_pengiriman, TRUE);
            $data_kab = $data_arr_pengiriman['kab'];
            $data_kec = $data_arr_pengiriman['kec'];
            $data_kel = $data_arr_pengiriman['kel'];

            $data_pengiriman2 = $this->generate_sort_location(
                $data_kab,
                $data_kec,
                $data_kel
            );
        } else {
            $data_pengiriman2 = [];
        }

        return view('kurir.kurir_index', ['data_kurir' => $data_kurir, 'data_pengiriman' => $data_pengiriman, 'data_pengiriman2' => $data_pengiriman2]);
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

    public function updatePengiriman($id, $kode)
    {
        $data_pengiriman = Pengiriman::select('lokasi_pengiriman')
            ->where(['id' => $id])
            ->first();

        $data_arr_pengiriman = json_decode($data_pengiriman->lokasi_pengiriman, TRUE);

        foreach ($data_arr_pengiriman as $key_a => $value_a) {
            foreach ($value_a as $key_a_b => $value_a_b) {
                if ($key_a_b == $kode) $data_arr_pengiriman[$key_a][$key_a_b] = 1;
            }
        }

        $data_json = json_encode($data_arr_pengiriman);

        $post = Pengiriman::find($id)->update(
            ['lokasi_pengiriman' => $data_json,]
        );

        return response()->json($post);
    }
}

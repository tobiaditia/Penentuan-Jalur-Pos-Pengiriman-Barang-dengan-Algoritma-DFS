<?php

namespace App\Http\Controllers;

use App\Barang;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class BarangController extends Controller
{

    public function index(Request $request)
    {
        if ($request->ajax())
            return DataTables::of(Barang::query())
                ->addColumn('action', function ($data) {
                    $button = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $data->id . '" data-original-title="Edit" class="edit btn btn-info btn-sm edit-post"><i class="far fa-edit"></i></a>';
                    $button .= '&nbsp;&nbsp;';
                    $button .= '<button type="button" name="delete" id="' . $data->id . '" class="delete btn btn-danger btn-sm"><i class="far fa-trash-alt"></i></button>';
                    return $button;
                })
                ->rawColumns(['action'])
                ->addIndexColumn()
                ->toJson();
        return view('barang.index');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_barang' => 'required',
            'berat' => 'required',
            'stok' => 'required',
        ]);
        $file       = $request->file('gambar');
        // dd($fileName   = $file->getClientOriginalExtension());
        if ($request->hasFile('gambar')) {
            $file       = $request->file('gambar');
            $fileName   = $file->getClientOriginalName();
            $fileName_name = pathinfo($fileName, PATHINFO_FILENAME);
            $fileName_extension = $file->getClientOriginalExtension();

            // if (@file_exists(public_path('images/' . $old_data))) unlink(public_path("'images\barang\'" . $old_data));

            $nama_gambar = substr($fileName_name, 0, 50) . date('_YmdHis_.') . $fileName_extension;

            $request->file('gambar')->move("images/barang/", $nama_gambar);
        } else {
            $old_data   = Barang::select('gambar')->where('id', $request->id)->first()->gambar;
            if (!empty($old_data)) {
                $nama_gambar = $old_data;
            } else {
                $nama_gambar = 'default.jpg';
            }
        }
        $data = [
            'nama_barang' => $request->nama_barang,
            'berat' => $request->berat,
            'stok' => $request->stok,
            'gambar' => $nama_gambar,
        ];
        // dd($data);
        if (empty($request->id)) {
            $post = Barang::insert(
                $data
            );
        } else {
            $post = Barang::where('id', $request->id)->update(
                $data
            );
        }


        return response()->json($post);
    }

    public function show(Barang $barang)
    {
        //
    }

    public function edit($id)
    {
        $barang = Barang::find($id);

        return response()->json($barang);
    }


    public function destroy($id)
    {
        $barang = Barang::find($id)->delete();

        return response()->json($barang);
    }
}

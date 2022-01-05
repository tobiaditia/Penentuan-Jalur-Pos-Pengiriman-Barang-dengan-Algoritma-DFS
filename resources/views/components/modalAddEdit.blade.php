<div class="modal fade" id="tambah-edit-modal" aria-hidden="true">
    <div class="modal-dialog 
        {{ isset($size) ? $size : '' }}
        ">
        <style>
            label {
                font-weight: normal !important;
            }

        </style>
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-judul">{{ $titleModal }}</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <form id="form-tambah-edit" name="form-tambah-edit" class="form-horizontal"
                    enctype="multipart/form-data" method="POST">
                    {{ csrf_field() }}
                    <div class="row">
                        <div class="col-sm-12">

                            <input type="hidden" name="id" id="id">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">

                            @if ($type == 'barang')
                                <x-input type="text" label="Nama Barang" field="nama_barang" />
                                <x-input type="text" label="Berat" field="berat" />
                                <x-input type="text" label="stok" field="stok" />
                                <x-inputImage label="Gambar" field="gambar" />
                            @elseif ($type == 'kurir')
                                <x-input type="text" label="Kode Kurir" field="kode_kurir" readonly="readonly" />
                                <x-input type="text" label="Nama Kurir" field="nama_kurir" />
                                <x-input type="text" label="Sedang Mengirim" field="sedang_mengirim" readonly="readonly"
                                    value="0" />

                            @endif

                        </div>

                        <div class="col-sm-offset-2 col-sm-12">
                            <button type="submit" class="btn btn-primary btn-block" id="tombol-simpan"
                                value="create">Simpan
                            </button>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>

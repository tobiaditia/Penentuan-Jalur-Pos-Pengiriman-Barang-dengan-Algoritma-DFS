@extends('layout.admin')
@section('title', 'Tambah Pengiriman')

@section('content')

    <form class="container" action="/kurir/store-pengiriman" method="post">
        <input type="hidden" name="id_kurir" value="{{ $data->id }}">
        @csrf
        <h2>Pilih Pesanan yang akan dikirim Kurir {{ $data->nama_kurir }}</h2>
        <div class="row">
            <div class="col-md-5">
                <select name="from" id="lstview" class="form-control" size="13" multiple="multiple">
                    @foreach ($data_barang as $i_data_barang)

                        <option value="{{ $i_data_barang->id }}">
                            {{ ' ( ' . $i_data_barang->nama_barang . ' ) ' . $i_data_barang->resi }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-2">
                <button type="button" id="lstview_undo" class="btn btn-danger btn-block">undo</button>
                <button type="button" id="lstview_rightAll" class="btn btn-secondary btn-block"><i
                        class="fas fa-forward"></i></button>
                <button type="button" id="lstview_rightSelected" class="btn btn-secondary btn-block"><i
                        class="fas fa-chevron-right"></i></button>
                <button type="button" id="lstview_leftSelected" class="btn btn-secondary btn-block"><i
                        class="fas fa-chevron-left"></i></button>
                <button type="button" id="lstview_leftAll" class="btn btn-secondary btn-block"><i
                        class="fas fa-backward"></i></button>
                <button type="button" id="lstview_redo" class="btn btn-warning btn-block">redo</button>
            </div>

            <div class="col-md-5">
                <select name="to[]" id="lstview_to" class="form-control" size="13" multiple="multiple"></select>
            </div>


        </div>
        <div class="row mt-4">
            <button type="submit" onclick="selectAll();" class="btn btn-primary w-100">Konfirmasi</button>
        </div>
    </form>

    <script>
        function selectAll() {
            selectBox = document.getElementById("lstview_to");

            for (var i = 0; i < selectBox.options.length; i++) {
                selectBox.options[i].selected = true;
            }
        }

    </script>

@endsection

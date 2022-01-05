@extends('layout.public')
@section('title', 'Show Barang')

@section('content')
    <form action="/public" method="post" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class='col-md-4'>
                <div class="card border-top-primary shadow p-2 m-2">
                    <img src="{{ url('images/barang') . '/' . $data->gambar }}" class="img-thumbnail" alt="">

                    {{ $data->nama_barang }}

                </div>
            </div>
            <div class="col-md-6">
                <input type="hidden" name="id_barang" value="{{ $data->id }}">
                <x-input type="text" label="Nama" field="nama_pembeli" />
                <x-select type="kabkota" />

                <x-select type="kecamatan" />
                <x-select type="kelurahan" />

                <button class="btn btn-primary w-100" type="submit">Beli</button>
            </div>
        </div>
    </form>
    <script>
        var val_kabkota_code;
        var val_kecamatan_code;
        $('#select2Kabkota').change(function() {
            val_kabkota_code = $(this).val();
            $('#select2Kelurahan').empty();
            if (val_kabkota_code) {
                $.ajax({
                    url: "{{ url('/public/get-kecamatan') }}/" + val_kabkota_code,
                    type: "GET",
                    dataType: "json",
                    success: function(data) {
                        $('#select2Kecamatan').empty();
                        $.each(data, function(key, value) {
                            $('#select2Kecamatan').append('<option value="' + value
                                .kecamatan_code + '">' +
                                value.kecamatan_name + '</option>');
                        });


                    }
                });
            } else {
                $('#select2Kecamatan').empty();
            }
        });

        $('#select2Kecamatan').change(function() {
            val_kecamatan_code = $(this).val();
            console.log(val_kabkota_code);
            console.log(val_kecamatan_code);
            if (val_kecamatan_code) {
                $.ajax({
                    url: "{{ url('/public/get-kelurahan') }}/" + val_kabkota_code + "/" +
                        val_kecamatan_code,
                    type: "GET",
                    dataType: "json",
                    success: function(data) {
                        $('#select2Kelurahan').empty();
                        $.each(data, function(key, value) {
                            $('#select2Kelurahan').append('<option value="' + value
                                .area_code + '">' +
                                value.kelurahan_name + '</option>');
                        });


                    }
                });
            } else {
                $('#select2Kecamatan').empty();
            }
        });

    </script>
@endsection

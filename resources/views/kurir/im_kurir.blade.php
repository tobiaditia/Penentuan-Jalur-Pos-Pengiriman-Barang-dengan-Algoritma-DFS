@extends('layout.public')
@section('title', 'Barang')

@section('content')
    <div class="d-sm-flex align-items-center justify-content-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Pilih Kurir</h1>
    </div>
    <div class="row">
        @foreach ($data as $data_i)
            <div class='col-md-2'>
                <div class="card border-top-primary shadow p-2 m-2"><span class="text-center mt-3 mb-3">
                        <b>{{ $data_i->nama_kurir }}</b>
                        <hr>
                        <span class='text-danger text-bold'> {{ $data_i->kode_kurir }} </span>
                    </span>
                    <a href="/kurir/im-kurir/{{ $data_i->id }}" class="btn btn-primary" type="button">Cek</a>

                </div>
            </div>
        @endforeach
    </div>
@endsection

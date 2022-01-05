@extends('layout.public')
@section('title', 'Barang')

@section('content')
    <div class="d-sm-flex align-items-center justify-content-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Produk Kami</h1>
    </div>
    <div class="row">
        @foreach ($data as $data_i)
            <div class='col-md-2'>
                <div class="card border-top-primary shadow p-2 m-2">
                    <img src="{{ url('images/barang') . '/' . $data_i->gambar }}" class="img-thumbnail" alt="">
                    <span class="text-center mt-3 mb-3">
                        <b>{{ $data_i->nama_barang }}</b>
                        <hr>

                        Stok : {!! $data_i->stok == 0 ? "<span class='text-danger text-bold'>" . $data_i->stok . '</span>' :
                        $data_i->stok !!}
                    </span>
                    @if ($data_i->stok == 0)
                        <button disabled class="btn btn-primary" type="button">Beli</button>
                    @else
                        <a href="/public/{{ $data_i->id }}" class="btn btn-primary" type="button">Beli</a>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
@endsection

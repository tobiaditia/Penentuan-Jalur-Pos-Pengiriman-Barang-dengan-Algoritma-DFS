@extends('layout.kurir')
@section('title', 'Update Pengiriman')

@section('content')
    @if ($data_kurir->sedang_mengirim)
        <table id="tableKurirId" class="table table-striped table-bordered table-sm w-100">
            <thead>
                <tr>
                    <th style="width: 10px">Urutan</th>
                    <th>Wilayah</th>
                    <th>Tingkatan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data_pengiriman2['urutan_array'] as $key_data_rute => $v_data_rute)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $v_data_rute['nama'] }}</td>
                        <td>
                            @if (substr($key_data_rute, 4) == '000000')
                                Kab
                            @elseif (substr($key_data_rute, 6) == '0000')
                                Kec
                            @elseif (substr($key_data_rute, 6) != '0000')
                                Kel
                            @else
                                Undifind
                            @endif
                        </td>
                        <td>
                            @if ($v_data_rute['kirim'] == 0)
                                <button type="button" data-kode="{{ $key_data_rute }}" data-id="{{ $data_pengiriman->id }}"
                                    class="sudah-kirim btn btn-sm btn-primary">Sudah
                                    Kirim</button>
                            @else
                                <button type="button" class="btn btn-sm btn-success disabled">Terkirim</button>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        Tidak Sedang Mengirim
    @endif


    <script>
        $(document).ready(function() {

            // var datatable = $('#tableKurirId').DataTable({
            //     processing: true,
            //     serverSide: true,
            //     ajax: {
            //         url: "{{ route('kurir.index') }}",
            //         type: "GET"
            //     },
            //     columns: [{
            //             data: 'DT_RowIndex',
            //             name: 'DT_RowIndex',
            //             orderable: false,
            //             searchable: false
            //         },
            //         {
            //             data: 'kode_kurir',
            //             name: 'kode_kurir'
            //         },
            //         {
            //             data: 'nama_kurir',
            //             name: 'nama_kurir'
            //         }, {
            //             data: 'mengirim',
            //             name: 'mengirim'
            //         },
            //         {
            //             data: 'action',
            //             name: 'action'
            //         },
            //     ],
            //     columnDefs: [{
            //         width: '60px',
            //         targets: 4
            //     }],
            // });

            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

            // $('#add').click(function() {
            //     $.get("{{ url('kurir/getMaxId') }}", function(data) {
            //         $('input[name="kode_kurir"]').val('KRR' + data.new_id);
            //     });

            //     $('#id').val('');
            //     $('#form-tambah-edit').trigger("reset");
            //     $('#tambah-edit-modal').modal('show');
            // });
            // var loadUrl = "http://fiddle.jshell.net/deborah/pkmvD/show/";
            // $("#loadbasic").click(function() {
            //     $("html").html(ajax_load).load(loadUrl);
            // });
            $('body').on('click', '.sudah-kirim', function() {
                var data_id = $(this).data('id');
                var data_kode = $(this).data('kode');
                var data_id_kurir = "{{ $data_kurir->id }}";
                $.get('/kurir/update-pengiriman/' + data_id + '/' + data_kode, function(data) {
                    location.reload();
                    // $('#modal-judul').html("Edit Kurir");
                    // $('#tombol-simpan').val("edit-post");
                    // $('#tambah-edit-modal').modal('show');

                    // $('#id').val(data.id);
                    // $('#kode_kurir').val(data.kode_kurir);
                    // $('#nama_kurir').val(data.nama_kurir);
                    // $('#sedang_mengirim').val(data.sedang_mengirim);
                })
            });

            $('body').on('click', '.lihat-pesanan', function() {
                var data_id = $(this).data('id');
                $('#table-kurir tbody > tr').remove();
                var html = '';
                $.get('kurir/lihat-pesanan/' + data_id, function(data) {

                    $('#kurir-pesanan-modal').modal('show');

                    $.each(data, function(i, item) {
                        html += '<tr>';
                        html += '<td>' + item.resi + '</td>';
                        html += '<td>' + item.nama_barang + '</td>';
                        html += '<td>' + item.nama_pembeli + '</td>';
                        html += '<td>' + item.tujuan + '</td></tr>';
                    });
                    $('#table-kurir tbody').append(html);
                })
            });

            $('body').on('click', '.lihat-rute', function() {
                var data_id = $(this).data('id');
                $('#perubahan_rute > li').remove();
                $('#kurir-rute-modal').modal('show');
                $.get('kurir/lihat-rute/' + data_id, function(data) {
                    console.log(data);
                    $('#perubahan_rute').append(data.html);
                    $("#rute").text(data.rute);
                    $(function() {
                        $("#organisation").orgChart({
                            container: $("#main")
                        });
                    });
                })
            });

            $('#tombol-simpan').click(function(evt) {
                evt.preventDefault();
                var data = new FormData(this.form);

                $.ajax({
                    url: "{{ route('kurir.store') }}",
                    type: "POST",
                    data: data,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        $('#form-tambah-edit').trigger("reset");
                        $('#tambah-edit-modal').modal('hide');
                        $('#tombol-simpan').html('Simpan');
                        datatable.ajax.reload();
                    },
                    error: function(response) {
                        console.log('Error:', data);
                        $('#tombol-simpan').html('Simpan');
                    }
                });
            });

            $(document).on('click', '.delete', function() {
                dataId = $(this).attr('id');
                $('#konfirmasi-modal').modal('show');
            });

            $('#tombol-hapus').click(function() {
                $.ajax({
                    url: "{{ route('kurir.index') }}/" + dataId, //eksekusi ajax ke url ini
                    type: 'delete',
                    beforeSend: function() {
                        $('#tombol-hapus').text('Hapus Data'); //set text untuk tombol hapus
                    },
                    success: function(data) { //jika sukses
                        setTimeout(function() {
                            $('#konfirmasi-modal').modal(
                                'hide'); //sembunyikan konfirmasi modal
                            datatable.ajax.reload();
                        });
                        // iziToast.warning({ //tampilkan izitoast warning
                        //     title: 'Data Berhasil Dihapus',
                        //     message: '{{ Session('
                        //     delete ') }}',
                        //     position: 'bottomRight'
                        // });
                    }
                })
            });

        });

    </script>
@endsection

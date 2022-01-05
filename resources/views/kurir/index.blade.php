@extends('layout.admin')
@section('title', 'Kurir')

@section('content')

    <div class="d-flex justify-content-end mb-3">
        <a href="javascript:void(0)" class="btn btn-sm btn-success" id="add">Tambah</a>
    </div>
    <table id="tableKurir" class="table table-striped table-bordered table-sm w-100">
        <thead>
            <tr>
                <th style="width: 10px">No</th>
                <th>Kode Kurir</th>
                <th>Nama</th>
                <th>Mengirim</th>
                <th>Aksi</th>
            </tr>
        </thead>
    </table>


    <x-modalAddEdit titleModal="Tambah Kurir" type="kurir" />
    <x-modalDelete titleModal="Hapus Kurir" />
    <x-modalKurirPesanan titleModal="List Pesanan" size="modal-lg" />
    <x-modalKurirRute titleModal="Rute Pengiriman" size="modal-lg" />
    <style>
        div.orgChart div.node.levelgreen {
            background-color: #cefbce;
        }

    </style>
    <script>
        $(document).ready(function() {

            var datatable = $('#tableKurir').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('kurir.index') }}",
                    type: "GET"
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'kode_kurir',
                        name: 'kode_kurir'
                    },
                    {
                        data: 'nama_kurir',
                        name: 'nama_kurir'
                    }, {
                        data: 'mengirim',
                        name: 'mengirim'
                    },
                    {
                        data: 'action',
                        name: 'action'
                    },
                ],
                columnDefs: [{
                    width: '60px',
                    targets: 4
                }],
            });

            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

            $('#add').click(function() {
                $.get("{{ url('kurir/getMaxId') }}", function(data) {
                    $('input[name="kode_kurir"]').val('KRR' + data.new_id);
                });

                $('#id').val('');
                $('#form-tambah-edit').trigger("reset");
                $('#tambah-edit-modal').modal('show');
            });

            $('body').on('click', '.edit-post', function() {
                var data_id = $(this).data('id');
                $.get('kurir/' + data_id + '/edit', function(data) {
                    $('#modal-judul').html("Edit Kurir");
                    $('#tombol-simpan').val("edit-post");
                    $('#tambah-edit-modal').modal('show');

                    $('#id').val(data.id);
                    $('#kode_kurir').val(data.kode_kurir);
                    $('#nama_kurir').val(data.nama_kurir);
                    $('#sedang_mengirim').val(data.sedang_mengirim);
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
                    $("#rute").html(data.rute);
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

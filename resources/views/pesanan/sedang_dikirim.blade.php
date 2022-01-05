@extends('layout.admin')
@section('title', 'Pesanan Belum Dikirim')

@section('content')

    <table id="tablePesananSedangKirim" class="table table-striped table-bordered table-sm w-100">
        <thead>
            <tr>
                <th style="width: 10px">No</th>
                <th>No. Resi</th>
                <th>Barang</th>
                <th>Pembeli</th>
                <th>Kode kurir</th>
                <th>Tgl. Kirim</th>
                <th>Tujuan</th>
                <th>Aksi</th>
            </tr>
        </thead>
    </table>

    <script>
        $(document).ready(function() {
            var datatable = $('#tablePesananSedangKirim').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ url('pesanan/sedang-dikirim') }}",
                    type: "GET"
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'resi',
                        name: 'resi'
                    },
                    {
                        data: 'nama_barang',
                        name: 'nama_barang'
                    }, {
                        data: 'nama_pembeli',
                        name: 'nama_pembeli'
                    },
                    {
                        data: 'kode_kurir',
                        name: 'kode_kurir'
                    },
                    {
                        data: 'tgl_kirim',
                        name: 'tgl_kirim'
                    }, {
                        data: 'tujuan',
                        name: 'tujuan'
                    }, {
                        data: 'action',
                        name: 'action'
                    },
                ],
                columnDefs: [{
                    width: '60px',
                    targets: 3
                }],
            });

            $('#add').click(function() {
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
            // if ($("#form-tambah-edit").length > 0) {
            //     $("#form-tambah-edit").validate({
            //         submitHandler: function(form) {
            //             $('#tombol-simpan').html('Sending..');
            //             $.ajax({
            //                 data: $('#form-tambah-edit').serialize(),

            //                 url: "{{ route('barang.store') }}",
            //                 type: "POST",
            //                 dataType: 'json',
            //                 success: function(data) {
            //                     console.log('sukse');
            //                     $('#form-tambah-edit').trigger("reset");
            //                     $('#tambah-edit-modal').modal('hide');
            //                     $('#tombol-simpan').html('Simpan');
            //                     datatable.ajax.reload();
            //                 },
            //                 error: function(data) {
            //                     console.log('Error:', data);
            //                     $('#tombol-simpan').html('Simpan');
            //                 }
            //             });
            //         }
            //     })
            // }

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

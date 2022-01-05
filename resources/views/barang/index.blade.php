@extends('layout.admin')
@section('title', 'Barang')

@section('content')
    <div class="d-flex justify-content-end mb-3">
        <a href="javascript:void(0)" class="btn btn-sm btn-success" id="add">Tambah</a>
    </div>
    <table id="tableBarang" class="table table-striped table-bordered table-sm w-100">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Berat</th>
                <th>Stok</th>
                <th>Aksi</th>
            </tr>
        </thead>
    </table>

    <x-modalAddEdit titleModal="Tambah Barang" type="barang" />
    <x-modalDelete titleModal="Hapus Barang" />

    <script>
        $(document).ready(function() {
            var datatable = $('#tableBarang').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('barang.index') }}",
                    type: "GET"
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'nama_barang',
                        name: 'nama_barang'
                    },
                    {
                        data: 'berat',
                        name: 'berat'
                    }, {
                        data: 'stok',
                        name: 'stok'
                    },
                    {
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
                $.get('barang/' + data_id + '/edit', function(data) {
                    $('#modal-judul').html("Edit Barang");
                    $('#tombol-simpan').val("edit-post");
                    $('#tambah-edit-modal').modal('show');

                    $('#id').val(data.id);
                    $('#nama_barang').val(data.nama_barang);
                    $('#berat').val(data.berat);
                    $('#stok').val(data.stok);
                })
            });

            $('#tombol-simpan').click(function(evt) {
                evt.preventDefault();
                var data = new FormData(this.form);

                $.ajax({
                    url: "{{ route('barang.store') }}",
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
                    url: "{{ route('barang.index') }}/" + dataId, //eksekusi ajax ke url ini
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

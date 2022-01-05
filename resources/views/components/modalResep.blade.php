<div class="modal fade" id="resep-modal" aria-hidden="true" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-scrollable modal-xl" role="document">
        <style>
            label{
                font-weight: normal !important;
            }
        </style>
        <form id="form-resep" name="form-resep" class="form-horizontal w-100">
            <input type="hidden" name="id_rekamedis" id="id_rekamedis">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal-judul">{{$titleModal}}</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-danger alert-dismissible" style="display: none;" role="alert" id="alertModal">
                        <strong id="isiAlert"></strong>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">

                            <input type="hidden" name="_token" value="{{csrf_token()}}">
                            
                            <x-selectObatFromKodeNama/>

                            <div class="clearfix mb-3">
                                <button type="button" class="pull-right btn btn-sm btn-success" id="addResepObat"><i class="fas fa-plus-square" ></i> Tambah Obat</button>
                            </div>

                            <div id="wrapResepObat">
                                
                            </div>
                            

                        </div>
                    </div>
            
                </div>
                <div class="modal-footer">
                    <div class="col-sm-offset-2 col-sm-12">
                        <button type="submit" class="btn btn-primary btn-block" id="tombol-simpan-resep"
                            value="create">Simpan
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<style>
    @media (min-width: 768px) {
      .modal-xl {
        width: 70%;
       max-width:1200px;
      }
    }
</style>

<script>
    var idObat = '';
    var kodeObat = '';
    var namaObat = '';
    var stokObat = 0;
    var obats = [];

    $('#obatAndKode').select2({
        allowClear: true,
        minimumInputLength: 1,
        placeholder: "Masukkan nama / kode obat"
    }).on("select2:select", function (e) {
        var selected_element = $(e.currentTarget);
        var id_obat = selected_element.val();

        $('#id_obat').val(id_obat);
        $.get("{{url('helper/obatFromKodeNama/')}}/"+id_obat, function(data, status){
            $('#readNamaObat').val(data.nama);
            $('#readKodeObat').val(data.kode);
            $('#readStokObat').val(formatNumber(data.stok));

            idObat = id_obat;
            kodeObat = data.kode;
            namaObat = data.nama;
            stokObat = formatNumber(data.stok);
        });
    });
    $("#obatAndKode").val("").trigger("change");
    $("#obatAndKode").trigger("change");

    $('#addResepObat').click(function(){
        if (jQuery.inArray( idObat, obats ) === -1) {
            if(stokObat != 0){
                obats.push(idObat);
                var addElement = 
                    '<div class="card w-100 addrowresep" id="card-0">'+
                        '<div class="card-header">'+
                            '<div class="d-flex justify-content-between">'+
                                '<p class="text-bold">Obat 1</p>'+
                                '<button data-id="'+idObat+'" class="btn btn-sm btn-danger pull-right hapusResepObat" title="Hapus Obat">'+
                                    '<i class="fas fa-trash-alt"></i>'+
                                '</button>'+
                            '</div>'+
                        '</div>'+
                        '<div class="card-body">'+
                            '<input type="hidden" name="id_obat[]" value="'+idObat+'">'+
                            '<table class="table table-borderless">'+
                                '<tbody>'+
                                    '<tr>'+
                                        '<td>Kode Obat</td>'+
                                        '<td class="text-bold">'+kodeObat+'</td>'+
                                    '</tr>'+
                                    '<tr>'+
                                        '<td>Nama Obat</td>'+
                                        '<td class="text-bold">'+namaObat+'</td>'+
                                    '</tr>'+
                                    '<tr>'+
                                        '<td>Stok Obat</td>'+
                                        '<td class="text-bold">'+stokObat+'</td>'+
                                    '</tr>'+
                                    '<tr>'+
                                        '<td>jumlah</td>'+
                                        '<td>'+
                                            '<input type="number" max="'+stokObat+'" class="form-control" id="jumlah[]" placeholder="Masukan jumlah obat yang akan diambil." name="jumlah[]" autocomplete="off" value="" required="">'+
                                        '</td>'+
                                    '</tr>'+
                                '</tbody>'+
                            '</table>'+
                        '</div>'+
                    '</div>';
                
                $('#wrapResepObat').append(addElement);
            }else{
                $('#isiAlert').text('Stok obat ini telah habis!');
                $("#alertModal").fadeTo(2000, 500).slideUp(500);
            }
        }else{
            $('#isiAlert').text('Obat ini sudah anda tambahkan!');
            $("#alertModal").fadeTo(2000, 500).slideUp(500);
        }
        $('#obatAndKode').val("").trigger( "change" );
        $('#readStokObat').val("").trigger( "change" );
    });

    $('body').on('click', '.hapusResepObat', function () {
        
        var index = obats.indexOf($(this).data('id').toString());
        obats.splice(index, 1);

        $(this).closest('.card').remove();
    });

    if ($("#form-resep").length > 0) {
        $("#form-resep").validate({
            submitHandler: function (form) {
                $('#tombol-simpan-resep').html('Sending..');
                $.ajax({
                    data: $('#form-resep').serialize(), 
                    url: "{{route('resepStore')}}", 
                    type: "POST", 
                    dataType: 'json', 
                    success: function (data) {  
                        $('#form-resep').trigger("reset"); 
                        $('#resep-modal').modal('hide'); 
                        $('#tombol-simpan-resep').html('Simpan'); 
                        datatable.ajax.reload();
                        sweetAlert("Berhasil menambahkan resep.","", "success");
                    },
                    error: function (data) { 
                        console.log('Error:', data);
                        $('#tombol-simpan').html('Simpan');
                        $('input').removeClass('is-invalid');
                        $.each(data.responseJSON.errors, function( index, value ) {
                            $('#err_'+index).text(value);
                            $('input[name='+index+']').addClass('is-invalid');
                        });
                    }
                });
            }
        })
    }
</script>
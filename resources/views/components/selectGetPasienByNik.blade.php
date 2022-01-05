@php
    $pasien = DB::table('v_pasien')->get();
@endphp
<div class="form-group row">
    <label class="col-sm-3 col-form-label">Masukkan NIK</label>
    <div class="col-sm-9">
        <select class="form-control select2" id="select2Pasien" style="width: 100%;" id="getPasien">
            <option value="">--Pilih--</option>
            @foreach ($pasien as $p)
                <option value="{{$p->id}}">{{$p->nik}}</option>
            @endforeach
        </select>
    </div>
</div>
<div class="form-group row">
    <label class="col-sm-3 col-form-label">Nama</label>
    <div class="col-sm-9">
        <input type="text" class="form-control" value="" id="nama" readonly required>
    </div>
</div>
<div class="form-group row">
    <label class="col-sm-3 col-form-label">Departemen</label>
    <div class="col-sm-9">
        <input type="text" class="form-control" value="" id="departemen" readonly required>
    </div>
</div>
<style>
    .select2-selection__rendered {
        line-height: 31px !important;
        padding: 0 0 0 12px !important;
    }
    .select2-container .select2-selection--single {
        height: 38px !important;
    }
    .select2-selection__arrow {
        height: 34px !important;
    }
</style>
<script>
    $('#select2Pasien').select2({
        allowClear: true,
        minimumInputLength: 1,
        placeholder: "Masukkan NIK"
    }).on("select2:select", function (e) {
        var selected_element = $(e.currentTarget);
        var id_pasien = selected_element.val();
        $('#id_pasien').val(id_pasien);

        $.get("{{url('helper/getPasien/')}}/"+id_pasien, function(data, status){
            $('#nama').val(data.nama);
            $('#departemen').val(data.departemen);
        });
        
    });
    $("#select2Pasien").val("").trigger("change");
    $("#select2Pasien").trigger("change");
</script>
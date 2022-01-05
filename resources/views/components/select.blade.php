@if ($type == 'kabkota')
    @php
    $kabkota = DB::table('v_kabkota')->get();
    @endphp
    <div class="form-group row">
        <label class="col-sm-3 col-form-label">Kabupaten/Kota</label>
        <div class="col-sm-9">
            <select class="form-control select2" id="select2Kabkota" name="select2Kabkota" style="width: 100%;">
                <option value="">--Pilih--</option>
                @foreach ($kabkota as $d_kabkota)
                    <option value="{{ $d_kabkota->kabupatenkota_code }}">{{ $d_kabkota->kabupatenkota_name }}</option>

                @endforeach
            </select>
        </div>
    </div>
@elseif ($type == 'kecamatan')
    <div class="form-group row">
        <label class="col-sm-3 col-form-label">Kecamatan</label>
        <div class="col-sm-9">
            <select class="form-control select2" id="select2Kecamatan" name="select2Kecamatan" style="width: 100%;">
            </select>
        </div>
    </div>
@elseif ($type == 'kelurahan')
    <div class="form-group row">
        <label class="col-sm-3 col-form-label">Kelurahan</label>
        <div class="col-sm-9">
            <select class="form-control select2" id="select2Kelurahan" name="select2Kelurahan" style="width: 100%;">
            </select>
        </div>
    </div>
@elseif ($type == 'kelas')
    @php
    $tingkat = DB::table('tingkat')->get();
    @endphp
    <div class="form-group">
        <label for="{{ $field }}" class="col-sm-12 control-label">{{ $label }}</label>
        <div class="col-sm-12">
            <select class="form-control select2" name="{{ $field }}" id="{{ $field }}">
                @foreach ($tingkat as $i_tingkat)
                    <option value="{{ $i_tingkat->id }}">{{ $i_tingkat->nama }}</option>
                @endforeach
            </select>
        </div>
    </div>

@elseif ($type == 'wali')
    @php
    $guru = DB::table('guru')->get();
    @endphp
    <div class="form-group">
        <label for="guru_id" class="col-sm-12 control-label">{{ $label }}</label>
        <div class="col-sm-12">
            <select class="form-control select2" name="guru_id" id="guru_id">
                <option value="0">Belum Ada</option>
                @foreach ($guru as $i_guru)
                    <option value="{{ $i_guru->id }}">{{ $i_guru->nama }}</option>
                @endforeach
            </select>
        </div>
    </div>

@elseif ($type == 'tahunAjaran')
    @php
    $tahun_ajaran = array();
    for ($i=date('Y'); $i > 2015; $i--) {
    $ii = $i + 1;
    $tahun_ajaran[] = $i."/".$ii;
    }
    @endphp

    <div class="form-group">
        <label for="tahunAjaran" class="col-sm-12 control-label">{{ $label }}</label>
        <div class="col-sm-12">
            <select class="form-control select2" name="tahunAjaran" id="tahunAjaran">
                @foreach ($tahun_ajaran as $i_tahun_ajaran)
                    <option value="{{ $i_tahun_ajaran }}">{{ $i_tahun_ajaran }}</option>
                @endforeach
            </select>
        </div>
    </div>

@elseif ($type == 'semester')

    <div class="form-group">
        <label for="semester" class="col-sm-12 control-label">{{ $label }}</label>
        <div class="col-sm-12">
            <select class="form-control select2" name="semester" id="semester">
                @for ($i = 1; $i < 3; $i++)
                    <option value="{{ $i }}">{{ $i }}</option>
                @endfor
            </select>
        </div>
    </div>

@endif

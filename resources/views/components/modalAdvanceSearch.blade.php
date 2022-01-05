<div class="modal fade" id="advance-filter-modal" aria-hidden="true">
    <div class="modal-dialog 
        {{isset($size) ? $size : ""}}
        ">
        <style>
            label{
                font-weight: normal !important;
            }
        </style>
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-judul">{{$titleModal}}</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="form-group row">
                    <label class="col-sm-3 col-form-label">Periode</label>
                    <div class="col-sm-9">
                        <div class="row input-daterange mb-2 mt-1">
                            <div class="col-md-5">
                                <input type="text" name="from_date" id="from_date" class="form-control" placeholder="From Date" readonly />
                            </div>
                            <div class="col-md-5">
                                <input type="text" name="to_date" id="to_date" class="form-control" placeholder="To Date" readonly />
                            </div>
                            <div class="col-md-2">
                                <button type="button" id="cleardate" class="btn btn-warning">Clear</button>
                            </div>
                        </div>
                    </div>
                </div>
                <x-input type="text" label="Kode Rekamedis" field="adv_koderekamedis"/>
                <x-input type="text" label="NIK" field="adv_nik"/>
                <x-input type="text" label="Nama Pasien" field="adv_nama"/>
                <x-input type="text" label="Keluhan" field="adv_keluhan"/>
            </div>
            <div class="modal-footer">
                    {{-- <button type="submit" class="btn btn-primary btn-block" id="tombol-simpan-resep"
                        value="create">Simpan
                    </button> --}}
                <button type="button" name="refresh" id="refreshform" class="btn btn-default border border-dark">Refresh</button>
                <button type="button" name="filter" id="filter" class="btn btn-primary">Filter</button>
            </div>
        </div>
    </div>
</div>
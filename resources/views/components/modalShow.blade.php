<div class="modal fade" id="show-modal" aria-hidden="true">
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
                <div class="row">
                    <div class="col-sm-12">
                        @if ($type == 'penduduk')
                            tes
                        @elseif ($type == 'wilayah')
                            tes
                        @endif

                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
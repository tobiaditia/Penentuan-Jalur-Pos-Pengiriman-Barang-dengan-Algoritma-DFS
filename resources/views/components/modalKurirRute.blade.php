<div class="modal fade" id="kurir-rute-modal" aria-hidden="true">
    <div class="modal-dialog 
        {{ isset($size) ? $size : '' }}
        ">
        <style>
            label {
                font-weight: normal !important;
            }

        </style>
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-judul">{{ $titleModal }}</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <div id="left" class="d-none">

                    <ul id="organisation">
                        <li class="levelgreen">Kantor Surabaya
                            <ul id="perubahan_rute">

                            </ul>
                        </li>
                    </ul>

                </div>

                <div id="content">

                    <div id="main">
                    </div>

                    <div id="rute">
                    </div>
                    <button type="button" class="btn btn-primary w-100 mt-3">Konfirmasi</button>
                </div>

            </div>
        </div>
    </div>
</div>

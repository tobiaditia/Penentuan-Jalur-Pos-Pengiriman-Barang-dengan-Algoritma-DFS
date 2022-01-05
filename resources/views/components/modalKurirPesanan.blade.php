<div class="modal fade" id="kurir-pesanan-modal" aria-hidden="true">
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
                <table class="table" id="table-kurir">
                    <thead>
                        <tr>
                            <th>No Resi</th>
                            <th>Barang</th>
                            <th>Nama Pembeli</th>
                            <th>Lokasi</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@extends('layout.public')
@section('title', 'Show Barang')

@section('content')

    @csrf
    <div class="row">
        <div class='col-md-3'>
            <x-input type="text" label="No. Resi" field="no_resi" />
            <button class="btn btn-primary w-100" id="lacak" type="button">Lacak</button>
        </div>
        <div class="col-md-9">
            <div id="left" class="d-none">

                <ul id="organisation">
                    <li>Kantor Surabaya
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
            </div>
        </div>
    </div>
    <style>
        div.orgChart div.node.levelgreen {
            background-color: #cefbce;
        }

    </style>
    <script>
        $('#lacak').click(function() {
            var resi = $('#no_resi').val();
            $('#perubahan_rute > li').remove();
            $.get("{{ url('public/get-lacak/') }}" + "/" + resi, function(data) {
                console.log(data);
                $('#perubahan_rute').append(data.html);
                $("#rute").html(data.rute);
                $(function() {
                    $("#organisation").orgChart({
                        container: $("#main")
                    });
                });
            }).fail(function(jqXHR, textStatus, errorThrown) {
                alert("Resi tidak ditemukan");
            });

            // $.ajax({
            //     statusCode: {
            //         500: function() {
            //             $('#perubahan_rute').append("tidak ditemukan");
            //         }
            //     },
            //     url: "{{ url('public/get-lacak/') }}" + "/" + resi,
            //     type: 'GET',
            //     success: function(data) {
            //         console.log(data);
            //         $('#perubahan_rute').append(data.html);
            //         $("#rute").text(data.rute);
            //         $(function() {
            //             $("#organisation").orgChart({
            //                 container: $("#main")
            //             });
            //         });
            //     },
            //     error: function(data) {
            //         $('#perubahan_rute').append("tidak ditemukan");
            //     }
            // });
        });

    </script>

@endsection

@if ($type == 'mapel')
@php
    $mapel = DB::table('mapel')->get();
    // $mapel_check = explode(",",$tingkat->mapel_id)
@endphp
<div class="form-group">
    <label>{{$label}}</label>
    <div class="form-control">
        <div class="row">
            @foreach ($mapel as $i_mapel)
            <div class="col-3">
                <div class="icheck-primary d-inline">
                <input class="ch" id="mapel{{$i_mapel->id}}" data-id="{{$i_mapel->id}}" type="checkbox" name="mapel_id[]" value="{{$i_mapel->id}}">
                <label for="mapel{{$i_mapel->id}}">
                    {{$i_mapel->nama}}
                </label>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endif
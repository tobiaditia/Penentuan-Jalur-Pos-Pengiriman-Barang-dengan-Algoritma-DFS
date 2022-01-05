
<div class="form-group row">
    <label for="{{$field}}" class="col-sm-3 col-form-label">{{$label}}</label>
    <div class="col-sm-9">
        <input type={{ $type }} class="form-control" id="{{$field}}" name="{{$field}}"
            @isset($value)
            value="{{old('$field') ? old('$field') : $value}}"
            @else  
            value="{{old('$field')}}"
            @endisset

            {{isset($readonly) ? $readonly : ""}}>
            <div class="invalid-feedback" id="err_{{$field}}"></div>
    </div>
</div>
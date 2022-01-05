<div class="form-group row">
    <label for="{{$field}}" class="col-sm-3 col-form-label">{{$label}}</label>
    <div class="col-sm-9">
        <input class="datepicker form-control" data-date-format="dd-mm-yyyy" id="{{ $field }}"
            name="{{$field}}"

            value="{{ date('d-m-Y') }}"
            
            autocomplete="off"
        required>
    </div>
</div>

<script>
    $('.datepicker').datepicker("setDate", new Date());
    $('.datepicker').css("padding",".375rem .75rem");
</script>
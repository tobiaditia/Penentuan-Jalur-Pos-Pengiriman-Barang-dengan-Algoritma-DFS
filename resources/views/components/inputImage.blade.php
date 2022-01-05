<div class="form-group row">
    <label for="{{ $field }}" class="col-sm-3 col-form-label">{{ $label }}</label>
    <div class="col-sm-9">
        {{-- <input type="file" id="{{ $field }}" name="{{ $field }}"
            class="form-control" /> --}}
        <input type="file" name="gambar">

        <div class="invalid-feedback" id="err_{{ $field }}"></div>
    </div>
</div>

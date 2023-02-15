<div class="form-group">
    <label for="name_english" class="col-md-2 control-label">الاسم*</label>

    <div class="col-md-5">
        <input id="name_english" type="text" class="form-control" name="name_english" required placeholder="Name" value="{{ $item->name_english ?? '' }}">
    </div>
    <div class="col-md-5">
        <input type="text" class="form-control" name="name_arabic"  value="{{ $item->name_arabic ?? '' }}" required placeholder="اسم">

    </div>
</div>

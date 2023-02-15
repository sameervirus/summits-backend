<div class="form-group">
    <label for="website" class="col-md-2 control-label">Position*</label>

    <div class="col-md-10">
        <select class="form-control" id="type" name="position" required>
            <option>Choose Position</option>
            @foreach($positions as $position)
            <option
                value="{{$position}}"
                {{ @$item && ($item->position == $position ||
                    old('position') == $position)  ? 'selected' : '' }}>
                {{ \Str::title(str_replace('_', ' ', $position)) }}
            </option>
            @endforeach
        </select>
    </div>
</div>
<div class="form-group">
    <label for="title_english" class="col-md-2 control-label">العنوان*</label>

    <div class="col-md-5">
        <input id="title_english" type="text" class="form-control" name="title_english" required placeholder="Title" value="{{ $item->title_english ?? '' }}">
    </div>
    <div class="col-md-5">
        <input type="text" class="form-control" name="title_arabic"  value="{{ $item->title_arabic ?? '' }}" required placeholder="العنوان">
    </div>
</div>
<div class="form-group">
    <label for="description_english" class="col-md-2 control-label">العنوان الفرعي</label>

    <div class="col-md-5">
        <input id="description_english" type="text" class="form-control" name="description_english" placeholder="Sub Title" value="{{ $item->description_english ?? '' }}">
    </div>
    <div class="col-md-5">
        <input type="text" class="form-control" name="description_arabic"  value="{{ $item->description_arabic ?? '' }}" placeholder="العنوان الفرعي">
    </div>
</div>

<div class="form-group">
    <label for="btnText_english" class="col-md-2 control-label">نص الزر</label>

    <div class="col-md-5">
        <input id="btnText_english" type="text" class="form-control" name="btnText_english" placeholder="btnText" value="{{ $item->btnText_english ?? '' }}">
    </div>
    <div class="col-md-5">
        <input type="text" class="form-control" name="btnText_arabic"  value="{{ $item->btnText_arabic ?? '' }}">
    </div>
</div>

<div class="form-group">
    <label for="slug" class="col-md-2 control-label">Url*</label>

    <div class="col-md-5">
        <input id="slug" type="text" class="form-control" name="slug" placeholder="url" value="{{ $item->slug ?? '' }}">
        <p class="help-block">like /search</p>
    </div>
    <label for="order" class="col-md-1 control-label">Order</label>
    <div class="col-md-4">
        <input id="order" type="number" class="form-control" name="order" placeholder="order for hero" value="{{ $item->order ?? '' }}">
        <p class="help-block">like /search</p>
    </div>
</div>

<div class="form-group">
    <label for="banner" class="col-md-2 control-label">Banner*</label>

    <div class="col-md-10">
        <input id="banner" type="file" class="form-control" name="banner" accept="images/*" {{ @$item ? '' : 'required'}}>
        <p class="help-block">hero = 1740x562</p>
        <p class="help-block">icons = 190x59</p>
        <p class="help-block">banner1 = 840x240</p>
        <p class="help-block">banner2 = 1130x240</p>
    </div>
</div>

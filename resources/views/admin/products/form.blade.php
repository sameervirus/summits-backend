<div class="form-group">
    <label for="website" class="col-md-2 control-label">Brand*</label>

    <div class="col-md-10">
        <select class="form-control" id="type" name="brand_id" required>
            <option>Choose Brand</option>
            @foreach($brands as $brand)
            <option
                value="{{$brand->id}}"
                {{ @$item && ($item->brand_id == $brand->id ||
                    old('brand_id') == $brand->id)  ? 'selected' : '' }}>
                {{ \Str::title(str_replace('_', ' ', $brand->name)) }}
            </option>
            @endforeach
        </select>
    </div>
</div>

<div class="form-group">
    <label for="name_english" class="col-md-2 control-label">الاسم*</label>

    <div class="col-md-5">
        <input id="name_english" type="text" class="form-control" name="name_english" required placeholder="Name" value="{{ $item->name_english ?? '' }}">
    </div>
    <div class="col-md-5">
        <input type="text" class="form-control" name="name_arabic"  value="{{ $item->name_arabic ?? '' }}" required placeholder="اسم">

    </div>
</div>

<div class="form-group">
    <label class="col-md-2 control-label">الوصف*</label>

    <div class="col-md-5">
        <textarea rows="4" class="form-control" name="description_english" placeholder="Sub Title" required>{{old('description_english', @$item->description_english)}}</textarea>
    </div>
    <div class="col-md-5">
        <textarea rows="4" class="form-control" name="description_arabic" placeholder="الوصف" required>{{old('description_arabic', @$item->description_arabic)}}</textarea>
    </div>
</div>

<div class="form-group">
    <label for="quantity" class="col-md-2 control-label">الكمية</label>
    <div class="col-md-2">
        <input id="quantity" type="text" class="form-control" name="quantity" placeholder="quantity" value="{{old('quantity', @$item->quantity)}}" required>
    </div>
    <label for="price" class="col-md-2 control-label">السعر قبل</label>
    <div class="col-md-2">
        <input id="price" type="text" class="form-control" name="price" placeholder="price" value="{{old('price', @$item->price)}}">
    </div>
    <label for="sale_price" class="col-md-2 control-label">السعر بعد</label>
    <div class="col-md-2">
        <input id="sale_price" type="text" class="form-control" name="sale_price" placeholder="Sale Price" value="{{old('sale_price', @$item->sale_price)}}">
    </div>
</div>

<div class="form-group">
    <label for="unit" class="col-md-2 control-label">الوحدة</label>
    <div class="col-md-4">
        <input id="unit" type="text" class="form-control" name="unit" placeholder="unit" value="{{old('unit', @$item->unit)}}">
    </div>
    <label for="weight" class="col-md-2 control-label">الوزن</label>
    <div class="col-md-4">
        <input id="weight" type="text" class="form-control" name="weight" placeholder="weight" value="{{old('weight', @$item->weight)}}">
    </div>
</div>

<div class="form-group">
    <label for="images" class="col-md-2 control-label">Images*</label>

    <div class="col-md-10">
        <input id="images" type="file" class="form-control" name="images[]" multiple accept="images/*" {{ @$item ? '' : 'required'}}>
        <p class="help-block">size = 1000x1000</p>
    </div>
</div>

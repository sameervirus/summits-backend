<div class="form-group">
    <label for="is_active" class="col-md-2 control-label">Active</label>

    <div class="col-md-10">
        <input id="is_active" type="checkbox" class="custom-control-input" name="is_active" {{ @$item->is_active ? 'checked':'' }}>
    </div>
</div>
<div class="form-group">
    <label for="name" class="col-md-2 control-label">الاسم*</label>

    <div class="col-md-5">
        <input id="name" type="text" class="form-control" name="name" required placeholder="Name" value="{{ $item->name ?? '' }}">
    </div>
    <div class="col-md-5">
        <input type="text" class="form-control" name="name_arabic"  value="{{ $item->name_arabic ?? '' }}" required placeholder="اسم">

    </div>
</div>

<div class="form-group">
    <label for="website" class="col-md-2 control-label">الموقع</label>

    <div class="col-md-5">
        <input id="website" type="text" class="form-control" name="website" placeholder="website" value="{{ $item->website ?? '' }}">
    </div>

    <label for="phone" class="col-md-1 control-label">phone</label>

    <div class="col-md-4">
        <input id="phone" type="text" class="form-control" name="phone" placeholder="phone" value="{{ $item->phone ?? '' }}">
    </div>
</div>

<div class="form-group">
    <label for="address" class="col-md-2 control-label">العنوان</label>

    <div class="col-md-5">
        <input id="address" type="text" class="form-control" name="address" placeholder="address" value="{{ $item->address ?? '' }}">
    </div>
    <div class="col-md-5">
        <input type="text" class="form-control" name="address_arabic"  value="{{ $item->address_arabic ?? '' }}" placeholder="العنوان">

    </div>
</div>

<div class="form-group">
    <label for="description" class="col-md-2 control-label">Description الوصف</label>

    <div class="col-md-5">
        <textarea type="text" name="description" id="description" class="form-control" row="4" placeholder="description">{!! $item->description ?? '' !!}</textarea>
    </div>
    <div class="col-md-5">
        <textarea type="text" name="description_arabic" class="form-control" row="4" placeholder="الوصف">{!! $item->description_arabic ?? '' !!}</textarea>
    </div>
</div>

<div class="form-group">
    <label for="logo" class="col-md-2 control-label">Logo*</label>

    <div class="col-md-10">
        <input id="logo" type="file" class="form-control" name="logo" accept="images/*" required>
    </div>
</div>

<div class="form-group">
    <label for="logo" class="col-md-2 control-label">Banner*</label>

    <div class="col-md-10">
        <input id="banner" type="file" class="form-control" name="banner" accept="images/*" required>
    </div>
</div>

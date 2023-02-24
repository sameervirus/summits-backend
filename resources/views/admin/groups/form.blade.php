<div class="form-group">
    <label for="name" class="col-md-2 control-label">Name*</label>

    <div class="col-md-10">
        <input id="name" type="text" class="form-control" name="name" required placeholder="Name" value="{{ $item->name ?? '' }}">
        <p>الاسم بسيط وذات معني</p>
    </div>
</div>
<div class="form-group">
    <label for="title_english" class="col-md-2 control-label">العنوان*</label>

    <div class="col-md-5">
        <input id="title_english" type="text" class="form-control" name="title_english" required placeholder="Title" value="{{old('title_english', @$item->title_english)}}">
    </div>
    <div class="col-md-5">
        <input type="text" class="form-control" name="title_arabic"  value="{{old('title_arabic', @$item->title_arabic)}}" required placeholder="العنوان">
    </div>
</div>
<div class="form-group">
    <label for="description_english" class="col-md-2 control-label">العنوان الفرعي</label>

    <div class="col-md-5">
        <input id="description_english" type="text" class="form-control" name="description_english" placeholder="Sub Title" required value="{{old('description_english', @$item->description_english)}}">
    </div>
    <div class="col-md-5">
        <input type="text" class="form-control" name="description_arabic"  value="{{old('description_arabic', @$item->description_arabic)}}" placeholder="العنوان الفرعي" required>
    </div>
</div>
<div class="form-group">
    <label for="file" class="col-md-2 control-label">Product List*</label>

    <div class="col-md-10">
        <input id="file" type="file" class="form-control" name="file" accept=".csv" {{ @$item ? '' : 'required'}}>
    </div>
</div>

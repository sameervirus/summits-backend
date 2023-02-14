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
    <label for="website" class="col-md-2 control-label">Parent</label>

    <div class="col-md-10">
    <select class="form-control" id="type" name="parent_id" required>
            <option value="null">No parent</option>
            @foreach(\App\Models\Category::all() as $category)
            <option value="{{$category->id}}" {{ @$item && $item->parent_id == $category->id ? 'selected' : '' }}>{{ $category->name }} - {{ $category->name_arabic }}</option>
            @endforeach
        </select>
    </div>
</div>

<div class="form-group">
    <label for="image" class="col-md-2 control-label">Image*</label>

    <div class="col-md-10">
        <input id="image" type="file" class="form-control" name="image" accept="images/*" {{ @$item ? '' : 'required'}}>
    </div>
</div>

<div class="form-group">
    <label for="types" class="col-md-2 control-label">Type</label>

    <div class="col-md-5">
        <input type="text" name="types" value="{{ $item->types ?? '' }}" class="form-control" list="types" autocomplete="off">
        <datalist id="types">
            @foreach(\App\Admin\Pproduct::groupBy('types')->select('types')->get() as $types)
            <option value="{{$types->types}}" {{ @$item && $item->types == $types->types ? 'selected' : '' }}>{{ \Str::title(str_replace('_', ' ', $types->types)) }}</option>
            @endforeach
        </datalist>
    </div>
    <div class="col-md-5">
        <input type="text" class="form-control" name="types_ar" required placeholder="النوع" value="{{ $item->types_ar ?? '' }}">
    </div>
</div>



<div class="form-group">
    <label for="categroy" class="col-md-2 control-label">Categroy</label>

    <div class="col-md-5">
        <input type="text" name="category" value="{{ $item->category ?? '' }}" class="form-control" list="category" autocomplete="off">
        <datalist id="category">
            @foreach(\App\Admin\Pproduct::groupBy('category')->select('category')->get() as $category)
            <option value="{{$category->category}}" {{ @$item && $item->category == $category->category ? 'selected' : '' }}>{{ \Str::title(str_replace('_', ' ', $category->category)) }}</option>
            @endforeach
        </datalist>
    </div>
    <div class="col-md-5">
        <input type="text" class="form-control" name="category_ar" required placeholder="التصنيف" value="{{ $item->category_ar ?? '' }}">
    </div>
</div>

<div class="form-group">
    <label for="sub" class="col-md-2 control-label">Sub Categroy</label>

    <div class="col-md-5">
        <input type="text" name="sub" value="{{ $item->sub ?? '' }}" class="form-control" list="sub" autocomplete="off">
        <datalist id="sub">
            @foreach(\App\Admin\Pproduct::groupBy('sub')->select('sub')->get() as $sub)
            <option value="{{$sub->sub}}" {{ @$item && $item->sub == $sub->sub ? 'selected' : '' }}>{{ \Str::title(str_replace('_', ' ', $sub->sub)) }}</option>
            @endforeach
        </datalist>
    </div>
    <div class="col-md-5">
        <input type="text" class="form-control" name="sub_ar" required placeholder="التصنيف الفرعي" value="{{ $item->sub_ar ?? '' }}">
    </div>
</div>

<div class="form-group">
    <label for="name" class="col-md-2 control-label">Name المنتج</label>

    <div class="col-md-5">
        <input id="name" type="text" class="form-control" name="model" required placeholder="Product Name" value="{{ $item->name ?? '' }}">
    </div>
    <div class="col-md-5">
        <input type="text" class="form-control" name="model_ar"  value="{{ $item->name_ar ?? '' }}" required placeholder="اسم المنتج بالعربي">

    </div>
</div>

<div class="form-group">
    <label for="features" class="col-md-2 control-label">Features المواصفات</label>

    <div class="col-md-5">
        <textarea type="text" name="features" id="features" class="form-control" row="4" placeholder="Features">{!! $item->features ?? '' !!}</textarea>
    </div>
    <div class="col-md-5">
        <textarea type="text" name="features_ar" class="form-control" row="4" placeholder="المواصفات">{!! $item->features_ar ?? '' !!}</textarea>
    </div>
</div>

<div class="form-group">
    <label for="description" class="col-md-2 control-label">Description الوصف</label>

    <div class="col-md-5">
        <textarea type="text" name="description" id="description" class="form-control" row="4" placeholder="Description">{!! $item->description ?? '' !!}</textarea>
    </div>
    <div class="col-md-5">
        <textarea type="text" name="description_ar" class="form-control" row="4" placeholder="الوصف">{!! $item->description_ar ?? '' !!}</textarea>
    </div>
</div>

<div class="form-group">
    <label for="technical_data" class="col-md-2 control-label">Technical Data البيانات التقنية</label>

    <div class="col-md-5">
        <textarea type="text" name="technical_data" id="technical_data" class="form-control" row="4" placeholder="Technical Data">{!! $item->technical_data ?? '' !!}</textarea>
    </div>
    <div class="col-md-5">
        <textarea type="text" name="technical_data_ar" class="form-control" row="4" placeholder="البيانات التقنية">{!! $item->technical_data_ar ?? '' !!}</textarea>
    </div>
</div>


<div class="form-group">
   <label class="control-label col-md-2 col-sm-2 ">Application</label>
   <div class="col-md-5 col-sm-5 ">
      <input type="text" name="application" class="tags form-control" value="{{ @$item && $item->applications->implode('name', ',') ?? '' }}" />
   </div>
   <div class="col-md-5 col-sm-5 ">
      <input type="text" name="application_ar" class="tags form-control" value="{{ @$item && $item->arapplications->implode('name', ',') ?? '' }}" />
   </div>
</div>

<div class="form-group">
   <label class="control-label col-md-2 col-sm-2 ">Fluid</label>
   <div class="col-md-5 col-sm-5 ">
      <input type="text" name="fluid" class="tags form-control" value="{{ @$item && $item->fluids->implode('name', ',') ?? '' }}" />
   </div>
   <div class="col-md-5 col-sm-5 ">
      <input type="text" name="fluid_ar" class="tags form-control" value="{{ @$item && $item->arfluids->implode('name', ',') ?? '' }}" />
   </div>
</div>

<div class="form-group">
    <label class="col-md-2 control-label">Code التحميلات</label>
    <div class="col-md-3">
        <input type="text" class="form-control" name="code" value="" placeholder="Code">
    </div>
    <div class="col-md-4">
        <input id="download" type="file" class="form-control" 
        name="download" value="{{ $item->download ?? '' }}" placeholder="التحميلات">
    </div>
    <p class="text-right">If you want only code without file choose pdf file name 1.pdf</p>
</div>


<div class="form-group{{ $errors->has('img') ? ' has-error' : '' }}">
    <label for="img" class="col-md-2 control-label">Product Images</label>
    
    <div class="col-md-10">
        <input id="img" type="file" class="form-control" name="file[]" value="" accept="image/*" multiple {{ @$item ? '' : 'required' }}>
        <p class="help-block">Use High resolution images</p>
    </div>
</div>
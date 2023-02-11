@extends('admin.admin')

@section('title', 'Products')

@section('cssFiles')
<link   rel="stylesheet" 
        type="text/css" 
        href="{{asset('vendors/jquery-ui-1.12.1/jquery-ui.min.css')}}" />

<style type="text/css">
    .pointer{cursor: move;}
    #sortable { list-style-type: none; margin: 0; padding: 0; }
    #sortable li { margin: 5px 5px 5px 0; padding: 10px; float: left; font-size: 1em; text-align: center; }
</style>
@endsection

@section('content')
<div class="right_col" role="main">
    <div class="">
        <div class="page-title">
            <div class="title_left">
                <h3>Products<small></small></h3>
            </div>
            <div class="title_right">
                <div class="col-md-5 col-sm-5 col-xs-12 form-group pull-right top_search">
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="Search for...">
                        <span class="input-group-btn">
                      <button class="btn btn-default" type="button">Go!</button>
                    </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="clearfix"></div>
        @if (session('message'))
            <div id="back-message" class="alert alert-success alert-dismissible fade in" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
                <strong>Oh yeah!</strong> {{session('message')}}
             </div>
        @endif
        @if (count($errors) > 0)
            <div class="alert alert-danger">
                <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
                </ul>
            </div>
        @endif
        <div class="clearfix"></div>
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                    <div class="x_title">
                        <h2>Products <small></small></h2>
                        <ul class="nav navbar-right panel_toolbox">
                            <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                            </li>
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-wrench"></i></a>
                            </li>
                            <li><a class="close-link"><i class="fa fa-close"></i></a>
                            </li>
                        </ul>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                      <p><a href="{{route('wproducts.create')}}" class="btn btn-primary btn-xs"><i class="fa fa-plus"></i> Add Products </a></p>
                        <div id="message-box">Drag and Drop to resort</div>
                        <div class="table-responsive">
                            <table class="table table-striped jambo_table">
                                <thead>
                                    <tr class="headings">
                                        <th>No.</th>
                                        <th class="column-title">Model</th>
                                        <th class="column-title">الموديل</th>
                                        <th class="column-title no-link last"><span class="nobr">Action</span>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody id="lisEelement">
                                   @forelse(\App\Admin\Wproduct::orderBy('sort_order')->get() as $product)
                                    <tr id="item-{{$product->id }}" class="even pointer">
                                        <td class="a-center ">
                                            {{ $loop->iteration }}
                                        </td>
                                        <td class="name">{{ \Str::title(str_replace('_', ' ', $product->category)) }}</td>
                                        <td class="name">{{ $product->name }}</td>
                                        <td class="">                                            
                                            <a href="{{route('wproducts.edit', ['wproduct' => $product->id] ) }}" data-id="" class="btn btn-info btn-xs"><i class="fa fa-pencil"></i> Edit </a>
                                            <a href="" onclick="event.preventDefault(); document.getElementById('del_{{$product->id}}').click();"
                                                class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i> Delete </a>
                                            <form 
                                                method="POST" 
                                                action="{{ route('wproducts.destroy' , ['wproduct' => $product ]) }}" 
                                                onsubmit="return confirm('هل تريد حقاً حذف هذا المنتج?');">
                                                {{ csrf_field() }} {{ method_field('DELETE') }}
                                               <button type="submit" class="hidden" id="del_{{$product->id}}">Delete</button>
                                            </form>
                                        </td>
                                        </td>
                                    </tr>                                    
                                   @empty

                                   @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('jsFiles')
    
    <!-- iCheck -->
    <script src="{{asset('vendors/iCheck/icheck.min.js')}}"></script>	    
    <script src="{{asset('vendors/tinymce/js/tinymce/tinymce.min.js')}}"></script>
    <script src="{{asset('vendors/jquery-ui-1.12.1/jquery-ui.min.js')}}"></script>
    <script type="text/javascript">
        /* when the DOM is ready */
        jQuery(document).ready(function() {
            $('#lisEelement').sortable({
                axis: 'y',
                update: function (event, ui) {
                    var data = $(this).sortable('serialize');

                    data = data + "&_token={{ csrf_token() }}";
                    data = data + "&table=products";
                    // POST to server using $.post or $.ajax
                    $.ajax({
                        data: data,
                        type: 'POST',
                        url: '{{ route("reorder") }}',
                        beforeSend: function( xhr ) {
                            $( "#message-box" ).html("Saving ...");
                        }
                    }).done(function(data) {
                        if (data == 1) {
                            $( "#message-box" ).html("Sort Saved!");
                        } else {
                            $( "#message-box" ).html("Something went wrong please try again!");
                        }
                    });
                }
            });
        });

        tinymce.init({
          selector: 'textarea',
          height: 200,
          images_upload_url: '/',
          theme: 'modern',
          plugins: 'code print preview searchreplace autolink directionality visualblocks visualchars fullscreen image link media template codesample table charmap hr pagebreak nonbreaking anchor toc insertdatetime advlist lists textcolor wordcount imagetools contextmenu colorpicker textpattern help paste',
          toolbar1: 'formatselect | bold italic strikethrough forecolor backcolor | link media image | alignleft aligncenter alignright alignjustify  | numlist bullist outdent indent  | removeformat | code',
          image_advtab: true,
          relative_urls : false,
          remove_script_host : false,
          document_base_url : "{{url('/uploads/')}}",

          content_css: [
            '//fonts.googleapis.com/css?family=Lato:300,300i,400,400i'
          ],
          images_upload_handler: function (blobInfo, success, failure) {
             var xhr, formData;
             xhr = new XMLHttpRequest();
             xhr.withCredentials = false;
             xhr.open('POST', "{{ url('/admin/upload/img') }}");
             var token = '{{ csrf_token() }}';
             xhr.setRequestHeader("X-CSRF-Token", token);
             xhr.onload = function() {
                 var json;
                 if (xhr.status != 200) {
                     failure('HTTP Error: ' + xhr.status);
                     return;
                 }
                 json = JSON.parse(xhr.responseText);

                 if (!json || typeof json.location != 'string') {
                     failure('Invalid JSON: ' + xhr.responseText);
                     return;
                 }
                 success(json.location);
             };
             formData = new FormData();
             formData.append('file', blobInfo.blob(), blobInfo.filename());
             xhr.send(formData);
         }
         });
    </script>

@endsection

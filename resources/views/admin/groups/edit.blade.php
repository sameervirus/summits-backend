@extends('admin.admin')

@section('title', $title)

@section ('cssFiles')
<link rel="stylesheet" href="{{asset('vendors/bootstrap-multiselect/dist/css/bootstrap-multiselect.css')}}" type="text/css"/>
    <style type="text/css">
      .inputfile {
        width: 0.1px;
        height: 0.1px;
        opacity: 0;
        overflow: hidden;
        position: absolute;
        z-index: -1;
      }
      .inputfile + label {
          font-size: 1.25em;
          font-weight: 700;
          color: white;
          background-color: black;
          display: inline-block;
          padding: 0 20px;
      }

      .inputfile:focus + label,
      .inputfile + label:hover {
          background-color: red;
      }
      .inputfile + label {
        cursor: pointer; /* "hand" cursor */
      }
      .thumbnail .image {min-height: 120px!important;}
    </style>
@endsection

@section('content')
<div class="right_col" role="main">
    <div class="">
        <div class="page-title">
            <div class="title_left">
                <h3>{{$title}}<small></small></h3>
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
                        <h2>{{$title}} <small></small></h2>
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
                        @if(@$item)
                        <form
                            class="form-horizontal"
                            role="form"
                            method="POST"
                            enctype="multipart/form-data"
                            action="{{route('admin.groups.update', $item ) }}">
                            {{ method_field('PUT') }}
                        @else

                        <form class="form-horizontal" role="form" method="POST" enctype="multipart/form-data" action="{{route('admin.groups.store')}}">

                        @endif

                            {{ csrf_field() }}

                            @include('admin.groups.form')

                            <a href="{{route('admin.groups.index')}}" type="button" class="btn btn-default" data-dismiss="modal">Close</a>
                            <button type="submit" class="btn btn-primary">Save</button>

                        </form>
                        </div>
                    </div>
                </div>
                 <hr/>

                 @if(isset($item))
                    <table class="table table-striped jambo_table">
                        <thead>
                            <tr class="headings">
                                <th>No.</th>
                                <th class="column-title">name</th>
                                <th class="column-title">الاسم</th>
                                <th class="column-title">Quantity</th>
                                <th class="column-title">Price</th>
                            </tr>
                        </thead>
                        <tbody id="lisEelement">
                            @foreach ($item->products as $product)
                            <tr id="item-{{$product->id }}" class="even pointer">
                                <td class="a-center ">
                                    {{ $loop->iteration }}
                                </td>
                                <td class="name">{{ $product->name_english }}</td>
                                <td class="name">{{ $product->name_arabic }}</td>
                                <td class="name">{{ $product->quantity }}</td>
                                <td class="name">{{ $product->price }}- {{ $product->sale_price }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                 @endif

            </div>
        </div>
    </div>
</div>

@endsection

@section('jsFiles')

    <!-- iCheck -->
    <script src="{{asset('vendors/iCheck/icheck.min.js')}}"></script>

@endsection

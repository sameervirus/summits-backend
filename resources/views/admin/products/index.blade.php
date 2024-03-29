@extends('admin.admin')

@section('title', $title)

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
                <h3>{{ $titles }}<small></small></h3>
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
                        <h2>Brand <small></small></h2>
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
                        <p>
                            <a href="{{ route('admin.products.create') }}" class="btn btn-primary btn-xs"><i class="fa fa-plus"></i> Add Item </a>
                        </p>
                        <form class="form-horizontal form-label-left">
                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12 text-right">Select Brand</label>
                                <div class="col-md-9 col-sm-9 col-xs-12">
                                  <select class="form-control" onchange="this.options[this.selectedIndex].value && (window.location = this.options[this.selectedIndex].value);">
                                    <option>Choose Brand</option>
                                    @foreach($brands as $brand)
                                    <option value="{{route('admin.products.show', $brand->id)}}"
                                        {{ @$items && @$items->first()->brand_id == $brand->id ? 'selected' : '' }}>
                                        {{ \Str::title(str_replace('_', ' ', $brand->name)) }}
                                    </option>
                                    @endforeach
                                  </select>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="clearfix"></div>

        @if(@$items)
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                    <div class="x_title">
                        <h2>{{ $titles }} <small></small></h2>
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
                        <p>
                            <a href="{{route('admin.products.create') }}" class="btn btn-primary btn-xs"><i class="fa fa-plus"></i> Add {{ $title }}</a>
                        </p>
                        <div class="table-responsive">
                            <table class="table table-striped jambo_table">
                                <thead>
                                    <tr class="headings">
                                        <th>ID</th>
                                        <th class="column-title">name</th>
                                        <th class="column-title">الاسم</th>
                                        <th class="column-title">Quantity</th>
                                        <th class="column-title">Price</th>
                                        <th class="column-title no-link last"><span class="nobr">Action</span>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody id="lisEelement">
                                   @foreach ($items as $item)
                                    <tr id="item-{{$item->id }}" class="even pointer">
                                        <td class="a-center ">
                                            {{ $item->id }}
                                        </td>
                                        <td class="name">{{ $item->name_english }}</td>
                                        <td class="name">{{ $item->name_arabic }}</td>
                                        <td class="name">{{ $item->quantity }}</td>
                                        <td class="name">{{ $item->price }}- {{ $item->sale_price }}</td>
                                        <td class="">
                                            <a href="{{route('admin.products.edit', ['product' => $item->id] ) }}" data-id="" class="btn btn-info btn-xs"><i class="fa fa-pencil"></i> Edit </a>
                                            <a href="" onclick="event.preventDefault(); document.getElementById('del_{{$item->id}}').click();"
                                                class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i> Delete </a>
                                            <form
                                                method="POST"
                                                action="{{ route('admin.products.destroy' , ['product' => $item ]) }}"
                                                onsubmit="return confirm('هل تريد حقاً حذف هذا العنصر?');">
                                                {{ csrf_field() }} {{ method_field('DELETE') }}
                                               <button type="submit" class="hidden" id="del_{{$item->id}}">Delete</button>
                                            </form>
                                        </td>
                                        </td>
                                    </tr>
                                   @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection

@section('jsFiles')


@endsection

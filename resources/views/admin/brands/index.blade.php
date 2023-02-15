@extends('admin.admin')

@section('title', 'Brands')

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
                <h3>Brands<small></small></h3>
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

        @if(@$brands)
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                    <div class="x_title">
                        <h2>Brands <small></small></h2>
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
                            <a href="{{route('admin.brands.create') }}" class="btn btn-primary btn-xs"><i class="fa fa-plus"></i> Add Brand</a>
                        </p>
                        <div id="message-box">Drag and Drop to resort</div>
                        <div class="table-responsive">
                            <table class="table table-striped jambo_table">
                                <thead>
                                    <tr class="headings">
                                        <th>No.</th>
                                        <th class="column-title">Name</th>
                                        <th class="column-title">الاسم</th>
                                        <th class="column-title no-link last"><span class="nobr">Action</span>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody id="lisEelement">
                                   @foreach ($brands as $brand)
                                    <tr id="item-{{$brand->id }}" class="even pointer">
                                        <td class="a-center ">
                                            {{ $loop->iteration }}
                                        </td>
                                        <td class="name">{{ $brand->name_english }}</td>
                                        <td class="name">{{ $brand->name_arabic }}</td>
                                        <td class="">
                                            <a href="{{route('admin.brands.edit', ['brand' => $brand->id] ) }}" data-id="" class="btn btn-info btn-xs"><i class="fa fa-pencil"></i> Edit </a>
                                            <a href="" onclick="event.preventDefault(); document.getElementById('del_{{$brand->id}}').click();"
                                                class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i> Delete </a>
                                            <form
                                                method="POST"
                                                action="{{ route('admin.brands.destroy' , ['brand' => $brand ]) }}"
                                                onsubmit="return confirm('هل تريد حقاً حذف هذا المنتج?');">
                                                {{ csrf_field() }} {{ method_field('DELETE') }}
                                               <button type="submit" class="hidden" id="del_{{$brand->id}}">Delete</button>
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

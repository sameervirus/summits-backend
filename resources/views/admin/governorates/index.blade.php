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
                            <a href="{{route('admin.applications.create') }}" class="btn btn-primary btn-xs"><i class="fa fa-plus"></i> Add {{ $title }}</a>
                        </p>
                        <div class="table-responsive">
                            <table class="table table-striped jambo_table">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Governorate Name (Arabic)</th>
                                        <th>Governorate Name (English)</th>
                                        <th>Shipping Fees</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($items as $governorate)
                                        <tr>
                                            <td>{{ $governorate->id }}</td>
                                            <td>{{ $governorate->governorate_name_ar }}</td>
                                            <td>{{ $governorate->governorate_name_en }}</td>
                                            <td>
                                                <form method="POST" action="{{ route('governorates.update', $governorate->id) }}">
                                                    @csrf
                                                    @method('PUT')

                                                    <input type="text" name="shipping_fees" value="{{ $governorate->shipping_fees }}">

                                                    <button type="submit">Update</button>
                                                </form>
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

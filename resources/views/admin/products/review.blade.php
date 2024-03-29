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
                        <div class="table-responsive">
                            <table class="table table-striped jambo_table">
                                <thead>
                                    <tr class="headings">
                                        <th class="column-title">Product</th>
                                        <th class="column-title">Rating</th>
                                        <th class="column-title">Name</th>
                                        <th class="column-title">Email</th>
                                        <th class="column-title">Title</th>
                                        <th class="column-title">Review</th>
                                        <th class="column-title no-link last"><span class="nobr">Action</span>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody id="lisEelement">
                                   @foreach ($items as $item)
                                    <tr id="item-{{$item->id }}" class="even pointer">
                                        <td class="a-center ">{{ $item->product_id }}</td>
                                        <td class="a-center ">{{ $item->rating }}</td>
                                        <td class="name">{{ $item->name }}</td>
                                        <td class="email">{{ $item->email }}</td>
                                        <td class="name">{{ $item->title }}</td>
                                        <td class="name">{{ $item->description }}</td>
                                        @if($item->status)
                                        <td class="name">{{ $item->status == 1 ? 'approved' : 'declined' }}
                                            @if($item->status != 1)
                                            <a href="" onclick="event.preventDefault(); document.getElementById('acc_{{$item->id}}').click();"
                                                class="btn btn-success btn-xs"> Approve </a>
                                            @else
                                            <a href="" onclick="event.preventDefault(); document.getElementById('dec_{{$item->id}}').click();"
                                                class="btn btn-danger btn-xs"> Decline </a>
                                            @endif
                                        </td>
                                        @else
                                        <td class="">
                                            <a href="" onclick="event.preventDefault(); document.getElementById('acc_{{$item->id}}').click();"
                                                class="btn btn-success btn-xs"> Approve </a>
                                            <a href="" onclick="event.preventDefault(); document.getElementById('dec_{{$item->id}}').click();"
                                                class="btn btn-danger btn-xs"> Decline </a>
                                            
                                        </td>
                                        @endif
                                            <form
                                                method="POST"
                                                action="{{ route('admin.review.update') }}">
                                                {{ csrf_field() }}
                                                <input type="hidden" name="id" value="{{$item->id}}">
                                                <input type="hidden" name="status" value="1">
                                               <button type="submit" class="hidden" id="acc_{{$item->id}}">Approve</button>
                                            </form>
                                            <form
                                                method="POST"
                                                action="{{ route('admin.review.update') }}">
                                                {{ csrf_field() }}
                                                <input type="hidden" name="id" value="{{$item->id}}">
                                                <input type="hidden" name="status" value="2">
                                               <button type="submit" class="hidden" id="dec_{{$item->id}}">Decline</button>
                                            </form>
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

@extends('admin.admin')

@section('title', 'Feedback')

@section('cssFiles')
<style type="text/css">
</style>
@endsection

@section('content')
<div class="right_col" role="main">
    <div class="">
        <div class="page-title">
            <div class="title_left">
                <h3>Website Feedback<small></small></h3>
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
                        <h2>Website Feedback <small></small></h2>
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
                                        <th>No.</th>
                                        <th class="column-title">Company</th>
                                        <th class="column-title">Name</th>
                                        <th class="column-title">Email</th>
                                        <th class="column-title">phone</th>
                                        <th class="column-title">Product</th>
                                        <th class="column-title">Message</th>
                                        <th class="column-title no-link last">Date</th>
                                    </tr>
                                </thead>
                                <tbody id="lisEelement">
                                   @foreach($items as $item)
                                    <tr class="even pointer">
                                        <td class="a-center ">
                                            {{ $item->id }}
                                        </td>
                                        <td class="name">{{ $item->company }}</td>
                                        <td class="name">{{ $item->name }}</td>
                                        <td class="name">{{ $item->email }}</td>
                                        <td class="name">{{ $item->phone }}</td>
                                        <td class="name">
                                            {{ $item->pproduct_id != 0 ? \s\Admin\Pproduct::find($item->pproduct_id)->first()->name : '' }}
                                        </td>
                                        <td class="name">{!! $item->comments !!}</td>
                                        <td class="">
                                            {{ $item->created_at }}
                                        </td>
                                        </td>
                                    </tr>
                                   @endforeach
                                </tbody>
                            </table>
                            {{ $items->links() }}
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
                    data = data + "&table=posts";
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

    </script>
@endsection

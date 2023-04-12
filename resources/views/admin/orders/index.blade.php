@extends('admin.admin')

@section('title', $title)

@section('cssFiles')
<link   rel="stylesheet"
        type="text/css"
        href="{{asset('vendors/jquery-ui-1.12.1/jquery-ui.min.css')}}" />

<style type="text/css">
    .pointer{cursor: pointer;}
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

        @if(@$orders)
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                    <div class="x_title">
                        <h2>{{ $titles }} <small></small></h2>
                        <div class="panel_toolbox">
                            <button onclick="htmlTableToExcel('xlsx')">Export to Excel</button>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                    <label><input type="checkbox" name="columns[]" value="id" checked data-column="column-id"> ID</label>
                    <label><input type="checkbox" name="columns[]" value="payment_gateway" checked data-column="column-payment_gateway"> Payment Gateway</label>
                    <label><input type="checkbox" name="columns[]" value="notes" checked data-column="column-notes"> Notes</label>
                    <label><input type="checkbox" name="columns[]" value="total" checked data-column="column-total"> Total</label>
                    <label><input type="checkbox" name="columns[]" value="shipping_fee" checked data-column="column-shipping_fee"> Shipping Fee</label>
                    <label><input type="checkbox" name="columns[]" value="name" checked data-column="column-name"> Name</label>
                    <label><input type="checkbox" name="columns[]" value="email" checked data-column="column-email"> Email</label>
                    <label><input type="checkbox" name="columns[]" value="phone" checked data-column="column-phone"> Phone</label>
                    <label><input type="checkbox" name="columns[]" value="address" checked data-column="column-address"> Address</label>
                    <label><input type="checkbox" name="columns[]" value="tracking_number" checked data-column="column-tracking_number"> Tracking Number</label>
                    <label><input type="checkbox" name="columns[]" value="paymob_order" checked data-column="column-paymob_order"> Paymob Order</label> 
                    <label><input type="checkbox" name="columns[]" value="discount" checked data-column="column-discount"> Discount</label> 
                    <label><input type="checkbox" name="columns[]" value="coupon" checked data-column="column-coupon"> Coupon</label> 
                    <label><input type="checkbox" name="columns[]" value="delivery_time" checked data-column="column-delivery_time"> Delivery Time</label> 
                    <label><input type="checkbox" name="columns[]" value="refunded" checked data-column="column-refunded"> Refunded</label> 
                    <label><input type="checkbox" name="columns[]" value="created_at" checked data-column="column-created_at"> Created At</label> 
                    <label><input type="checkbox" name="columns[]" value="status_id" checked data-column="column-status_id"> Status</label> 
                        <div class="table-responsive">
                            <table id="orders-table" class="table jambo_table">
                                <thead>
                                    <tr>
                                        <th class="column-id">ID</th>
                                        <th class="column-payment_gateway">Payment Gateway</th>
                                        <th class="column-notes">Notes</th>
                                        <th class="column-total">Total</th>
                                        <th class="column-shipping_fee">Shipping Fee</th>
                                        <th class="column-name">Name</th>
                                        <th class="column-email">Email</th>
                                        <th class="column-phone">Phone</th>
                                        <th class="column-address">Address</th>
                                        <th class="column-tracking_number">Tracking Number</th>
                                        <th class="column-paymob_order">Paymob Order</th>
                                        <th class="column-discount">Discount</th>
                                        <th class="column-coupon">Coupon</th>
                                        <th class="column-delivery_time">Delivery Time</th>
                                        <th class="column-refunded">Refunded</th>
                                        <th class="column-created_at">Created At</th>
                                        <th class="column-status_id">Status</th>
                                        <th class=""></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($orders as $order)
                                        <tr class="{{ $order->status_id == 4 ? 'bg-success' : ''}}{{ $order->status_id == 0 ? 'bg-danger' : ''}}">
                                            <td class="column-id">{{ $order->id }}</td>
                                            <td class="column-payment_gateway">{{ $order->payment_gateway }}</td>
                                            <td class="column-notes">{{ $order->notes }}</td>
                                            <td class="column-total">{{ $order->total }}</td>
                                            <td class="column-shipping_fee">{{ $order->shipping_fee }}</td>
                                            <td class="column-name">{{ $order->fname }} {{ $order->lname }}</td>
                                            <td class="column-email">{{ $order->email }}</td>
                                            <td class="column-phone">{{ $order->phone }}</td>
                                            <td class="column-address">{{ $order->address }}</td>                                            
                                            <td class="column-tracking_number">
                                                <form action="{{ route('admin.orders.update', $order) }}" method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <input type="text" name="tracking_number" value="{{ $order->tracking_number }}" onchange="this.form.submit()">
                                                </form>
                                            </td>
                                            <td class="column-paymob_order">{{ $order->paymob_order }}</td>
                                            <td class="column-discount">{{ $order->discount }}</td>
                                            <td class="column-coupon">{{ $order->coupon }}</td>
                                            <td class="column-delivery_time">{{ optional($order->delivery_time)->format('Y-m-d') }}</td>
                                            <td class="column-refunded">{{ $order->refunded ? 'Yes' : '' }}</td>
                                            <td class="column-created_at">{{ $order->created_at->format('Y-m-d') }}</td>
                                            <td class="column-status_id">
                                                <form method="POST" action="{{ route('admin.orders.update', $order) }}">
                                                    @csrf
                                                    @method('PUT')
                                                    <select name="status_id" onchange="this.form.submit()">
                                                        <option value="0" {{ $order->status_id == 0 ? 'selected' : '' }}>Cancel</option>
                                                        <option value="1" {{ $order->status_id == 1 ? 'selected' : '' }}>Pending</option>
                                                        <option value="2" {{ $order->status_id == 2 ? 'selected' : '' }}>Processing</option>
                                                        <option value="3" {{ $order->status_id == 3 ? 'selected' : '' }}>Shipped</option>
                                                        <option value="4" {{ $order->status_id == 4 ? 'selected' : '' }}>Delivered</option>
                                                    </select>
                                                </form>
                                            </td>
                                            <td><span class="fa fa-angle-down pointer" onclick="openId('order_{{$order->id}}')"></span></td>
                                        </tr>
                                        @if($order->products->count() > 0)
                                        <tr id="order_{{$order->id}}" class="addHidden hidden">
                                            <td colspan="17">
                                                <table class="table table-bordered">
                                                    <tr>
                                                        <th>ID</th>
                                                        <th>Product</th>
                                                        <th>Quantity</th>
                                                        <th>Price</th>
                                                    </tr>
                                                    @foreach ($order->products as $product)
                                                    <tr>
                                                        <td>{{$product->pivot->product_id}}</td>
                                                        <td>{{$product->pivot->name}}</td>
                                                        <td>{{$product->pivot->quantity}}</td>
                                                        <td>{{$product->pivot->price}}</td>
                                                    </tr>
                                                    @endforeach
                                                </table>
                                            </td>
                                        </tr>
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>
                            {{ $orders->links() }}
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

<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.3/xlsx.full.min.js"></script>
<script>
    $(document).ready(function() {
        // Hide all columns by default
        // $('th[class^="column-"], td[class^="column-"]').hide();

        // Read saved selection from local storage
        var selectedColumns = JSON.parse(localStorage.getItem('selectedColumns')) || [];

        if(selectedColumns.length > 0) {
            // Update checkboxes based on saved selection
            $('input[name="columns[]"]').each(function() {
                var column = $(this).data('column');
                var checked = selectedColumns.includes(column);
                $(this).prop('checked', checked);
                $('th.' + column + ', td.' + column).toggle(checked);
            });
        }

        // Save selection to local storage when checkboxes are changed
        $('input[name="columns[]"]').change(function() {
            var selectedColumns = $('input[name="columns[]"]:checked').map(function() {
                return $(this).data('column');
            }).get();
            localStorage.setItem('selectedColumns', JSON.stringify(selectedColumns));
        });

        // Show the selected columns
        $('input[name="columns[]"]').change(function() {
            var checked = $(this).is(':checked');
            var column = $(this).val();
            $('th.column-' + column + ', td.column-' + column).toggle(checked);
        });

        
        
    });
    // Handle button click event
    function htmlTableToExcel(type){
        var data = document.getElementById('orders-table');
        var excelFile = XLSX.utils.table_to_book(data, {sheet: "sheet1"});
        XLSX.write(excelFile, { bookType: type, bookSST: true, type: 'base64' });
        XLSX.writeFile(excelFile, 'ExportedFile:HTMLTableToExcel.' + type);
    }

    function openId(id) {
        if(!id) return false;
        $('.addHidden').each(function() {
            $(this).hasClass('hidden') ? '': $(this).addClass('hidden');
        })
        $('#'+ id).toggleClass('hidden');
    }
</script>
@endsection

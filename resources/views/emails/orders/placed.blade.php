<x-mail::message>
<div style="font-size: 1.2em; margin-bottom: 1em;">
    <strong>{{__('Order Confirmation')}}</strong>
</div>

<div style="font-size: 1.1em; margin-bottom: 1em;">
    {{__('Hello')}} {{ $customerName }},
</div>

<div style="font-size: 1.1em; margin-bottom: 1em;">
{{__('Thank you for placing an order with ')}}{{ config('app.name') }}! {{__('We have received your order and it is currently being processed. Your order details are as follows:')}}
</div>

<table style="font-size: 1.1em; border-collapse: collapse; width: 100%; margin-bottom: 1em;">
    <tr>
        <th style="text-align: left; padding: 0.5em; border-bottom: 1px solid #ddd;">{{__('Order Number')}}:</th>
        <td style="padding: 0.5em; border-bottom: 1px solid #ddd;">{{ $orderNumber }}</td>
    </tr>
    <tr>
        <th style="text-align: left; padding: 0.5em; border-bottom: 1px solid #ddd;">{{__('Order Date')}}:</th>
        <td style="padding: 0.5em; border-bottom: 1px solid #ddd;">{{ $orderDate }}</td>
    </tr>
    <tr>
        <th style="text-align: left; padding: 0.5em; border-bottom: 1px solid #ddd;">{{__('Order Total')}}:</th>
        <td style="padding: 0.5em; border-bottom: 1px solid #ddd;">{{ $orderTotal }}</td>
    </tr>
</table>
<br/>
<br/>
<div style="font-size: 1.1em; margin-bottom: 1em;">
    <strong>{{__('Order Details')}}:</strong>
</div>

<table style="font-size: 1.1em; border-collapse: collapse; width: 100%; margin-bottom: 1em;">
    <thead>
        <tr>
            <th style="text-align: left; padding: 0.5em; border-bottom: 1px solid #ddd;">Item</th>
            <th style="text-align: left; padding: 0.5em; border-bottom: 1px solid #ddd;">Quantity</th>
            <th style="text-align: left; padding: 0.5em; border-bottom: 1px solid #ddd;">Price</th>
            <th style="text-align: left; padding: 0.5em; border-bottom: 1px solid #ddd;">Total</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($orderItems as $item)
        <tr>
            <td style="padding: 0.5em; border-bottom: 1px solid #ddd;">{{ $item['name'] }}</td>
            <td style="padding: 0.5em; border-bottom: 1px solid #ddd;">{{ $item['quantity'] }}</td>
            <td style="padding: 0.5em; border-bottom: 1px solid #ddd;">{{ $item['price'] }}</td>
            <td style="padding: 0.5em; border-bottom: 1px solid #ddd;">{{ $item['price'] * $item['quantity'] }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
<br/>
<br/>
{{__('Thanks')}},<br>
{{ config('app.name') }}
</x-mail::message>

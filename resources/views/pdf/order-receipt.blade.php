{!! $data['styles'] !!}
<table width="100%" class="border">
    <tr>
        <td valign="top" id="company" class="border p-3">
            <p class="h3 uppercase"><strong>{{ strtoupper($data['company']) }}</strong></p>
            <p>Brgy. San Nicolas, Oton, Iloilo, Philippines</p>
            <p>09053481057</p>
        </td>
        <td valign="middle" align="center" width="35%" id="order_number" class="border p-3">
            <p class="mb-2">{{ __('Order No.') }}</p>
            <p class="h6"><strong>#{{ $data['order_number'] }}</strong></p>
        </td>
    </tr>
    <tr>
        <td colspan="2" id="customer" class="border-r border-l p-3">
            <p class="h6 mb-2"><strong>{{ __('CUSTOMER') }}:</strong></p>
            <p><strong>{{ __('Name') }}:</strong> {{ $data['customer']['name'] }}</p>
            <p><strong>{{ __('Address') }}:</strong> {{ $data['customer']['address'] }}</p>
            <p><strong>{{ __('Email') }}:</strong> {{ $data['customer']['email'] }}</p>
        </td>
    </tr>
    <tr>
        <td colspan="2" id="items">            
            <table width="100%">
                <tr>
                    <td class="border p-1"><strong>{{ __('Item') }}</strong></td>
                    <td class="border p-1"><strong>{{ __('Qty') }}</strong></td>
                    <td class="border p-1"><strong>{{ __('Price') }}</strong></td>
                    <td class="border p-1"><strong>{{ __('Unit Price') }}</td>
                </tr>
                @foreach ($data['items'] as $item)
                <tr>
                    <td class="border p-1">{{ $item['item_name'] }}</td>
                    <td class="border p-1">{{ $item['qty'].'pc(s)' }}</td>
                    <td class="border p-1">{{ Format::price($item['price']) }}</td>
                    <td class="border p-1">{{ Format::price($item['item_cost']) }}</td>
                </tr>
                @endforeach
                <tr>
                    <td colspan="3" align="right" class="border p-1"><strong>{{ __('TOTAL') }}</strong></td>
                    <td class="border p-1"><strong>{{ Format::price(array_sum(array_column($data['items'], 'item_cost'))) }}</strong></td>
                </tr>
            </table>                               
        </td>
    </tr>
</table>
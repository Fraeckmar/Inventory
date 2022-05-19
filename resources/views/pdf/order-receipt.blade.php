{!! $data['styles'] !!}
<div class="p-2">
    <table width="100%" class="border">
        <tr>
            <td valign="top" id="company" class="border-r border-b p-2">
                {{-- <table width="100%">
                    <tr>
                        <td valign="middle" width="20%">
                            <img src="{{ $data['logo'] }}" width="100%" height="auto"/>
                        </td>
                        <td valign="middle">
                            <p class="h5 uppercase"><strong class="pl-2">{{ strtoupper($data['company']) }}</strong></p>
                        </td>
                    </tr>
                </table> --}}
                <p class="h5 uppercase"><strong class="pl-2">{{ strtoupper($data['company']) }}</strong></p>
                <p>Brgy. San Nicolas, Oton, Iloilo, Philippines</p>
                <p>{{ $data['contact_no'] }}</p>
            </td>
            <td valign="middle" align="center" width="35%" id="order_number" class="border-b p-2">
                <p class="mb-1">{{ __('Order No.') }}</p>
                <p class="h6"><strong>#{{ $data['order_number'] }}</strong></p>
            </td>
        </tr>
        <tr>
            <td colspan="2"><p class="h6 p-1 bg-black text-white center"><strong>{{ __('CUSTOMER') }}</strong></p></td>
        </tr>
        <tr>
            <td colspan="2" id="customer" class="border-b p-2">
                <p><strong>{{ __('Name') }}:</strong> {{ $data['customer']['name'] }}</p>
                <p><strong>{{ __('Address') }}:</strong> {{ $data['customer']['address'] }}</p>
                <p><strong>{{ __('Email') }}:</strong> {{ $data['customer']['email'] }}</p>
            </td>
        </tr>
        <tr>
            <td colspan="2"><p class="h6 p-1 bg-black text-white center"><strong>{{ __('ITEMS') }}:</strong></p></td>
        </tr>
        <tr>
            <td colspan="2" valign="top" id="items" style="height: 485px;">        
                <table width="100%">
                    <tr>
                        <td class="border p-1"><p><strong>{{ __('Item') }}</p></strong></td>
                        <td class="border p-1"><p><strong>{{ __('Qty') }}</strong></p></td>
                        <td class="border p-1"><p><strong>{{ __('Price') }}</strong></p></td>
                        <td class="border p-1"><p><strong>{{ __('Unit Price') }}</strong></p></td>
                    </tr>
                    @foreach ($data['items'] as $item)
                    <tr>
                        <td class="border p-1"><p>{{ $item['item_name'] }}</p></td>
                        <td class="border p-1"><p>{{ $item['qty'].' '.$data['piece_unit'] }}</p></td>
                        <td class="border p-1"></p>{{ Format::price($item['price']) }}</p></td>
                        <td class="border p-1"><p>{{ Format::price($item['item_cost']) }}</p></td>
                    </tr>
                    @endforeach
                    <tr>
                        <td colspan="3" align="right" class="p-1"><strong>{{ __('TOTAL') }}:</strong></td>
                        <td class="p-1"><strong>{{ Format::price(array_sum(array_column($data['items'], 'item_cost'))) }}</strong></td>
                    </tr>
                </table>                               
            </td>
        </tr>
    </table>
</div>
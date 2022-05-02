@extends('dashboard.index')
@section('content')
@php
    //dd($history);
@endphp
<section class="container mx-auto sm:p-6">
    <div class="w-full lg:w-3/4 m-auto">
        <h3 class="shadow-md text-3xl font-semibold text-center bg-gray-300 rounded p-3 mb-3 sm:mb-8">{{ $order['order_number'] }}</h3>
        <h3 class="shadow-md text-xl font-semibold text-center bg-gray-300 rounded p-2 uppercase">{{ __('Item Details') }}</h3>
        <div class="mb-3 sm:mb-8 overflow-auto rounded shadow-xl">
            <table class="w-full table-border">
                <thead class="bg-white">
                    <tr>
                        <th class="py-3 px-1.5 sm:py-4 sm:px-3 text-ms text-left"> {{ __('No.') }} </th>
                        @foreach ($order['items_data'][0] as $idx => $item )
                            <th class="py-3 px-1.5 sm:py-4 sm:px-3 text-ms text-left"> {{ $item['label'] }} </th>
                        @endforeach
                    </tr> 
                </thead>
                <tbody class="bg-white">
                    @foreach ($order['items_data'] as $idx => $items )
                        <tr>
                            <td class="py-3 px-1.5 sm:py-4 sm:px-3 text-ms">{{ $idx+1 }}</td>
                            @foreach ($items as $item)
                                <td class="py-3 px-1.5 sm:py-4 sm:px-3 text-ms">{{ $item['value'] }}</td>
                            @endforeach
                        </tr>
                    @endforeach 
                    <tr>
                        <td colspan="4" class="py-3 px-1.5 sm:py-4 sm:px-3 text-ms text-center font-semibold uppercase">{{ __('Total') }}: {{ $order['total_cost'] }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
        {{-- Order Details --}}
        <h3 class="shadow-md text-xl font-semibold text-center bg-gray-300 rounded p-2 uppercase">{{ __('Order Details') }}</h3>
        <div class="mb-3 overflow-auto rounded shadow-xl">
            <table class="w-full table-border">
                <thead class="bg-white">
                    <tr>
                        <th class="py-3 px-1.5 sm:py-4 sm:px-3 text-ms text-left w-auto sm:w-32"> {{ __('Date') }} </th>
                        @if ($order['type'] == 'outbound')
                            <th class="py-3 px-1.5 sm:py-4 sm:px-3 text-ms text-left"> {{ __('Customer') }} </th>
                        @endif
                        <th class="py-3 px-1.5 sm:py-4 sm:px-3 text-ms text-left"> {{ __('Remarks') }} </th>
                        <th class="py-3 px-1.5 sm:py-4 sm:px-3 text-ms text-left w-auto sm:min-w-[150px]"> {{ __('Created By') }} </th>
                    </tr> 
                </thead>
                <tbody class="bg-white">
                    <tr>
                        <td class="py-3 px-1.5 sm:py-4 sm:px-3 text-ms">{{ $order['created_at'] }}</td>
                        @if ($order['type'] == 'outbound')
                            <td class="py-3 px-1.5 sm:py-4 sm:px-3 text-ms">{{ $order['customer'] }}</td>
                        @endif
                        <td class="py-3 px-1.5 sm:py-4 sm:px-3 text-ms">{{ $order['remarks'] }}</td>
                        <td class="py-3 px-1.5 sm:py-4 sm:px-3 text-ms">{{ $order['updated_by'] }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</section>
@endsection
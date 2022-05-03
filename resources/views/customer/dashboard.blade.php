@extends('dashboard.index')
@section('content')
<section class="container mx-auto p-6">
    {{-- Customer Info --}}
	<div class="w-full mb-8 overflow-hidden rounded shadow-lg">
		<div class="w-full overflow-auto sm:overflow-hidden">
            <table class="w-full">
            <thead>
                <tr class="text-md font-semibold tracking-wide text-left bg-gray-300 uppercase border-b border-gray-600">
                    <th class="px-4 py-3 text-xs">{{ __('Name') }}</th>
                    <th class="px-4 py-3 text-xs">{{ __('Email') }}</th>
                    <th class="px-4 py-3 text-xs">{{ __('Address') }}</th>
                </tr>
            </thead>
            <tbody class="bg-white">
                <tr class="text-gray-700">
                    <td class="px-4 py-3 text-xs font-semibold border"> {{ $customer->name }} </td>
                    <td class="px-4 py-3 text-xs border"> {{ $customer->email }} </td>
                    <td class="px-4 py-3 text-xs border"> {{ $customer->address }} </td>
                  </tr>       
            </tbody>
            </table>
        </div>
	</div>
    {{-- Order History --}}
    <h3 class="shadow-md text-xl font-semibold text-center bg-gray-300 rounded p-2 uppercase">{{ __('Order History') }}</h3>
    <div class="mb-3 overflow-scroll rounded shadow-xl">
        <div>
            <table class="w-full table-border table-auto">
                <thead class="bg-white">
                    <tr>
                        <th class="py-3 px-1.5 sm:py-4 sm:px-3 text-xs text-left w-32"> {{ __('Order No.') }} </th>
                        <th class="py-3 px-1.5 sm:py-4 sm:px-3 text-xs text-left"> {{ __('Date') }} </th>
                        <th class="py-3 px-1.5 sm:py-4 sm:px-3 text-xs text-left"> {{ __('Item(s).') }} </th>
                        <th class="py-3 px-1.5 sm:py-4 sm:px-3 text-xs text-left "> {{ __('Qty') }} </th>
                        <th class="py-3 px-1.5 sm:py-4 sm:px-3 text-xs text-left"> {{ __('Unit Cost') }} </th>
                        
                        {{-- <th class="py-3 px-1.5 sm:py-4 sm:px-3 text-xs text-left"> {{ __('Created By') }} </th> --}}
                        {{-- <th class="py-3 px-1.5 sm:py-4 sm:px-3 text-xs text-left w-auto"> {{ __('Remarks') }} </th> --}}
                    </tr> 
                </thead>
                <tbody class="bg-white">
                    @foreach ($orders as $order_no => $order)
                    <tr>
                        <td class="py-3 px-1.5 sm:py-4 sm:px-3 text-xs font-semibold text-blue-600">{!! $order['order_number'] !!}</td>
                        <td class="py-3 px-1.5 sm:py-4 sm:px-3 text-xs">{{ $order['created_at'] }}</td>
                        <td class="py-3 px-1.5 sm:py-4 sm:px-3 text-xs">{!! $order['items'] !!}</td>
                        <td class="py-3 px-1.5 sm:py-4 sm:px-3 text-xs">{{ $order['unit_qty'] }}</td>
                        <td class="py-3 px-1.5 sm:py-4 sm:px-3 text-xs">{{ Format::price($order['unit_cost']) }}</td>
                        
                        {{-- <td class="py-3 px-1.5 sm:py-4 sm:px-3 text-xs">{{ $order['updated_by'] }}</td> --}}
                        {{-- <td class="py-3 px-1.5 sm:py-4 sm:px-3 text-xs">{{ $order['remarks'] }}</td> --}}
                    </tr>
                    @endforeach
                    <tr>
                        <td colspan="7" class="py-3 px-1.5 sm:py-4 sm:px-4 uppercase text-center text-md font-bold">{{ __('Total') }}: {{ Format::price($order['total_cost']) }}</td>
                    </tr>
                </tbody>
            </table>
        </div>        
    </div>    
</section>
@endsection
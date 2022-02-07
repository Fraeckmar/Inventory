@extends('dashboard.index')
@section('content')
<section class="container mx-auto p-6">
    {{-- Customer Info --}}
    <h3 class="text-xl font-semibold mb-4">{{ __('Customer') }}</h3>
	<div class="w-full mb-8 overflow-hidden rounded-lg shadow-lg">
		<div class="w-full overflow-auto sm:overflow-hidden">
            <table class="w-full">
            <thead>
                <tr class="text-md font-semibold tracking-wide text-left text-gray-900 bg-gray-200 uppercase border-b border-gray-600">
                    <th class="px-4 py-3">{{ __('Name') }}</th>
                    <th class="px-4 py-3">{{ __('Email') }}</th>
                    <th class="px-4 py-3">{{ __('Address') }}</th>
                </tr>
            </thead>
            <tbody class="bg-white">
                <tr class="text-gray-700">
                    <td class="px-4 py-3 text-ms font-semibold border"> {{ $customer->name }} </td>
                    <td class="px-4 py-3 text-xs border"> {{ $customer->email }} </td>
                    <td class="px-4 py-3 text-xs border"> {{ $customer->address }} </td>
                  </tr>       
            </tbody>
            </table>
        </div>
	</div>
    {{-- Order Details --}}
    <h3 class="text-xl font-semibold mb-4">{{ __('Oder Details') }}</h3>
    <div class="w-full mb-8 overflow-hidden rounded-lg shadow-lg">
		<div class="w-full overflow-auto sm:overflow-hidden">
            <table class="w-full">
                <thead>
                    <tr class="text-md font-semibold tracking-wide text-left text-gray-900 bg-gray-200 uppercase border-b border-gray-600">
                        <th class="px-4 py-3">{{ __('Item') }}</th>
                        <th class="px-4 py-3">{{ __('Date') }}</th>
                        <th class="px-4 py-3">{{ __('Qty') }}</th>
                        <th class="px-4 py-3">{{ __('Unit Price') }}</th> 
                        <th class="px-4 py-3">{{ __('Unit Total') }}</th>                        
                    </tr>
                </thead>
                <tbody class="bg-white">
                    @forelse ($orders as $order)
                        <tr class="text-gray-700">
                            <td class="px-4 py-3 text-ms font-semibold border"> {{ $order->item }} </td>
                            <td class="px-4 py-3 text-xs border"> {{ Format::toDateTime($order->date) }} </td>
                            <td class="px-4 py-3 text-xs border"> {{ $order->qty }} </td>
                            <td class="px-4 py-3 text-xs border"> {{ Format::price($order->price) }} </td>
                            <td class="px-4 py-3 text-xs border"> {{ Format::price($order->item_total) }} </td>
                        </tr>  
                    @empty
                        <tr>
                            <td><p class="text-center">{{ __('No records found!') }}</p></td>
                        </tr>
                    @endforelse
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3"></td>
                        <td class="px-4 py-3 text-xs border font-bold text-lg uppercase" align="right">{{ __('Total') }}</td>
                        <td class="px-4 py-3 text-xs border gont-bold text-lg">{{ Format::price($total_order) }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
	</div>
</section>
@endsection
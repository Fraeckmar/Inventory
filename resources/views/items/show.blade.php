@extends('dashboard.index')
@section('content')
@php
    //dd($history);
@endphp
<section class="container mx-auto p-6">
	<div class="w-full mb-8 overflow-hidden rounded-lg shadow-lg">
		<div class="w-full">
            <table class="w-full">
            <thead>
                <tr class="text-md font-semibold tracking-wide text-left text-gray-900 bg-gray-200 uppercase border-b border-gray-600">
                    <th class="px-4 py-3">Item</th>
                    <th class="px-4 py-3">Description</th>
                    <th class="px-4 py-3">Price</th>
                    <th class="px-4 py-3">Balance</th>
                    <th class="px-4 py-3">Category</th>
                </tr>
            </thead>
            <tbody class="bg-white">
                <tr class="text-gray-700">
                    <td class="px-4 py-3 text-ms font-semibold border"> {{ $item->item }} </td>
                    <td class="px-4 py-3 text-xs border"> {{ $item->description }} </td>
                    <td class="px-4 py-3 text-xs border"> {{ number_format($item->price, 2) }} </td>
                    <td class="px-4 py-3 text-sm border"> {{ $item->balance }} </td>
                    <td class="px-4 py-3 text-sm border"> {{ $item->category }} </td>
                  </tr>       
            </tbody>
            </table>
        </div>
	</div>
    {{-- INBOUND --}}
    <h3 class="uppercase text-xl font-semibold mb-4">{{ __('Inbound') }}</h3>
    <div class="w-full mb-8 overflow-hidden rounded-lg shadow-lg">
		<div class="w-full">            
            <table class="w-full">
                <thead>
                    <tr class="text-md font-semibold tracking-wide text-left text-gray-900 bg-gray-200 uppercase border-b border-gray-600">
                        <th class="px-4 py-3 w-[18%]">{{ __('Date') }}</th>
                        <th class="px-4 py-3 w-[18%]">{{ __('Type') }}</th>
                        <th class="px-4 py-3 w-[18%]">{{ __('Qty') }}</th>
                        <th class="px-4 py-3 w-[18%]">{{ __('Updated By') }}</th>
                        <th class="px-4 py-3 w-[28%]">{{ __('Remarks') }}</th>
                    </tr>
                </thead>                
                <tbody class="bg-white">
                    @forelse ($inbounds as $inbound)
                        <tr class="text-gray-700">
                            <td class="px-4 py-3 text-sm border font-semibold"> {{ $inbound->created_at }} </td>
                            <td class="px-4 py-3 text-sm border uppercase"> {{ $inbound->type }} </td>
                            <td class="px-4 py-3 text-sm border"> {{ $inbound->qty }} </td>
                            <td class="px-4 py-3 text-sm border"> {{ $inbound->name }} </td>
                            <td class="px-4 py-3 text-sm border"> {{ $inbound->remarks }} </td>
                        </tr>  
                    @empty
                        <tr>
                            <td colspan="5">
                                <p class="text-md text-center p-3">{{ __('No Inbound found!') }}</p>
                            </td>
                        </tr>
                    @endforelse     
                </tbody>                
            </table>
        </div>
	</div>
    {{-- OUTBOUND --}}
    <h3 class="uppercase text-xl font-semibold mb-4">{{ __('Outbound') }}</h3>
    <div class="w-full mb-8 overflow-hidden rounded-lg shadow-lg">
		<div class="w-full">            
            <table class="w-full">
                <thead>
                    <tr class="text-md font-semibold tracking-wide text-left text-gray-900 bg-gray-200 uppercase border-b border-gray-600">
                        <th class="px-4 py-3 w-[18%]">{{ __('Date') }}</th>
                        <th class="px-4 py-3 w-[18%]">{{ __('Type') }}</th>
                        <th class="px-4 py-3 w-[18%]">{{ __('Qty') }}</th>
                        <th class="px-4 py-3 w-[18%]">{{ __('Updated By') }}</th>
                        <th class="px-4 py-3 w-[28%]">{{ __('Remarks') }}</th>
                    </tr>
                </thead>                
                <tbody class="bg-white">
                    @forelse ($outbounds as $outbound)
                        <tr class="text-gray-700">
                            <td class="px-4 py-3 text-sm border font-semibold"> {{ date('Y-m-d', strtotime($outbound->created_at)) }} | {{ date('H:i', strtotime($outbound->created_at)) }} </td>
                            <td class="px-4 py-3 text-sm border uppercase"> {{ $outbound->type }} </td>
                            <td class="px-4 py-3 text-sm border"> {{ $outbound->qty }} </td>
                            <td class="px-4 py-3 text-sm border"> {{ $outbound->name }} </td>
                            <td class="px-4 py-3 text-sm border"> {{ $outbound->remarks }} </td>
                        </tr>  
                    @empty
                        <tr>
                            <td colspan="5">
                                <p class="text-md text-center p-3">{{ __('No Outbound found!') }}</p>
                            </td>
                        </tr>
                    @endforelse     
                </tbody>                
            </table>
        </div>
	</div>
    {{-- Totals --}}
    <div class="grid grid-cols-1 lg:grid-cols-3">
        <div class=""></div>
        <div class=""></div>
        <div class="bg-white round-lg shadow-lg p-4">
            <p class="font-bold text-xl">{{ __('TOTAL') }}</p>
            <p class="font-semibold text-lg py-2">{{ __('Balance') }}: {{ $item->balance }} {{ Format::unit('pcs') }}</p>
            <p class="font-semibold text-lg py-2">{{ __('Total Inbound') }}: {{ $total_inbounds }} {{ Format::unit('pcs') }}</p>
            <p class="font-semibold text-lg py-2">{{ __('Total Outbound') }}: {{ $total_outbounds }} {{ Format::unit('pcs') }}</p>
        </div>
    </div>
</section>
@endsection
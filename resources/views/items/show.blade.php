@extends('dashboard.index')
@section('content')
@php
    //dd($history);
@endphp
<section class="container mx-auto sm:p-6">
    <div class="w-full lg:w-3/4 m-auto">
        <h3 class="shadow-md text-3xl font-semibold text-center bg-gray-300 rounded p-3 ">{{ $item->item }}</h3>
        <div class="mb-3 sm:mb-8 overflow-auto rounded shadow-xl">
            <table class="w-full table-border">
                <thead class="bg-white">
                    <tr>
                        <th class="px-4 py-3 text-left">{{ __('Date') }}</th>
                        <th class="px-4 py-3 text-left">{{ __('Description') }}</th>
                        <th class="px-4 py-3 text-left">{{ __('Price') }}</th>
                        <th class="px-4 py-3 text-left">{{ __('Balance') }}</th>
                        <th class="px-4 py-3 text-left">{{ __('Category') }}</th>
                    </tr> 
                </thead>
                <tbody class="bg-white">
                    <tr>
                        <td class="px-4 py-3 text-xs border"> {{ Format::toDate($item->created_at) }} </td>
                        <td class="px-4 py-3 text-xs border"> {{ $item->description }} </td>
                        <td class="px-4 py-3 text-xs border"> {{ Format::price($item->price) }} </td>
                        <td class="px-4 py-3 text-sm border"> {{ $item->balance }} </td>
                        <td class="px-4 py-3 text-sm border"> {{ $item->category }} </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</section>
@endsection
@extends('dashboard.index')
@section('content')
@php

@endphp
<div class="flex-1">
    <div class="flex flex-wrap">
        <div class="w-full md:w-1/2 xl:w-1/3 p-6">
            <a href="{{ url('orders').'?date_from='.date('m/d/Y').'&date_to='.date('m/d/Y') }}">
                <div class="bg-gradient-to-b from-green-200 to-green-100 border-b-4 border-green-600 rounded-lg shadow-xl p-5">
                    <div class="flex flex-row items-center">
                        <div class="flex-shrink pr-5">
                            <div class="bg-green-600 rounded-full py-4 w-16 h-16 text-center"><i class="fa fa-coins fa-2x fa-inverse"></i></div>
                        </div>
                        <div class="flex-1">
                            <h2 class="font-bold uppercase text-gray-600">{{ __('Daily') }}</h2>
                        <p class="font-bold text-3xl"> {{ Format::price($revenue['daily']) }} <span class="text-green-500"><i class="fas fa-caret-up"></i></span></p>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <div class="w-full md:w-1/2 xl:w-1/3 p-6">
            <a href="{{ url('orders').'?date_from='.$week_dates['start_date'].'&date_to='.$week_dates['end_date'] }}">
                <div class="bg-gradient-to-b from-pink-200 to-pink-100 border-b-4 border-pink-500 rounded-lg shadow-xl p-5">
                    <div class="flex flex-row items-center">
                        <div class="flex-shrink pr-5">
                            <div class="bg-pink-600 rounded-full py-4 w-16 h-16 text-center"><i class="fa fa-coins fa-2x fa-inverse"></i></i></div>
                        </div>
                        <div class="flex-1">
                            <h2 class="font-bold uppercase text-gray-600">{{ __('Weekly') }}</h2>
                            <p class="font-bold text-3xl"> {{ Format::price($revenue['weekly']) }} <span class="text-pink-500"><i class="fas fa-caret-up"></i></span></p>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <div class="w-full md:w-1/2 xl:w-1/3 p-6">
            <a href="{{ url('orders').'?date_from='.date('m/01/Y').'&date_to='.date('m/t/Y') }}">
                <div class="bg-gradient-to-b from-yellow-200 to-yellow-100 border-b-4 border-yellow-600 rounded-lg shadow-xl p-5">
                    <div class="flex flex-row items-center">
                        <div class="flex-shrink pr-5">
                            <div class="bg-yellow-600 rounded-full py-4 w-16 h-16 text-center"><i class="fa fa-coins fa-2x fa-inverse"></i></div>
                        </div>
                        <div class="flex-1">
                            <h2 class="font-bold uppercase text-gray-600">{{ __('Monthly') }}</h2>
                            <p class="font-bold text-3xl"> {{ Format::price($revenue['monthly']) }} <span class="text-yellow-600"><i class="fas fa-caret-up"></i></span></p>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <div class="w-full md:w-1/2 xl:w-1/3 p-6">
            <a href="{{ url('orders') }}">
                <div class="bg-gradient-to-b from-blue-200 to-blue-100 border-b-4 border-blue-500 rounded-lg shadow-xl p-5">
                    <div class="flex flex-row items-center">
                        <div class="flex-shrink pr-5">
                            <div class="bg-blue-600 rounded-full py-4 w-16 h-16 text-center"><i class="fas fa-coins fa-2x fa-inverse"></i></div>
                        </div>
                        <div class="flex-1">
                            <h2 class="font-bold uppercase text-gray-600">{{ __('Total Sales') }}</h2>
                            <p class="font-bold text-3xl">{{ Format::price($revenue['total']) }} <span class="text-blue-600"><i class="fas fa-caret-up"></i></span></p>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <div class="w-full md:w-1/2 xl:w-1/3 p-6">
            <a href="{{ url('orders') }}">
                <div class="bg-gradient-to-b from-red-200 to-red-100 border-b-4 border-red-500 rounded-lg shadow-xl p-5">
                    <div class="flex flex-row items-center">
                        <div class="flex-shrink pr-5">
                            <div class="bg-red-600 rounded-full py-4 w-16 h-16 text-center"><i class="fas fa-dolly-flatbed fa-2x fa-inverse"></i></div>
                        </div>
                        <div class="flex-1">
                            <h2 class="font-bold uppercase text-gray-600">{{ __('Orders') }}</h2>
                            <p class="font-bold text-3xl"> {{ $orders }} <span class="text-red-500"><i class="fas fa-caret-up"></i></span></p>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <div class="w-full md:w-1/2 xl:w-1/3 p-6">
            <a href="{{ url('users') }}">
                <div class="bg-gradient-to-b from-indigo-200 to-indigo-100 border-b-4 border-indigo-500 rounded-lg shadow-xl p-5">
                    <div class="flex flex-row items-center">
                        <div class="flex-shrink pr-5">
                            <div class="bg-indigo-600 rounded-full py-4 w-16 h-16 text-center"><i class="fas fa-users fa-2x fa-inverse"></i></div>
                        </div>
                        <div class="flex-1">
                            <h2 class="font-bold uppercase text-gray-600">{{ __('Customers') }}</h2>
                            <p class="font-bold text-3xl">{{ $customers }} <span class="text-indigo-600"><i class="fas fa-caret-up"></i></span></p>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        {{-- Critical Stocks --}}
        <h3 class="font-bold text-2xl uppercase p-6 w-full">{{ __('Critical Item(s)') }}</h3>
        <div class="flex flex-wrap critical-items">
            @forelse ( $critical_items as $item)
                <div class="w-full md:w-60 item" data-canvas_id="critical-{{ $item['id'] }}" data-item_id="{{ $item['id'] }}" data-percentage="{{ $item['percentage'] }}" data-item_name="{{ $item['name'] }}" data-remaining="{{ $item['remaining'] }}">
                    <canvas id="critical-{{ $item['id'] }}"></canvas>
                </div>
            @empty
                <p class="text-bold">{{ __('All items are good.') }}</p>
            @endforelse
        </div>        
    </div>
</div>
@endsection
@section('footer_script')
    <script src="{{ asset('js/chart/chart.min.js') }}" defer></script>
    <script src="{{ asset('js/chart/chartjs-plugin-doughnutlabel.min.js') }}" defer></script>
@endsection
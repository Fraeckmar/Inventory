@extends('dashboard.index')

@section('content')
@if(session('error'))
	{{ GenField::notification(session('error'), 'error') }}
@endif

<section class="w-full lg:w-1/1 md:w-1/2 mx-auto p-6">
    <div class="grid grid-cols-1 gap-2">
        <div class="bg-white shadow-lg round-lg p-4">
            {{ Form::open(['action'=>['App\Http\Controllers\ItemBoundController@generate_report']]) }}
                @csrf   
                {{-- Customer --}}
                <div class="mb-6">
                    <label for="customer" class="text-sm font-medium text-gray-900 block dark:text-gray-300 my-2">{{ __('Customer') }}</label>
                    <select name="customer" class="block border border-grey-light w-full p-3 rounded mb-4">
                        <option value="">{{ __('Choose..') }}</option>
                        @if (!empty($customers))
                            @foreach ( $customers as $customer )
                                <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                            @endforeach
                        @endif
                    </select>	
                    @error('customer')
                        <p class="text-red-500 m-0">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Type --}}
                <label for="type" class="text-sm font-medium text-gray-900 block dark:text-gray-300 my-2">{{ __('Type') }}</label>
                {{ GenField::input([
                    'type' => 'select',
                    'key' => 'type',
                    'value' => '',
                    'options' => $type_options,
                    'class' => 'block border border-grey-light w-full p-3 rounded mb-4'
                ]) }}

                {{-- Date --}}
                <div class="mb-6">
                    <label class="text-sm font-medium text-gray-900 block dark:text-gray-300 my-2">{{ __('Date') }}</label>
                    <div date-rangepicker class="flex items-center z-0">
                        <div class="relative w-full">
                            <div class="flex absolute inset-y-0 left-0 items-center pl-3 pointer-events-none">
                                <i class="fa fa-calendar text-gray-500"> </i>
                            </div>
                            <input name="date_from" type="text" class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Date From" autocomplete="off">
                        </div>
                        <span class="mx-4 text-gray-500">to</span>
                        <div class="relative w-full z-10">
                            <div class="flex absolute inset-y-0 left-0 items-center pl-3 pointer-events-none">
                                <i class="fa fa-calendar text-gray-500"> </i>
                            </div>
                            <input name="date_to" type="text" class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Date To" autocomplete="off">
                        </div>
                    </div>
                    @error('date')
                        <p class="text-red-500 m-0">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-6 text-right">
                    <button 
                        type="submit" 
                        class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-md text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800 uppercase">
                        {{ __('Submit') }}
                    </button>
                </div>
            {{ Form::close() }}
        </div>
    </div>
</section>
@endsection
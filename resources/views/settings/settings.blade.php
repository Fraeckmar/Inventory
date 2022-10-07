@extends('dashboard.index')
@section('content')
@php
@endphp
<div id="settings">
    @if(session('error'))
        <p class="bg-white shadow-md rounded-sm p-4 mb-4 mx-auto w-full">
            <span class="text-red-500">{{ session('error') }}</span>
        </p>
    @endif
    @if(session('success'))
        <p class="bg-white shadow-md rounded-sm p-4 mb-4 mx-auto w-full">
            <span class="text-green-500">{{ session('success') }}</span>
        </p>
    @endif
    <form action="save-settings" method="POST" enctype="multipart/form-data">
        @csrf
        {{-- General Setting --}}
        <div class="card mb-4">
            <div class="card-header">
                <h3 class="text-left">{{ __('General Setting') }}</h3>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label for="app_lang" class="text-sm font-medium text-gray-900 block dark:text-gray-300 my-2">{{ __('Language') }}</label>
                    <select name="app_lang" class="bg-gray-50 border border-gray-300 block w-full sm:text-sm rounded-md p-2.5">
                        <option value="">Choose..</option>
                        <option value="en" @if(isset($settings['app_lang']) && $settings['app_lang'] == 'en') selected @endif>{{ __('English') }}</option>
                        <option value="cn" @if(isset($settings['app_lang']) && $settings['app_lang'] == 'cn') selected @endif>{{ __('Chinese') }}</option>
                    </select>
                </div>
            </div>
        </div>
        {{-- App Setting --}}
        <div class="card mb-4">
            <div class="card-header">
                <h3 class="text-left">{{ __('Company Setting') }}</h3>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label for="app_logo" class="text-sm font-medium text-gray-900 block dark:text-gray-300 my-2">{{ __('Logo') }}</label>
                    @if (array_key_exists('app_logo', $settings))  
                        <img class="h-28 my-2" src="{{ URL::to('images').'/'.$settings['app_logo'] }}" />
                    @endif
                    <input 
                        type="file"
                        name="app_logo" 
                        id="app_logo" 
                        class="bg-gray-50 border border-gray-300 sm:text-sm rounded-md w-full p-2.5">
                </div>
                <div class="mb-3">
                    <label for="app_name" class="text-sm font-medium text-gray-900 block dark:text-gray-300 my-2">{{ __('Company Name') }}</label>
                    <input 
                        type="text"
                        name="app_name" 
                        id="app_name"  
                        class="bg-gray-50 border border-gray-300 sm:text-sm rounded-md block w-full p-2.5"
                        value="@if(array_key_exists('app_name', $settings)) {{ $settings['app_name'] }}@endif"/>
                </div>
                <div class="mb-3">
                    <label for="app_contact" class="text-sm font-medium text-gray-900 block dark:text-gray-300 my-2">{{ __('Contact Number') }}</label>
                    <input 
                        type="text"
                        name="app_contact_no" 
                        id="app_contact_no"  
                        class="bg-gray-50 border border-gray-300 sm:text-sm rounded-md block w-full p-2.5"
                        value="@if(array_key_exists('app_contact_no', $settings)) {{ $settings['app_contact_no'] }}@endif"/>
                </div>
            </div>
        </div>
        {{-- Item --}}
        <div class="card mb-4">
            <div class="card-header">
                <h3 class="text-left">{{ __('Item Setting') }}</h3>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label for="piece_unit" class="text-sm font-medium text-gray-900 block dark:text-gray-300 my-2">{{ __('Piece Unit') }}</label>
                    <input 
                        type="text"
                        name="piece_unit" 
                        id="piece_unit"  
                        class="bg-gray-50 border border-gray-300 sm:text-sm rounded-md block w-full p-2.5"
                        value="@if(array_key_exists('piece_unit', $settings)) {{ $settings['piece_unit'] }} @else {{ 'pcs' }} @endif"/>
                </div>
                <div class="mb-3">
                    <label for="items_category" class="text-sm font-medium text-gray-900 block dark:text-gray-300 my-2">{{ __('Categories') }}</label>
                    <textarea 
                        id="items_category"
                        name="items_category"
                        class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-md block w-full p-2.5"
                        rows="4">@if (array_key_exists('items_category', $settings)) {{ $settings['items_category'] }} @endif</textarea>
                    @error('settings')
                        <p class="text-red-500 m-0">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="critical_when" class="text-sm font-medium text-gray-900 block dark:text-gray-300 my-2">{{ __('Item is critical when ') }} ({{ $settings['piece_unit'] ?? __('pcs') }}) left.</label>
                    <input 
                        type="text"
                        name="critical_when" 
                        id="piece_unit"  
                        class="bg-gray-50 border border-gray-300 sm:text-sm rounded-md block w-full p-2.5"
                        value="{{ $settings['critical_when'] ?? 100 }}"/>
                </div>
            </div>
        </div>
        <div class="card mb-4">
            <div class="card-body">
                <button 
					type="submit" 
					class="text-white bg-blue-700 hover:bg-blue-800 font-medium rounded-md text-sm px-5 py-2.5 uppercase">
					{{ __('Save Settings') }}
				</button>
            </div>
        </div>
    </form>
</div>
@endsection
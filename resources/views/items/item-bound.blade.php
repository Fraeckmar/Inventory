@extends('dashboard.index')
@section('content')
@php
	//dd($customers);
@endphp
@if(session('success'))
	<p class="text-center py-4 bg-white shadow-md rounded-lg mx-auto max-w-screen-md w-full">
		<span class="text-green-600">{{ session('success') }}</span>
	</p>
@endif
@if(session('error'))
	<p class="text-center py-4 bg-white shadow-md rounded-lg mx-auto max-w-screen-md w-full">
		<span class="text-red-600">{{ session('error') }}</span>
	</p>
@endif
<div class="bg-white shadow-md border border-gray-200 rounded-lg mx-auto mt-4 max-w-screen-md w-full">
	<h1 class="bg-blue-100 block w-full text-center text-xl font-medium uppercase p-4">{{ $type }}</h1>
	<div class="p-4">
		<form action="/item-bound" method="POST">
			@csrf
			<input type="hidden" name="type" value="{{ $type }}"/>
			{{-- Item --}}
			<div class="mb-6">
				<label for="item" class="text-sm my-2 font-medium text-gray-900 block dark:text-gray-300">{{ __('Item') }}</label>
				<select id="item" name="item" class="block border border-grey-light w-full p-3 rounded mb-4">
					<option value="">{{ __('Choose..') }}</option>
					@foreach ($items as $item)
						<option value="{{ $item->id }}">{{ $item->item }}</option>
					@endforeach                
				</select>
				@error('item')
					<p class="text-red-500 m-0">{{ $message }}</p>
				@enderror
			</div>
	
			{{-- Stock Number --}}
			<div class="mb-6">
				<label for="qty" class="text-sm my-2 font-medium text-gray-900 block dark:text-gray-300">{{ __('Quantity') }}</label>
				<input 
					type="number" 
					name="qty" 
					class="block border border-grey-light w-full p-3 rounded mb-4"
					value="{{ old('qty') }}"/>
				@error('qty')
					<p class="text-red-500 m-0">{{ $message }}</p>
				@enderror
			</div>

			{{-- Customer --}}
			@if (!empty($customers))
				<div class="mb-4">
					<label for="customer" class="text-sm font-medium text-gray-900 block mb-2 dark:text-gray-300">{{ __('Customer') }}</label>
					<select name="customer" class="block border border-grey-light w-full p-3 rounded mb-4">
						<option>{{ __('Choose..') }}</option>
						@foreach ($customers as $customer )
							<option value="{{ $customer->id }}">{{ $customer->name }}</option>
						@endforeach					
					</select>

					@error('customer')
						<p class="text-red-500 m-0">{{ $message }}</p>
					@enderror
				</div>
			@endif
	
			{{-- Remarks --}}
			<div class="mb-6">
				<label for="remarks" class="text-sm my-2 font-medium text-gray-900 block dark:text-gray-300">{{ __('Remarks') }}</label>
				<textarea 
					id="remarks" 
					name="remarks"
					rows="3" 				
					class="block border border-grey-light w-full p-3 rounded mb-4">{{ old('remarks') }}</textarea>
			</div>
			{{-- Submit --}}
			<div>
				<button 
					type="submit" 
					class="w-full text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-md text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800 uppercase">
					{{ __('Submit') }}
				</button>
			</div>
		</form>
	</div>
</div>
@endsection
@extends('dashboard.index')
@section('content')
@if(session('message'))
	<p class="text-center py-4 bg-white shadow-md rounded-lg mx-auto w-full sm:w-full md:w-2/3">
		<span class="text-green-600">{{ session('message') }}</span>
	</p>
@endif
<div class="card mx-auto mt-4 w-full sm:w-full md:w-2/3">
	<h1 class="card-header">{{ __('Add New Item') }}</h1>
	<div class="card-body">
		<form action="/items" method="POST">
			@csrf
	
			{{-- Stock Number --}}
			<div class="mb-6">
				<label for="item" class="text-sm font-medium block dark:text-gray-300 my-2">{{ __('Item') }}</label>
				<input 
					type="text" 
					name="item" 
					id="item" 
					required 
					class="bg-gray-50 border border-gray-300 sm:text-sm rounded-md block w-full p-2.5">
	
				@error('item')
					<p class="text-red-500 m-0">{{ $message }}</p>
				@enderror
			</div>
	
			{{-- Description --}}
			<div class="mb-6">
				<label for="description" class="text-sm font-medium block dark:text-gray-300 my-2">{{ __('Description') }}</label>
				<input 
					type="text" 
					name="description" 
					id="description" 
					required 
					class="bg-gray-50 border border-gray-300 sm:text-sm rounded-md block w-full p-2.5">
	
				@error('description')
					<p class="text-red-500 m-0">{{ $message }}</p>
				@enderror
			</div>
	
			{{-- Preice --}}
			<div class="mb-6">
				<label for="price" class="text-sm font-medium text-gray-900 block dark:text-gray-300 my-2">{{ __('Price') }}</label>
				<input 
					type="text" 
					name="price" 
					id="price" 
					required 
					class="bg-gray-50 border border-gray-300 sm:text-sm rounded-md block w-full p-2.5">
	
				@error('price')
					<p class="text-red-500 m-0">{{ $message }}</p>
				@enderror
			</div>
	
			{{-- Balance --}}
			<div class="mb-6">
				<label for="balance" class="text-sm font-medium text-gray-900 block dark:text-gray-300 my-2">{{ __('Balance') }}</label>
				<input 
					type="text"
					name="balance" 
					id="balance" 
					required 
					class="bg-gray-50 border border-gray-300 sm:text-sm rounded-md block w-full p-2.5">
	
				@error('balance')
					<p class="text-red-500 m-0">{{ $message }}</p>
				@enderror
			</div>
	
			{{-- Category --}}
			<div class="mb-6">
				<label for="category" class="text-sm font-medium text-gray-900 block dark:text-gray-300 my-2">{{ __('Category') }}</label>
				<select name="category" class="block border border-grey-light w-full p-3 rounded mb-4">
					<option value="">{{ __('Choose..') }}</option>
					@if (!empty($categories))
						@foreach ( $categories as $category )
							<option value="{{ $category }}">{{ ucwords($category) }}</option>
						@endforeach
					@endif
				</select>
				@error('category')
					<p class="text-red-500 m-0">{{ $message }}</p>
				@enderror
			</div>
			<div>
				<button 
					type="submit" 
					class="w-full text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-md text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800 uppercase">
					{{ __('Add Item') }}
				</button>
			</div>
		</form>
	</div>
</div>
@endsection
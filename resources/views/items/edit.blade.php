@extends('dashboard.index')

@section('content')
@if(session('message'))
	<p class="text-center py-4 bg-white shadow-md rounded-sm mx-auto w-full sm:w-full md:w-2/3">
		<span class="text-green-600">{{ session('message') }}</span>
	</p>
@endif

<div class="bg-white shadow-md border border-gray-200 rounded-sm mx-auto mt-4 w-full sm:w-full md:w-2/3">
	<h1 class="bg-blue-100 block w-full text-center text-xl font-medium uppercase p-4">{{ __('Edit Item') }}</h1>
	<div class="p-4">
		<form action="/items/{{$item->id}}" method="POST">
			@csrf
			<input name="_method" type="hidden" value="PUT">
			{{-- Stock Number --}}
			<div class="mb-6">
				<label for="item" class="text-sm font-medium text-gray-900 block dark:text-gray-300">{{ __('Stock Number') }}</label>
				<input 
					type="text" 
					name="item" 
					id="item" 
					required 
					class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-md focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white"
					value="{{ $item->item }}"/>
	
				@error('item')
					<p class="text-red-500 m-0">{{ $message }}</p>
				@enderror
			</div>
	
			{{-- Description --}}
			<div class="mb-6">
				<label for="description" class="text-sm font-medium text-gray-900 block dark:text-gray-300">{{ __('Description') }}</label>
				<input 
					type="text" 
					name="description" 
					id="description" 
					required 
					class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-md focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white"
					value="{{ $item->description }}"/>
	
				@error('description')
					<p class="text-red-500 m-0">{{ $message }}</p>
				@enderror
			</div>
	
			{{-- Preice --}}
			<div class="mb-6">
				<label for="price" class="text-sm font-medium text-gray-900 block dark:text-gray-300">{{ __('Price') }}</label>
				<input 
					type="text" 
					name="price" 
					id="price" 
					required 
					class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-md focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white"
					value="{{ $item->price }}"/>
	
				@error('price')
					<p class="text-red-500 m-0">{{ $message }}</p>
				@enderror
			</div>
	
			{{-- Balance --}}
			<div class="mb-6">
				<label for="balance" class="text-sm font-medium text-gray-900 block dark:text-gray-300">{{ __('Balance') }}</label>
				<input 
					type="text"
					name="balance" 
					id="balance" 
					required 
					class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-md focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white"
					value="{{ $item->balance }}"/>
	
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
							<option value="{{ $category }}" @if($category == $item->category) selected @endif>{{ ucwords($category) }}</option>
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
					{{ __('Update Item') }}
				</button>
			</div>
		</form>
	</div>
</div>
@endsection
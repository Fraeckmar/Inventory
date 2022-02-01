@extends('dashboard.index')
@section('content')
@if(session('message'))
	<p class="text-center py-4 bg-white shadow-md rounded-sm mx-auto w-full sm:w-full md:w-2/3">
		<span class="text-green-600">{{ session('message') }}</span>
	</p>
@endif
<div class="bg-white shadow-md border border-gray-200 rounded-sm mx-auto mt-4 w-full sm:w-full md:w-2/3">
	<h1 class="bg-blue-100 block w-full text-center text-xl font-medium uppercase p-4">{{ __('Edit Customer') }}</h1>
	<div class="p-4">
		<form action="/user/{{$customer->id}}" method="POST">
			@csrf
			<input name="_method" type="hidden" value="PUT">
			{{-- Stock Number --}}
			<div class="mb-6">
				<label for="name" class="text-sm font-medium text-gray-900 block dark:text-gray-300">{{ __('Name') }}</label>
				<input 
					type="text" 
					name="name" 
					id="name" 
					required 
					class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-md focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white"
					value="{{ $customer->name }}"/>
	
				@error('name')
					<p class="text-red-500 m-0">{{ $message }}</p>
				@enderror
			</div>
	
			{{-- Description --}}
			<div class="mb-6">
				<label for="email" class="text-sm font-medium text-gray-900 block dark:text-gray-300">{{ __('Email') }}</label>
				<input 
					type="email" 
					name="email" 
					id="email" 
					required 
					class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-md focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white"
					value="{{ $customer->email }}"/>
	
				@error('email')
					<p class="text-red-500 m-0">{{ $message }}</p>
				@enderror
			</div>
	
			{{-- Address --}}
			<div class="mb-6">
				<label for="address" class="text-sm font-medium text-gray-900 block dark:text-gray-300">{{ __('Address') }}</label>
				<input 
					type="text" 
					name="address" 
					id="address" 
					class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-md focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white"
					value="{{ $customer->address }}"/>
	
				@error('address')
					<p class="text-red-500 m-0">{{ $message }}</p>
				@enderror
			</div>
	
			{{-- Role --}}
			<div class="mb-6">
				<label for="role" class="text-sm font-medium text-gray-900 block dark:text-gray-300 my-2">{{ __('Role') }}</label>
				<select name="role" class="block border border-grey-light w-full p-3 rounded mb-4">
					<option value="">{{ __('Choose..') }}</option>
					@foreach (Field::customerRoles() as $key => $role )
						<option @if($customer->role == $key) selected @endif value="{{ $key }}">{{ $role }}</option>
					@endforeach	
				</select>
	
				@error('role')
					<p class="text-red-500 m-0">{{ $message }}</p>
				@enderror
			</div>
			<div>
				<button 
					type="submit" 
					class="w-full text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-md text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800 uppercase">
					{{ __('Update Customer') }}
				</button>
			</div>
		</form>
	</div>
</div>
@endsection
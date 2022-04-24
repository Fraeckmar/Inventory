@extends('dashboard.index')
@section('content')
<div class="bg-grey-lighter min-h-screen flex flex-col">
	<div class="container max-w-md mx-auto flex-1 flex flex-col items-center justify-center px-2 mt-20">
		<div class="card w-full">
			<div class="card-header">
				@if (Auth::check() && Auth::user()->role = 'administrator')
					<h1 class="text-2xl text-center">{{ __('Create Account') }}</h1>
				@else
					<h1 class="text-2xl text-center">{{ __('Sign Up') }}</h1>
				@endif
				
			</div>
			<div class="card-body">
				<form action="/user" method="POST">
					@csrf

					{{-- Full Name --}}
					<div class="mb-4">
						<label for="name" class="text-sm font-medium text-gray-900 block mb-2 dark:text-gray-300">{{ __('Name') }}</label>
						<input 
							type="text"
							id="name"
							class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white"
							name="name"
							placeholder="{{ __('Full Name') }}" />

						@error('name')
							<p class="text-red-500 m-0">{{ $message }}</p>
						@enderror
					</div>
	
					{{-- Email --}}	
					<div class="mb-4">
						<label for="email" class="text-sm font-medium text-gray-900 block mb-2 dark:text-gray-300">{{ __('Email') }}</label>
						<input 
							type="text"
							id="email"
							class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white"
							name="email"
							placeholder="{{ __('sample@gmail.com') }}" />

						@error('email')
							<p class="text-red-500 m-0">{{ $message }}</p>
						@enderror
					</div>

					{{-- Address --}}	
					<div class="mb-4">
						<label for="address" class="text-sm font-medium text-gray-900 block mb-2 dark:text-gray-300">{{ __('Addresss') }}</label>
						<input 
							type="text"
							id="address"
							class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white"
							name="address" />

						@error('address')
							<p class="text-red-500 m-0">{{ $message }}</p>
						@enderror
					</div>					

	
					{{-- Role --}}
					<div class="mb-4">
						<label for="role" class="text-sm font-medium text-gray-900 block mb-2 dark:text-gray-300">{{ __('Role') }}</label>
						<select name="role" class="block border border-grey-light w-full p-3 rounded mb-4">
							<option>{{ __('Choose..') }}</option>
							@foreach (Field::customerRoles() as $key => $role )
								<option value="{{ $key }}">{{ $role }}</option>
							@endforeach					
						</select>

						@error('role')
							<p class="text-red-500 m-0">{{ $message }}</p>
						@enderror
					</div>
	
					{{-- Password --}}
					<div class="mb-4">
						<label for="password" class="text-sm font-medium text-gray-900 block mb-2 dark:text-gray-300">{{ __('Password') }}</label>
						<input 
							type="password"
							id="password"
							class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white"
							name="password"
							placeholder="{{ __('*******') }}" />

						@error('password')
							<p class="text-red-500 m-0">{{ $message }}</p>
						@enderror		
					</div>
					
	
					<div>
						<button
						type="submit"
						class="w-full text-center py-3 rounded bg-blue-800 text-white hover:text-blue-800 hover:bg-white border border-blue-800 focus:outline-none my-1"
						>{{ __('Create Account') }}</button>
					</div>
				</form>
				@if (!Auth::check())
					<div class="text-sm font-medium text-gray-500 dark:text-gray-300 mt-2">
						{{ __('Already have an account') }}? 
						<a href="{{ url('login') }}" class="text-blue-700 hover:underline dark:text-blue-500">{{ __('Login') }}</a>
					</div>
				@endif
				
			</div>
		</div>
	</div>
</div>
@endsection
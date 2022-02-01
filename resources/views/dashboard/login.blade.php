@extends('dashboard.index')
@section('content')
<div class="container max-w-md mx-auto flex-1 flex flex-col items-center justify-center px-2 mt-20">
	<div class="card w-full">
		<div class="card-header">
			<h3 class="text-2xl font-medium text-gray-900 dark:text-white">{{ __('Login') }}</h3>
		</div>
		<div class="card-body">
			<form class="space-y-6" action="{{ route('login') }}" method="POST">
				@csrf
				
				
				@if ($errors->any())
					<p class="text-red-500 m-0">{{ $errors->first() }}</p>
				@endif
	
				<div>
					<label for="email" class="text-sm font-medium text-gray-900 block mb-2 dark:text-gray-300">{{ __('Email') }}</label>
					<input 
						type="email" 
						name="email" 
						id="email" 
						placeholder="name@company.com" 
						required 
						class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white">
				</div>
				<div>
					<label for="password" class="text-sm font-medium text-gray-900 block mb-2 dark:text-gray-300">{{ __('Password') }}</label>
					<input 
						type="password" 
						name="password" 
						id="password" 
						placeholder="••••••••" 
						required 
						class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white">
				</div>
				<div class="flex items-start">
					<div class="flex items-start">
						<div class="flex items-center h-5">
							<input 
							id="remember" 
							aria-describedby="remember" 
							type="checkbox" 
							class="bg-gray-50 border border-gray-300 focus:ring-3 focus:ring-blue-300 h-4 w-4 rounded dark:bg-gray-700 dark:border-gray-600 dark:focus:ring-blue-600 dark:ring-offset-gray-800">
						</div>
						<div class="text-sm ml-3">
							<label for="remember" class="font-medium text-gray-900 dark:text-gray-300">{{ __('Remember me') }}</label>
						</div>
					</div>
					<a 
						href="#" 
						class="text-sm text-blue-700 hover:underline ml-auto dark:text-blue-500">
						{{ __('Lost Password') }}?
					</a>
				</div>
	
				<button 
					type="submit" 
					class="w-full text-center py-3 rounded bg-blue-800 text-white hover:bg-white hover:text-blue-800 border border-blue-800 focus:outline-none my-1 uppercase">
					{{ __('Login') }}
				</button>			
	
				<div class="text-sm font-medium text-gray-500 dark:text-gray-300">
					{{ __('Not registered') }}? 
					<a href="/register" class="text-blue-700 hover:underline dark:text-blue-500">{{ __('Create account') }}</a>
				</div>
			</form>
		</div>
	</div>		
</div>
@endsection
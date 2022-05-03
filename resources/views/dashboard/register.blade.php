@extends('dashboard.index')

@section('content')
@if(session('message'))
	{{ GenField::notification(session('message'), 'success') }}
@endif

<div class="container max-w-lg mx-auto flex-1 flex flex-col items-center justify-center px-2 sm:mt-5">
	<div class="card w-full">
		<div class="card-header">
			@if (Auth::check() && Auth::user()->role = 'administrator')
				<h1 class="text-2xl text-center">{{ __('Create Account') }}</h1>
			@else
				<h1 class="text-2xl text-center">{{ __('Sign Up') }}</h1>
			@endif
			
		</div>
		<div class="card-body">
			{{ Form::open(['action'=>['App\Http\Controllers\UserController@store'], 'method'=>'post' ]) }}
				@foreach (Field::user() as $key => $field)
					@php
						$options = [];
						$value = '';
						if ($key == 'role') {
							$options = Field::customerRoles();
							$value = 'customer';
						}						
						$field['value'] = $value;
						$field['options'] = $options;
					@endphp
					{{ GenField::input($field) }}	
					@error($key)
						{{ GenField::notification($message, 'error', true) }}
					@enderror		
				@endforeach
				{{-- Submit --}}
				{{ GenField::input([
					'type' => 'submit',
					'key' => 'add_customer',
					'label' => __('Add Customer'),
					'class' => Field::fieldClass()['user']['button']
				]) }}
			{{ Form::close() }}
		</div>
	</div>
</div>
@endsection
@extends('dashboard.index')
@section('content')
@if(session('message'))
	{{ GenField::notification(session('message'), 'success') }}
@endif
<div class="bg-white shadow-md border border-gray-200 rounded-sm mx-auto mt-4 w-full sm:w-full md:w-2/3">
	<h1 class="bg-blue-100 block w-full text-center text-xl font-medium uppercase p-4">{{ __('Edit Customer') }}</h1>
	<div class="p-4">
		{{ Form::open(['action'=>['App\Http\Controllers\UserController@update', $customer->id], 'method'=>'put' ]) }}
			@foreach ( Field::user() as $key => $field)
				@php
					$option = ($field['type'] == 'select')? Field::customerRoles() : [];
				@endphp
				<div class="mb-5">
					{{ Form::label( $key, $field['label'], ['class' => $field['label_class']] ) }}
					{{ GenField::input( $field['type'], $key, $customer->$key, $field['class'], $option ) }}
					@error($key)
						{{ GenField::notification($message, 'error') }}
					@enderror
				</div>				
			@endforeach
			{{-- Submit --}}
			{{-- {{ Form::submit( 'Submit', ['class'=>'test']) }} --}}
			{{ GenField::input('submit', '', 'Update Customer', $field['class']) }}
		{{ Form::close() }}
	</div>
</div>
@endsection
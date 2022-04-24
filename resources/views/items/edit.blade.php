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
		{!! Form::open(['action'=>['App\Http\Controllers\ItemsController@update',$item->id], 'method'=>'put']) !!}
			@foreach ( Field::item() as $key => $field )
				@php
					$option = ($field['type'] == 'select' && !empty($categories))? $categories : [];
				@endphp
				<div class="mb-3">
					{{ Form::label($key, $field['label'], ['class'=>$field['label_class']]) }}
					{{ GenField::input($field['type'], $key, $item->$key, $field['class'], $option) }}

					@error($key)
						{{ GenField::notification($message, 'field') }}
					@enderror
				</div>
			@endforeach
			{{-- Submit --}}
			{{ GenField::input('submit', '', __('Update Item')) }}
		{!! Form::close() !!}
	</div>
</div>
@endsection
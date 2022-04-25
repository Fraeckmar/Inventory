@extends('dashboard.index')
@section('content')
@if(session('message'))
	{{ GenField::notification(session('message'), 'success') }}
@endif
<div class="card mx-auto mt-4 w-full sm:w-full md:w-2/3">
	<h1 class="card-header">{{ __('Add New Item') }}</h1>
	<div class="card-body">
		{{ Form::open(['action'=>'App\Http\Controllers\ItemsController@store', 'method'=>'post']) }}
			@foreach ( Field::item() as $key => $field )
				@php
					$options = ($field['type'] == 'select' && !empty($categories))? $categories : [];
					$field['options'] = $options;
				@endphp
				@error($key)
					{{ GenField::notification($message, 'field', true) }}
				@enderror
				{{ GenField::input($field) }}
			@endforeach

			{{-- Submit --}}
			{{ GenField::input([
				'type' => 'submit',
				'key' => 'add_item',
				'label' => __('Add Item'),
				'class' => Field::fieldClass()['button']
			]) }}
		{{ Form::close() }}
	</div>
</div>
@endsection
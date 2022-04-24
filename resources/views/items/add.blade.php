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
					$option = ($field['type'] == 'select' && !empty($categories))? $categories : [];
				@endphp
				<div class="mb-5">
					{{ Form::label($key, $field['label'], ['class'=>$field['label_class']]) }}
					{{ GenField::input($field['type'], $key, '', $field['class'], $option) }}
				</div>
			@endforeach
			{{-- Submit --}}
			{{ GenField::input('submit', '', __('Add Item')) }}
		{{ Form::close() }}
	</div>
</div>
@endsection
@extends('dashboard.index')

@section('content')
@if(session('message'))
	{{ GenField::notification(session('message'), 'success') }}
@endif
<div class="bg-white shadow-md border border-gray-200 rounded-sm mx-auto mt-4 w-full sm:w-full md:w-2/3">
	<h1 class="bg-blue-100 block w-full text-center text-xl font-medium uppercase p-4">{{ __('Edit Item') }}</h1>
	<div class="p-4">
		{!! Form::open(['action'=>['App\Http\Controllers\ItemsController@update',$item->id], 'method'=>'put']) !!}
			@foreach ( Field::item() as $key => $field )
				@php
					$options = ($field['type'] == 'select' && !empty($categories))? $categories : [];
					$field['value'] = $item->$key;
					$field['options'] = $options;
					if ($key == 'balance') {
						$field['attributes'] = 'readonly';
					}
				@endphp
				@error($key)
					{{ GenField::notification($message, 'field', true) }}
				@enderror
				{{ GenField::input($field) }}
			@endforeach
			
			{{-- Submit --}}
			{{ GenField::input([
				'type' => 'submit',
				'key' => 'update_item',
				'label' => __('Update Item'),
				'class' => Field::fieldClass()['button']
			]) }}
		{!! Form::close() !!}
	</div>
</div>
@endsection
@extends('dashboard.index')

@section('content')
@if(session('success'))
	{{ GenField::notification(session('success'), 'success') }}
@endif
<div class="bg-white shadow-md border border-gray-200 rounded-sm mx-auto mt-4 w-full sm:w-full md:w-2/3">
	<h1 class="bg-blue-100 block w-full text-center text-xl font-medium uppercase p-4">{{ __('Edit Item') }}</h1>
	<div class="p-4">
		{!! Form::open(['action'=>['App\Http\Controllers\ItemBoundController@update',$itemBound->id], 'method'=>'put']) !!}
			@foreach ( Field::boundFields('outbound') as $field )
				@php
					$key = $field['key'];
                    $options = [];
                    if ($field['type'] == 'select') {
                        if ($key == 'customer') {
                            $options = $customers;
                        }
                        if ($key == 'item') {
                            $options = $items;
                        }
                    }
					$field['value'] = $itemBound->$key;
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
				'key' => 'edit_order',
				'label' => __('Update Order'),
				'class' => Field::fieldClass()['button']
			]) }}
		{!! Form::close() !!}
	</div>
</div>
@endsection
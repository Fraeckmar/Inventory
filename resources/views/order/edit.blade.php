@extends('dashboard.index')

@section('content')
@if(session('success'))
	{{ GenField::notification(session('success'), 'success') }}
@endif
<div class="bg-white shadow-md border border-gray-200 rounded-sm mx-auto mt-4 w-full sm:w-full md:w-2/3">
	<h1 class="bg-blue-100 block w-full text-center text-xl font-medium uppercase p-4">{{ __('Edit Item') }}</h1>
	<div class="p-4">
		{!! Form::open(['action'=>['App\Http\Controllers\ItemBoundController@update',$itemBound->id], 'method'=>'put']) !!}
			@foreach ( Field::boundFields('outbound') as $key => $field )
				@php
                    $options = [];
                    if ($field['type'] == 'select') {
                        if ($key == 'customer') {
                            $options = $customers;
                        }
                        if ($key == 'item') {
                            $options = $items;
                        }
                    }
				@endphp
				<div class="mb-3">
					{{ Form::label($key, $field['label'], ['class'=>$field['label_class']]) }}
					{{ GenField::input($field['type'], $key, $itemBound->$key, $field['class'], $options) }}

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
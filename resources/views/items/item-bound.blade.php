@extends('dashboard.index')

@section('content')
@php
	//dd($items);
@endphp
@if(session('success'))
	{{ GenField::notification(session('success'), 'success') }}
@endif
@if(session('error'))
	{{ GenField::notification(session('error'), 'error') }}
@endif
<div class="card mx-auto mt-4 w-full sm:w-full md:w-2/3">
	<h1 class="card-header uppercase">{{ $type }}</h1>
	<div class="card-body">
		<div class="p-4">
			{!! Form::open(['action'=>'App\Http\Controllers\ItemBoundController@store', 'method'=>'post']) !!}
				<input type="hidden" name="type" value="{{ $type }}"/>
				@foreach ( Field::boundFields($type) as $key => $field )
					@php
						$options = [];
						if($key == 'item' && !empty($items)){
							$options = $items;
						}
						if($key == 'customer' && !empty($customers)){
							$options = $customers;
						}
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
					'key' => 'update_item',
					'label' => __('Submit'),
					'class' => Field::fieldClass()['button']
				]) }}
			{!! Form::close() !!}
		</div>
	</div>
</div>
@endsection
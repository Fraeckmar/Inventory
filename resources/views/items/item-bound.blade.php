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
						$option = [];
						if($key == 'item' && !empty($items)){
							$option = $items;
						}
						if($key == 'customer' && !empty($customers)){
							$option = $customers;
						}
					@endphp
					<div class="mb-5">
						{{ Form::label($key, $field['label'], ['class'=>$field['label_class']]) }}
						{{ GenField::input($field['type'], $key, '', $field['class'], $option) }}
	
						@error($key)
							{{ GenField::notification($message, 'error', true) }}
						@enderror
					</div>
				@endforeach
				{{-- Submit --}}
				{{ GenField::input('submit', '', __('Submit')) }}
			{!! Form::close() !!}
		</div>
	</div>
</div>
@endsection
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
		<div>
			{!! Form::open(['action'=>'App\Http\Controllers\ItemBoundController@store', 'method'=>'post']) !!}
				<input type="hidden" name="type" value="{{ $type }}"/>
				<div class="repeater">
					<div data-repeater-list="items">
						<div class="flex content-end items-end w-full" data-repeater-item>
							{{ GenField::input([
								'type' => 'select',
								'key' => 'item',
								'label' => __('Items'),
								'options' => $items,
								'class' => Field::fieldClass()['item']['select'].' mb-0',
								'label_class' => Field::fieldClass()['item']['label'],
								'required' => 'required',
								'container_class' => 'grow mb-0'
							]) }}
							{{ GenField::input([
								'type' => 'number',
								'key' => 'qty',
								'label' => __('Qty'),
								'class' => Field::fieldClass()['item']['number']. ' mb-0',
								'label_class' => Field::fieldClass()['item']['label'],
								'container_class' => 'w-32 ml-3 mb-0',
								'required' => 'required'
							]) }}
							@if ($type == 'outbound')
								{{ GenField::input([
									'type' => 'button',
									'key' => 'delete_item',
									'label' => '<i class="fa fa-minus"></i>',
									'class' => 'w-full text-center py-3 rounded bg-red-600 text-white hover:bg-red-700 border border-red-500 cursor-pointer',
									'container_class' => 'w-12 ml-3 mb-0',
									'attribues' => 'data-repeater-delete'
								]) }}
							@endif
							
						</div>
					</div>
					@if ($type == 'outbound')
						<div class="flex content-end items-end w-full" data-repeater-item>
							<div class="grow"></div>
							{{ GenField::input([
								'type' => 'button',
								'key' => 'delete_item',
								'label' => '<i class="fa fa-plus"></i>',
								'class' => 'w-full text-center py-3 rounded bg-blue-600 text-white hover:bg-blue-700 border border-blue-500 cursor-pointer',
								'container_class' => 'w-12 ml-3 mt-1 mb-0',
								'attribues' => 'data-repeater-create'
							]) }}
						</div>
					@endif
				</div>
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
						<br />
					@enderror
					@if (!in_array($key, ['item', 'qty']))
						{{ GenField::input($field) }}
					@endif					
				@endforeach

				{{-- Submit --}}
				{{ GenField::input([
					'type' => 'submit',
					'key' => 'submit_btn',
					'label' => __('Submit'),
					'class' => Field::fieldClass()['button']
				]) }}
			{!! Form::close() !!}
		</div>
	</div>
</div>
@endsection
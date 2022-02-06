@extends('dashboard.index')
@section('content')
<section class="container mx-auto sm:p-6 font-mono">
	<div class="w-full mb-8 overflow-auto md:overflow-hidden rounded-lg shadow-lg">
		@if (!empty($items))
		<div class="w-full">
	      <table class="w-full">
	        <thead>
	          <tr class="text-md font-semibold tracking-wide text-left text-gray-900 bg-gray-200 uppercase border-b border-gray-600">
	            <th class="px-4 py-3">{{ __('Item') }}</th>
	            <th class="px-4 py-3">{{ __('Description') }}</th>
	            <th class="px-4 py-3">{{ __('Price') }}</th>
	            <th class="px-4 py-3">{{ __('Balance') }}</th>
				<th class="px-4 py-3">{{ __('Print') }}</th>
	            <th class="px-4 py-3">{{ __('Action') }}</th>
	          </tr>
	        </thead>
	        <tbody class="bg-white">
	        	@foreach ($items as $item)
	    			<tr class="text-gray-700">
			            <td class="px-4 py-3 text-ms font-semibold border"> {{ $item['item'] }} </td>
			            <td class="px-4 py-3 text-xs border"> {{ $item['description'] }} </td>
			            <td class="px-4 py-3 text-xs border"> {{ number_format($item['price'], 2) }} </td>
			            <td class="px-4 py-3 text-sm border"> {{ $item['balance'] }} </td>
						<td class="px-4 py-3 text-sm border"> <span class="cursor-pointer" title="Print"><i class="fas fa-print text-xl text-gray-700 hover:text-gray-800"></i></span> </td>
			            <td class="px-4 py-3 text-sm border">
							<a href="{{ url('items/'.$item["id"]) }}" class="p-1">
								<span title="View">
									<i class="fas fa-eye text-lg transition-colors duration-150 text-green-500 hover:text-green-600"></i>
								</span>
							</a>
			            	<a href="{{ url('items/'.$item["id"].'/edit') }}" class="p-1">
								<span title="Edit">
									<i class="fas fa-edit text-lg transition-colors duration-150 text-blue-500 hover:text-blue-600"></i>
								</span>
							</a>
			            	<a href="{{ url('items/'.$item["id"]) }}" class="delete-item p-1" data-label="{{ __('Are you sure to delete this Item?') }}" data-form="#delete-item">
								<span title="Delete">
									<i class="fas fa-trash-alt text-lg transition-colors duration-150 text-red-500 hover:text-red-600"></i>
								</span>
							</a>
			            	<form id="delete-item" class="hidden" action="{{ url('items/'.$item['id']) }}" method="POST">
			            		@csrf			            		
			            		<input type="hidden" name="_method" value="DELETE">
			            	</form>
			            </td>
			          </tr>
				@endforeach	          
	        </tbody>
	      </table>
	    </div>
	    @else
	     <p class="text-lg text-center text-red-500 py-3">{{ __('No items found!') }}</p>
		@endif    
	</div>
</section>
@endsection
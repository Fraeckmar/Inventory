@extends('dashboard.index')
@section('content')
<section class="container mx-auto sm:p-6 font-mono">
	<div class="w-full mb-8 overflow-auto md:overflow-hidden rounded-lg shadow-lg">
		@if (!empty($customers))
		<div class="w-full">
	      <table class="w-full">
	        <thead>
	          <tr class="text-md font-semibold tracking-wide text-left text-gray-900 bg-gray-200 uppercase border-b border-gray-600">
	            <th class="px-4 py-3">{{ __('Name') }}</th>
	            <th class="px-4 py-3">{{ __('Email') }}</th>
	            <th class="px-4 py-3">{{ __('Address') }}</th>
	            <th class="px-4 py-3">{{ __('No. of Items') }}</th>
				<th class="px-4 py-3">{{ __('Total') }}</th>
	            <th class="px-4 py-3">{{ __('Action') }}</th>
	          </tr>
	        </thead>
	        <tbody class="bg-white">
	        	@foreach ($customers as $customer)
	    			<tr class="text-gray-700">
			            <td class="px-4 py-3 text-ms font-semibold border"> {{ $customer->name }} </td>
			            <td class="px-4 py-3 text-xs border"> {{ $customer->email }} </td>
			            <td class="px-4 py-3 text-xs border"> {{ $customer->address }} </td>
			            <td class="px-4 py-3 text-sm border"> {{ $customer->total_items }} </td>
						<td class="px-4 py-3 text-sm border"> {{ Format::price($customer->total_amount) }} </td>
			            <td class="px-4 py-3 text-sm border">
							<a href="{{ url('/user/'.$customer->id) }}" class="p-1">
								<span title="View">
									<i class="fas fa-eye text-lg transition-colors duration-150 text-green-500 hover:text-green-600"></i>
								</span>
							</a>
                            <a href="{{ url('/user/'.$customer->id.'/edit') }}" class="p-1">
								<span title="View">
									<i class="fas fa-edit text-lg transition-colors duration-150 text-blue-500 hover:text-blue-600"></i>
								</span>
							</a>
                            <a href="{{ url('user/'.$customer->id) }}" class="p-1 delete-item" data-label="{{ __('Are you sure to delete this Customer?') }}" data-form="#delete-customer">
								<span title="Delete">
									<i class="fas fa-trash-alt text-lg transition-colors duration-150 text-red-500 hover:text-red-600"></i>
								</span>
							</a>
			            	<form id="delete-customer" class="hidden" action="{{ url('user/'.$customer->id) }}" method="POST">
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
	     <p class="text-lg text-center text-red-500 py-3">{{ __('No records found!') }}</p>
		@endif    
	</div>
</section>
@endsection
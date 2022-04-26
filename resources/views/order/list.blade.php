@extends('dashboard.index')

@section('content')
<section class="container overflow-x-scroll lg:overflow-hidden w-full lg:w-auto mx-auto p-0 lg:p-1 font-mono z-20">
	@if (session('error'))
		{{ GenField::nofication(session('error'), 'error') }}
	@else
		<div class="row">
			{!! $dataTable->draw() !!}
		</div>
	@endif
</section>
@endsection
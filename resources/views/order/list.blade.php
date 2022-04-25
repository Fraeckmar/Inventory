@extends('dashboard.index')

@section('content')
<section class="container overflow-hidden md:overflow-x-scroll w-full lg:s-auto mx-auto p-0 sm:p-0 font-mono z-20">
	<div class="row">
		{!! $dataTable->draw() !!}
	</div>
</section>
@endsection
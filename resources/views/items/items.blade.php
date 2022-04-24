@extends('dashboard.index')

@section('content')
<section class="container mx-auto sm:p-6 font-mono">
	<div class="row">
		<div class="w-full mb-8 overflow-auto md:overflow-hidden rounded-lg shadow-lg">
			{!! $dataTable->draw() !!}
		</div>
	</div>
</section>
@endsection
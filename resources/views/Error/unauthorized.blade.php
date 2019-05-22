@extends('layouts.qmlayout')
@section('content')
<style>
	.addnew{float:right;color:#fff;font-size:25px;}
	.text{float:right;font-size:20px;}
</style>
<div class="col-md-12">
	<div class="card">
		@if(Session::has('alert-success'))
			<div class="alert alert-success">
				{{Session::get('alert-success')}}
			</div>
		@endif
		
		@if(Session::has('alert-failure'))
			<div class="alert alert-danger">
				{{Session::get('alert-failure')}}
			</div>
		@endif
		<div class="card-content">
			<div class="card-header" data-background-color="purple">
				<div class="row">
					<div class="col-md-8">
						<h4 class="title">Error page</h4>
						<p class="category">401: Authorized Access</p>
					</div>
				</div>
			</div>
			<div class="card-content table-responsive">
					<h3>Your are not authorized to view this section.</h3>
				<div class="clearfix"></div>
			</div>
		</div>
	</div>
</div>
@endsection
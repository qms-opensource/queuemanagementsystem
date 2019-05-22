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
						<h4 class="title">Summary Report</h4>
						<p class="category">Status</p>
					</div>
					<div class="col-md-4">
						<div class=""><a href="{{url('/token-status')}}"><span class="text">Refresh <i class="fa fa-refresh"></i></span></a></div>
					</div>
				</div>
			</div>
			<div class="card-content table-responsive">
				<table class="table">
					<thead class="text-primary">
						<th>Department</th>
						<th>Doctor</th>
						<th>Token Status</th>
						<th>Total</th>
					</thead>
					<tbody>
					 @if(!empty($tokens))
						@foreach($tokens as $token)
							<tr>
								<td>{{ $token['departmentName']}}</td>
								<td>{{ $token['doctorName']}}</td>
								<td>{{ $token['tokenStatus']}}</td>
								<td>{{ $token['total']}}</td>
							</tr>
						@endforeach
					@endif
					</tbody>
				</table>
				<div class="clearfix"></div>
			</div>
			<div class="card-header" style="margin-top:20px;" data-background-color="purple">
				<div class="row">
					<div class="col-md-4">
						<h4>Total Visited: {{$visitedCount}}</h4>
					</div>
					<div class="col-md-4">
						<h4>Skipped:1</h4>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection
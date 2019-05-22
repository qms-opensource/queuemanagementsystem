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
					<div class="col-md-12">
						<h4 class="title">Doctor Portal</h4>
					</div>
				</div>
			</div>
			<div class="card-content table-responsive">
				<table class="table">
					<thead class="text-primary">
						<th>Hi Doctor..</th>
						<th></th>
					</thead>
					<tbody>

							<tr>
								<td>Department:</td>
								<td>{{ $departinfo['get_department']['name']}}</td>
							</tr>
							<tr>
								<td>Mobile Phone:</td>
								<td>{{ $departinfo['phone']}}</td>
							</tr>
							<tr>
								<td>Floor No:</td>
								<td>{{ $departinfo['get_department']['floor']}}</td>
							</tr>
							<tr>
								<td>Room No:</td>
								<td>{{ $departinfo['get_department']['room_no']}}</td>
							</tr>
							<tr>
								<td>Total Appointment</td>
								<td>40</td>
							</tr>
					</tbody>
				</table>
				<div class="clearfix"></div>
			</div>
		</div>
	</div>
</div>
@endsection
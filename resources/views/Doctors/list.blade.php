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
		
		@if(Session::has('alert-danger'))
			<div class="alert alert-danger">
				{{Session::get('alert-danger')}}
			</div>
		@endif
		<div class="card-content">
			<div class="card-header" data-background-color="purple">
				<div class="row">
					<div class="col-md-8">
						<h4 class="title">Manage Doctors</h4>
						<p class="category">Doctor List</p>
					</div>
					<div class="col-md-4">
						<div class=""><a href="{{url('/manage-doctors/create')}}"><span class="text">Add Doctor &nbsp;<i class="fa fa-plus"></i></span></a></div>
					</div>
				</div>
			</div>
			<div class="card-content table-responsive">
			@if(!empty($doctors) && count($doctors) >= 1)
				<table class="table">
					<thead class="text-primary">
						<th>Name</th>
						<th>Mobile Number</th>
						<th>Department</th>
						<th>Room</th>
						<th>Action</th>
					</thead>
					<tbody>
						@foreach($doctors as $doctor)
							<tr>
								<td>{{ $doctor->doctors_name}}</td>
								<td>{{ $doctor->doctor_phone}}</td>
								<td>@if(!empty($doctor->department_name)) {{ $doctor->department_name}} @else No department linked yet. @endif</td>
								<td>@if($doctor->room_name != '') {{ $doctor->room_name}} @else - @endif</td>
								
								<td>
									<a title="Edit" href="{{url('/manage-doctors/edit', $doctor->doctor_id )}}"><i class="fa fa-edit" aria-hidden="true"></i></a>
									&nbsp; &nbsp;<a title="Delete" onclick="return confirm('Are you sure want to delete?');" href="{{url('/manage-doctors/delete',$doctor->doctor_id)}}"><i class="fa fa-trash-o" aria-hidden="true"></i></a>
								</td>
							</tr>
						@endforeach
					</tbody>			
				</table>
				{!! $doctors->render() !!}
				@else
				<h4 class="title" style="color:#34659d!important;padding-left:12px;">No doctor exists.</h4>
				@endif
				<div class="clearfix"></div>
			</div>
		</div>
	</div>
</div>
@endsection
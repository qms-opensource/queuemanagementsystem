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
						<h4 class="title">Departments</h4>
						<p class="category">Department List</p>
					</div>
					<div class="col-md-4">
						<div class=""><a href="{{url('/department/create')}}"><span class="text">Add Department  <i class="fa fa-plus"></i></span></a></div>
					</div>
				</div>
			</div>
			<div class="card-content table-responsive">
				<table class="table">
					<thead class="text-primary">
						<th>Name</th>
						<!-- <th>Room No</th>
						<th>Floor</th> -->
						<th>Action</th>
					</thead>
					<tbody>
					 @if(!empty($departments))
						@foreach($departments as $department)
							<tr>
							@if($department['add_hall'] == 0)
								<td>{{ $department['name']}}</td>
								<!-- <td>{{ $department['room_no']}}</td>
								<td>{{ $department['floor']}}</td> -->
								<td>
								</td>
							@else
								<td>{{ $department['name']}}</td>
								<!-- <td>{{ $department['room_no']}}</td>
								<td>{{ $department['floor']}}</td> -->
								<td>
									<a title="Edit" href="{{url('/department/edit', $department['id'] )}}"><i class="fa fa-edit" aria-hidden="true"></i></a>
									&nbsp; &nbsp;<a title="Delete" onclick="return confirm('Are you sure want to delete?');" href="{{url('/department/delete',$department['id'])}}"><i class="fa fa-trash-o" aria-hidden="true"></i></a>
								</td>
							</tr>
							@endif
						@endforeach
					@endif
					</tbody>
					{{! $departments->renders() !}}
				</table>
				<div class="clearfix"></div>
			</div>
		</div>
	</div>
</div>
@endsection
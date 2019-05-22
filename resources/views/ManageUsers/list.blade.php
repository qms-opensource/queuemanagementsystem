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
						<h4 class="title">Manage Users</h4>
						<p class="category">User List</p>
					</div>
					<div class="col-md-4">
						<div class=""><a href="{{url('/manage-users/create')}}"><span class="text">Add User &nbsp;<i class="fa fa-plus"></i></span></a></div>
					</div>
				</div>
			</div>
			<div class="card-content table-responsive">
			@if(!empty($users) && count($users) >= 1)
				<table class="table">
					<thead class="text-primary">
						<th>User Name</th>
						<th>Email</th>
						<th>Role</th>
						<th>Action</th>
					</thead>
					<tbody>
						@foreach($users as $user)
							<tr>
								<td>{{ $user->name}}</td>
								<td>{{ $user->email}}</td>
								<td>{{ $user->role_name}}</td>
								<td>@if($user->role_id != 10)
									<a title="Edit" href="{{url('/manage-users/edit', $user->id )}}"><i class="fa fa-edit" aria-hidden="true"></i></a>
									&nbsp; &nbsp;<a  title="Delete" onclick="return confirm('Are you sure want to delete?');" href="{{url('/manage-users/delete',$user->id)}}"><i class="fa fa-trash-o" aria-hidden="true"></i></a>
									@endif
								</td>
							</tr>
						@endforeach
					</tbody>
				</table>
				{!! $users->render() !!}
				@else
					<h4 class="title" style="color:#34659d!important;padding-left:12px;">No User exists.</h4>
				@endif
				<div class="clearfix"></div>
			</div>
		</div>
	</div>
</div>
@endsection
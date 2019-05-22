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
						<h4 class="title">Manage Roles</h4>
						<p class="category">Role list</p>
					</div>
					<div class="col-md-4">
						<div class=""><a href="{{url('/manage-roles/create')}}"><span class="text">Add <i class="fa fa-plus"></i></span></a></div>
					</div>
				</div>
			</div>
			<div class="card-content table-responsive">
				<table class="table">
					<thead class="text-primary">
						<th>Role Name</th>
						<th>Action</th>
					</thead>
					<tbody>
					 @if(!empty($roles))
						@foreach($roles as $role)
							<tr>
								<td>{{ $role->role}}</td>
								<td>
									<a title="Edit" href="{{url('/manage-roles/edit', $role->id )}}"><i class="fa fa-edit" aria-hidden="true"></i></a>
									&nbsp; &nbsp;<a title="Delete" onclick="return confirm('Are you sure want to delete?');" href="{{url('/manage-roles/delete',$role->id)}}"><i class="fa fa-trash-o" aria-hidden="true"></i></a>
								</td>
							</tr>
						@endforeach
					@endif
					</tbody>
				</table>
				<div class="clearfix"></div>
			</div>
		</div>
	</div>
</div>
@endsection
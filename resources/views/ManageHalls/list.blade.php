<?php use App\Rooms;   ?>
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
				<div class="row"><div class="col-md-8">
					<h4 class="title">Manage Blocks</h4>
					<p class="category">Block List</p>
				</div>
				<div class="col-md-4">
					<div class=""><a href="{{url('/manage-block/add')}}"><span class="text">Add Block <i class="fa fa-plus"></i></span></a></div>
				</div></div>
			</div>
			<div class="card-content table-responsive">
			@if(!empty($halls) && count($halls) >= 1)
				<table class="table">
					<thead class="text-primary">
						<th>Block Name</th>
						<th>Capacity</th>
						<th>Department</th>
						<th>Action</th>
					</thead>
					<tbody>
						@foreach($halls as $hall)
							<tr>
								<td>{{ $hall->name }}</td>
								<td>{{ $hall->capacity }}</td>
								<td>@if(!empty($hall->department)){{ $hall->department }} @else
                                  No department linked yet. @endif
								</td>
								<td class="text-primary">
								<?php 
								$rooms = 0;
								if(Rooms::where('hall',$hall->id)->exists())
								{
									$rooms = 1;  
								}
								?>
									<a href="{{url('/manage-block/edit', $hall->id)}}"><i class="fa fa-edit" aria-hidden="true"></i></a>
									&nbsp; &nbsp;<a title="Delete" id="@if(!empty($rooms)){{ $rooms }}@else{{ $rooms }}@endif" onclick="return deleteData(this)" href="{{url('/manage-block/delete', $hall->id)}}"><i class="fa fa-trash-o" aria-hidden="true"></i></a>
								</td>
							</tr>
						@endforeach
					</tbody>
				</table>
				{!! $halls->render() !!}
				@else
					<h4 class="title" style="color:#34659d!important;padding-left:12px;">No block exists.</h4>
				@endif
			</div>
		</div>
	</div>
</div>
@endsection
@section('script')
<script>
		function deleteData(input)
			{
				var roomsdata = input.id;
				if(roomsdata == 1)
				{
					return confirm('On deleting block, linked rooms will be deleted and the doctor association will also be removed.')
				} else{
					return confirm('Are you sure want to delete?')
				} 
				
			}
</script>
@endsection
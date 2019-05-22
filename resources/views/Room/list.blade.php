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
						<h4 class="title">Manage Rooms</h4>
						<p class="category">Room List</p>
					</div>
					<div class="col-md-4">
						<div class=""><a href="{{url('/manage-rooms/create')}}"><span class="text">Add Room <i class="fa fa-plus"></i></span></a></div>
					</div>
				</div>
			</div>
			<div class="card-content table-responsive">
				@if(!empty($rooms) && count($rooms) >= 1)
				<table class="table">
					<thead class="text-primary">
						<th>Room Name</th>
						<th>Department</th>
						<th>Block</th>
						<th>Action</th>
					</thead>
					<tbody>
						@foreach($rooms as $room)
							<tr>
								<td>{{ $room->room_name}}</td>
								<td>@if(!empty($room->department)){{ $room->department}} @else No department linked yet. @endif</td>
								<td>@if($room->hall_status == 1){{ $room->hall}} @else - @endif</td>
								<td>
								@if($room->status == 0)
									<a title="Activate Room" href="{{url('/manage-rooms/updateStatus/'. $room->id.'/'. $room->status)}}" class="t-margin-right-sm"><i class="fa fa-close" aria-hidden="true"></i></a>
								@endif
								@if($room->status == 1)
									<a title="Deactivate Room" href="{{url('/manage-rooms/updateStatus/'. $room->id.'/'.$room->status)}}" class="t-margin-right-sm"><i class="fa fa-check" aria-hidden="true"></i></a>
								@endif
									<a title="Edit" href="{{url('/manage-rooms/edit/'.$room->id.'/'.$room->hall_id )}}"><i class="fa fa-edit" aria-hidden="true"></i></a>
									&nbsp; &nbsp;<a title="Delete" onclick="return confirm('Are you sure want to delete?');" href="{{url('/manage-rooms/delete',$room->id)}}"><i class="fa fa-trash-o" aria-hidden="true"></i></a>
								</td>
							</tr>
						@endforeach
					</tbody>
				</table>
				{!! $rooms->render() !!}
				@else <h4 class="title" style="color:#34659d!important;padding-left:12px;">No room exists.</h4>
				@endif
				<div class="clearfix"></div>
			</div>
		</div>
	</div>
</div>
@endsection
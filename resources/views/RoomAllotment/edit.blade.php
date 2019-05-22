@extends('layouts.qmlayout')
@section('content')
<div class="col-md-12">
	<div class="card">
		<div class="card-header" data-background-color="purple">
			<h4 class="title">Room</h4>
			<p class="category">Add Room</p>
		</div>
		<div class="card-content">
			<form name="manageroom" method="post" action="{{url('/manage-rooms/update')}}">
				<div class="row">
					{{ csrf_field() }}
					<div class="col-md-4">
						<div class="form-group label-floating">
							<label class="control-label">Room Name</label>
							<input type="text" name="room_name" value="{{ old('room_name', $roomData->room_name) }}" class="form-control">
							<span class="has-error">{{ $errors->edit->first('room_name') }}</span>
							<input type="hidden" name="id" value="{{$roomData->id}}" />
						</div>
					</div>
					<div class="col-md-4">
						<div class="form-group label-floating">
							<label class="control-label">Department</label>
							<select name="department" value="{{ old('department', $roomData->department ) }}" class="department form-control">
								<option value=""></option>
								@foreach($departments as $department)
									<option value="{{$department->id}}"
									@if ($department->id == old('department', $roomData->department))
										selected="selected"
									@endif
									>{{$department->name}}</option>
								@endforeach
							</select>
							<span class="has-error">{{ $errors->edit->first('department') }}</span>
						</div>
					</div>
					<div class="col-md-4">
						<div class="form-group label-floating">
							<label class="control-label">Hall</label>
							<select name="hall" value="{{ old('hall') }}" class="form-control">
								<option value=""></option>
								@foreach($halls as $hall)
									<option value="{{ $hall->id }}"
									@if($hall->id == old('hall', $roomData->hall))
										selected="selected"
									@endif
									>{{ $hall->name }}</option>
								@endforeach
							</select>
							<span class="has-error">{{ $errors->edit->first('hall') }}</span>
						</div>
					</div>
					
					</div>
				<button type="submit" class="btn btn-primary pull-right">Update Room</button>
				<div class="clearfix"></div>
			</form>
		</div>
	</div>
</div>
@endsection
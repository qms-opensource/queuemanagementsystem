@extends('layouts.qmlayout')
@section('content')
<div class="col-md-12">
	<div class="card">
		<div class="card-header" data-background-color="purple">
			<h4 class="title">Room</h4>
			<p class="category">Add Room</p>
		</div>
		<div class="card-content">
			<form name="addPatientForm" method="post" action="{{ route('manage-rooms.store') }}">
				<div class="row">
					{{ csrf_field() }}
					<div class="col-md-4">
						<div class="form-group label-floating">
							<label class="control-label">Room Name</label>
							<input type="text" name="room_name" value="{{ old('room_name') }}" class="form-control">
							<span class="has-error">{{ $errors->add->first('room_name') }}</span>
						</div>
					</div>
					<div class="col-md-4">
						<div class="form-group label-floating">
							<label class="control-label">Department</label>
							<select name="department" value="{{ old('department') }}" class="department form-control">
								<option value=""></option>
								@foreach($departments as $department)
									<option value="{{$department->id}}">{{$department->name}}</option>
								@endforeach
							</select>
							<span class="has-error">{{ $errors->add->first('department') }}</span>
						</div>
					</div>
					<div class="col-md-4">
						<div class="form-group label-floating">
							<label class="control-label">Hall</label>
							<select name="hall" value="{{ old('hall') }}" class="form-control">
								<option value=""></option>
								@foreach($halls as $hall)
									<option value="{{ $hall->id }}">{{ $hall->name }}</option>
								@endforeach
							</select>
							<span class="has-error">{{ $errors->add->first('hall') }}</span>
						</div>
					</div>
					
					</div>
				<button type="submit" class="btn btn-primary pull-right">Add Room</button>
				<div class="clearfix"></div>
			</form>
		</div>
	</div>
</div>
@endsection
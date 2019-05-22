@extends('layouts.qmlayout')
@section('content')
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
				<h4 class="title">Patient Summary</h4>
				<p class="category">Patients List</p>
			</div>
			@foreach($allRooms as $room)
				<div class="card-content table-responsive">
				@if(isset($patients[$room->id]))
					@if($room->status == 1)
						<div style="border:1px solid #dddddd; padding:8px;">
							<div class="row" style="font-weight:bold;">
								<div class="col-md-4">{{$room->room_name}} </div>
								<div class="col-md-4">Current Token  - {{$currentToken[$room->id]}} </div>
								<div class="col-md-4">Total Token {{count($patients[$room->id])}} </div>
							</div>
							<table class="table">
								<thead class="text-primary">
									<th>Patient Name</th>
									<th>Age</th>
									<th>Mobile No</th>
									<th>CR NO.</th>
									<th>Token</th>
									<th>Department</th>
									<th>Hall</small></th>
									<th>Created Date</th>
									<th>Action</th>
								</thead>
								<tbody>
								@foreach($patients[$room->id] as $patient)
									<tr>
										<td>{{ ucwords($patient->patient_name) }}</td>
										<td>{{ $patient->age }}</td>
										<td>{{ $patient->patient_phone }}</td>
										<td>{{ $patient->crno }}</td>
										<td>{{ $patient->token }}</td>
										<td>{{ $patient->department_name }}</td>
										<td> {{$patient->hall}}</td>

										<td>{{ $patient->patient_register_date }}</td>
										<td>
											<a title="Re-Visit" href="{{url('/manage-patient/edit', $patient->pid )}}"><i class="fa fa-repeat" aria-hidden="true"></i></a>
											&nbsp; &nbsp;<a onclick="return confirm('Are you sure want to delete?');" href="{{url('/manage-patient/delete',$patient->pid)}}"><i class="fa fa-trash-o" aria-hidden="true"></i></a>
										</td>
									</tr>
								@endforeach
								</tbody>
							</table>
						</div>
					</div>
				@endif
			@endif
			@endforeach
		</div>
	</div>
</div>
@endsection
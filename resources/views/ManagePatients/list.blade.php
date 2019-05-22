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
				<h4 class="title">Patient Queue</h4>
				<!--p class="category"></p-->
			</div>
			<div class="card-content table-responsive">
			@if(!empty($patients) && count($patients) >= 1)
				<table class="table">
					<thead class="text-primary">
						<th>Patient Name</th>
						<th>Age</th>
						<th>Mobile No</th>
						<th>CR No</th>
						<th>Token</th>
						<th>Department</th>
						<th>Room<small>&nbsp;(Block)</small></th>
						<th>Created Date</th>
					</thead>
					<tbody>
					@foreach($patients as $patient)
						<tr>
							<td>{{ $patient->patient_name }}</td>
							<td>{{ $patient->age }}</td>
							<td>{{ $patient->patient_phone }}</td>
							<td>{{ $patient->crno }}</td>
							<td>{{ $patient->token }}</td>
							<td>@if(!empty($patient->department_name) && isset($patient->department_name))  {{ $patient->department_name }} @else  No department linked yet @endif </td>
							<td>@if(!empty($patient->room_no)&& $patient->add_hall ==1 || !empty($patient->hall) && $patient->add_hall ==1 ) {{$patient->room_no}} @if(!empty($patient->hall)) ({{$patient->hall}}) @else  - @endif @elseif($patient->add_hall ==0) {{$patient->room_no}} @else -  @endif</td>
							<td>{{ Carbon\Carbon::parse($patient->patient_register_date)->format('d-m-Y g:i A') }}</td>
						</tr>
					@endforeach
					</tbody>
				</table>
				{!! $patients->render() !!}
				@else
				<h4 class="title" style="color:#34659d!important;padding-left:12px;">No patient exists.</h4>
				@endif
			</div>
		</div>
	</div>
</div>
@endsection
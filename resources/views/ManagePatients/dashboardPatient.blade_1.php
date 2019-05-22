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
				<h4 class="title">Dashboard</h4>
			</div>
			<div class="row">
				<div class="col-md-6" style="float:left;">
				<div class="form-group label-floating" style="margin: 33px 0 0 0; font-weight: bold;">Total Tokens: {{count($patientData)}}
				</div>
				</div>
				<div class="col-md-6" style="padding-right:35px; float:right;">
					<div class="form-group label-floating">
						<select name="department" value="{{ old('department') }}" class="department form-control">
							<option value="all">All Departments</option>
							@foreach($departments as $department)
								<option value="{{$department->id}}"  @if($id == $department->id) selected="selected" @endif>{{$department->name}}</option>
							@endforeach
						</select>
						<span class="has-error">{{ $errors->patient->first('department') }}</span>
					</div>
				</div>
			</div>
			<?php 
			if(count($departments)>0){
				foreach($departments as $department){
				if(isset($allHalls[$department['id']])){
				if(count($allHalls[$department['id']]) >0){
			
				?>
			<table class="dashboard-tbl" border="1" width="100%">
				
				<tr>
					<td style="text-align:center; font-weight: bold; background-color: #eaf3e9; border-right:1px solid #eaf3e9;" colspan="5"><h4>Department Name: {{$department['name']}} </td><td style="text-align:center; font-weight: bold; background-color: #eaf3e9; border:0px; ">Total Tokens: 				@if(isset($departCount[$department['id']]))
							{{$departCount[$department['id']]}} 
						@else
							0
						@endif</h4></td>
				</tr>
				<?php foreach($allHalls[$department['id']] as $hall){ ?>
				<tr class="room">
					<td style="text-align:center; font-weight: bold; padding:5px 10px; background-color: #e0e0e0;" width="15%" rowspan="<?php if(!empty($allRooms[$hall->id])) echo count($allRooms[$hall->id])+1;?>"><span style="font-weight: normal;">hall(Tokens)</span><h5>{{$hall->hall_name}}
					<br>(@if(isset($hallCount[$hall->id]))
							{{$hallCount[$hall->id]}} 
						@else
							0
						@endif
				)</h5></td>
					
					<?php if(isset($allRooms[$hall->id])){
						
						echo '<td>Room Name</td><td>Total Tokens</td><td>Current Token</td><td>Processed Token</td><td>Skipped Token</td></tr>';	
			foreach($allRooms[$hall->id] as $key =>  $room){
			?>
			<tr style="background-color: #d9edff;">
					<td width="10%"><?php echo $room->room_name; ?></td>
					<td>
						@if(isset($patients[$room->id]))
							{{count($patients[$room->id])}} 
						@else
							0
						@endif
					</td>
					<td> @if(isset($currentToken[$room->id]))
							{{$currentToken[$room->id]}} 
						@else
							-
						@endif
					
					</td>
					<td>@if(isset($roomProcessedToken[$room->id]))
							{{count($roomProcessedToken[$room->id])}} 
						@else
							0
						@endif</td>
					<td>@if(isset($roomSkippedToken[$room->id]))
							{{count($roomSkippedToken[$room->id])}} 
						@else
							0
						@endif</td>
				</tr>
			<?php }?>
					<?php }?><?php }?>
				
			</table>
			<br>
			<?php 
				
			}
		}}}
			/* }
			} */
			/* ?>
			@if(count($allRooms) >0)
			@foreach($allHalls as $hall)
			<div style="border:1px solid #c3c3c3; padding:8px; margin:5px;">
				<h5><strong>{{$hall->hall_name}}</strong></h5>
			@if(isset($allRooms[$hall->id]))
			@foreach($allRooms[$hall->id] as $room)
			<div class="card-content table-responsive">
			@if(isset($patients[$room->id]))
			@if($room->status == 1)
				<div style="border:1px solid #dddddd; padding:8px;">
				<div class="row" style="font-weight:bold; padding-left:15px;">{{$room->room_name}}</div>
				<div class="row" style="">
					@if(isset($currentToken[$room->id]))
						<div class="col-md-3">Current Token  - {{$currentToken[$room->id]}} </div>
					@else
						<div class="col-md-3">Current Token  - </div>
					@endif
					<div class="col-md-3">Total Token {{count($patients[$room->id])}} </div>
					<div class="col-md-3">Processed Tokens 
						@if(isset($roomProcessedToken[$room->id]))
							{{count($roomProcessedToken[$room->id])}} 
						@else
							0
						@endif
					</div>
					<div class="col-md-3">Skipped Tokens 
						@if(isset($roomSkippedToken[$room->id]))
							{{count($roomSkippedToken[$room->id])}} 
						@else
							0
						@endif
					</div>
				</div>
				
			</div>
			
			@else
				<div style="border:1px solid #dddddd; padding:8px;">
			
					<div class="row" style="font-weight:bold;"><div class="col-md-4">{{$room->room_name}} </div></div>
					The room is currently deactivated.
				</div>	
			@endif
			@else
				<div style="border:1px solid #dddddd; padding:8px;">
				<div class="row" style="font-weight:bold;"><div class="col-md-4">{{$room->room_name}} </div></div>
					No patient in this room</div>
			@endif
			</div>
			@endforeach
			@else
				<div style="border:1px solid #dddddd; padding:8px; margin-left:20px;">
				<div class="row"><div class="col-md-4">No room in this hall </div></div>
					</div>
			@endif
			</div>
			@endforeach
			@else
				<div class="card-content table-responsive"> 
					<div style="border:1px solid #dddddd; padding:8px; text-align: center;">
						<h5>No room in the selected department.</h5>
					</div>	
				<div class="clearfix"></div>
			</div>
			@endif */?>
		</div>
	</div>
</div>
@endsection
@section('script')
<script>
	$(document).ready(function(){
		$('.department').on('change',function(){
			var id = $(this).val();
			//getPatientByDepartment(id);
			window.location.href = '{{url("manage-patient/dashboardData/")}}/'+id;
		});
	});
</script>
@endsection
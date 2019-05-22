@extends('layouts.qmlayout')
@section('content')
<style>
	.addnew{float:right;color:#fff;font-size:25px;}
	.text{float:right;font-size:20px;}
	.next{
		display:inline-block;
		width: 100%;
	}
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
		<div class="alert alert-danger" style="display:none;">
		  One patient is already is in process.
		</div>
		<div class="card-content doc_dashboard">
			<div class="card-header" data-background-color="purple">
				<div class="row">
					<div class="col-md-12">
						<h4 class="title">Patient Queue</h4>
					</div>
				</div>
			</div>
			<div class="card-content table-responsive">	
			<form>
				<?php $i = 1;?>
					 @if(!empty($appointmentdata))
				<table class="table" id="maindata">
					<thead class="text-primary">
						<th width="10%">Sr No.</th>
						<th width="15%">Token</th>
						<th width="15%">Name</th>
						<th width="15%">Phone</th>
						<th width="15%">Age</th>
						<th width="15%">Department</th>
						<th width="15%">Remarks</th>
						<th width="15%">Action</th>
					</thead>
						<?php	$firstdata = array_shift($appointmentdata); ?>
						<tr>
							<td>{{ $i }}</td>
							<td>{{ $firstdata['token'] }}</td>
							<td>{{ $firstdata['patient_name'] }}</td>
							<td>{{ $firstdata['phone'] }}</td>
							<td>{{ $firstdata['age'] }}</td>
							<td>@if(!empty($firstdata['department_name'])){{ $firstdata['department_name'] }} @else No department linked yet @endif</td>
							<td><textarea name="remarks" id="remarks">@if(!empty($firstdata['remarks'])) {{ $firstdata['remarks'] }} @endif</textarea></td>
							<td>
							@if($firstdata['queue_status'] == 1)
							<a data-id = "{{ $firstdata['pid'] }}" doctor-data="{{ $doctor_id }}" title="2" href="javascript:void(0);" class="btn btn-primary btn-small active doc_btn process" role="button">Process</a>&nbsp; &nbsp;
							<a data-id = "{{ $firstdata['pid'] }}" title="3" href="javascript:void(0);" class="btn btn-primary btn-small active doc_btn process" role="button">Skip</a>
							@elseif($firstdata['queue_status'] == 2)
							<a  data-id = "{{ $firstdata['pid'] }}" title="1" href="javascript:void(0);" class="btn btn-primary btn-small active doc_btn process" role="button">In Process</a>
							&nbsp; &nbsp;
							<a  data-id = "{{ $firstdata['pid'] }}" title="3" href="javascript:void(0);" class="btn btn-primary btn-small active doc_btn process" role="button">Skip</a>
							@elseif($firstdata['queue_status'] == 3)
							<a data-id = "{{ $firstdata['pid'] }}" title="1" href="javascript:void(0);" class="btn btn-primary btn-small active doc_btn process" role="button">In Process</a>
							&nbsp; &nbsp;<a data-id = "{{ $firstdata['pid'] }}" title="2" href="{{url('/manage-doctor/manage-patient-appointment/processdata') }}" class="btn btn-primary btn-small doc_btn active process" role="button">Process</a>
							@else
								<a data-id = "{{ $firstdata['pid'] }}"  href="javascript:void(0);" title="1" class="btn btn-primary btn-small doc_btn active process" role="button">In Process</a>
								&nbsp; &nbsp;<a data-id = "{{ $firstdata['pid'] }}"  href="javascript:void(0);" title="3" class="btn btn-primary btn-small doc_btn active process" role="button">Skip</a>
							@endif
							</td>
						</tr>							
						@foreach($appointmentdata as $appointmentinfo=>$appointmentinfoval)
							@if(!empty($appointmentinfoval))
						<?php $i = $i+1; ?>
								<tr>
									<td>{{ $i }}</td>
									<td>{{  $appointmentinfoval['token'] }}</td>
									<td>{{  $appointmentinfoval['patient_name'] }}</td>
									<td>{{  $appointmentinfoval['phone'] }}</td>
									<td>{{ $appointmentinfoval['age'] }}</td>
									<td>{{  $appointmentinfoval['department_name'] }}</td>
									<td>
									</td>
								</tr>
					@endif
					@endforeach				
					</tbody>
					@else
					<h4 class="title" style="color:#34659d;">No Patient exists.</h4>
					@endif
				</table>
				</form>
				<div class="clearfix"></div>
			</div>
		</div>
		<div class="card-content doc_dashboard">
			<div class="card-header" data-background-color="purple">
				<div class="row">
					<div class="col-md-12">
						<h4 class="title">Processed</h4>
					</div>
				</div>
			</div>
			<div class="card-content table-responsive">
			@if(!empty($processdata))
				<table class="table" id="processdata">
					<thead class="text-primary">
						<th width="10%">Sr No.</th>
						<th width="10%">Token</th>
						<th width="15%">Name</th>
						<th width="15%">Phone</th>
						<th width="10%">Age</th>
						<th width="15%">Department</th>
						<th width="10%">Remarks</th>
						<th width="15%"></th>
					</thead>
					<tbody>
					<?php $i = 1; ?>
						@foreach($processdata as $processdatainfo)
							@if(!empty($processdatainfo))
								<tr>
									<td>{{ $i }}</td>
									<td>{{ $processdatainfo['token'] }}</td>
									<td>{{ $processdatainfo['patient_name'] }}</td>
									<td>{{ $processdatainfo['phone'] }}</td>
									<td>{{ $processdatainfo['age'] }}</td>
									<td>{{ $processdatainfo['department_name'] }}</td>
									<td>{{ $processdatainfo['remarks'] }}</td>
									<td><a room-data ="{{ $processdatainfo['room_id']}}" doctor-data="{{ $doctor_id }}" data-id="{{ $processdatainfo['pid'] }}"  href="javascript:void(0);" class="btn btn-primary btn-small active doc_btn process" title= "0" role="button">Re-Queue</a></td>
								</tr>
								<?php  $i++;   ?>
							@endif
						@endforeach
					</tbody>
					@else
					<h4 class="title" style="color:#34659d;">No Patient exists.</h4>
					@endif
				</table>
				<div class="clearfix"></div>
			</div>
		</div>
		<div class="card-content doc_dashboard">
			<div class="card-header" data-background-color="purple">
				<div class="row">
					<div class="col-md-12">
						<h4 class="title">Skipped</h4>
					</div>
				</div>
			</div>
		<div class="card-content table-responsive">
			@if(!empty($skippdata))
				<table class="table" id="skipdata">
					<thead class="text-primary">
						<th width="10%">Sr No.</th>
						<th width="15%">Token</th>
						<th width="15%">Name</th>
						<th width="15%">Phone</th>
						<th width="15%">Age</th>
						<th width="10%">Remarks</th>
						<th width="15%">Department</th>
						<th width="15%"></th>
					</thead>
					<tbody>
					<?php $i = 1; ?>
						@foreach($skippdata as $skippdatainfo)
							@if(!empty($skippdatainfo))
								<tr>
									<td>{{ $i }}</td>
									<td>{{ $skippdatainfo['token'] }}</td>
									<td>{{ $skippdatainfo['patient_name'] }}</td>
									<td>{{ $skippdatainfo['phone'] }}</td>
									<td>{{ $skippdatainfo['age'] }}</td>
									<td>{{ $skippdatainfo['remarks'] }}</td>
									<td>{{ $skippdatainfo['department_name'] }}</td>	
									<td><a  <a room-data ="{{ $skippdatainfo['room_id']}}" data-id="{{  $skippdatainfo['pid'] }}"  href="javascript:void(0);" class="btn btn-primary btn-small active doc_btn process" title= "0" role="button">Re-Queue</a></td>
								</tr>
								<?php  $i++;   ?>
							@endif
						@endforeach
					</tbody>
					@else
					<h4 class="title" style="color:#34659d;">No Patient exists.</h4>
					@endif
				</table>
				<div class="clearfix"></div>
			</div>
		</div>
	</div>
</div>
@endsection
@section('script')
<script>
	$(document).ready(function(){
		$('body').on('click','.process',function(){	
		var remark=$('#remarks').val();
		
		var processid = $(this).attr('data-id');
		var queue_status = $(this).attr('title');
		var room_data = $(this).attr('room-data');
		var doctor_data = {{ $doctor_id }};
		var URL='{{url("/manage-doctor/manage-patient-appointment/processdata")}}';
		$.ajax({
			url:URL,
			type:'POST',
			headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
			data:{'doctor_id':doctor_data,'remarks':remark,'processid':processid,'queue_status':queue_status,'room_data': room_data },
			success:function(data){	
				if(data != null)
				{
					if(data.inprocess == 1)
					{
						$('.alert-danger').css('display','block');
					}
				}
				window.location.reload();	

		    }
		})
})
	});
</script>
@endsection
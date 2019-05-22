<style>
.form-group{ padding: 0px !important; margin: 0px !important;}
</style>
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
				<h4 class="title">Manage Doctor Linking</h4>
			</div>
			<div class="row">
				<input type="hidden" value="<?php echo $roomWithDoc?>" class="roomWithDoc">
				<table border="0" class="dashboard-tbl" id="dashboard-tbl" border="1px" style="width: 96%; margin:0px auto;float: none; margin-top:30px;">
					<tr  style="border: 0px;">
						<th width="40%" style="text-align:left; border-bottom:2px solid #939393; color: #34659d !important">Room</th>
						<th width="35%" style="text-align:left; border-bottom:2px solid #939393; color: #34659d !important">Doctor</th>
						<th width="20%" style="text-align:left; border-bottom:2px solid #939393; color: #34659d !important">Action</th>
						<th width="5%" style="text-align:left;"></th>
					</tr>
						<?php foreach($rooms as $rkey => $roomRow){
						?>
						<tr  style="border: 0px;">
							<td style="text-align:left; border-bottom:1px solid #939393;"><?php echo $roomRow['room_name'];?></td>
							<td style="border-bottom:1px solid #939393;">
							<div class="col-md-10">
							<select  name="doctor[]" id="doctor" value="{{ old('doctor') }}" class="form-control doctor<?php echo $roomRow['id'];?>">
								<option value="0">None</option>
								@foreach($doctors as $doctor)
					<option  value="{{$doctor['id']}}" @if(old('doctor') == $doctor['id']|| $doctor['room_id'] == $roomRow['id'] )  selected="selected" @endif>{{ $doctor['name'] }}</option>
								@endforeach
							</select>
							</div>
						</td>
						<td style="text-align:left;border-bottom:1px solid #939393;"><button type="button" room-id="<?php echo $roomRow['id'];?>" id="LinkDoctor1" class="LinkDoctor btn btn-primary" style="margin: 0; padding: 6px 20px; font-weight: bold;">Save</button><!-- <i class="material-icons" style="color:#5cb85c; font-size:28px;">check_box</i> -->
						<i class="fa fa-spinner fa-spin fa-3x fa-fw fa<?php echo $roomRow['id'];?>" style="display:none;font-size:20px;"></i>
						<i class="material-icons checkBox<?php echo $roomRow['id'];?>" style="color:#5cb85c; font-size:20px; display: none;bottom: -4px; position: relative;">check_box</i><td>
						
						<td></td>
					</tr>
					<?php }?>
				</table>	
			</div>
		</div>
	</div>
</div>
@endsection
@section('script')
<script>
	$(document).ready(function(){
		$('.LinkDoctor').on('click',function(){
			var roomID = $(this).attr('room-id');
			
			//var roomID = $(this).attr('doctor-id');
			var docID = $('.doctor'+roomID).val();
			console.log(docID);
			console.log(roomID);
			
			$.ajax({
			type:'GET',
			url:'{{url("manage-doctors/findDocInfo")}}/'+roomID+'/'+docID,
			success:function(response){
				var obj = jQuery.parseJSON(response);
				//console.log(obj);return false;
				if(obj.status == true){
					
				var roomWithDoc = $('.roomWithDoc').val();
			var roomArr = roomWithDoc.split('*');
			var docPos = $.inArray( docID, roomArr ) ;
			if(docPos>0){
			   var success = confirm('Doctor is already associated with another room. Are you sure to change the association?');
				if(success)
				{
					linkDoctorToRoom(roomID, docID, '1');
				}else{
					linkDoctorToRoom(roomID, docID, '0');
				} 
			}else{
				linkDoctorToRoom(roomID, docID, '0');
			}
				}
				
			}
		});
			{
			}
			
		});
	});
	function linkDoctorToRoom(room_id, doctor_id, status){
		$(".fa"+room_id).show();
		$.ajax({
			type:'GET',
			url:'{{url("manage-doctors/updateRoomDoc")}}/'+room_id+'/'+doctor_id+'/'+status,
			success:function(response){
				var obj = jQuery.parseJSON(response);
				$(".fa"+room_id).hide();
				if(obj.status == true){
					$('.roomWithDoc').val(obj.data);
					$( "#dashboard-tbl" ).load( "http://st9.idsil.com/hqms/public/manage-doctors/linkDoctorWithRoom#dashboard-tbl" );
					$(".checkBox"+room_id).show().delay(3000).fadeOut();
					console.log(obj.oldRoomID);
					if(obj.oldRoomID != 0){
						
						$('.doctor'+obj.oldRoomID).val(0);
					}
				}
				//console.log(obj.data);return false;
				//$(".checkBox"+room_id).show().delay(5000).fadeOut();
				/* if(status == 1)
					$('.doctor'+room_id).val(0); */
			}
		});
	}
</script>
@endsection

@extends('layouts.qmlayout')
@section('content')
<style>
	 .table>thead>tr>th, .table>tbody>tr>th, .table>tfoot>tr>th, .table>thead>tr>td, .table>tbody>tr>td, .table>tfoot>tr>td {
        padding: 0px 0px!important; 
        vertical-align: middle!important;
        } 
</style>
<div class="col-md-12">
	<div class="card">
		@if(Session::has('alert-success'))
			<div class="alert alert-success">
				{{Session::get('alert-success')}}
			</div>
		@endif
		
	
		@if(session::has('alert-danger'))	
            @if(!empty(session::has('alert-danger')))		
				@foreach(Session::get('alert-danger') as $session_message)
				<div class="alert alert-danger">
					{{ $session_message }}
				</div>
				@endforeach
			@endif
		@endif
		
		<div class="card-content">
			<div class="card-header" data-background-color="purple">
				<div class="row">
					<div class="col-md-12">
						<h4 class="title">Manage Doctor Linking</h4>
					</div>
				</div>
			</div>
			 <form name="doctorLinkForm" method="post" action="{{url('/manage-doctors/link')}}" class="formValidate" id="formValidate" novalidate="novalidate">
			 	<div class="card-content table-responsive">
			   {{ csrf_field() }}
				@if(!empty($rooms) && count($rooms) >= 1)
				<table class="table">
					<thead class="text-primary">
						<th>Room Name</th>
						<th width="30%">Doctors</th>
					</thead>
					<tbody>
						@foreach($rooms as $room)
							<tr>
								<td>{{ $room->room_name}}</td>
								<td><div class="form-group label-floating">
									
									<select  name="doctor[]" id="doctor" value="{{ old('doctor') }}" class="form-control doctor">
									@foreach($doctors as $doctor)
										@if( $doctor['room_id'] == $room['id'] ) 
										
											<?php $room_data = $doctor['id']."-".$room['id']; ?>
										@endif

									@endforeach
									<option value="none" data_room="@if(isset($room_data)) {{ $room_data }} @endif" >None</option>
										@foreach($doctors as $doctor)
											<option  value="{{$doctor['id']}}-{{$room['id']}}" related-room="{{$doctor['room_id']}}" data_room="@if(isset($room_data)) {{ $room_data }} @endif" @if(old('doctor') == $doctor['id']|| $doctor['room_id'] == $room['id'] )  selected="selected" @endif>{{ $doctor['name'] }}
											</option>
										@endforeach
									</select>
									@foreach($doctors as $doctor)
										@if( $doctor['room_id'] == $room['id'] ) 
										
											<input type="hidden" id="old_data" name="old_data[]" value="{{$doctor['id']}}-{{$room['id']}}"  >
										@endif

									@endforeach
									<span class="has-error">{{ $errors->doctor->first('doctor') }}</span>
								</div>
								</td>
							</tr>
							<?php unset($room_data);   ?>
						@endforeach
					</tbody>
				</table>
				<input type="hidden" id="room_data" name="room_data[]" >
				<input type="hidden" id="room_current_data" name="room_current_data[]" >
				<button type="submit" id="LinkDoctor" class="btn btn-primary pull-right" style="position: fixed;top: 199px;right: 55px;z-index:999;">Link Doctor</button>
				</div>
				</form>
				@else <h4 class="title" style="color:#34659d!important;padding-left:12px;">No room exists.</h4>
				@endif
				<div class="clearfix"></div>
		</div>
	</div>
</div>
@endsection
@section('script')
<script>
	$(document).ready(function(){
		$('.doctor').on('focusin', function(){
	      var olddoctor =  $(this).val();
		  if(olddoctor != 'none' )
		  {
			var res = olddoctor.split("-");
			  $('option[value="none"]', this).val(res[0]+"-"+0); 
		  }
	    });  
		var txt = [];
		var txtfordoctor = [];
		 $('.doctor').on('change',function () {    
            var mydata = $('option:selected', this).val();
            var arrnone =  mydata.split('-');
            var mycurrentdoctor = $('option:selected', this).attr('data_room');
            var arrayforroom; 
            if(mydata.length > 0 && mydata != 'none')
            {            		          		
            	txt.push(mydata);
            	jQuery('#room_data').val(txt);
       		}
        }); 
	
		});
</script>
@endsection
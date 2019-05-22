@extends('layouts.qmlayout')
@section('content')
<div class="col-md-12">
	<div class="card">
		
		<div class="card-content">
		<div class="card-header" data-background-color="purple">
			<h4 class="title">Patient Registration</h4>
			<p class="category">Add Patient</p>
		</div>
		@if(Session::has('alert-success'))
			<div class="alert alert-success">
				{{Session::get('alert-success')}}
			</div>
		@endif
		
		@if(Session::has('alert-failure'))
			<div class="alert alert-danger" style="margin-top: 10px;">
				{{Session::get('alert-failure')}}
			</div>
		@endif
			<form name="addPatientForm" method="post" action="{{url('/manage-patient/save')}}" class="formValidate" id="formValidate" novalidate="novalidate">
					{{ csrf_field() }}
					<div class="row">
						<div class="col-md-4">
						<div class="col-md-1 reqStar">*</div>
						<div class="col-md-11">
							<div class="form-group label-floating">
								<label class="control-label">Patient Name</label>
								<input type="text" name="name" id="name" value="{{ old('name') }}" class="form-control" maxlength="30">
								<span class="has-error">{{ $errors->patient->first('name') }}</span>
							</div>
						</div>
						</div>
						<div class="col-md-4">
						<div class="col-md-1 reqStar"></div>
						<div class="col-md-11">
							<div class="form-group label-floating">
								<label class="control-label">Aadhar Number(xxxxxxxxxxxx)</label>
								<input type="text" pattern="^[123456789]\d{11}$" name="adhar_number" value="{{ old('adhar_number') }}" class="form-control adhar_card_number" maxlength="12" title="Aadhar Number should not start from 0.">
								<span class="has-error has-error2">{{ $errors->patient->first('adhar_number') }}</span>
							</div>
						</div>
						</div>
						<div class="col-md-4">
						<div class="col-md-1 reqStar">*</div>
						<div class="col-md-11">
							<div class="form-group label-floating">
								<label class="control-label">Mobile Number(xxxxxxxxxx)</label>
								<input type="text" name="mobile" pattern="^[123456789]\d{9}$" id="mobile" value="{{ old('mobile') }}" class="form-control" maxlength="10" title="Mobile Number should not start from 0." >
								<span class="has-error">{{ $errors->patient->first('mobile') }}</span>
							</div>
						</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-4">
						<div class="col-md-1 reqStar">*</div>
						<div class="col-md-11">
							<div class="form-group label-floating">
								<label class="control-label">Age</label>
								<input type="text" name="age" value="{{ old('age') }}" class="form-control" maxlength="3">
								<span class="has-error">{{ $errors->patient->first('age') }}</span>
							</div>
						</div>
						</div>
						<div class="col-md-4">
						<div class="col-md-1 reqStar">*</div>
						<div class="col-md-11">
							<div class="form-group label-floating">
								<label class="control-label">Gender</label>
								<select name="gender" value="{{ old('gender') }}" class="department form-control">
									<option value=""></option>
									<option value="male"  @if(old('gender') == 'male') selected="selected" @endif>Male</option>
									<option value="female"  @if(old('gender') == 'female') selected="selected" @endif>Female</option>
									<option value="third gender"  @if(old('gender') == 'third gender') selected="selected" @endif>Third Gender</option>
								</select>
								<span class="has-error">{{ $errors->patient->first('gender') }}</span>
							</div>
						</div>
						</div>
						<div class="col-md-4">
						<div class="col-md-1 reqStar"></div>
						<div class="col-md-11">
							<div class="form-group label-floating">
								<label class="control-label">Address</label>
								<input type="text" name="address" value="{{ old('address') }}" class="form-control" maxlength="100">
								<span class="has-error">{{ $errors->patient->first('address') }}</span>
							</div>
						</div>
						</div>		
					</div>
					<div class="row">
						<div class="col-md-4">
							<div class="col-md-1 reqStar"></div>
							<div class="col-md-11">
								<div class="form-group label-floating">
									<label class="control-label">Email</label>
									<input type="email" name="email" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$" value="{{ old('email') }}" class="form-control" maxlength="50">
									<span class="has-error">{{ $errors->patient->first('email') }}</span>
								</div>
							</div>
						</div>
						<div class="col-md-4">
							<div class="col-md-1 reqStar" style="">*</div>
							<div class="col-md-11">
								<div class="form-group label-floating">
									<label class="control-label">CR No</label>
									<input type="text" name="crno" pattern="^[123456789]\d{11}$" value="{{ old('crno') }}" class="form-control" maxlength="12" title="CR Number should not start from 0.">
									<span class="has-error">{{ $errors->patient->first('crno') }}</span>
								</div>
							</div>
						</div>
						<div class="col-md-4">
							<div class="col-md-1 reqStar">*</div>
							<div class="col-md-11">
								<div class="form-group label-floating">
									<label class="control-label">Department</label>
									<select name="department" id="department" value="{{ old('department') }}" class="department form-control" onchange="getHallForDepart(this.value)">
										<option value=""></option>
										@foreach($departments as $department)
											<option hall-status="{{$department->add_hall}}" value="{{$department->id}}"  @if(old('department') == $department->id) selected="selected" @endif>{{$department->name}}</option>
										@endforeach
									</select>
									<span class="has-error">{{ $errors->patient->first('department') }}</span>
								</div>
							</div>
						</div>
					</div>
					
					<div class="row ">
						@if(!empty($roomdata))
						{{ $roomdata }}
						@endif
						<div class="col-md-4" id="hallDiv"  @if(!empty(old('hall'))) style="display:block;" @else style="display:none;" @endif >		
							<div class="col-md-1 reqStar">*</div>
							<div class="col-md-11 hallRoomDiv">
								<div class="form-group label-floating">
									<label class="control-label">Hall</label>
									<select name="hall" id="hall" value="{{ old('hall') }}" class="hall form-control">
										<option value=""></option>
										 @foreach($halls as $hall)
											<option value="{{$hall->id}}"  @if(old('hall') == $hall->id) selected="selected" @endif>{{$hall->name}}</option>
										@endforeach
									</select>
									<span class="has-error">{{ $errors->patient->first('hall') }}</span>
								</div>
							</div>
							<div class=" fa-loaderHall col-md-11" style="display: none; padding: 31px 8px;">
								<i class="fa fa-spinner fa-spin fa-3x fa-fw" style="font-size:22px;"></i>
							</div>
						</div>
					</div>	
					<!--div class="row">
					<div class="hallRoomDiv3">
						<div class="col-md-4" id="roomDiv" @if(!empty(old('roomdata'))) style="display:block;" @else  style="display:none;" @endif >
							<div class="col-md-1 reqStar">*</div>
							<div class="col-md-11 hallRoomDiv">
								<div class="form-group label-floating">
									<label class="control-label">Room</label>
									<select name="room" id="room" value="{{ old('room') }}" class="room form-control">
										<option value=""></option>
										@foreach($radioRooms as $room)
										@foreach($room as $innerroom)
											<option value="{{$innerroom['id']}}"  @if(old('room') == $innerroom['id']) selected="selected" @endif>{{$innerroom['room_name']}}</option>
										@endforeach
										@endforeach
									</select>
									<span class="has-error">{{ $errors->patient->first('hall') }}</span>
								</div>
							</div>
							<div class=" fa-loaderRoom col-md-11" style="display: none; padding: 31px 8px;">
								<i class="fa fa-spinner fa-spin fa-3x fa-fw" style="font-size:22px;"></i>
							</div>
						</div>
						</div-->
						
						<input type="hidden" name="roomdata" id="roomdata" value="@if(!empty(old('roomdata'))){{ old('roomdata')}} @endif">
					</div>
					<div class="row">
					</div>
				<button type="submit" id="registerpatient" class="btn btn-primary pull-right">Add Patient</button>
				<div class="clearfix"></div>
			</form>
		</div>
	</div>
</div>
@endsection
@section('script')
<script>
	$(document).ready(function(){
            		/* var roomdata = jQuery('#roomdata').val();
            		if(roomdata.length == 0)
            		{
            			$('#roomDiv').hide();
						$('#hallDiv').show();
            		} else {
            			$('#roomDiv').show();
						$('#hallDiv').hide();
            		}	 
		*/
		$("#formValidate").validate({
			rules: {
				name: {
					required: true,
					minlength: 2,
					maxlength: 30,
					alphaspace: true
				},
				adhar_number: {
					
					number: true,
					rangelength: [12, 12],
				},
				crno: {
					required: true,
					minlength: 12,
					maxlength: 12,
					number: true,
				},
				mobile: {
					required: true,
					rangelength: [10, 10],
					number: true,
				},
				email: {
					email: true,
				},
				gender: {
					required: true,
				},
				room: {
					required: true,
					remote: {
							url: "{{url('/manage-patient/checkRoomDoctorInfo/')}}",  
							type: "post",
							data: {
								_token: function() { return "{{csrf_token()}}"
									}
								}
							} 
				},
				hall: {
					required: true,
					remote: {
							url: "{{url('/manage-patient/check-room-for-depart/')}}",  
							type: "post",
							data: {
								_token: function() { return "{{csrf_token()}}"
									}
								}
							} 
				}, 
				age: {
					required: true,
					number: true,
				},
				department: {
					required: true,
				}
			},
			messages: {
				name: {
					required: "Please enter name",
					minlength: "Name must consist of at least 2 characters",
					maxlength: "Max of 30 characters allowed",
					alphaspace: "Letters and space only please"
				},
				adhar_number: {
					required: "Please enter Aadhar number",
					number: "Please enter a valid Aadhar number",
					rangelength: "Aadhar number must be 12 characters long",
					remote: "Aadhar number already exists",
				},
				crno: {
					required: "Please enter CR No",
					number: "Only numbers allowed",
					minlength: "CR No must consist of at least 12 characters",
					maxlength: "Max of 12 characters allowed",
					remote: "CR No number already exists",
				},
				mobile: {
					rangelength: "Enter a valid mobile no",
					required: "Please enter mobile number",
					number: "Please enter a valid mobile number",
				},
				email: {
					email: "Please enter a valid email",
				},
				room: {
					required: "Please select room",
					remote: "No doctor alloted for the selected room",
				},
				hall: {
					required: "Please select hall",
					remote: "Either doctor or room not alloted for the selected hall",
				}, 
				gender: {
					required: "Please select gender",
				}, 
				age: {
					required: "Please enter age",
					number: "Please enter valid age",
				},department: {
					required: "Please select department"
				}
			},submitHandler: function (form) {
            		$("#registerpatient").prop("disabled", true); //disable to prevent multiple submits
            		form.submit(); 
       		 } 
		});	

	});
	function getHallForDepart(id){
		//$('.hallRoomDiv').css('display','none');
		
		var hall_status = $("#department option:selected").attr("hall-status"); 
		var selected_dept = $("#department option:selected").text();
		if((hall_status === 1 && selected_dept !== 'Pediatrics Surgeon'|| selected_dept !== 'Pediatrics') ){
			    $('.hallRoomDiv').css('display','none');
				$('.fa-loaderHall').css('display','block');
					$.ajax({
					type:'GET',
					url:'{{url("manage-rooms/get-hall/")}}/'+id,
					success:function(data){
						
						//console.log(data);return false;
						var obj = jQuery.parseJSON(data);
						$('.fa-loaderHall').css('display','none');
						$('.hallRoomDiv').css('display','block');
						$("#hall").html("<option value=''></option>");
						$("#roomdata").val('');
						if(obj.status==true){
							console.log(obj.data);
							$.each(obj.data,function(i,item){
								console.log(item);
								$("#hall").append($("<option></option>").val(item.id).html(item.name));
							});
						}
					}
				});
				$('#roomDiv').hide();
				$('#hallDiv').show();
	}else if(hall_status == 0 || selected_dept == 'Pediatrics Surgeon' || selected_dept == 'Pediatrics'){ // get rooms for selected department;
		$('.fa-loaderRoom').css('display','block');
			$.ajax({
				type:'GET',
				url:'{{url("manage-rooms/get-room-for-depart/")}}/'+id,
				success:function(data){
					//console.log(data);return false;
					var obj = jQuery.parseJSON(data);
					console.log(obj);
					$('.fa-loaderRoom').css('display','none');
					$('.hallRoomDiv').css('display','block');
					$("#room").html("<option value=''></option>");
					if(obj.status==true){
						console.log(obj.data);
						$.each(obj.data,function(i,item){
							console.log(item);
							$("#room").append($("<option></option>").val(item.id).html(item.room_name));
							$("#roomdata").val(item.room_name);
						});
					}
				}
			});
			$('#roomDiv').show();
			$('#hallDiv').hide();	
		} else {
		}
	}
	function getDoctorsByDepartment(id){
		console.log(id);
		if(id!=""){
			$.ajax({
				type:'GET',
				url:'{{url("manage-patient/get-doctors/")}}/'+id,
				success:function(data){
					var obj = jQuery.parseJSON(data);
					console.log(obj);
					//console.log(obj.status);
					$(".doctors").html("<option value=''></option>");
					if(obj.status==true){
						console.log(obj.data);
						$.each(obj.data,function(i,item){
							console.log(item);
							$(".doctors").append($("<option></option>").val(item.id).html(item.name));
						});
					}
				}
			});
		}
	}
</script>
@endsection
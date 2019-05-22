@extends('layouts.qmlayout')
@section('content')
<div class="col-md-12">
	<div class="card">
		<div class="card-content">
			<div class="card-header" data-background-color="purple">
				<h4 class="title">Manage Doctors</h4>
				<p class="category">Edit Doctor</p>
			</div>
			<form autocomplete="off" name="addPatientForm" method="post" action="{{url('/manage-doctors/update')}}" class="formValidate" id="formValidate" novalidate="novalidate">
				<div class="row">
					{{ csrf_field() }}
					<div class="col-md-4">
						<div class="col-md-1 reqStar">*</div>
						<div class="col-md-11">
							<div class="form-group label-floating">
								<label class="control-label">Doctor Name</label>
								<input type="text" name="name" value="{{ old('name', $doctorData->name) }}" maxlength="30" class="form-control">
								<input type="hidden" name="id" value="{{$doctorData->user_id}}" />
								<span class="has-error">{{ $errors->edit->first('name') }}</span>
							</div>
						</div>
					</div>
					<div class="col-md-4">
						<div class="col-md-1 reqStar">*</div>
						<div class="col-md-11">
							<div class="form-group label-floating">
								<label class="control-label">Phone Number</label>
								<input type="text" pattern="^[123456789]\d{9}$" name="phone" value="{{ old('phone', $doctorData->phone) }}" maxlength="10"  class="form-control" title="Invalid format">
								<span class="has-error">{{ $errors->edit->first('phone') }}</span>
							</div>
						</div>
					</div>
					<div class="col-md-4">
						<div class="col-md-1 reqStar">*</div>
						<div class="col-md-11">
							<div class="form-group label-floating">
								<label class="control-label">Email</label>
								<input type="email" name="email" id="email" value="{{ old('email', $doctorData->email) }}"  pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$" maxlength="50" class="form-control">
								<span class="has-error">{{ $errors->edit->first('email') }}</span>
							</div>
						</div>
					</div>
				</div>
				<div class="row">
				   <div class="col-md-4">
						<div class="col-md-1 reqStar"></div>
						<div class="col-md-11">
							<div class="form-group label-floating">
								<label class="control-label">Password</label>
								<input type="password" name="pass" id="pass" value="{{ old('pass') }}" class="form-control"  maxlength="20" autocomplete="false" readonly onfocus="this.removeAttribute('readonly');">
								<span class="has-error">{{ $errors->edit->first('pass') }}</span>
							</div>
						</div>
					</div>
					<div class="col-md-4">
						<div class="col-md-1 reqStar">*</div>
						<div class="col-md-11">
							<div class="form-group label-floating">
								<label class="control-label">Select Department</label>
								<select name="department" id="department" class="form-control">
								 <option value=""></option>
								   @foreach($departments as $department)
										  <option value="{{$department->id}}" @if ($department->id ==  old('department',$doctorData->d_id))
												selected="selected" @endif > {{$department->name}}
										  </option>
									@endforeach
								</select>
								<span class="has-error room-error">{{ $errors->add->first('department') }}</span>
							</div>
						</div>
					</div>
					<div class="col-md-4">
						<div class="col-md-1 reqStar">*</div>
						<div class="col-md-11 RoomDiv">
							<div class="form-group label-floating">
								<label class="control-label">Select Room</label>
								<select name="room" id="room" class="form-control">
								</select>
							</div>
						</div>
						<div class=" fa-loaderRoom col-md-11" style="display: none; padding: 31px 8px; ">
							<i class="fa fa-spinner fa-spin fa-3x fa-fw" style="font-size:22px;"></i>
						</div>
					</div>

				</div>
				<button type="submit" class="btn btn-primary pull-right" id="editdoctor">Update</button>
				<div class="clearfix"></div>
			</form>
		</div>
	</div>
</div>
@endsection
@section('script')
<script>
	$(document).ready(function(){
		$('#pass').val('');
		$("#pass").trigger("change");
		$("#formValidate").validate({
			rules: {
				name: {
					required: true,
					minlength: 2,
					maxlength: 30,
					alphaspace: true
				},
				phone: {
					required: true,
					number: true
				},
				department: {
					required: true,
				},
				room: {
					required: true,
				},
				email: {
					required: true,
					email: true
				},
				pass: {
					minlength: 6
				},
			},
			messages: {
				name: {
					required: "Please enter doctor name",
					minlength: "Doctor name must consist of at least 2 characters",
					maxlength: "Max of 30 characters allowed",
					alphanumeric: "Letters, numbers, and underscores only please"
				},
				phone: {
					required: "Please enter mobile number",
					number: "Please enter a numeric value",
				},
				department: {
					required: "Please select a department"
				},
				room: {
					required: "Please select a room"
				},
				email: {
					required: "Please enter email address",
					email: "Please enter a valid email address"
				},
				pass: {
					minlength:"password length should be at least 6 characters"
				}
			},submitHandler: function (form) {
            		$("#editdoctor").prop("disabled", true); //disable to prevent multiple submits
            		form.submit(); 
       		 } 
		});
		if($('#department').val() != 'null')
		{
			var selectRoom = {{$doctorData->room_id}};
			var id = $('#department').val();
			getRoomsByDepartment(id,selectRoom);
		}
		$('#department').on('change',function(){
			var id = $(this).val();
			var selectRoom = '0';
			var oldDepartId = {{$doctorData->department_id}};
			if(oldDepartId == id)
				selectRoom = {{$doctorData->room_id}};
			$('.RoomDiv').css('display','none');
			$('.fa-loaderRoom').css('display','block');
			getRoomsByDepartment(id,selectRoom);
		});
		function getRoomsByDepartment(id, selectRoom){
			if(id!=""){
				$.ajax({
					type:'GET',
					url:'{{url("manage-patient/get-room/")}}/'+id+'/'+selectRoom,
					success:function(data){
						
						var obj = jQuery.parseJSON(data);
						$('.fa-loaderRoom').css('display','none');
						$('.RoomDiv').css('display','block');
						$("#room").html("<option value=''></option>");
						if(obj.status==true) {
							$('.room-error').html('');
							$.each(obj.data,function(i,item){
								if(selectRoom == item.id)
									$("#room").append($("<option></option>").val(item.id).attr("selected","selected").html(item.room_name));
								else
									$("#room").append($("<option></option>").val(item.id).html(item.room_name));
							});
						}
						else
						{
							$('.room-error').html("No room available in this department.");
						}
						$("#room").trigger("change");
					}
				});
			}
		} 
	});
</script>
@endsection
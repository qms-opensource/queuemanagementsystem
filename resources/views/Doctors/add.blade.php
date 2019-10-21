@extends('layouts.qmlayout')
@section('style_page')
<style type="text/css">
</style>
@endsection
@section('content')
<div class="col-md-12">
	<div class="card">
		<div class="card-content">
			<div class="card-header" data-background-color="purple">
				<h4 class="title">Manage Doctors</h4>
				<p class="category">Add Doctor</p>
			</div>
			<form name="addPatientForm" method="post" action="{{ route('manage-doctors.store') }}" class="formValidate" id="formValidate" novalidate="novalidate">
				<div class="row">
					{{ csrf_field() }}
					<div class="col-md-4">
						<div class="col-md-1 reqStar">*</div>
						<div class="col-md-11">
							<div class="form-group label-floating">
								<label class="control-label">Doctor Name</label>
								<input type="text" name="name" value="{{ old('name') }}" maxlength="30" class="form-control">
								<span class="has-error">{{ $errors->add->first('name') }}</span>
							</div>
						</div>
					</div>
					<div class="col-md-4">
						<div class="col-md-1 reqStar">*</div>
						<div class="col-md-11">
							<div class="form-group label-floating">
								<label class="control-label">Mobile Number</label>
								<input type="text" pattern="^[123456789]\d{9}$" name="phone" id="phone" value="{{ old('phone') }}" maxlength="10" class="form-control" title="Invalid format">
								<span class="has-error">{{ $errors->add->first('phone') }}</span>
							</div>
						</div>
					</div>
					<div class="col-md-4">
						<div class="col-md-1 reqStar">*</div>
						<div class="col-md-11">
							<div class="form-group label-floating">
								<label class="control-label">Email</label>
								<input type="email" name="email" id="email" pattern="[a-z0-9._%+-A-Z]+@[a-z0-9.-]+\.[a-z]{2,4}$" value="{{ old('email') }}" maxlength="50" class="form-control">
								<span class="has-error">{{ $errors->add->first('email') }}</span>
							</div>
						</div>
					</div>
					
				</div>
				<div class="row">
					<div class="col-md-4">
						<div class="col-md-1 reqStar">*</div>
						<div class="col-md-11">
							<div class="form-group label-floating">
								<label class="control-label">Password</label>
								<input type="password" name="pass" id="pass" value="{{ old('pass') }}" maxlength="20" class="form-control" readonly  
									 onfocus="this.removeAttribute('readonly');">
								<span class="has-error">{{ $errors->add->first('pass') }}</span>
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
									<option value="{{$department->id}}"  @if(old('department') == $department->id) selected="selected" @endif>{{$department->name}}</option>
									@endforeach
								</select>
								<span class="has-error room-error">{{ $errors->add->first('department') }}</span>
							</div>
						</div>
					</div>
					<div class="col-md-4 hidedata" style="display:none;" >
						<!--div class="hidespinner" style="display:none;">
							<i class="fa fa-spinner fa-spin" style="font-size:24px"></i>
						</div-->
						<div class="showspinner">
						<div class="col-md-1 reqStar">*</div>
						<div class="col-md-11 RoomDiv">
							<div class="form-group label-floating">
								<label class="control-label">Select Room</label><!--i class="fa fa-spinner fa-spin" style="font-size:24px"></i-->
								<select name="room" id="room" class="form-control">
								</select>
								<span class="has-error">{{ $errors->add->first('room') }}</span>
							</div>
						</div>
						<div class=" fa-loaderRoom col-md-11" style="display: none; padding: 31px 8px; ">
							<i class="fa fa-spinner fa-spin fa-3x fa-fw" style="font-size:22px;"></i>
						</div>
					</div>
					</div>
				</div>
				<button type="submit" id="adddoctor" class="btn btn-primary pull-right">Add Doctor</button>
				<div class="clearfix"></div>
			</form>
		</div>
	</div>
</div>
@endsection
@section('script')
<script>
	$(document).ready(function(){
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
					number: true,
					rangelength: [10, 10],
				},
				department: {
					required: true,
				},
				room: {
					required: true,
				},
				email: {
					required: true,
					email:true,
					remote: {
							url: "{{url('/manage-users/check-email/')}}",  
							type: "post",
							data: {
								_token: function() { return "{{csrf_token()}}"
									}
								}
							}
				},
				pass: {
					required: true,
					minlength: 6
				},
			},
			messages: {
				name: {
					required: "Please enter doctor name",
					minlength: "Doctor name must have at least 2 characters",
					maxlength: "Maximum 30 characters are allowed",
					alphaspace: "Letters, space only please"
				},
				phone: {
					rangelength: "Please a valid mobile number",
					required: "Please enter mobile number",
					number: "Please enter a numeric value",
				},
				department: {
					required: "Please select a department"
				},
				room: {
					required: "Please select room"
				},
				email: {
					required: "Please enter email address",
					email: "Please enter a valid email address",
					remote: "Email already exists",
				},
				pass: {
					required: "Please enter password",
					minlength:"Password length should be at least 6 characters"
				}
			},submitHandler: function (form) {
            		$("#adddoctor").prop("disabled", true); //disable to prevent multiple submits
            		form.submit(); 
       		 } 
		});
		
	}); 
	 $('#department').on('change',function(){

			var id = $(this).val();
			var selectRoom = 0;
			$('.RoomDiv').css('display','none');
			$('.fa-loaderRoom').css('display','block');
			getRoomsByDepartment(id, selectRoom);
	});
	function getRoomsByDepartment(id, selectRoom){
		 $('.hidedata').show();
		 $('.hidedata').toggleClass("collapsed pressed"); //you can list several class names 
		 console.log(id);
		if(id!=""){
			$.ajax({
				type:'GET',
				url:'{{url("manage-patient/get-room/")}}/'+id+'/'+selectRoom,
				success:function(data){
					var obj = jQuery.parseJSON(data);
					console.log(obj);
					$("#room").html("<option value=''></option>");
						$('.fa-loaderRoom').css('display','none');
						$('.RoomDiv').css('display','block');
					if(obj.status==true){
						console.log(obj.data);
						$.each(obj.data,function(i,item){
							console.log(item);
							$('.room-error').html("");
							$("#room").append($("<option></option>").val(item.id).html(item.room_name));
						});
					}
					else
					{
						$('.room-error').html("No room available in this department.");
					}
				}
			});
		}
	}  
$(window).load(function() {
    $(".loader").fadeOut("slow");
});
</script>
@endsection

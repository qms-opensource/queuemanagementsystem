@extends('layouts.qmlayout')
@section('content')
<div class="col-md-12">
	<div class="card">
		<div class="card-header" data-background-color="purple">
			<h4 class="title">Patient Re-Visit Registration Form</h4>
			<p class="category">Re-Visit Form</p>
		</div>
		<div class="card-content">
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
			<form name="addPatientForm" method="post" action="{{url('/manage-patient/update')}}" class="formValidate" id="formValidate" novalidate="novalidate">
					{{ csrf_field() }}
				<input type="hidden" name="patient_id" value="{{ $patients->id }}">
				<div class="row">
					
					<div class="col-md-4">
					<div class="col-md-1 reqStar">*</div>
					<div class="col-md-11">
						<div class="form-group label-floating">
							<label class="control-label">Patient Name</label>
							<input type="text" maxlength="30" name="name" value="{{ old('hall_name', $patients->name) }}" class="form-control"->
							<span class="has-error">{{ $errors->patient->first('name') }}</span>
						</div>
					</div>
					</div>
					<div class="col-md-4">
					<div class="col-md-1 reqStar"></div>
					<div class="col-md-11">
						<div class="form-group label-floating">
							<label class="control-label">Adhar Number(xxxx-xxxx-xxxx)</label>
							<input type="text"  maxlength="12" name="adhar_number" value="{{ old('adhar_number',$patients->adhar_number) }}" class="form-control adhar_card_number">
							<span class="has-error has-error2">{{ $errors->patient->first('adhar_number') }}</span>
						</div>
					</div>
					</div>
					<div class="col-md-4">
					<div class="col-md-1 reqStar">*</div>
					<div class="col-md-11">
						<div class="form-group label-floating">
							<label class="control-label">Mobile(xxxx-xxxxxx)</label>
							<input type="text" name="phone" maxlength="10" value="{{ old('phone',$patients->phone) }}" class="form-control">
							<span class="has-error">{{ $errors->patient->first('phone') }}</span>
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
							<input type="text" name="age"  maxlength="3" value="{{ old('phone',$patients->age) }}" class="form-control">
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
							<input type="text" name="address" maxlength="3" value="{{ old('address',$patients->address) }}" class="form-control">
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
							<label class="control-label">Email address</label>
							<input type="email" name="email" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$" maxlength="50" value="{{ old('email',$patients->email) }}" class="form-control">
							<span class="has-error">{{ $errors->patient->first('email') }}</span>
						</div>
					</div>
					</div>
					<div class="col-md-4">
					<div class="col-md-1 reqStar">*</div>
					<div class="col-md-11">
						<div class="form-group label-floating">
							<label class="control-label">CR No</label>
							<input type="text" name="crno" maxlength="12" value="{{ old('crno',$patients->crno) }}" class="form-control">
							<span class="has-error">{{ $errors->patient->first('crno') }}</span>
						</div>
					</div>
					</div>
					<div class="col-md-4">
					<div class="col-md-1 reqStar">*</div>
					<div class="col-md-11">
						<div class="form-group label-floating">
							<label class="control-label">Hall</label>
							<select name="hall" value="{{ old('hall') }}" class="hall form-control">
								<option value=""></option>
								@foreach($halls as $hall)
									<option value="{{$hall->id}}"  @if(old('hall') == $hall->id) selected="selected" @endif>{{$hall->name}}</option>
								@endforeach
							</select>
							<span class="has-error">{{ $errors->patient->first('hall') }}</span>
						</div>
					</div>
					</div>
				</div>
				<div class="row">
				</div>
				<button type="submit" class="btn btn-primary pull-right">Re-Visit</button>
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
				adhar_number: {
					//required: true,
					number: true,
					rangelength: [12, 12],
				},
				crno: {
					required: true,
					minlength: 12,
					maxlength: 12,
					number: true,
					remote: {
							url: "{{url('/manage-patient/check-crno/')}}",  
							type: "post",
							data: {
								_token: function() { return "{{csrf_token()}}"
									},
								id: function() { return "{{$patients->id}}"
								}
							}
					}
				},
				phone: {
					required: true,
					maxlength: 10,
					number: true,
					remote: {
							url: "{{url('/manage-patient/check-phone/')}}",  
							type: "post",
							data: {
								_token: function() { return "{{csrf_token()}}"
									},
								id: function() { return "{{$patients->id}}"
									}
								}
							}
				},
				gender: {
					required: true,
				},email: {
					email: true,
				},
				doctor: {
					required: true,
				},
				department: {
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
				}
			},
			messages: {
				name: {
					required: "Please enter name",
					minlength: "Name must consist of at least 2 characters",
					maxlength: "Max of 30 characters allowed",
					alphaspace: "Letters, space only please"
				},
				adhar_number: {
					required: "Please enter adhar number",
					number: "Please enter a valid adhar number",
					rangelength: "Adhar number must be 12 characters long",
					remote: "Adhar number already exists",
				},
				crno: {
					required: "Please enter CR No",
					number: "Letters, numbers, and underscores only please",
					minlength: "CR No must consist of at least 12 characters",
					maxlength: "Max of 12 characters allowed",
					remote: "CR No number already exists",
				},
				phone: {
					required: "Please enter mobile number",
					number: "Please enter a valid mobile number",
					remote: "Phone number already exists",
				},
				email: {
					email: "Please enter a valid email",
				},
				doctor: {
					required: "Please select doctor",
				},
				gender: {
					required: "Please select gender",
				},
				department: {
					required: "Please select department",
					remote: "No room, doctor or hall alloted for the selected department",
				},
				age: {
					required: "Please enter age",
					number: "Please enter valid age",
				}
			}
		});
		
		$('.department').on('change',function(){
			var id = $(this).val();
			getDoctorsByDepartment(id);
		});
	});
	
	function getDoctorsByDepartment(id){
		console.log(id);
		if(id!=""){
			$.ajax({
				type:'GET',
				url:'http://st9.idsil.com/hqms/public/manage-patient/get-doctors/'+id,
				success:function(data){
					var obj = jQuery.parseJSON(data);
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
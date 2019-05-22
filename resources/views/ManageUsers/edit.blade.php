@extends('layouts.qmlayout')
@section('content')
<div class="col-md-12">
	<div class="card">
		
		<div class="card-content">
		<div class="card-header" data-background-color="purple">
			<h4 class="title">User</h4>
			<p class="category">Edit User</p>
		</div>
			<form name="manageEditrole" method="post" action="{{url('/manage-users/update')}}" class="formValidate" id="formValidate" novalidate="novalidate">
				<div class="row">
					{{ csrf_field() }}
					<div class="col-md-4">
						<div class="form-group label-floating">
							<label class="control-label">Name</label>
							<input type="text" name="name" maxlength="30" value="{{ old('name', $userData->name) }}" class="form-control">
							<span class="has-error">{{ $errors->edit->first('name') }}</span>
							<input type="hidden" name="id" value="{{$userData->id}}" />
							<input type="hidden" name="tokendata" id="tokendata" value="{{ Auth::user()->name }}" />
						</div>
					</div>
					<div class="col-md-4">
						<div class="form-group label-floating">
							<label class="control-label">Email</label>
							<input type="text" id="email" name="email" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$" maxlength="50" value="{{ old('email' , $userData->email)}}" class="form-control">
							<span class="has-error">{{ $errors->edit->first('email') }}</span>
						</div>
					</div>
					<div class="col-md-4">
						<div class="form-group label-floating">
							<label class="control-label">Role</label>
							<select name="role" value="{{ old('role') }}" class="role form-control">
								<option value=""></option>
								@foreach($roles as $role)
									<option value="{{$role->id}}" @if($userData['role_id'] == $role->id) selected="selected" @endif>{{$role->role}}</option>
								@endforeach
							</select>
							<span class="has-error">{{ $errors->edit->first('role') }}</span>
						</div>
					</div>
					</div>
				<button type="submit" id="edituser" class="btn btn-primary pull-right">Update</button>
				<div class="clearfix"></div>
			</form>
		</div>
	</div>
</div>
@endsection
@section('script')
<script>
	$(document).ready(function(){
		$("#email").mouseout(function(){
			getemailvalue();
		});
		$("#edituser").click(function(){
            getemailvalue();
		});

		function getemailvalue()
		{
			$.ajax({
				url:"{{url('/manage-users/check-login')}}",
				success:function(data){
					//alert(data);
					if(data == 'false')
					{ 
						return false;
					}else {
							$("#formValidate").validate({
							rules: {
								name: {
									required: true,
									minlength: 2,
									maxlength: 30,
									alphaspace: true
								},
								email: {
									required: true,
									email: true,
									remote: { 
										url: "{{url('/manage-users/check-email/')}}",  
											type: "post",
											data: {
												_token: function() { return "{{csrf_token()}}"
													},
												id: function() { return "{{$userData->id}}"
													}
											}
									}
											
								},
								role: {
									required: true,
								},
							},
							messages: {
								name: {
									required: "Please enter name",
									minlength: "Name must consist of at least 2 characters",
									maxlength: "Max of 30 characters allowed",
									alphaspace: "Letters, space only please"
								},
								email: {
									required: "Please enter email",
									email: "Please enter a valid email",
									remote: "Email already exists"
								},
								role: {
									required: "Please select role"
								}
							},submitHandler: function (form) {
								$("#edituser").prop("disabled", true); //disable to prevent multiple submits
								form.submit(); 
							}		 
						});
					}
				}
			}); 
		}
	}); 
</script>
@endsection
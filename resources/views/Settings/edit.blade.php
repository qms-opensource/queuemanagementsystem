@extends('layouts.qmlayout')
@section('content')
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
		<div class="card-content">
		   <div class="card-header" data-background-color="purple">
				<h4 class="title">Manage Settings</h4>
				<p class="category">Edit Setting</p>
			</div>
			<form name="managesetting" method="post" enctype="multipart/form-data" action="{{url('/manage-settings/update')}}" class="formValidate" id="formValidate" novalidate="novalidate">
			 <input type="hidden" name="settingid" value="{{$settingData->id}}">
				<div class="row">
					{{ csrf_field() }}
					<div class="col-md-4">
					<div class="col-md-1 reqStar"></div>
					<div class="col-md-11">
						<div class="form-group label-floating">
							<label class="control-label">App Logo</label>
							<input type="file" name="app_logo" maxlength="30" value="{{ old('app_logo', $settingData->logo_path) }}" class="form-control">
							<span class="has-error">{{ $errors->edit->first('app_logo') }}</span>
						</div>
					</div>
					</div>
					<div class="col-md-4">
					<div class="col-md-1 reqStar">*</div>
					<div class="col-md-11">
						<div class="form-group label-floating">
							<label class="control-label">App Name</label>
							<input type="text" name="app_display_name" id="app_display_name" value="{{ old('app_display_name', $settingData->display_name ) }}" class="department form-control"/>
							<span class="has-error">{{ $errors->edit->first('app_display_name') }}</span>
						</div>
					</div>
					</div>
					<div class="col-md-4">
					<div class="col-md-1 reqStar">*</div>
					<div class="col-md-11">
						<div class="form-group label-floating">
							<label class="control-label">App System Name</label>
							<input type="text" name="app_display_system" id="app_display_system" value="{{ old('app_display_system', $settingData->display_system ) }}" class="department form-control"/>
							<span class="has-error">{{ $errors->edit->first('app_display_system') }}</span>
						</div>
					</div>
					</div>
					</div>
					<div class="card-header" data-background-color="purple" style="margin-top:12px;
					">
						<h4 class="title">Mail Settings</h4>
					</div>
					<div class="row">
					{{ csrf_field() }}
					<div class="col-md-4">
					<div class="col-md-1 reqStar"></div>
					<div class="col-md-11">
						<div class="form-group label-floating">
							<label class="control-label">Mail User</label>
							<input type="text" name="mail_user" maxlength="30" value="{{ old('mail_user', $settingData->mail_username) }}" class="form-control">
							<span class="has-error">{{ $errors->edit->first('mail_user') }}</span>
						</div>
					</div>
					</div>
					<div class="col-md-4">
					<div class="col-md-1 reqStar"></div>
					<div class="col-md-11">
						<div class="form-group label-floating">
							<label class="control-label">Mail Password</label>
							<input type="password" name="mail_password"  autocomplete="new-password" id="mail_password" value="{{ old('mail_password') }}" class="department form-control"/>
							<span class="has-error">{{ $errors->edit->first('mail_password') }}</span>
						</div>
					</div>
					</div>
					<div class="col-md-4">
					<div class="col-md-1 reqStar"></div>
					<div class="col-md-11">
						<div class="form-group label-floating">
							<label class="control-label">Mail Encryption</label>
							<input type="text" name="mail_encryption" id="mail_encryption" value="{{ old('mail_encryption', $settingData->mail_encryption ) }}" class="department form-control"/>
							<span class="has-error">{{ $errors->edit->first('mail_encryption') }}</span>
						</div>
					</div>
					</div>
					</div>
					<div class="row">
					{{ csrf_field() }}
					<div class="col-md-4">
					<div class="col-md-1 reqStar"></div>
					<div class="col-md-11">
						<div class="form-group label-floating">
							<label class="control-label">Mail Driver</label>
							<input type="text" name="mail_driver" maxlength="30" value="{{ old('mail_driver', $settingData->mail_driver) }}" class="form-control">
							<span class="has-error">{{ $errors->edit->first('mail_driver') }}</span>
						</div>
					</div>
					</div>
					<div class="col-md-4">
					<div class="col-md-1 reqStar"></div>
					<div class="col-md-11">
						<div class="form-group label-floating">
							<label class="control-label">Mail Host</label>
							<input type="text" name="mail_host" id="mail_host" value="{{ old('mail_host',$settingData->mail_host ) }}" class="department form-control"/>
							<span class="has-error">{{ $errors->edit->first('mail_host') }}</span>
						</div>
					</div>
					</div>
					<div class="col-md-4">
					<div class="col-md-1 reqStar"></div>
					<div class="col-md-11">
						<div class="form-group label-floating">
							<label class="control-label">Mail Port</label>
							<input type="text" name="mail_port" id="mail_port" value="{{ old('mail_port',$settingData->mail_port ) }}" class="department form-control"/>
							<span class="has-error">{{ $errors->edit->first('mail_port') }}</span>
						</div>
					</div>
					</div>
					</div>
				<button type="submit" id="editsetting" class="btn btn-primary pull-right">Update</button>
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
				app_display_name: {
					required: true,
					minlength: 2,
					maxlength: 50,
					alphaspace: true
				},
				app_display_system: {
					required:true
					minlength: 2,
					maxlength: 50,
					alphaspace: true
				},
				/* mail_driver: {
					required: true,
					minlength: 2,
					maxlength: 30,
				},
				mail_port: {
					required: true,
					minlength: 2,
					maxlength: 20,
					number: true,
				},
				mail_host: {
					required: true,
					minlength: 2,
					maxlength: 20,
				},
				mail_user: {
					required: true,
					minlength: 2,
					maxlength: 30,
				},
				/* mail_password: {
					required: true,
					minlength: 2,
					maxlength: 20,
				}, */
			  /* mail_encryption: {
					required: true,
					minlength: 2,
					maxlength: 10,
				} */
			},
			messages: {
				app_display_name: {
					required: "Please enter app name",
					minlength: "Name must consist of at least 2 characters",
					maxlength: "Max of 50 characters allowed",
					alphaspace: "Letters and space only please"
				},
				app_display_system: {
					required: "Please enter App system",
					minlength: "System must consist of at least 2 characters",
					maxlength: "Max of 50 characters allowed",
					alphaspace: "Letters and space only please"
				},
			/*	mail_driver: {
					required: "Please enter Mail driver",
					minlength: "System must consist of at least 2 characters",
					maxlength: "Max of 50 characters allowed",
				},
				mail_port: {
					required: "Please enter Mail port",
					minlength: "Mail port must consist of at least 2 characters",
					maxlength: "Max of 10 characters allowed",
					number: "Numbers only please"
				},
				mail_host: {
					required: "Please enter Mail hostname",
					minlength: "Mail hostname must consist of at least 2 characters",
					maxlength: "Max of 20 characters allowed"
				},
				mail_user: {
					required: "Please enter Mail user",
					minlength: "Mail user must consist of at least 2 characters",
					maxlength: "Max of 30 characters allowed",
				},
				 mail_password: {
					required: "Please enter Mail password",
					minlength: "Mail password must consist of at least 2 characters",
					maxlength: "Max of 20 characters allowed"
				}, 
				mail_encryption: {
					required: "Please enter Mail encryption",
					minlength: "Mail encryption must consist of at least 2 characters",
					maxlength: "Max of 10 characters allowed",
				} */
			},submitHandler: function (form) {
            		$("#editsetting").prop("disabled", true); //disable to prevent multiple submits
            		form.submit(); 
       		 } 
		});	

	});
</script>
@endsection
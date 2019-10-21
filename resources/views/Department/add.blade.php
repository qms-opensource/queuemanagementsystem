@extends('layouts.qmlayout')
@section('content')
<div class="col-md-12">
	<div class="card">
		<div class="card-content">
			<div class="card-header" data-background-color="purple">
				<h4 class="title">Manage Departments</h4>
				<p class="category">Add Department</p>
			</div>
			<form name="addPatientForm" method="post" action="{{ route('department.store') }}" class="form-inline" id="formValidate" novalidate="novalidate">
				<div class="row">
					{{ csrf_field() }}
					<div class="col-md-4">
						<div class="col-md-1 reqStar">*</div>
						<div class="col-md-11">
							<div class="form-group label-floating">
								<label class="control-label">Department Name</label>
								<input type="text" id="department" name="name" maxlength="30" value="{{ old('name') }}" class="form-control" style="width:100%;">
								<span class="has-error department">{{ $errors->add->first('name') }}</span>
							</div>
						</div>
					</div>
					<div class="col-md-8" style="display:none;">
						<div class="col-md-12">
						<label class="control-label" style="font-size:14px">Link To</label>
						&nbsp;&nbsp;
							<div class="form-group label-floating">
								  <label class="radio-inline">
										<input type="radio" name="linkedtype" value="@if(!empty(old('linkedtype'))){{ old('linkedtype') }} @else block @endif" checked="checked">
										Block
								   </label>
							</div>
							   &nbsp;&nbsp;
							<div class="form-group label-floating">
									<label class="radio-inline">
										<input type="radio" name="linkedtype" value="@if(!empty(old('linkedtype'))){{ old('linkedtype') }} @else room @endif">Room
									</label>
							</div>
						</div>
					</div>
				</div>
				<button type="submit" id="adddepartment" class="btn btn-primary pull-right">Add Department</button>
				<div class="clearfix"></div>
			</form>
		</div>
	</div>
</div>
@endsection
@section('script')
<script>
	$(document).ready(function(){
		$('#department').mouseout(function(){
			$('.department').hide();
		})
		$("#formValidate").validate({
			rules: {
				name: {
					required: true,
					minlength: 2,
					maxlength: 30,
					characterSet: true
				}
			},
			messages: {
				name: {
					required: "Please enter department name",
					minlength: "Department name must consist of at least 2 characters",
					maxlength: "Max 30 characters allowed",
					characterSet: "Letters, numbers, underscores,space only please"
				}
			},submitHandler: function (form) {
            		$("#adddepartment").prop("disabled", true); //disable to prevent multiple submits
            		form.submit(); 
       		 } 
			
		});
		
	}); 
</script>
@endsection

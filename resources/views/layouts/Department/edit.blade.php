@extends('layouts.qmlayout')
@section('content')
<div class="col-md-12">
	<div class="card">
		
		<div class="card-content">
		<div class="card-header" data-background-color="purple">
			<h4 class="title">Department</h4>
			<p class="category">Add Department</p>
		</div>
			<form name="addPatientForm" method="post" action="{{url('/department/update')}}" class="formValidate" id="formValidate" novalidate="novalidate">
				<div class="row">
					{{ csrf_field() }}
					<div class="col-md-4">
					<div class="col-md-1 reqStar">*</div>
					<div class="col-md-11">
						<div class="form-group label-floating">
							<label class="control-label">Department Name</label>
							<input type="text" name="name" maxlength="70" value="{{ old('name', $departmentData->name) }}" class="form-control">
							<span class="has-error"></span>
							<span class="has-error">{{ $errors->edit->first('name') }}</span>
							<input type="hidden" name="id" value="{{$departmentData->id}}" />
						</div>
					</div>
					</div>
				</div>
				<button type="submit" class="btn btn-primary pull-right">Update Department</button>
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
					maxlength: 70,
					characterSet: true
				}
			},
			messages: {
				name: {
					required: "Please enter department name",
					minlength: "Department name must consist of at least 2 characters",
					maxlength: "Max of 30 characters allowed",
					characterSet: "Letters, numbers, space and underscores only please"
				}
			}
		});
		
	}); 
</script>
@endsection
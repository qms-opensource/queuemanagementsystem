@extends('layouts.qmlayout')
@section('content')
<div class="col-md-12">
	<div class="card">
		<div class="card-header" data-background-color="purple">
			<h4 class="title">Role</h4>
			<p class="category">Edit Role</p>
		</div>
		<div class="card-content">
			<form name="managerole" method="post" action="{{url('/manage-roles/update')}}" class="formValidate" id="formValidate" novalidate="novalidate">
				<div class="row">
					{{ csrf_field() }}
					<div class="col-md-12">
					<div class="col-md-3">
						<div class="form-group label-floating">
							<label class="control-label">Role Name</label>
							<input type="text" name="role_name" value="{{ old('role', $roleData->name) }}" class="form-control" readonly>
							<span class="has-error">{{ $errors->edit->first('role') }}</span>
							<input type="hidden" name="id" value="{{$roleData->id}}" />
						</div>
					</div>
					</div>
					<div class="col-md-12">
					<div class="col-md-3">
						<div class="form-group label-floating">
							 <div class="checkbox">
                                    <label>
                                        <input type="checkbox" name="checkall" {{ old('checkall') ? 'checked' : '' }} id="checkAll"> Check All
                                    </label>
                                </div>
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group label-floating">
							 <div class="checkbox">
                                    <label>
                                        <input class="role_check" type="checkbox" name="manage_patient" {{ old('manage_patient', $rolePrivilege->manage_patient) ? 'checked' : '' }} > Manage Patient
                                    </label>
                                </div>
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group label-floating">
							 <div class="checkbox">
                                    <label>
                                        <input class="role_check" type="checkbox" name="register_patient" {{ old('register_patient', $rolePrivilege->register_patient) ? 'checked' : '' }}>Register Patient
                                    </label>
                                </div>
						</div>
					</div>
					
					<div class="col-md-3">
						<div class="form-group label-floating">
							 <div class="checkbox">
								<label>
									<input class="role_check" type="checkbox" name="manage_doctor" {{ old('manage_doctor',$rolePrivilege->manage_doctor ) ? 'checked' : '' }}> Manage Doctors
								</label>
                            </div>
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group label-floating">
							 <div class="checkbox">
								<label>
									<input class="role_check" type="checkbox" name="manage_department" {{ old('manage_department', $rolePrivilege->manage_department) ? 'checked' : '' }}> Manage Department
								</label>
                            </div>
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group label-floating">
							 <div class="checkbox">
								<label>
									<input class="role_check" type="checkbox" name="manage_hall" {{ old('manage_hall',$rolePrivilege->manage_hall ) ? 'checked' : '' }}> Manage Halls
								</label>
                            </div>
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group label-floating">
							 <div class="checkbox">
								<label>
									<input class="role_check" type="checkbox" name="manage_room" {{ old('manage_room',$rolePrivilege->manage_room ) ? 'checked' : '' }}> Manage Room
								</label>
                            </div>
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group label-floating">
							 <div class="checkbox">
								<label>
									<input class="role_check" type="checkbox" name="summary_report" {{ old('summary_report',$rolePrivilege->summary_report) ? 'checked' : '' }}> Summary Report
								</label>
                            </div>
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group label-floating">
							 <div class="checkbox">
								<label>
									<input class="role_check" type="checkbox" name="patient_status" {{ old('patient_status',$rolePrivilege->patient_status) ? 'checked' : '' }}> Patient Status
								</label>
                            </div>
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group label-floating">
							 <div class="checkbox">
								<label>
									<input class="role_check" type="checkbox" name="manage_user" {{ old('manage_user',$rolePrivilege->manage_user) ? 'checked' : '' }}> Manage Users
								</label>
                            </div>
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group label-floating">
							 <div class="checkbox">
								<label>
									<input class="role_check" type="checkbox" name="manage_role" {{ old('manage_role',$rolePrivilege->manage_role) ? 'checked' : '' }}> Manage Role
								</label>
                            </div>
						</div>
					</div>
					
					</div>
				<button type="submit" class="btn btn-primary pull-right">Update</button>
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
				role: {
					required: true,
					minlength: 2,
					maxlength: 30,
					characterSet: true
				}
			},
			messages: {
				role: {
					required: "Please enter role name",
					minlength: "Role name must consist of at least 2 characters",
					maxlength: "Max of 30 characters allowed",
					characterSet: "Letters, numbers, space and underscores only please"
				}
			}
		});
		$('#checkAll').on('change',function(){
			if ($('#checkAll').is(":checked")){
				$('input:checkbox').not('#checkAll').prop('checked', this.checked);
			}else{
				$('input:checkbox').not('#checkAll').prop('checked',false);
			}
		});
		$('.role_check').on('change',function(){
			var checkedboxes = $(".role_check:checked").length;
			var allCheckBox = $(".role_check").length;
			if(checkedboxes == allCheckBox)
				$('#checkAll').prop('checked', true);
			else
				$('#checkAll').prop('checked', false);
		});
	}); 
</script>
@endsection
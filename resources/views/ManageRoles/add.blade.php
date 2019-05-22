@extends('layouts.qmlayout')
@section('content')
<div class="col-md-12">
	<div class="card">
		<div class="card-header" data-background-color="purple">
			<h4 class="title">Role</h4>
			<p class="category">Add Role</p>
		</div>
		<div class="card-content">
			<form name="addPatientForm" method="post" action="{{ route('manage-roles.store') }}" class="formValidate" id="formValidate" novalidate="novalidate">
				<div class="row">
					{{ csrf_field() }}
					<div class="col-md-12">
					<div class="col-md-3">
						<div class="form-group label-floating">
							<label class="control-label">Role</label>
							<select name="role" value="{{ old('role') }}" class="role form-control">
								<option value=""></option>
								@foreach($roles as $role)
									<option value="{{$role->id}}" @if(old('role') == $role->id) selected="selected" @endif>{{$role->role_name}}</option>
								@endforeach
							</select>
							<span class="has-error">{{ $errors->add->first('role') }}</span>
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
                                        <input class="role_check" type="checkbox" name="manage_patient" {{ old('manage_patient') ? 'checked' : '' }}> Manage Patient
                                    </label>
                                </div>
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group label-floating">
							 <div class="checkbox">
                                    <label>
                                        <input class="role_check" type="checkbox" name="register_patient" {{ old('register_patient') ? 'checked' : '' }}>Register Patient
                                    </label>
                                </div>
						</div>
					</div>
					
					<div class="col-md-3">
						<div class="form-group label-floating">
							 <div class="checkbox">
								<label>
									<input class="role_check" type="checkbox" name="manage_doctor" {{ old('manage_doctor') ? 'checked' : '' }}> Manage Doctors
								</label>
                            </div>
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group label-floating">
							 <div class="checkbox">
								<label>
									<input class="role_check" type="checkbox" name="manage_department" {{ old('manage_department') ? 'checked' : '' }}> Manage Department
								</label>
                            </div>
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group label-floating">
							 <div class="checkbox">
								<label>
									<input class="role_check" type="checkbox" name="manage_hall" {{ old('manage_hall') ? 'checked' : '' }}> Manage Halls
								</label>
                            </div>
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group label-floating">
							 <div class="checkbox">
								<label>
									<input class="role_check" type="checkbox" name="manage_room" {{ old('manage_room') ? 'checked' : '' }}> Manage Room
								</label>
                            </div>
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group label-floating">
							 <div class="checkbox">
								<label>
									<input class="role_check" type="checkbox" name="summary_report" {{ old('summary_report') ? 'checked' : '' }}> Summary Report
								</label>
                            </div>
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group label-floating">
							 <div class="checkbox">
								<label>
									<input class="role_check" type="checkbox" name="patient_status" {{ old('patient_status') ? 'checked' : '' }}> Patient Status
								</label>
                            </div>
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group label-floating">
							 <div class="checkbox">
								<label>
									<input class="role_check" type="checkbox" name="manage_user" {{ old('manage_user') ? 'checked' : '' }}> Manage Users
								</label>
                            </div>
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group label-floating">
							 <div class="checkbox">
								<label>
									<input class="role_check" type="checkbox" name="manage_role" {{ old('manage_role') ? 'checked' : '' }}> Manage Role
								</label>
                            </div>
						</div>
					</div>
					
					</div>
				<button type="submit" class="btn btn-primary pull-right">Add Role</button>
				<div class="clearfix"></div>
			</form>
		</div>
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
				}
			},
			messages: {
				role: {
					required: "Please enter role name",
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
		$('.role').on('change',function(){
			var id = $(this).val();
			if(id!=""){
				$.ajax({
					type:'GET',
					url:'{{url("manage-roles/checkRole/")}}/'+id,
					success:function(data){
						if(data != 'true')
							 window.location.href = '{{url("manage-roles/edit/")}}/'+data;
					}
				});
			}
		});
	}); 
</script>
@endsection
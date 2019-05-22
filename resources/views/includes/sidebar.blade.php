<div class="sidebar" data-color="purple" data-image="../assets/img/sidebar-1.jpg">
    <?php 
		$collapseOpen = false;
		$action = Route::getCurrentRoute()->getActionName();
		if($action=='App\Http\Controllers\ManageHallController@index' || $action=='App\Http\Controllers\ManageHallController@add' || $action=='App\Http\Controllers\ManageHallController@edit' || $action=='App\Http\Controllers\DepartmentController@create' || $action=='App\Http\Controllers\DepartmentController@index' || $action=='App\Http\Controllers\DepartmentController@edit' || $action=='App\Http\Controllers\ManageRoomController@create' || $action=='App\Http\Controllers\ManageRoomController@index' || $action=='App\Http\Controllers\ManageRoomController@edit' || $action=='App\Http\Controllers\DoctorController@index' || $action=='App\Http\Controllers\DoctorController@create' || $action=='App\Http\Controllers\DoctorController@edit' || $action=='App\Http\Controllers\ManageRoleController@create' || $action=='App\Http\Controllers\ManageRoleController@index' || $action=='App\Http\Controllers\ManageRoleController@edit' ||$action=='App\Http\Controllers\ManageUserController@create' || $action=='App\Http\Controllers\ManageUserController@index' || $action=='App\Http\Controllers\ManageUserController@edit' || 	$action=='App\Http\Controllers\manage-users@index' || $action=='App\Http\Controllers\DoctorController@showDoctorLink'|| $action=='App\Http\Controllers\SettingsController@edit'){
			$collapseOpen = true;
		}	
		?>
        <div class="sidebar-wrapper">
			<nav>
                <ul class="nav">
                     <?php 
					use App\User;
					use App\Setting;
					$setting_id = Setting::pluck('id')->first();
					$id = \Auth::user()->id;
						$user= User::where('id',$id)->first();
						$usertype = $user->type;
						$session_role_id = '';
						$userRoles = Session::get('user.user_roles');
						$user = Session::get('user.user');
							if(empty($user)) {
								$user = Auth::user();
								Session::put('user.user', $user);
							}
							$userID = $user['id'];								
							if(empty($userRoles)) {
								$userData = User::getUserRoleData($userID);
								$userRoles = json_decode($userData->role_privilege, true);
								Session::put('user.user_roles', $userRoles);
								Session::put('user.role_type_id', $userData->role_type_id);
							}		
					?>@if(Session::has('user.role_type_id'))
							<?php $session_role_id = Session::get('user.role_type_id');?>
						@endif
					@if($usertype == 1)
						<li class="@if(Route::getCurrentRoute()->getActionName()=='App\Http\Controllers\ManageDashboardController@showDoctorAppointment') active @endif">
	                        <a href="{{url('/manage-doctor/patient-appointment')}}">
	                            <i class="material-icons">unarchive</i>
	                            <p>Patient Appointments</p>
	                        </a>
	                    </li>
					@else
						<?php if($session_role_id == 1 || $session_role_id == 2){?>
	                   <li class="@if(Route::getCurrentRoute()->getActionName()=='App\Http\Controllers\ManagePatientController@dashboardData') active @endif">
	                         <a href="{{url('/manage-patient/dashboardData/all')}}">
	                            <i class="material-icons">dashboard</i>
	                            <p>Dashboard</p>
	                        </a>
	                    </li>
						<li class="@if(Route::getCurrentRoute()->getActionName()=='App\Http\Controllers\ManagePatientController@add' || Route::getCurrentRoute()->getActionName()=='App\Http\Controllers\ManagePatientController@edit') active @endif">
	                        <a href="{{url('/manage-patient/add')}}">
	                            <i class="material-icons">unarchive</i>
	                            <p>Register Patient</p>
	                        </a>
	                    </li>
						<?php }?>
						<?php if($session_role_id == 1 || $session_role_id == 2){?>
								<li class="@if(Route::getCurrentRoute()->getActionName()=='App\Http\Controllers\ManagePatientController@index') active @endif">
									<a href="{{url('/manage-patient')}}">
									<i class="material-icons">local_hospital</i>	
										<p>Patient Queue</p>
									</a>
								</li>
						<?php }
						if($session_role_id == 1){ 
							?>	
							
						<li>
							<a href="#" class="manageBtn" id="btn-1" data-toggle="collapse" data-target="#submenu1" aria-expanded="<?php if($collapseOpen == true) echo 'true'; else echo 'false';?>"><i class="material-icons t-material" style="margin-top: -3px;"><?php if($collapseOpen == true) echo 'indeterminate_check_box'; else echo 'add_box';?></i>Manage</a>
							<ul class="nav collapse <?php if($collapseOpen == true){ echo 'in'; } ?>" 


							id="submenu1" role="menu" aria-labelledby="btn-1">
								<?php if($session_role_id == 1){?>
								<li class="@if((Route::getCurrentRoute()->getActionName()=='App\Http\Controllers\DepartmentController@create') || ((Route::getCurrentRoute()->getActionName()=='App\Http\Controllers\DepartmentController@index')) || ((Route::getCurrentRoute()->getActionName()=='App\Http\Controllers\DepartmentController@edit'))) active @endif">
									<a href="{{url('/department')}}">
										<i class="fa fa-building t-margin-left"></i>
										<p>Departments</p>
									</a>
								</li>
								<li class="@if(Route::getCurrentRoute()->getActionName()=='App\Http\Controllers\ManageHallController@index' || Route::getCurrentRoute()->getActionName()=='App\Http\Controllers\ManageHallController@add' || Route::getCurrentRoute()->getActionName()=='App\Http\Controllers\ManageHallController@edit') active @endif">
									<a href="{{url('/manage-block/')}}">
										<i class="fa fa-bank t-margin-left"></i>
										<p>Blocks</p>
									</a>
								</li>
								<li class="@if((Route::getCurrentRoute()->getActionName()=='App\Http\Controllers\ManageRoomController@create') || ((Route::getCurrentRoute()->getActionName()=='App\Http\Controllers\ManageRoomController@index')) || ((Route::getCurrentRoute()->getActionName()=='App\Http\Controllers\ManageRoomController@edit'))) active @endif">
									<a href="{{url('/manage-rooms')}}">
										<i class="material-icons t-margin-left">room</i>
										<p>Rooms</p>
									</a>
								</li>
								  <li class="@if(Route::getCurrentRoute()->getActionName()=='App\Http\Controllers\DoctorController@index' || Route::getCurrentRoute()->getActionName()=='App\Http\Controllers\DoctorController@create' || Route::getCurrentRoute()->getActionName()=='App\Http\Controllers\DoctorController@edit') active @endif">
									<a href="{{url('/manage-doctors/')}}">
										<i class="fa fa-user-md t-margin-left"></i>
										<p>Doctors</p>
									</a>
								</li>
								 <li class="@if(Route::getCurrentRoute()->getActionName()=='App\Http\Controllers\DoctorController@showDoctorLink') active @endif">
								    <a href="{{url('/manage-doctors/showdoctorlink')}}">
										<!--i class="fa fa-user-md t-margin-left"></i-->
										<i class="fa fa-hospital-o t-margin-left" aria-hidden="true"></i>
										<p>Link doctor to room</p>
									</a>
								</li>
								<?php if($session_role_id == 1){?>
								<li class="@if((Route::getCurrentRoute()->getActionName()=='App\Http\Controllers\ManageUserController@create') || ((Route::getCurrentRoute()->getActionName()=='App\Http\Controllers\ManageUserController@index')) || ((Route::getCurrentRoute()->getActionName()=='App\Http\Controllers\ManageUserController@edit'))) active @endif">
									<a href="{{url('/manage-users')}}">
										<i class="material-icons t-margin-left">group</i>
										<p>Users</p>
									</a>
								</li>
								<li class="@if((Route::getCurrentRoute()->getActionName()=='App\Http\Controllers\SettingsController@edit')))) active @endif">
									<a href="{{url('/manage-settings/edit/'.$setting_id )}}">
										<i class="fa fa-cog  t-margin-left" aria-hidden="true"></i>
										<p>Settings</p>
									</a>
								</li>
								<?php }?>
							</ul>
						</li>
						<?php  }  ?>
	                    <li class="@if(Route::getCurrentRoute()->getActionName()=='App\Http\Controllers\ManagePatientController@patientStatus') active @endif">
	                        <a href="{{url('/manage-patient/patient-status')}}">
	                            <i class="material-icons">note_add</i>
	                            <p>Patient Status</p>
	                        </a>
	                    </li>
						<?php }?>
	                 @endif
					<li class="@if(Route::getCurrentRoute()->getActionName()=='App\Http\Controllers\ManageUserController@logout') active @endif">
	                        <a href="{{url('/logout')}}">
	                            <i class="material-icons">power_settings_new</i>
	                            <p>Logout</p>
	                        </a>

					</li>
				</ul>
			</nav>
		</div>
	</div>

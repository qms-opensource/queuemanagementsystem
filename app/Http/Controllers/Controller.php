<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Session;
use App\User;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
		public function __construct()
		{
           
		}
		public function checkUserPermissions($moduleName)
		{
			$user = Session::get('user.user');
			if(empty($user)) {
				$user = Auth::user();
				Session::put('user.user', $user);
			}
			$userID = $user['id'];
			//$userRoles = Session::get('user.user_roles');
            $userRoles = '';			
			
			if(empty($userRoles)) {
				$userData = User::getUserRoleData($userID);
				$userRoles = json_decode($userData->role_privilege, true);
				Session::put('user.user_roles', $userRoles);
				Session::put('user.role_type_id', $userData->role_type_id);
			}
			if($userRoles[$moduleName] == 1)
				return true;
			else
				return false;
		}
		public function updateSession($userID)
		{
			if(empty($userRoles))
			{
				$userData = User::getUserRoleData($userID);
				$userRoles = json_decode($userData->role_privilege, true);
				Session::put('user_roles', $userRoles);
			}
			return true;
		}
		
		public function showUnauthorizedPage1()
		{
            return view('Error/unauthorized');
        }
		
		public function showUnauthorizedPage()
		{
			$role_type_id = Session::get('user.role_type_id'); 
			if($role_type_id == 1) //superadmin
				return redirect('manage-patient/dashboardData/all');
			else if($role_type_id == 2) //Registeration
				return redirect('manage-patient/dashboardData/all');
			else if($role_type_id == 3) //doctor
				return redirect('manage-doctor/patient-appointment');
			else
				return view('Error/unauthorized');				
		}
}
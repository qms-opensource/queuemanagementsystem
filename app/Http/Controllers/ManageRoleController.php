<?php 
    namespace App\Http\Controllers;
    use Illuminate\Http\Request;
    use App\Departments;
    use App\Halls;
    use App\Rooms;
    use App\User;
    use App\RoleTypes;
    use App\Roles;
	use Illuminate\Support\Facades\Validator;
	use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
	use Illuminate\Support\Facades\Auth;
	use Illuminate\Support\Facades\Route;

    class ManageRoleController extends Controller
	{
        public function __construct()
		{
            $this->middleware('auth');
		}

		/*
		**	@function Name: view
		**	@param: NA
		**	@description: role listing
		**	@return: role list 
		**  Author Name: IDS
		*/	
        public function view()
		{
			$checkAccess = $this->checkUserPermissions('manage_role');
			if($checkAccess == false)
				return redirect('unauthorized-access');
            $roles = new Roles();
			$roles = $roles::getAllRoles();
            return view('ManageRoles.list')->with('roles',$roles); 
        }

        public function index()
		{ 
		    return $this->view();
        }

		/*
		**	@function Name: create
		**	@param: NA.
		**	@description: role add form
		**	@return: halls and departments list 
		**  Author Name: IDS
		*/
        public function create()
		{
			$checkAccess = $this->checkUserPermissions('manage_role');
			if($checkAccess == false)
				return redirect('unauthorized-access');
        	$departments = Departments::all();
			$halls = Halls::all();
			$type = new RoleTypes;
			$roles = $type->getRoleTypes();
			return view('ManageRoles.add',['departments'=>$departments,'halls'=>$halls, 'roles'=>$roles]);
        }

		/*
		**	@function Name: STORE
		**	@param: $request : form data.
		**	@description: save role data to DB
		**	@return: void 
		**  Author Name: IDS
		*/
        public function store(Request $request)
		{
			$validator = Validator::make($request->all(), [
            'role' => 'required|max:30',
		]);

        if ($validator->fails()) {
            return redirect('manage-roles/create')
                        ->withInput()->withErrors($validator,'add');
        }
            $data = $request->all();
			$saveData['role'] = $data['role'];
			$roleData = [
                'manage_patient' => isset($data['manage_patient']) ? '1' : '0',
                'register_patient' =>isset($data['register_patient']) ? '1' : '0',
                'manage_department' =>isset($data['manage_department']) ? '1' : '0',
                'manage_hall' => isset($data['manage_hall']) ? '1' : '0',
                'summary_report' =>isset($data['summary_report']) ? '1' : '0',
                'manage_room' => isset($data['manage_room']) ? '1' : '0',
                'patient_status' => isset($data['patient_status']) ? '1' : '0',
                'manage_user' => isset($data['manage_user']) ? '1' : '0',
                'manage_role' => isset($data['manage_role']) ? '1' : '0',
                'manage_doctor' => isset($data['manage_doctor']) ? '1' : '0'
            ];
			$saveData['role_privilege'] = json_encode($roleData);
			$check = Roles::create($saveData);  
			$request->session()->flash('alert-success',"Role Saved Successfully");
			return redirect('/manage-roles');			
        }
		
		/*
		**	@function Name: edit
		**	@param: role id
		**	@description: role edit form
		**	@return: role data, rolePrivilege list
		**  Author Name: IDS
		*/
		public function edit($id)
		{
			$checkAccess = $this->checkUserPermissions('manage_role');
			if($checkAccess == false)
				return redirect('unauthorized-access');
			$title = "Edit Role";
			$roles = new Roles;
			$roleData = $roles->getRoleInfoById($id);
			$rolePrivilege = json_decode($roleData->role_privilege);
			return view('ManageRoles.edit',['title'=>$title,'roleData'=>$roleData, 'rolePrivilege'=>$rolePrivilege]);
		}
		
		/*
		**	@function Name: update
		**	@param: $request : form data.
		**	@description: update role data to DB
		**	@return: void 
		**  Author Name: IDS
		*/
		public function update(Request $request){
			
			$id = $request->input('id');
			$roles = new Roles;
			$roleDataRow = Roles::find($id);
			$role = $roleDataRow['role_type_id'];
			$manage_patient = $register_patient = $manage_department = $manage_hall = $summary_report = $manage_room = $patient_status = $manage_user = $manage_role = $manage_doctor = 0;
			if(!empty($request->input('manage_patient')))
				$manage_patient = 1;
			if(!empty($request->input('register_patient')))
				$register_patient = 1;
			if(!empty($request->input('manage_department')))
				$manage_department = 1;
			if(!empty($request->input('manage_hall')))
				$manage_hall = 1;
			if(!empty($request->input('summary_report')))
				$summary_report = 1;
			if(!empty($request->input('manage_room')))
				$manage_room = 1;
			if(!empty($request->input('patient_status')))
				$patient_status = 1;
			if(!empty($request->input('manage_user')))
				$manage_user = 1;
			if(!empty($request->input('manage_role')))
				$manage_role = 1;
			if(!empty($request->input('manage_doctor')))
				$manage_doctor = 1;
			$roleData = [
				'manage_patient'=>$manage_patient,
				'register_patient'=>$register_patient,
				'manage_department'=>$manage_department,
				'manage_hall'=>$manage_hall,
				'summary_report'=>$summary_report,
				'manage_room'=>$manage_room,
				'patient_status'=>$patient_status,
				'manage_user'=>$manage_user,
				'manage_role'=>$manage_role,
				'manage_doctor'=>$manage_doctor
			];
			$update = Roles::where('id',$id)
			  ->update(['role_type_id' => $role,'role_privilege'=>json_encode($roleData),'updated_at'=>date('Y-m-d h:i:s')]);
			if($update) {
				$loggedInUser = Auth::user();
				if($loggedInUser['role_id'] == $id) {
					$this->updateSession($loggedInUser['id']);
				} 
				$request->session()->flash('alert-success',"Role Updated Successfully");
				return redirect('/manage-roles');
			} else {
				$request->session()->flash('alert-success',"Something ent wrong. Please try again later.");
				return redirect('/manage-roles');
			}
		}
		
		/*
		**	@function Name: deleteRole
		**	@param: $request : form data and delete role ID
		**	@description: delete role
		**	@return: void 
		**  Author Name: IDS
		*/
		public function deleteRole(Request $request,$id)
		{
			$checkAccess = $this->checkUserPermissions('manage_role');
			if($checkAccess == false)
				return redirect('unauthorized-access');
			$checkRole = User::checkRoleWithUser($id);
			if($checkRole == 0) {
				$role = Roles::find($id);
				$delete = $role->delete();
				if($delete) {
					$request->session()->flash('alert-success',"Role Deleted Successfully");
					return redirect('/manage-roles');
				} else {
					$request->session()->flash('alert-danger',"Something going wrong. Please try again later.");
					return redirect('/manage-roles');
				}
			} else {
				$request->session()->flash('alert-danger',"Role can not be deleted, as role associated with user(s).");
				return redirect('/manage-roles');
			}
		}
		
		/*
		**	@function Name: checkRole
		**	@param: role ID
		**	@description: check role exists
		**	@return: true|false 
		**  Author Name: IDS
		*/
		public function checkRole($id)
		{
			$role = new Roles;
			$data = $role->getRoleInfo($id);
			if(!empty($data))
				$result = $data->id;
			else
				$result = 'true';
			return $result;
		}
	}
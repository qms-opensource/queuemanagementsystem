<?php


    namespace App\Http\Controllers;
	
    use Illuminate\Http\Request;
    use App\Departments;
    use App\Halls;
    use App\User;
    use App\Rooms;
    use App\Roles;
	use Illuminate\Support\Facades\Validator;
	use Illuminate\Support\Facades\Auth;
	use Illuminate\Contracts\Mail\Mailer as MailerContract;
	use App\Mail\DoctorEmail;
	use Session;
	use Config;
	use Mail;

    class ManageUserController extends Controller 
    {

        public function __construct()
		{
             $this->middleware('auth')->except('checkUniqueEmail');
		}
		
		/*
		**	@function Name: logout
		**	@param: NA
		**	@description: logout auth user and session
		**	@return: void 
		**  Author Name: IDS
		*/
        public function logout()
		{
			Auth::logout();
			Session::flush();
			return redirect(\URL::previous());
        }
		
		/*
		**	@function Name: view
		**	@param: NA
		**	@description: user listing
		**	@return: User list 
		**  Author Name: IDS
		*/	
		public function view() {
			$checkAccess = $this->checkUserPermissions('manage_user');
			if($checkAccess == false)
				return redirect('unauthorized-access');
			$users = User::getAllUserData();
			return view('ManageUsers.list')->with('users',$users); 
        }

        public function index()
		{ 
		    return $this->view();
        }
		
		/*
		**	@function Name: create
		**	@param: NA.
		**	@description: user add form
		**	@return: roles list 
		**  Author Name: IDS
		*/
		public function create()
		{
			$checkAccess = $this->checkUserPermissions('manage_user');
			if($checkAccess == false)
				return redirect('unauthorized-access');
        	$role = new Roles;
			$roles = $role->getAllRolesData();
        	return view('ManageUsers.add',['roles'=>$roles]);
        }
		
		/*
		**	@function Name: STORE
		**	@param: $request : form data.
		**	@description: save user data to DB
		**	@return: void 
		**  Author Name: IDS
		*/
        public function store(Request $request)
		{
			$validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'role' => 'required',
            
			]);

			if ($validator->fails()) {
				return redirect('manage-users/create')
							->withInput()->withErrors($validator,'add');
			}
            $data = $request->all();
			$password = $data['password'] = $this->random_password(6);
			$saveData = [
                'role_id' => $data['role'],
                'name' => $data['name'],
				'email' => $data['email'],
				'type' => 0,
				'password' => bcrypt($password),
            ];
            $check = User::create($saveData); 
			$login_link = Config::get('constants.url_login');
            $message = "Thanks for registering with us. Kindly use these credentials for login.";
			//Mail::to($data['email'])->send(new DoctorEmail($data['name'],$data['password'],$data['email'],$message,$login_link));
			$request->session()->flash('alert-success',"User Saved Successfully");
			return redirect('/manage-users');			
        }
		
		/*
		**	@function Name: random_password
		**	@param: length of password required
		**	@description: generate a random password
		**	@return: random string 
		**  Author Name: IDS
		*/
		public function  random_password($length = 6) 
		{
   		 	$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
   			$password = substr(str_shuffle( $chars ), 0, $length );
    		return $password;
		}
		
		/*
		**	@function Name: checkLoginUser
		**	@param: NA
		**	@description: check authentication
		**	@return: authentication true|false
		**  Author Name: IDS
		*/
		public function checkLoginUser()
		{
			$loginstatus = Auth::check();
			if($loginstatus == 1)
				return 'true';
			else
				return 'false';
		}
		
		/*
		**	@function Name: edit
		**	@param: user id
		**	@description: user edit form
		**	@return: user data, roles list
		**  Author Name: IDS
		*/
		public function edit($id)
		{
			$checkAccess = $this->checkUserPermissions('manage_user');
			if($checkAccess == false)
				return redirect('unauthorized-access');
			$title = "Edit Role";
			$userData = User::find($id);
			if(!empty($userData)) {
				$role = new Roles;
				$roles = $role->getAllRolesData();
				return view('ManageUsers.edit',['roles'=>$roles,'title'=>$title,'userData'=>$userData]);
			} else {
				return redirect('/pagenotfound');
			}
		}
		
		/*
		**	@function Name: update
		**	@param: $request : form data.
		**	@description: update user data to DB
		**	@return: void 
		**  Author Name: IDS
		*/
		public function update(Request $request)
		{
			$id = $request->input('id');
			$loggedInUser = Auth::id();
			$validator = Validator::make($request->all(), [
				'name' => 'required|string|max:255',
				'email' => 'required|string|email|max:255|unique:users,email,'.$id,
				'role' => 'required',
			]);
			if ($validator->fails()) {
				return redirect('manage-users/edit/'.$id)
							->withInput()->withErrors($validator,'edit');
			}
			$name = $request->input('name');
			$email = $request->input('email');
			$role = $request->input('role');
			$userData = User::find($id);
			if(!empty($userData))
			{
				$password = $data['password'] = $this->random_password(6);
				if(strcmp($userData->email,$email) == 0) {
					$update = User::where('id',$id)
				  ->update(['name' => $name,'email'=>$email,'role_id'=>$role,'updated_at'=>date('Y-m-d h:i:s')]);
				} else {
					$update = User::where('id',$id)
				     ->update(['name' => $name,'email'=>$email,'password'=>bcrypt($password),'role_id'=>$role,'updated_at'=>date('Y-m-d h:i:s')]);
				   $login_link =  Config::get('app.url');
	            	$message = "Your credentials has changed.Kindly use these credentials for login.";
					//Mail::to($email)->send(new DoctorEmail($name,$data['password'],$email,$message,$login_link));
				}
				
				/** update Session **/ 
				if($update) {
					if($loggedInUser == $id) {
						$this->updateSession($loggedInUser);
					}
					$request->session()->flash('alert-success',"User Updated Successfully");
					return redirect('/manage-users');
				} else {
					$request->session()->flash('alert-danger',"Something went wrong. Please try again later.");
					return redirect('/manage-users');
				}
			} else {
				return redirect('/pagenotfound');
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
			$checkAccess = $this->checkUserPermissions('manage_user');
			if($checkAccess == false)
				return redirect('unauthorized-access');
			$user = User::find($id);
			if(!empty($user)) {
				$delete = $user->delete();
				if($delete) {
					$request->session()->flash('alert-success',"Record Deleted Successfully");
					return redirect('/manage-users');
				}else {
					$request->session()->flash('alert-danger',"Something going wrong. Please try again later.");
					return redirect('/manage-users');
				}
			} else {
				return redirect('/pagenotfound');
			}
			
		}
		
		/*
		**	@function Name: checkUniqueEmail
		**	@param: $request : form data
		**	@description: check email exists or not
		**	@return: email uniqueness true|false
		**  Author Name: IDS
		*/
		public function checkUniqueEmail(Request $request)
		{
			$id = NULL;
			$email = $request['email'];
			$id = $request['id'];
			$user = User::checkEmailExists($email, $id);
			if($user == 0)
				return 'true';
			else
				return 'false'; 
		}
	}
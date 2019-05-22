<?php 
    namespace App\Http\Controllers;
    
	use Illuminate\Http\Request;
    use App\Departments;
    use App\Halls;
	use App\User;
    use App\Rooms;
    use App\Patients;
    use App\RoomAllocations;
	use Illuminate\Support\Facades\Validator;
	use App\Doctors;
	use Hash;
	use App\Mail\DoctorEmail;
	use App\Http\Controllers\ManageUserController;
	use Mail;
	use Config;
	use Response;
	use DB;

	
    class DoctorController extends Controller{

        public function __construct()
		{
            $this->middleware('auth');
        }
		
		/*
		**	@function Name: view
		**	@param: NA
		**	@description: doctor listing
		**	@return: Doctor list 
		**  Author Name: IDS
		*/	
        public function index()
		{
			$checkAccess = $this->checkUserPermissions('manage_doctor');
			if($checkAccess == false)
				return redirect('unauthorized-access');
            $doctors = Doctors::getAllDoctorsAndDepartment();
			return view('Doctors.list')->with('doctors',$doctors); 
        }

		/*
		**	@function Name: create
		**	@param: NA.
		**	@description: doctor add form
		**	@return: department list 
		**  Author Name: IDS
		*/
        public function create()
		{
			$checkAccess = $this->checkUserPermissions('manage_doctor');
			if($checkAccess == false)
				return redirect('unauthorized-access');
            $departments = Departments::orderBy('id','DESC')->get();
            return view('Doctors.add')->with('departments',$departments);
        }
		
		/*
		**	@function Name: STORE
		**	@param: $request : form data.
		**	@description: save doctor data to DB
		**	@return: void 
		**  Author Name: IDS
		*/
		public function store(Request $request)
		{
			$validator = Validator::make($request->all(), [
            'name' => 'required|max:30',
            'department' => 'required',
			'phone' => 'required|numeric',
			'email' => 'required|email|unique:users,email',
			'pass' => 'required|min:6'
        ]);

        if ($validator->fails()) {
            return redirect('manage-doctors/create')
                        ->withInput()->withErrors($validator,'add');
        }
            $data = $request->all();
			$userdata = [
				'name' => $data['name'],
				'email' => $data['email'],
				'password' => Hash::make($data['pass']),
				'type' => 1,
				'role_id' => 3
				];	
			// When adding a new doctor, add the room to Room allocation table so that patient can be allocated to the room with no patient first 
			
			if(!empty($data['room'])){
				
				$room = new Rooms;
				$getRoomInfo = $room->getRoomInfoAdd($data['room']);
				//dd($data['room']);
				$roomallocationRow = RoomAllocations::getNextRoomAllocation($data['department'],$getRoomInfo->hall_id);

				 if(!empty($roomallocationRow)){
						RoomAllocations::where('id',$roomallocationRow[0]->id)
				  ->update(['department_id' => $data['department'],'hall_id' => $getRoomInfo->hall_id,'room_id'=>$data['room'], 'updated_at'=>date('Y-m-d h:i:s')]);
				} 
				
			}
            if(!empty($data['pass'])){
            	$message ="Thanks for registering with us.Kindly use these credentials for login.";
				$login_link =  Config::get('app.url');
				//Mail::to($data['email'])->send(new DoctorEmail($data['name'],$data['pass'],$data['email'],$message,$login_link));	
            }            
			$checkuser = User::create($userdata); 
			$lastuserid = $checkuser->id;
			$saveData = [
                'department_id' => $data['department'],
                'name' => $data['name'],
                'phone' => $data['phone'],
				'room_id' => $data['room'],
				'user_id' => $lastuserid
            ];	

            $check = Doctors::create($saveData); 
				if($check && $checkuser) {		
					$request->session()->flash('alert-success',"Doctors Saved Successfully");
						return redirect('/manage-doctors');	
				} else {
					$request->session()->flash('alert-danger',"Something going to be wrong.");
						return redirect('/manage-doctors');	
				}		
        }

        public function treatPatient()
		{
            return view('Doctors.treatPatients');
        }
		
		/*
		**	@function Name: edit
		**	@param: doctor id
		**	@description: doctor edit form
		**	@return: doctor data, department list
		**  Author Name: IDS
		*/
		public function edit($id)
		{
			$checkAccess = $this->checkUserPermissions('manage_doctor');
			if($checkAccess == false)
				return redirect('unauthorized-access');
            $title = "Edit Doctors";
			$departments = Departments::orderBy('id','DESC')->get();
			$doctors = new doctors;
			$doctor_exist = User::find($id);
			if(!empty($doctor_exist)) {
				$doctorData = $doctors->getDoctorInfoById($id);
				return view('Doctors.edit',['departments'=>$departments,'title'=>$title,'doctorData'=>$doctorData]);
			} else {
				return redirect('/pagenotfound');
			}
			
		}
       
       /*
		**	@function Name: showDoctorLink
		**	@param: $request : no parameter.
		**	@description: show the list of doctors and corresponding doctors
		**	@return: doctor data and room data 
		**  Author Name: IDS
		*/
		public function showDoctorLink()
		{
			$rooms = Rooms::all();
			$doctors = Doctors::get()->toArray();
			$doctorswithroom = Rooms::with('getDoctor')->get();
			$roomwithdoctordata = Rooms::with('getDoctor')->whereHas('getDoctor', function($q){
   				 $q->where('name','!=','');
			})->get()->toArray();

			return view('DoctorLinkToRoom.list',['rooms'=>$rooms,'doctors'=>$doctors,'doctorswithroom'=>$doctorswithroom]);
		}



		public function postDoctorLink(Request $request)
		{
			    $myarrayd = $request->old_data;
				$mynewarrayd = array_filter($request->room_data);
				$numItems = count(array_filter($myarrayd));
				//$numNewItems = count(array_filter($mynewarrayd));
				//$itemsCount = $numItems+$numNewItems; 
                $i = 1;
				$message = array();

				if(!empty($mynewarrayd) && sizeof($mynewarrayd) >= 1)
				{
					
					foreach($mynewarrayd as $myk=>$myv)
					{
		
						
						if(!empty($myv) && strpos($myv, '-') !== false)
						{
							
							$mn = explode(',',$myv);						
							$countdata = count($mn); 
							for($im=0;$im<$countdata;$im++)
							{
							   $mp = explode('-',$mn[$im]);
							   $s1 = $mp[0];
							   $s2 = $mp[1];
								if(!empty($myarrayd))
								{
									$k=0;
									foreach($myarrayd as $mk=>$mv)
									{
										$ms = explode('-',$mv);
										$ms1 = $ms[0];
										$ms2 = $ms[1];
										if($s1 == $ms1 && $s2 != 0)
										{
											$doctor = Doctors::where('id',$ms1)->select('name')->pluck('name')->first();
											$room = Rooms::where('id',$ms2)->select('room_name')->pluck('room_name')->first();
											$message[] = "Doctor ".$doctor." already asigned to ".$room." room";
											
										}
										$k++;
									}
								}
							
							}
							

						}
						$i++;
						if(isset($message) && !empty($message) && $k == $numItems)
						{
							$request->session()->flash('alert-danger',$message);
							return redirect('/manage-doctors/showdoctorlink');
						} else if(empty($message) && $k == $numItems) {
							if(!empty($request->room_data) && count($request->room_data) >= 1)
							{
								foreach($request->room_data as $key =>$val)
								{
									$doctor_filtered_data =  str_replace('"',"",str_replace('"]','',str_replace('["','',$val)));
									$doctor_data = explode(',',$doctor_filtered_data);
									 foreach($doctor_data as $innerkey)
									 {
										$doctor_data = explode('-',$innerkey);
										$id= $doctor_data[0];

										$room_id= !empty($doctor_data[1]) ? $doctor_data[1]: 0;
										Doctors::where('id', $id)->update(['room_id'=>$room_id]);
									 }
									
								} 
							}
							$request->session()->flash('alert-success',"Doctor has linked successfully");
							return redirect('/manage-doctors/showdoctorlink');
						
						}
						
						
						
					}
				} else {
					$request->session()->flash('alert-success',"Doctor has linked successfully");
					return redirect('/manage-doctors/showdoctorlink');
				}
				
		}
		
		/*
		**	@function Name: update
		**	@param: $request : form data.
		**	@description: update doctor data to DB
		**	@return: void 
		**  Author Name: IDS
		*/
		public function update(Request $request)
		{
			$id = $request->input('id');
			$validator = Validator::make($request->all(), [
				'name' => 'required|max:30',
	            'department' => 'required',
				'phone' => 'required|numeric',
				'email' => 'required|email|unique:users,email,'.$id,
			]);
			if ($validator->fails()) {
				return redirect('manage-doctors/edit/'.$id)
							->withInput()->withErrors($validator,'edit');
			}
			$data = $request->all();
			
			$doctor_exist = User::find($id);	
			if(!empty($doctor_exist))
			{
				$updateuser =  User::find($id); 
				$updateuser->name = $data['name'];
				$updateuser->email = $data['email'];
				if(!empty($data['pass']))
				{
				 	$updateuser->password =  Hash::make($data['pass']);
				}else if(strcmp($data['email'],$doctor_exist->email) != 0 && empty($data['pass']))
				{
					$userobj = new ManageUserController();
					$password = $userobj->random_password(6);
					$updateuser->password =  Hash::make($password);
					$data['pass'] = $password;
				}
				$updateuser->type = 1;
				$updateuser->role_id= 3;
				$updateuser->updated_at= date('Y-m-d h:i:s');
				$updateuserinfo = $updateuser->update();
                $userobj = new ManageUserController();
				if(!empty($data['pass'])){
					$message ="Your credentials has been changed Kindly use these credentials for login.";
					$login_link =  Config::get('app.url');
					//Mail::to($data['email'])->send(new DoctorEmail($data['name'],$data['pass'],$data['email'],$message,$login_link));	
				}   
				$saveData = [
	                'department_id' => $data['department'],
	                'name' => $data['name'],
	                'phone' => $data['phone'],
					'room_id' => $data['room'],
					'user_id' => $id,
					'updated_at'=>date('Y-m-d h:i:s')
	            ];			
	            $update = Doctors::where('user_id',$id)->update($saveData); 
				
				/* $update = Doctors::where('id',$id)
				  ->update(['phone' => $phone,'name'=>$name,'department_id'=>$department,'updated_at'=>date('Y-m-d h:i:s')]); */
				 
				if($updateuserinfo && $update) {
					$request->session()->flash('alert-success',"Record Updated Successfully");
					return redirect('/manage-doctors');
				} else {
					$request->session()->flash('alert-danger',"Something went wrong. Please try again later.");
					return redirect('/manage-doctors');
				}
			} else {
				return redirect('/pagenotfound');
			}
		}
		
		/*
		**	@function Name: deleteDoctor
		**	@param: $request : form data and delete doctor ID
		**	@description: delete doctor
		**	@return: void 
		**  Author Name: IDS
		*/
		public function deleteDoctor(Request $request,$id)
		{
			$checkAccess = $this->checkUserPermissions('manage_doctor');
			if($checkAccess == false)
				return redirect('unauthorized-access');
			$doctor_exist = User::find($id);
			if(!empty($doctor_exist)) {
				$roomid = Doctors::where('user_id',$id)->pluck('room_id');
				$patientdata = Patients::where('room_id',$roomid[0])->whereDate('created_at', '=', date('Y-m-d'))->exists();

				if($patientdata == 1) {
					$request->session()->flash('alert-danger',"There are some patients associated with this doctor, so you are not allowed to delete until patients associated to that doctor.");
								return redirect('/manage-doctors');
				}
				$userdelete = User::find($id)->delete();
	            $doctordelete = Doctors::where('user_id',$id)->delete();
				if($doctordelete && $userdelete) {
					$request->session()->flash('alert-success',"Record Deleted Successfully");
					return redirect('/manage-doctors');
				}else {
					$request->session()->flash('alert-danger',"Something went wrong. Please try again later.");
					return redirect('/manage-doctors');
				}
			} else {
				return redirect('/pagenotfound');
				
			}
		}
		
		/*
		**	@function Name: linkDoctorWithRoom
		**	@param: $request : doctor and room ID
		**	@description: Associate room with doctor
		**	@return: void 
		**  Author Name: IDS
		*/
		public function linkDoctorWithRoom($asd = null,$asds = null){
			$rooms = Rooms::all();
			$doctorswithroom = $doctors = Doctors::all();
			$roomWithDoc = '';
			foreach($doctorswithroom as $doc){
				if( $doc['room_id'] != 0){
					$roomWithDoc = $roomWithDoc.'*'.$doc['id'];
				}
			}
			return view('DoctorLinkToRoom.list_relation',['rooms'=>$rooms,'doctors'=>$doctors,'roomWithDoc'=>$roomWithDoc]);
		}
		public function findDocInfo($roomID, $doc){
			$docInfo = Doctors::find($doc);
			//echo '<pre>'; print_r($docInfo);die;
			if($roomID == $docInfo['room_id'])
				echo json_encode(['status' => false]);
			else
				echo json_encode(['status' => true]);
		}
		public function updateRoomDoc($roomID, $doc, $status){
			$oldRoomID = 0;
			if($status == 1){
				$docInfo = Doctors::find($doc);
				$oldRoomID = $docInfo['room_id']; 
			}
			if($doc == 0){
				$update = Doctors::where('room_id',$roomID)->update(['room_id' => 0]);
			}else{
				
				$roomEarlierDoc = Doctors::where('room_id', $roomID)->first(); 
				if(!empty($roomEarlierDoc)){
					$update = Doctors::where('room_id',$roomID)->update(['room_id' => 0]);
				}
				$roomInfo = Rooms::find($roomID);
				$update = Doctors::where('id',$doc)->update(['room_id' => $roomID, 'department_id'=>$roomInfo['department']]);
			}
			//echo $roomID;echo $doc;die('herer');
			
		//	$update = Doctors::where('id',$doc)->update(['room_id' => $roomID]);
			$doctorswithroom = Doctors::all();
			$roomWithDoc = '';
			foreach($doctorswithroom as $doc){
				if( $doc['room_id'] != 0){
					$roomWithDoc = $roomWithDoc.'*'.$doc['id'];
				}
			}
			$rooms = Rooms::all();
			$doctorswithroom = $doctors = Doctors::all();
			$roomWithDoc = '';
			foreach($doctorswithroom as $doc){
				if( $doc['room_id'] != 0){
					$roomWithDoc = $roomWithDoc.'*'.$doc['id'];
				}
			}
			echo json_encode(['status' => true, 'data' => $roomWithDoc, 'oldRoomID'=>$oldRoomID, 'rooms'=>$rooms,'doctors'=>$doctors,'roomWithDoc'=>$roomWithDoc]);
		}
    }
?>
<?php 

	namespace App\Http\Controllers;
	
	use Illuminate\Http\Request;
	use Illuminate\Support\Facades\Validator;
	use Illuminate\Support\Facades\Auth;
	use App\Departments;
	use App\Doctors;
	use App\Halls;
	use App\Patients;
	use App\Rooms;
	use App\ReservedRoomToken;
	use App\User;
	use Twilio;
	use Config;


    class ManageRoomController extends Controller
	{

        public function __construct()
		{
			$this->middleware('auth');
		}
		
		/*
		**	@function Name: view
		**	@param: NA
		**	@description: room listing
		**	@return: Room list 
		**  Author Name: IDS
		*/	
        public function view()
		{
			$checkAccess = $this->checkUserPermissions('manage_room');
			if($checkAccess == false)
				return redirect('unauthorized-access');
	
            $rooms = Rooms::getAllRoomsAndDepartment();
            return view('Room.list')->with('rooms',$rooms); 
        }
		
		/*
		**	@function Name: getRoomsByDepartment
		**	@param: $department_id, room_id
		**	@return: room info 
		**  Author Name: IDS
		*/
		public function getRoomsByDepartment($id, $room_id = null)
		{
			$data = [];
			$rooms = new Rooms;
			$data = $rooms->getRoomsNotAllotedToDoctor($id);
			if($room_id != null && $room_id != 0) {
				$dataInfo = $rooms->getRoomData($room_id);
				$data = array_merge($dataInfo, $data);
			}
			if($data) {
				echo json_encode(['selectRoom'=>$room_id, 'status' => true, 'data' => $data]);
			}else {
				echo json_encode(['status' => false]);
			}
		}
		
		/*
		**	@function Name: index
		**	@param: NA
		**	@description: room listing
		**	@return: Room list 
		**  Author Name: IDS
		*/	
        
        public function index()
		{ 
		    return $this->view();
        }

		/*
		**	@function Name: create
		**	@param: NA.
		**	@description: room add form
		**	@return: department list, hall list 
		**  Author Name: IDS
		*/
        public function create()
		{
			$checkAccess = $this->checkUserPermissions('manage_room');
			if($checkAccess == false)
				return redirect('unauthorized-access');
			$departments = Departments::orderBy('id','DESC')->get();
			$halls = Halls::all();
			return view('Room.add',['departments'=>$departments,'halls'=>$halls]);
        }

		/*
		**	@function Name: STORE
		**	@param: $request : form data.
		**	@description: save room data to DB
		**	@return: void 
		**  Author Name: IDS
		*/
        public function store(Request $request)
		{
			if(isset($request->hall)) {
				$validator = Validator::make($request->all(), [
				'department' => 'required',
				'room_name' => 'required|max:30|unique:rooms',
				'hall' => 'required',
				]);
			} else {
				$validator = Validator::make($request->all(), [
				'department' => 'required',
				'room_name' => 'required|max:30|unique:rooms',
				'capacity' =>'required|numeric|min:1'
				]);
			}
			if ($validator->fails()) {
				return redirect('manage-rooms/create')
							->withInput()->withErrors($validator,'add');
			}
            $data = $request->all();
			$department = Departments::where('id',$data['department'])->first()->name;
			$Pediatrics = config('app.d1');
			$Surgen = config('app.d2');
		/*	if($department == $Pediatrics || $department == $Surgen)
			{
				$prfix = $roomname = $data["room_name"];
				$x = 'A';
			    if(Rooms::where('room_name','like',"%{$roomname}%")->exists())
				{
					$room_db_name = Rooms::where('room_prefix',$roomname)->latest()->first()->room_name;
					$room_part = explode('-',$room_db_name);
					$room_part_first = $room_part[0];
					$room_part_second = $room_part[1];
					$room_part_second++;
					$x = $room_part_second;
				}					 
				$saveData = [
                'department' => $data['department'],
                'room_prefix' => $prfix,
                'room_name' => isset($room_part_first) ? $room_part_first."-".$x : $roomname.'-'.$x,
                'hall' => isset($data['hall']) ? $data['hall']:''
                 ];
				
			} 
		*/
			if(isset($data['capacity']) && !empty($data['capacity']))
			{
				$savehall = [
					'department_id' => $data['department'],
					'name' => $data['room_name'],
					'capacity' => $data['capacity'],
					'Status' => 0
				];
				
			
				$check = Halls::create($savehall); 
			}
            $saveNormalData = [
                'department' => $data['department'],
                'room_name' => $data['room_name'],
                'hall' => isset($data['hall']) ? $data['hall']: $check->id
            ];
			$Pediatrics = config('app.d1');
			$Surgen = config('app.d2');
			if(!empty($saveData))
				Rooms::create($saveData);
		    else
				Rooms::create( $saveNormalData);
			$request->session()->flash('alert-success',"Room Saved Successfully");
			return redirect('/manage-rooms');			
        }
		
		/*
		**	@function Name: edit
		**	@param: room id,hall_id.
		**	@description: room edit form
		**	@return: room data, department list, hall list. 
		**  Author Name: IDS
		*/
		public function edit($id,$hall_id = null)
		{
			$checkAccess = $this->checkUserPermissions('manage_room');
			if($checkAccess == false)
				return redirect('unauthorized-access');
			$title = "Edit Patient";
			$roomid = Rooms::find($id);
			if(empty($roomid)) {
				return redirect('/pagenotfound');
			}
			$departments = Departments::orderBy('id','DESC')->get();
			$halls = Halls::where('Status',1)->where('id',$hall_id)->get();
			$departmentdata =  Departments::where('id',$roomid->department)->first();
			$hallid = Halls::find($hall_id);
			if(!empty($hallid) && $hallid->Status == 0) {
				$roomData = Rooms::where('hall',$hall_id)->first();
				$hallData = Halls::find($hall_id);
			}else {
				$roomData = Rooms::find($id);
				if(!empty($hallid))
				$hallData = Halls::find($hall_id);
			    else
				$hallData = '';
				if(empty($halldata)) {
					$halls = Halls::where('Status',1)->where('department_id',$roomid->department)->get();
				}
			}
			if(!empty($roomData)) {
				return view('Room.edit',['departments'=>$departments,'title'=>$title,'halls'=>$halls,'roomData'=>$roomData,'departmentData'=>$departmentdata,'hallData'=>$hallData]);
			} else {
				return redirect('/pagenotfound');
			}
		}
		
		/*
		**	@function Name: update
		**	@param: $request : form data.
		**	@description: update room data to DB
		**	@return: void 
		**  Author Name: IDS
		*/
		public function update(Request $request)
		{
			$id = $request->input('id');
			$hall_id = $request->input('hall_id');
			$department = $request->department;
			$department_id = Departments::where('id',$department)->first();
			if(!empty($department_id) && $department_id->add_hall == 0) {
				$validator = Validator::make($request->all(), [
					'department' => 'required',
					'room_name' => 'required|max:30|unique:rooms,room_name,'.$id,
					'capacity1' =>'required|numeric|min:1',
				]);
			} else if(!empty($request->halldata) ) {
				$validator = Validator::make($request->all(), [
					'department' => 'required',
				    'room_name' => 'required|max:30|unique:rooms,room_name,'.$id,
				    'hall' => 'required',
				]);
			} else if(empty($request->halldata)) {
				$validator = Validator::make($request->all(), [
					'department' => 'required',
					'room_name' => 'required|max:30|unique:rooms,room_name,'.$id,
				]);
			}
			if ($validator->fails()) {
				return redirect('manage-rooms/edit/'.$id.'/'.$hall_id)
							->withInput()->withErrors($validator,'edit');
			}
			if(isset($request->capacity1) && !empty($request->capacity1)) {
					$patient = Patients::where('room_id',$id)->exists();
					if($patient == 1)
					{
						$request->session()->flash('alert-danger',"We can not change room untill patient be empty.");
						return redirect('/manage-rooms');
					}
			}
			$roomid = Rooms::where('id',$id)->first();
			$hallmatchid = $roomid->hall;
			$halldata =  Halls::where('id',$hallmatchid)->first();
			$hall_current_status = $request->hall_status;
			if($department_id->add_hall == 0)
			{
				$halls =new Halls();
				$capacity = $request->input('capacity1');

				$room_name = $request->input('room_name');
				$hallUpdate =['name'=>$room_name,'capacity'=>$capacity,'department_id'=> $department,'Status'=>0];
				if(empty($halldata) && $hall_current_status == 0 || $hall_current_status == 0)
				{
					$halldatanew = Halls::create($hallUpdate);
					$hall =$halldatanew->id;
				} else if(!empty($hallmatchid) && empty($hall_current_status)){
				   Halls::find($hallmatchid)->update($hallUpdate); 
				}

			}else if($department_id->add_hall == 1)
			{
				if($hall_current_status == 1)
				{
					Halls::find($hall_id)->delete();
				}
				$hall = $request->input('hall');
			}
				$room_name = $request->input('room_name');
				$roomData = Rooms::where('id',$id)->exists();
			if($roomData == 1)
			{
			 $Pediatrics = config('app.d1');
			$Surgen = config('app.d2');
			if($department_id->name == $Pediatrics || $department->name == $Surgen)
			{
				$x = 'A';
			  /*  if(Rooms::where('room_name','like',"%{$room_name}%")->exists())
				{
					$room_db_name = Rooms::where('room_name','like',"%{$room_name}%")->latest()->first()->room_name;
					$room_part = explode('-',$room_db_name);
					$room_part_first = $room_part[0];
					$room_part_second = $room_part[1];
					$room_part_second++;
					$x = $room_part_second;
				}	*/				 
				$saveDataForSurgeP = [
                'department' => $department,
                'room_name' => $room_name,
                'hall' => $hall,
                'updated_at'=>date('Y-m-d h:i:s')
                 ];
				
			}  
			    $saveData = ['room_name' => $room_name,'hall'=>$hall,'department'=>$department,'updated_at'=>date('Y-m-d h:i:s')];
			    if(!empty($saveDataForSurgeP))
			    {
			    	$update = Rooms::where('id',$id)
				  	->update($saveDataForSurgeP);
				 
				 } else {
				 	$update = Rooms::where('id',$id)
				  	->update($saveData);
				 }
					
				if($update){
					$request->session()->flash('alert-success',"Room Updated Successfully");
					return redirect('/manage-rooms');
				}else{
					$request->session()->flash('alert-danger',"Something went wrong. Please try again later.");
					return redirect('/manage-rooms');
				}
			} else {
				return redirect('/pagenotfound');
			}
				
		}
		
		/*
		**	@function Name: deleteRoom
		**	@param: $request : form data and delete room ID
		**	@description: delete room
		**	@return: void 
		**  Author Name: IDS
		*/
		public function deleteRoom(Request $request,$id)
		{
		
			$checkAccess = $this->checkUserPermissions('manage_room');
			if($checkAccess == false)
				return redirect('unauthorized-access');
			$patientdata = Patients::where('room_id',$id)->whereDate('created_at', '=', date('Y-m-d'))->exists();
			if($patientdata == 1) {
				$request->session()->flash('alert-danger',"There are some patients associated with this room, so you are not allowed to delete until patients associated to that room.");
					return redirect('/manage-rooms');
			}
			$room = Rooms::find($id);
			if(!empty($room)) {
				$delete = $room->delete();
				if($delete){
					$request->session()->flash('alert-success',"Room Deleted Successfully");
					return redirect('/manage-rooms');
				}else {
					$request->session()->flash('alert-danger',"Something going wrong. Please try again later.");
					return redirect('/manage-rooms');
				}
			} else {
				return redirect('/pagenotfound');
			}
			
		}
		
		/*
		**	@function Name: getHallForDepartment
		**	@param: department id
		**	@description: get halls for the selected department
		**	@return: hall data 
		**  Author Name: IDS
		*/
		public function getHallForDepartment($id)
		{
			$data = Halls::where('department_id', '=', $id)->where('Status',1)->orderBy('id', 'DESC')->get();
			if($data) {
				echo json_encode(['status' => true, 'data' => $data]);
			}else {
				return response()->json(['status' => false]);
			}
		}
		
		/*
		**	@function Name: getRoomForDepartment
		**	@param: department id
		**	@description: get rooms for the selected department
		**	@return: void 
		**  Author Name: IDS
		*/
		public function getRoomForDepartment($id)
		{
			$data = Rooms::where('department', '=', $id)->orderBy('id', 'DESC')->get();
			if($data) {
				echo json_encode(['status' => true, 'data' => $data]);
			}else {
				echo json_encode(['status' => false]);
			}
		}
		
		/*
		**	@function Name: updateStatus
		**	@param: form data, room id, status
		**	@description: update room status
		**	@return: void 
		**  Author Name: IDS
		*/
		public function updateStatus(Request $request,$id, $status){
			
			if($status == 0)
				$sts = 1;
			else
				$sts = 0;
			$checkAccess = $this->checkUserPermissions('manage_room');
			if($checkAccess == false)
				return redirect('unauthorized-access');
			
			$update = Rooms::where('id',$id)
			  ->update(['status' => $sts]);
			 
			if($update) {
				$request->session()->flash('alert-success',"Room Updated Successfully");
				return redirect('/manage-rooms');
			}else{
				$request->session()->flash('alert-success',"Something went wrong. Please try again later.");
				return redirect('/manage-rooms');
			}
		}
		
	}
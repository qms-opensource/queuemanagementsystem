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
use App\RoomAllocations;
use App\ReservedRoomToken;
use App\User;
use Session;
use Twilio;
use Config;

class ManagePatientController extends Controller
{
	public function __construct(){
		$this->middleware('auth')->except(['checkRoomDoctorInfo','checkRoomForDepart']);
	}
	
	/*
	**	@function Name: index
	**	@param: $request : form data.
	**	@description: patient listing
	**	@return: patient listing
	**  Author Name: IDS
	*/
    public function index()
	{
		$checkAccess = $this->checkUserPermissions('manage_patient');
		if($checkAccess == false)
			return redirect('unauthorized-access');
            $title = "Patients List";
		$patients = Patients::getAllPatients();
		return view('ManagePatients.list')->with('title',$title)->with('patients',$patients);
	}
	
	/*
	**	@function Name: add
	**	@param: NA
	**	@description: Register patient.
	**	@return department and hall data
	**  Author Name: IDS
	*/
	
	public function add()
	{
		/* check if user has add permissions */ 
		$checkAccess = $this->checkUserPermissions('manage_patient');
		if($checkAccess == false)
			return redirect('unauthorized-access');
		/* to show radiology selected by default  */
		$selectedID = Config::get('constants.RADIOLOGY');
		$departmentswithonlyroom = Departments::where('add_hall',0)->pluck('id');
		$did = $departmentswithonlyroom;
		 $radioRoom['data'] = array();
		foreach($did as $firstd)
		{
         $radioRoom['data'][] = Rooms::where('department',$firstd)->get()->toArray();
		}
		$radioOriginalRoom['data']= Rooms::where('department',$selectedID)->get()->toArray();
		$finalarray =array();
		$finalarray = $radioRoom['data'];
		$departments = Departments::getAllDepartmentWithRoom();
		$doctors = Doctors::all();
		$halls = Rooms::getAllAvailableRooms();
		$patients = Patients::all();
		return view('ManagePatients.addPatient',['departments'=>$departments,'doctors'=>$doctors,'halls'=>$halls,'patients'=>$patients,'radioRooms'=>$finalarray, 'selectedID'=>$selectedID,'radioOriginalRooms'=>$radioOriginalRoom['data']]);
	}
	
	public function edit($id)
	{
		$checkAccess = $this->checkUserPermissions('manage_patient');
		if($checkAccess == false)
			return redirect('unauthorized-access');
		$title = "Edit Patient";
		$departments = Departments::all();
		$doctors = Doctors::all();
		$halls = Halls::all();
		$patients = Patients::find($id);
		$doctorsInfo = $this->getDoctorsByDepartmentInEdit($patients->department_id);
		return view('ManagePatients.editPatient',['departments'=>$departments,'title'=>$title,'doctors'=>$doctors,'halls'=>$halls,'patients'=>$patients,'doctorsInfo'=>$doctorsInfo]);
	}

	public function checkAdhar(Request $request)
	{
		$adhar = $request->adharVal;
		
		$checkAdhar = Patients::checkAdharAlreadyExist($adhar);
		if($checkAdhar){
			return json_encode(['status' => true]);
		}else{
			return json_encode(['status' => false]);
		}
	}
	
	public function checkUniqueAdhar(Request $request)
	{
		$id = $request['id'];
		$adhar = $request['adhar_number'];
		$checkAdhar = Patients::checkAdharExist($adhar, $id);
		if($checkAdhar == 0)
			return 'true';
		else
			return 'false';
	}
	
	/*
	**	@function Name: checkUniqueCrno
	**	@param: $request : form data.
	**	@description: check unique Cr no.
	**	@return: status:true|false
	**  Author Name: IDS
	*/
	public function checkUniqueCrno(Request $request)
	{
		$id = $request['id'];
		$crno = $request['crno'];
		$checCrno = Patients::checkCrnoExist($crno, $id);
		if($checCrno == 0)
			return 'true';
		else
			return 'false';
	}
	
	/*
	**	@function Name: checkUniquePhone
	**	@param: $request : form data.
	**	@description: check unique phone no.
	**	@return: status:true|false
	**  Author Name: IDS
	*/
	
	public function checkUniquePhone(Request $request)
	{
		$phone = $request['phone'];
		$id = $request['id'];
		$checkPhone = Patients::checkPhoneAlreadyExist($phone, $id);
		if($checkPhone == 0)
			return 'true';
		else
			return 'false';
	}
	
	/*
	**	@function Name: checkRoomForDepart
	**	@param: $request : form data.
	**	@description: check if room is available for selected department
	**	@return: status:true|false
	**  Author Name: IDS
	*/
	
	public function checkRoomForDepart(Request $request)
	{
		$room = new Rooms;
		$hallId = $request['hall'];
		$hall = new Halls;
		$hallInfo = $hall->getHallInfo($hallId);
		$departID = $hallInfo->department_id;
		$roomCount = $room->getAllRoomsByDepartment($departID, $hallId);
		if(count($roomCount) == 0)
			return 'false';
		else
			return 'true';
	}
	
	public function tokensInfo()
	{
		//$this->allTokenStatus
	}
	
	
	public function update(Request $request)
	{
		$patientId = $request->input('patient_id');
		$validator = Validator::make($request->all(), [
            'name' => 'required|max:30',
			'phone' => 'required|max:14|unique:patients,phone,'.$patientId,
			'age' => 'required',
			'crno' => 'required|numeric|unique:patients,crno,'.$patientId,
			 ]);
		
			if ($validator->fails()) {
				return redirect('manage-patient/edit/'.$patientId)
							->withInput()->withErrors($validator,'patient');
			}
		$roomAllocation	 = new RoomAllocations;
		$room	 = new Rooms;
		$patient = new Patients;
		$departID = $request->input('department');
		$allRooms = $room->getAllRoomsByDepartment($departID);
		if(count($allRooms) == 0){
			$request->session()->flash('alert-failure',"No room, doctor or hall alloted for the selected department.");
			return redirect('manage-patient/add/')
							->withInput()->withErrors($validator,'patient');
		}
		$patientRoomCount = $patient->getRoomCountByDepartment($departID);
		$roomTobeAllocatedNowRec = $roomAllocation->getNextRoomAllocation($departID);
		if(count($allRooms) == 1){
			$nextRoomTobeAlloted = $room_id = $allRooms[0]->id;
		}else{
			
			foreach($allRooms as $key=> $roomRec){
				if(!empty($roomTobeAllocatedNowRec)){
					$roomTobeAllocatedNow = $roomTobeAllocatedNowRec->room_id;
					if($roomTobeAllocatedNow == $roomRec->id){
						if($key+1 == count($allRooms))
							$nextRoomTobeAlloted = $allRooms[0]->id;
						else
						$nextRoomTobeAlloted = $allRooms[$key+1]->id;
						$room_id = $roomRec->id;
						break;
					}
				}else{
					$room_id = $roomRec->id;
					$nextRoomTobeAlloted = $allRooms[$key+1]->id;
					break;
				}
			
			}
			if(count($patientRoomCount) >1){
				$arrRoom = [];
				foreach($patientRoomCount as $prCount){
					$arrRoom[$prCount->room_id] = $prCount->patient_count;
				}
				$roomWithMinPatient = array_search(min($arrRoom), $arrRoom); 
				if($arrRoom[$roomWithMinPatient] < $arrRoom[$room_id])
					$room_id  = $roomWithMinPatient;
			}
		}
		
		if(!empty($roomTobeAllocatedNowRec)){
			$update = RoomAllocations::where('id',$roomTobeAllocatedNowRec[0]->id)
			  ->update(['department_id' => $departID,'room_id'=>$nextRoomTobeAlloted, 'updated_at'=>date('Y-m-d h:i:s')]);
		}else{
			$roomAllocation->department_id = $departID;
			$roomAllocation->room_id = $nextRoomTobeAlloted;
			$insertRoomAll = $roomAllocation->save();
		}
		$token = $this->createToken($departID);
		$reservedroomtoken = new ReservedRoomToken;
		$tokenReservedData = $reservedroomtoken->getReservedTokenForDepart($departID);
		if($patient->age >= 60 && !empty($tokenReservedData)){
			$token = $tokenReservedData[0]->token;
			// Delete Token
			$deleteTokenData = ReservedRoomToken::find($tokenReservedData[0]->id);
			$delete = $deleteTokenData->delete();
		}else if($patient->age < 60 && $token%4 == 0){
			$reservedroomtoken->department_id = $departID;
			$reservedroomtoken->token = $token;
			$insertToken = $reservedroomtoken->save();
			if($insertToken)
				$token = $token+1;
		}
		$patients = Patients::find($patientId);
		
		$deviceId = $patients->device_id;
		$patient = new Patients;
		$name = $request->input('name');
		$adhar_number = $request->input('adhar_number');
		$phone = $request->input('phone');
		$email = $request->input('email');
		$gender = $request->input('gender');
		$department_id = $request->input('department');
		$crno = $patient->crno = $request->input('crno');
		
		$room_id = $patients->room_id;
		$age = $request->input('age');
		$address = $request->input('address');
		$token = $this->createToken($request->input('doctor'),$request->input('department'));
		$patient->token = $token;
		$departments = Departments::find($request->input('department'));
		$update = Patients::where('id',$patients->id)
			  ->update(['name' => $name,'gender' => $gender,'crno'=>$crno,'token'=>$token,'address'=>$address,'age'=>$age,'room_id'=>$room_id,'department_id'=>$department_id,'email'=>$email,'adhar_number'=>$adhar_number,'phone'=>$phone,'updated_at'=>date('Y-m-d h:i:s')]);
		if($update){
			$title = "HQMS";
			$body = "You are registered successfully. Your token is ".$token.". CR_No is ".$crno.". You have to visit the Department ".$departments->name.". Floor ".$departments->floor." Hall No is ".$request->input('hall')." and Room no is. ".$departments->room_no;
			//Twilio::message("+91".$request->input('phone'),$body);
			if(!empty($deviceId)):
				$this->sendNotification($title,$body,$deviceId);
			endif;
			$request->session()->flash('alert-success',"Patient revisit form updated successfully");
			return redirect('/manage-patient');
		}else{
			$request->session()->flash('alert-success',"Something going wrong. Please try again later.");
			return redirect('/manage-patient');
		}
	}
	
	public function save(Request $request)
	{
		$messages = ['crno.required'=>'CR No field is required','crno.numeric'=>'CR No field should contain numeric digits'];
		$validator = Validator::make($request->all(), [
            'name' => 'required|max:30',
            'gender' => 'required',
			'mobile' => 'required|max:10',
			'age' => 'min:0|required|numeric',
			'crno' =>'required|numeric'
        ],$messages);
        if ($validator->fails()) {
            return redirect('manage-patient/add/')
                        ->withInput()->withErrors($validator,'patient');
        }
		$insert = '';
		if(!empty($request->department)){
			$departInfo = Departments::find($request->department);
			if(($departInfo->add_hall == 1 && ( strcmp($departInfo->name,'Surgen') == 1 || strcmp($departInfo->name,'Pediatrics') == 1 )) || $departInfo->add_hall == 1){
				$hallId = $request->input('hall');
				$hall = new Halls;
				$departID = $request->department;
				$patient = new Patients;
				$room = new Rooms;
				$patient->name = trim($request->input('name'));
				$patient->adhar_number = !empty($request->input('adhar_number')) ? $request->input('adhar_number'): null;
				$patient->phone = $request->input('mobile');
				$patient->gender = $request->input('gender');
				$patient->email = !empty($request->input('email')) ? $request->input('email'): null;
				$patient->department_id = $departID;
				$patient->hall_id = $hallId;
				$patient->age = $request->input('age');
				$patient->address = !empty($request->input('address')) ? $request->input('address'): null;
				$crno = $patient->crno = $request->input('crno');
				
				$roomAllocation	 = new RoomAllocations;
				/* Check if room or doctor is alloted to the selected hall */
				$allRooms = Rooms::getAllRoomsByDepartment($departID, $hallId);
				if(count($allRooms) == 0){
					$request->session()->flash('alert-failure',"No room, doctor alloted for the selected hall.");
					return redirect('manage-patient/add/')
									->withInput()->withErrors($validator,'patient');
				}
				
				/* get the patient count in a room */
				$patientRoomCount = $patient->getRoomCountByDepartment($departID, $hallId);
				
				/* get the room id which will be allocated to current patient*/
				$roomTobeAllocatedNowRec = $roomAllocation->getNextRoomAllocation($departID, $hallId);
				$newRooomArrAll = []; $arrRoom = [];
				if(count($allRooms) == 1) {
					/* room id for room allocation table */
					$nextRoomTobeAlloted = $room_id = $allRooms[0]->id;
				}else {
					
					$newRooomArrAll = [];
					foreach($allRooms as $aKey=> $roomRec){
						$newRooomArrAll[$roomRec->id] = $roomRec->id;
					}
					foreach($allRooms as $key=> $roomRec){
							$room_id = $roomRec->id;
							$nextRoomTobeAlloted = $allRooms[1]->id;
						if(!empty($roomTobeAllocatedNowRec)){
							$roomTobeAllocatedNow = $roomTobeAllocatedNowRec[0]->room_id;
							if($roomTobeAllocatedNow == $roomRec->id){
								if($key+1 == count($allRooms))
									$nextRoomTobeAlloted = $allRooms[0]->id;
								else
								$nextRoomTobeAlloted = $allRooms[$key+1]->id;
								$room_id = $roomRec->id;
								break;
							}
						}else{
							$room_id = $roomRec->id;
							$nextRoomTobeAlloted = $allRooms[$key+1]->id;
							break;
						}
					}
					if(count($patientRoomCount) >1) {
						foreach($patientRoomCount as $prCount) {
							$arrRoom[$prCount->room_id] = $prCount->patient_count;
						}
						$roomWithMinPatient = array_search(min($arrRoom), $arrRoom); 
						if($room_id != ''){
							if(isset($arrRoom[$room_id])){
								if($arrRoom[$roomWithMinPatient] < $arrRoom[$room_id])
								$room_id  = $roomWithMinPatient;
							}
						}
					}
					if(count($arrRoom) > 0 && count($newRooomArrAll)>0){
						if(count($arrRoom) < count($newRooomArrAll)){
							$a = array_diff_key($newRooomArrAll, $arrRoom);
							$value = reset($a);
							if(!empty($value))
								$room_id = $value;
						}
					}
				}
				
				if(!empty($roomTobeAllocatedNowRec)) {
					/* if record already present then update with new room id */
					$update = RoomAllocations::where('id',$roomTobeAllocatedNowRec[0]->id)
					  ->update(['department_id' => $departID,'hall_id' => $hallId,'room_id'=>$nextRoomTobeAlloted, 'updated_at'=>date('Y-m-d h:i:s')]);
				}else {
					/* insert room which will available for next patient */
					$roomAllocation->department_id = $departID;
					$roomAllocation->room_id = $nextRoomTobeAlloted;
					$roomAllocation->hall_id = $hallId;
					$insertRoomAll = $roomAllocation->save();
				}
				/* get next incremented token */
				$token = $this->createToken($departID, $hallId);
				$reservedroomtoken = new ReservedRoomToken;
				
				/* reserved token for senior citizen */
				$tokenReservedData = $reservedroomtoken->getReservedTokenForDepart($departID, $hallId);
				if($patient->age >= 60 && !empty($tokenReservedData)){
					$token = $tokenReservedData[0]->token;
					/* Delete Token as the token has been allocated to senior citizen */
					$deleteTokenData = ReservedRoomToken::find($tokenReservedData[0]->id);
					$delete = $deleteTokenData->delete();
				}else if($patient->age < 60 && $token%4 == 0){
					/* if current token is a multiple of 4, reserve it!! */
					$reservedroomtoken->department_id = $departID;
					$reservedroomtoken->hall_id = $hallId;
					$reservedroomtoken->token = $token;
					$insertToken = $reservedroomtoken->save();
					if($insertToken)
						$token = $token+1;
				}
				$patient->token = $token;
				$patient->room_id = $room_id;
				$hall = new halls;
				$hallData = $hall->find($hallId); 
				$countPatientInHall = $patient->getAllHallPatient($hallId);
				$approachMsg = '';
				$roomInfo = $room->getRoomInfo($room_id);
				$hall_name = halls::where('id',$roomInfo->hall)->select('name')->pluck('name')->first();
			/*	if(($countPatientInHall)< $hallData->capacity && $hallData->Status == 1) {  */
					/* send message when there is space for sitting in the hall */
				/*	$twilioMsg = Twilio::message("+91".$request->input('mobile'),"you are registered successfully. Token is ".$token.". CR No is ".$crno.", Block: ".$hall_name.", Room No: ".$roomInfo->room_name. ".Wait in ".$hall_name);  */
			/*	}else if($countPatientInHall < $hallData->capacity && $hallData->Status == 0) { */
					/* send message when there is no hall present in the department */
				/*	$twilioMsg = Twilio::message("+91".$request->input('mobile'),"You are registered successfully. Token is ".$token.". CR No is ".$crno.", Room No: ".$roomInfo->room_name."Stay near room."); 
				}else if($countPatientInHall >= $hallData->capacity && $hallData->Status == 0) { */
					/* send message when sitting hall  capacity is full and there is no hall present in the department */
			/*		$twilioMsg = Twilio::message("+91".$request->input('mobile'),"You are registered successfully. Token is ".$token.". CR No is ".$crno." , Room No: ".$roomInfo->room_name.". "); 
				}
				else {  */
					/* send message when no siitng place in the hall */
				/*	$twilioMsg = Twilio::message("+91".$request->input('mobile'),"You are registered successfully. Your token is ".$token.". CR No is ".$crno.", Block: ".$hall_name." , Room No: ".$hall_name.". "); }
				if(!empty($twilioMsg)) {  */
					/* twillio messgae sending failed */
				/*	if(isset($twilioMsg->status) && $twilioMsg->status == 400){
						if(isset($twilioMsg->message)){
							$msg = $twilioMsg->message;
							$request->session()->flash('alert-failure',$msg);
							return redirect('manage-patient/add/')
							->withInput()->withErrors($validator,'patient');
						}
					} else {  */
						if($patient->save())
						{
							$insert = true;
						} else {
							$insert = false;
						}
					//}
			//	}
				
			} else if($departInfo->add_hall == 0 || strcmp($departInfo->name,'Pediatrics') == 0 || strcmp($departInfo->name,'Pediatrics Surgeon') == 0) {
					dd('there');
				$roomInfo = Rooms::find($request->input('room'));
				$hallId = !empty($roomInfo->hall) ? $roomInfo->hall: 0;
				$departID = $request->department;
				$patient = new Patients;
				$room = new Rooms;
				$getRoomInfo = $room->getAllRoomInfoById($request->input('room'));
				if(count($getRoomInfo) == 0){
					$request->session()->flash('alert-failure',"No doctor alloted for the selected room.");
					return redirect('manage-patient/add/')
									->withInput()->withErrors($validator,'patient');
				}
				$patient->name = trim($request->input('name'));
				$patient->adhar_number = !empty($request->input('adhar_number')) ? $request->input('adhar_number'): null;
				$patient->phone = !empty($request->input('mobile')) ? $request->input('mobile'): null;
				$patient->gender = $request->input('gender');
				$patient->email =  !empty($request->input('email')) ? $request->input('email'): null;;
				$room_ID = $patient->room_id = $request->input('room');
				$patient->department_id = $departID;
				$patient->hall_id = $hallId;
				$patient->age = $request->input('age');
				$patient->address =  !empty($request->input('address')) ? $request->input('address'): null;;
				$crno = $patient->crno = $request->input('crno');
				$token = $this->createTokenForRoom($departID, $room_ID);
				$patient->token = $token;
				$hall = new halls;
				$hallData = $hall->find($hallId); 
				$countPatientInHall = $patient->getAllHallPatient($hallId);
				$approachMsg = '';
				//die($countPatientInHall.$hallData->capacity);
				/* if($countPatientInHall< $hallData->capacity && $hallData->Status == 1){
					$twilioMsg = Twilio::message("+91".$request->input('mobile'),"You are registered successfully. Token is ".$token.". CR No is ".$crno." , Block: ".$hallData->name." and Room No: ".$roomInfo->room_name.". Wait in ".$hallData->name); 
				}else if($countPatientInHall < $hallData->capacity && $hallData->Status == 0){
					$twilioMsg = Twilio::message("+91".$request->input('mobile'),"You are registered successfully. Token is ".$token.". CR No is ".$crno."and Room No: ".$roomInfo->room_name. ". Stay near room."); 
				}else if($countPatientInHall >= $hallData->capacity && $hallData->Status == 0){
					$twilioMsg = Twilio::message("+91".$request->input('mobile'),"You are registered successfully. Token is ".$token.". CR No is ".$crno." . "); 
		        }else{
					$twilioMsg = Twilio::message("+91".$request->input('mobile'),"You are registered successfully. Your token is ".$token.". CR No is ".$crno.", Block: ".$hallData->name." . "); }
				if(!empty($twilioMsg)){
					if(isset($twilioMsg->status) && $twilioMsg->status == 400){
						if(isset($twilioMsg->message)){
							$msg = $twilioMsg->message;
							$request->session()->flash('alert-failure',$msg);
							return redirect('manage-patient/add/')
							->withInput()->withErrors($validator,'patient');
						}
					} else {   */
						if($patient->save())
						{
							$insert = true;
						} else {
							$insert = false;
						}
					//}
				//}
			}
		}
		if($insert == true){
			$request->session()->flash('alert-success',"Patient registered successfully");
						return redirect('/manage-patient');
		}else{
			$request->session()->flash('alert-success',"Something going wrong. Please try again later.");
			return redirect('/manage-patient');
		}	
	} 
	public function checkRoomDoctorInfo(Request $request)
	{
		$room = $request['room'];
		$checkRoom = Rooms::getAllRoomInfoById($room);
		if(count($checkRoom) > 0)
			return 'true';
		else
			return 'false';
	}
	
	public function sendNotification($title,$body,$registrationIds)
	{
		#prep the bundle
		 $msg = array
			  (
			'body' 	=> $body,
			'title'	=> $title,
					'icon'	=> 'myicon',/*Default Icon*/
					'sound' => 'mySound'/*Default sound*/
			  );
		$fields = array
				(
					'to'		=> $registrationIds,
					'notification'	=> $msg
				);
		
		
		$headers = array
				(
					'Authorization: key=' . Config::get('settings.API_ACCESS_KEY'),
					'Content-Type: application/json'
				);
	#Send Reponse To FireBase Server	
			$ch = curl_init();
			curl_setopt( $ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send' );
			curl_setopt( $ch,CURLOPT_POST, true );
			curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
			curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
			curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
			curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
			$result = curl_exec($ch );
			curl_close( $ch );
		#Echo Result Of FireBase Server
		return true;
	}
	
	public function delete(Request $request,$id)
	{
		$checkAccess = $this->checkUserPermissions('manage_patient');
		if($checkAccess == false)
			return redirect('unauthorized-access');
		$patient = Patients::find($id);
		$delete = $patient->delete();
		if($delete){
			/* Add reserve Token */
			/* $reservedroomtoken->department_id = $patient->department_id;
			$reservedroomtoken->token = $token;
			$insertToken = $reservedroomtoken->save(); */
			
			$request->session()->flash('alert-success',"Patient Deleted Successfully");
			return redirect('/manage-patient');
		}else{
			$request->session()->flash('alert-success',"Something going wrong. Please try again later.");
			return redirect('/manage-patient');
		}
	}
	
	/*
	**	@function Name: createToken
	**	@param: $request : form data.
	**	@description: auto incremented token when hall is linked to a department on the basis of depart and hall
	**	@return: token
	**  Author Name: IDS
	*/
	
	private function createToken($departmentId, $hallID)
	{
		// Get the Last Number of the token created in the database for the particular doctor
		$tokensInfo = Patients::getInfoOfToken($departmentId, $hallID);
		if(!empty($tokensInfo) && !empty($tokensInfo[0]->token)){
			$token = ($tokensInfo[0]->token) +1;
		}else{
			$token = 1;
		}
		return $token;
	}
	
	/*
	**	@function Name: createTokenForRoom
	**	@param: $request : form data.
	**	@description: auto incremented token when room directly linked to the department on the basis of depart and room
	**	@return: token
	**  Author Name: IDS
	*/
	private function createTokenForRoom($departmentId, $roomID)
	{
		// Get the Last Number of the token created in the database for the particular doctor
		$tokensInfo = Patients::getInfoOfTokenForRoom($departmentId, $roomID);
		if(!empty($tokensInfo) && !empty($tokensInfo[0]->token)){
			$token = ($tokensInfo[0]->token) +1;
		}else{
			$token = 1;
		}
		return $token;
	}
	
	/*
	**	@function Name: getDoctorsByDepartment
	**	@param: $request : form data.
	**	@description: get doctor list by depart id
	**	@return: array of doctors
	**  Author Name: IDS
	*/
	
	public function getDoctorsByDepartment($id)
	{
		$data = Doctors::where('department_id', '=', $id)->get();
		if($data){
			echo json_encode(['status' => true, 'data' => $data]);
		}else{
			return response()->json(['status' => false]);
		}
	}
	
	public function getDoctorsByDepartmentInEdit($id)
	{
		$data = Doctors::where('department_id', '=', $id)->get();
		if($data){
			return $data;
		}else{
			return false;
		}
	}

	public function getPatientFromCrno($crno)
	{
		return Patients::getPatientFromCrno($crno);
	}

	public function updateDeviceId($crno, $device_id)
	{
		return Patients::updateDeviceId($crno, $device_id);
	}

	public function allTokenStatus($department_id, $room_id)
	{
		//total number of tokens
		$total	=	Patients::allTokens($department_id, $room_id);
			
		//active token
		$active_tokens	=	Patients::activeTokens($department_id, $room_id);

		//current token
		$current_token	=	($total==$active_tokens)? $active_tokens:$active_tokens+1;

		//merge and return
		$merge	=	['total'=>$total, 'active'=>$active_tokens, 'current'=>$current_token];
		
		return $merge;
	}

	public function patientData($crno, $device_id){
		$checkAccess = $this->checkUserPermissions('manage_patient');
		if($checkAccess == false)
			return redirect('unauthorized-access');
		//get device_id and crno
		//Check CR No is valid or not
		$isValidCrno	=	Patients::isValidCrno($crno);
		if(!$isValidCrno){
			//if not valid then
			return response()->json(['status' => false, 'data' => []]);
		}else{
			$a = $this->updateDeviceId($crno, $device_id);
			
			//get patient record
			$patientDataAll	=	$this->getPatientFromCrno($crno);
			foreach($patientDataAll as $patientData){
			$department_id	=	$patientData->department_id;
			$room_id		=	$patientData->room_id;
			$doctorInfo = Doctors::getDoctorInfo($room_id);
			$doctor_id = $doctorInfo->id; 
			//get token status
			$tokenStatus	=	$this->allTokenStatus($department_id, $room_id);

		
				$get_department	=	Departments::find($department_id);
			$get_doctor	=	Doctors::find($doctor_id);
			$get_hall	=	Halls::find($patientData->hall_id);
			$roomData	=	Rooms::find($room_id);
			
			//format data according to response
			$format[]	=	[
				
				
					"Patient_Name" => $patientData->name,
					"Department" => $get_department->name,
					"Room" => $roomData['room_name'],
					"Doctor_Name" => $get_doctor->name,
					"Patient_Phone" => $patientData->phone,
					"Patient_Email" => $patientData->email,
					"CR_No" => $patientData->crno,
					"Patient_Token" => $patientData->token,
					"Current_Token" => $tokenStatus['current'],
					"Total_Token" => $tokenStatus['total'] 
				
			];
			}
			if(!empty($format)){
				$formatAllData = ["status"=> true,'data'=>$format];
			}
			// reponse in json
			return response()->json($formatAllData);
		}
	}
	
	public function isDoctorValid($doctor_phone){
		// get doctor phone number as request parameter
		$isDoctorValid	=	Doctors::isDoctorValid($doctor_phone);
		
		if(!$isDoctorValid){
			//if not valid
			return response()->json(['status' => false, 'data' => []]);
		}else{
			//if valid
			//send OTP
			$otp	=	rand(1000,9999);
			Twilio::message("+91".$doctor_phone,"Your HQMS One Time Password (OTP) is ".$otp."");

			$get_department	=	Departments::find($isDoctorValid->department_id); // get department
			$get_doctor	=	Doctors::find($isDoctorValid->id); // get doctor
			
			//get token status
			$tokenStatus	=	$this->allTokenStatus($isDoctorValid->department_id, $isDoctorValid->id);
			
			//format data according to response
			$format	=	[
				"status"	=>	true,
				"otp"		=>	$otp,
				"data"		=>	[
					"name"	=>	$get_doctor->name,
					"phone"	=>	$get_doctor->phone,
					"department"	=>	$get_department->name,
					"floor"	=>	$get_department->floor,
					"room_no"	=>	$get_department->room_no,
					"token_total"	=>	$tokenStatus['total']
				]
			];

			// print final output
			//dd($format);

			//send response to device with required data
			return response()->json($format);
		}
		
		
	}

	public function tokenStatus($doctor_phone)
	{
		// get doctor phone number as request parameter
		$isDoctorValid	=	Doctors::isDoctorValid($doctor_phone);
		
		if(!$isDoctorValid){
			//if not valid
			return response()->json(['status' => false, 'data' => []]);
		}else{
			//if valid
			$otp	=	''; // set blank otp
			
			$get_department	=	Departments::find($isDoctorValid->department_id); // get department
			$get_doctor	=	Doctors::find($isDoctorValid->id); // get doctor
			
			//get token status
			$tokenStatus	=	$this->allTokenStatus($isDoctorValid->department_id, $isDoctorValid->id);
			
			// get current patient details
			$currentTokens	=	Patients::currentTokens($isDoctorValid->department_id, $isDoctorValid->id, $tokenStatus['current']);

			$patientArr	=	[]; // blank patient data arr - finally merge with format array
			if($currentTokens){
				$get_hall	=	Halls::find($currentTokens->hall_id);
				// patient data format
				$patientArr	=	[
					"name" => $currentTokens->name,
					"department" => $get_department->name,
					"floor" => $get_department->floor,
					"room_number" => $get_department->room_no,
					"doctor_name" => $get_doctor->name,
					"phone" => $currentTokens->phone,
					"email" => $currentTokens->email,
					"crno" => $currentTokens->crno,
					"waiting_hall" => $get_hall->name,
					"token" => $currentTokens->token
				];

				// Update current patient status
				Patients::updateCurrentPatient($currentTokens->crno);
				
				$hall_capacity	=	$get_hall->capacity;
				$patientAvailable=Patients::isAvailable($isDoctorValid->department_id, $isDoctorValid->id, $hall_capacity+$tokenStatus['current']);

				if($patientAvailable){
					$body	=	"Dear ".$patientAvailable->name.", Please approach to waiting area. Token no. ".$patientAvailable->token." CR No. ".$patientAvailable->crno." Thanks. PGI
					";
					$this->sendNotification('PGI : Your Turn', $body, $patientAvailable->device_id);

					//Twilio::message("+91".$patientAvailable->phone, $body);
				}
				//$this->sendNotification('Your Turn', 'Eligible for wait in waiting room',$registrationIds);
			}

			//format data according to response
			$format	=	[
				"status"	=>	true,
				"otp"		=>	$otp,
				"data"		=>	[
					"patient" => $patientArr,
					"token" => [
						"status" => $tokenStatus['current'],
						"total" => $tokenStatus['total']
					]
				]
			];

			// print final output
			//dd($currentTokens, $format);

			//send response to device with required data
			return response()->json($format);
		}
	}

	public function patientStatus()
	{
		$checkAccess = $this->checkUserPermissions('patient_status');
		if($checkAccess == false)
			return redirect('unauthorized-access');
		return view('ManagePatients.patientStatus');
	}
	
	/*
	**	Function Name: dashboardData()
	**	Params: {$id} - selected department Id
	**	Description: This method returns current day's patient statistic  - total tokens, token info for each department or the department selected.
	**  Author Name: IDS
	*/
	
	public function dashboardData($id = null)
	{
		/* check if user has permissions for dashboard */ 
		$checkAccess = $this->checkUserPermissions('dashboardData');
		if($checkAccess == false)
			return redirect('unauthorized-access');
		$departments = Departments::orderBy('id','DESC')->get();
		$departId = null;
		if($id != 'all')
			$departId = $id;
		$patient = new Patients;
		$room = new Rooms;
		$hall = new Halls;
		
		/* Get current day's patients */
		$patientData = $patient::getAllPatientsDashboard($departId);
		$allRooms = $room->getAllRoomsAndDepartmentDashboard($departId);
		$allHalls = $hall->getAllHallsForDashBoard($departId);
		$allHalls1 = [];
		foreach($allHalls as $hallRec){
			$allHalls1[$hallRec->department_id][] = $hallRec;
		}
		$hallCount = $departCount = [];
		$roomArr = $roomWiseData = $currentToken = $roomSkippedToken = $roomProcessedToken = [];
		foreach($allRooms as $roomRec){
			$roomArr[$roomRec->hall_id][$roomRec->id] = $roomRec;
		}
		
		/* Get each room's total tokens, current ,skipped and processed tokens */
		foreach($patientData as $patient){	
			$roomWiseData[$patient->room_id][] = $patient;
			if($patient->queue_status == 1)
				$currentToken[$patient->room_id] = $patient->token;
			if($patient->queue_status == 3)	
				$roomSkippedToken[$patient->room_id][] = $patient->id;
			if($patient->queue_status == 2)	
				$roomProcessedToken[$patient->room_id][] = $patient->id;
				
				if(isset($departCount[$patient->department_id]))
					$departCount[$patient->department_id]  = $departCount[$patient->department_id]+1;
				else
					$departCount[$patient->department_id] = 1;
				
				if(isset($hallCount[$patient->hall_id]))
					$hallCount[$patient->hall_id]  = $hallCount[$patient->hall_id]+1;
				else
					$hallCount[$patient->hall_id] = 1;
		}
		return view('ManagePatients.dashboardPatient',['currentToken'=>$currentToken,'departments'=>$departments, 'patients'=>$roomWiseData, 'allHalls'=>$allHalls1,'allRooms'=>$roomArr, 'roomProcessedToken'=>$roomProcessedToken, 'roomSkippedToken'=>$roomSkippedToken, 'id'=>$id, 'hallCount'=>$hallCount, 'departCount'=>$departCount, 'patientData'=>$patientData]);
	}
}

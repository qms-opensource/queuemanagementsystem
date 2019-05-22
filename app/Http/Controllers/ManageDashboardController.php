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

class ManageDashboardController extends Controller
{
    public function __construct()
	{
		$this->middleware('auth');
	}
	
	/*
	**	@function Name: showDoctorAppointment
	**	@param: None
	**	@description:  Shows the all patients under one doctor
	**	@return: Patients data
	**  Author Name: IDS
	*/
    public function showDoctorAppointment()
	{
		$checkAccess = $this->checkUserPermissions('doctor_dashboard');
		if($checkAccess == false)
			return redirect('unauthorized-access');
		return $this->showAppointmentDataForDifferentState();		
	}
	
	/*
	**	@function Name: updateInProcess
	**	@param: patient id
	**	@description:  Updates patient in process state
	**	@return: Void
	**  Author Name: IDS
	*/
	public function updateInProcess($id)
	{
		$checkAccess = $this->checkUserPermissions('doctor_dashboard');
		if($checkAccess == false)
			return redirect('unauthorized-access');
		$matchThese = ['id' => $id];
		Patients::where($matchThese)->whereDate('created_at', '=', date('Y-m-d'))->update(['queue_status' => 1]);
		 if($id != null) {
			$patient = new Patients;
			$patientData = $patient->getPatientDataById($id);
			$allHallPatientCount = $patient->getAllHallPatient($patientData->hall_id);
			 if($allHallPatientCount >= $patientData->hall_capacity) {
				// next patient to approach hall 
				$nextPatientMsg = $patient->getPatientNextRecForHall($patientData->hall_capacity, $patientData->hall_id);
				if(!empty($nextPatientMsg)&& $patientData->hall_status == 1) {
					Twilio::message("+91".$nextPatientMsg[0]->phone, "Dear ".$nextPatientMsg[0]->name.", Please  approach to waiting area. Your token is ".$nextPatientMsg[0]->token.", CR No: ".$nextPatientMsg[0]->crno.", Hall: ".$nextPatientMsg[0]->hall_name.", Room: ".$nextPatientMsg[0]->room_name."."); 
				} else {
					$timeToWait = $patientData->hall_capacity*5;
					Twilio::message("+91".$nextPatientMsg[0]->phone, "Dear ".$nextPatientMsg[0]->name.", Your turn will come approximately in ".$timeToWait." mins. Your token is ".$nextPatientMsg[0]->token.", CR No: ".$nextPatientMsg[0]->crno.", Room: ".$nextPatientMsg[0]->room_name."."); 
				}
			}  
		} 
		return redirect('manage-doctor/patient-appointment');

	}

	public function updateProcessState(Request $request)
	{

		$process_id = $request->processid;
		$queue_status = $request->queue_status;
		$remarks = $request->remarks;
        $doctor_id = $request->doctor_id;
		if(!empty($request->room_data))
		   $room_id = $request->room_data;
		$checkAccess = $this->checkUserPermissions('doctor_dashboard');
		if($checkAccess == false)
			return redirect('unauthorized-access');
		if(!empty($room_id))
		{
			$matchThesePatient = ['room_id' => $room_id,'queue_status'=>1];
			if(Patients::where($matchThesePatient)->exists()) {
					return response()->json([
	    					'inprocess' => '1'
							]);
			}
		}
		//($room_id);
			
		if($queue_status == 2){
			Patients::where('id',$process_id)->whereDate('created_at', '=', date('Y-m-d'))->update(['queue_status' => $queue_status,'remarks' => $remarks, 'doctor_id'=>$doctor_id]);
		} else if($queue_status == 3){
			Patients::where('id',$process_id)->whereDate('created_at', '=', date('Y-m-d'))->update(['queue_status' => $queue_status,'remarks' => $remarks]);
		} else{
			Patients::where('id',$process_id)->whereDate('created_at', '=', date('Y-m-d'))->update(['queue_status' => $queue_status]);
		}
		 if($process_id != null && $queue_status == 2 ) {
			$patient = new Patients;
			$patientData = $patient->getPatientDataById($process_id);
			$allHallPatientCount = $patient->getAllHallPatient($patientData->hall_id);
			//die($allHallPatientCount.'-'.$patientData->hall_capacity);
		/*	if($allHallPatientCount >= $patientData->hall_capacity) {
				// next patient to approach hall 
				$nextPatientMsg = $patient->getPatientNextRecForHall($patientData->hall_capacity, $patientData->hall_id);
				if(!empty($nextPatientMsg)&& $patientData->hall_status == 1) {
					Twilio::message("+91".$nextPatientMsg[0]->phone, "Dear ".$nextPatientMsg[0]->name.", Please  approach to waiting area. Your token is ".$nextPatientMsg[0]->token.", CR No: ".$nextPatientMsg[0]->crno.", Hall: ".$nextPatientMsg[0]->hall_name.", Room: ".$nextPatientMsg[0]->room_name."."); 
				} else {
					$timeToWait = $patientData->hall_capacity*5;
					Twilio::message("+91".$nextPatientMsg[0]->phone, "Dear ".$nextPatientMsg[0]->name.", Your turn will come approximately in ".$timeToWait." mins. Your token is ".$nextPatientMsg[0]->token.", CR No: ".$nextPatientMsg[0]->crno.", Room: ".$nextPatientMsg[0]->room_name."."); 
				}
			}  */
		} 
		return redirect('manage-doctor/patient-appointment');

	}
	
	/*
	**	@function Name: updateProcess
	**	@param: patient id
	**	@description:  Updates patient processed state
	**	@return: Void
	**  Author Name: IDS
	*/
	/* public function updateProcess($pid)
	{

		$checkAccess = $this->checkUserPermissions('doctor_dashboard');
		if($checkAccess == false)
			return redirect('unauthorized-access');
		$matchThese = ['id' => $pid];
		$patient = new Patients;
		Patients::where($matchThese)->whereDate('created_at', '=', date('Y-m-d'))->update(['queue_status' => 2]);
		return redirect('manage-doctor/patient-appointment');
	} */
	
	/*
	**	@function Name: updateSkipp
	**	@param: patient id
	**	@description:  Updates patient in skip state
	**	@return: Void
	**  Author Name: IDS
	*/
	/* public function updateSkipp($pid)
	{
		$matchThese = ['id' => $pid];
		Patients::where($matchThese)->whereDate('created_at', '=', date('Y-m-d'))->update(['queue_status' => 3]);
		return redirect('manage-doctor/patient-appointment');
	} */
	
	/*
	**	@function Name: updateSkipp
	**	@param: patient id
	**	@description: Shows all patients of one doctor
	**	@return: Patients under one doctor
	**  Author Name: IDS
	*/
	 public function showAppointmentDataForDifferentState()
	{

		$checkAccess = $this->checkUserPermissions('doctor_dashboard');
		if($checkAccess == false)
			return redirect('unauthorized-access');
		$rawdata = array();
		$data =array();
		$id = \Auth::user()->id;
		$doctor_id = Doctors::where('user_id',$id)->select('id')->pluck('id')->first();
		$data['appointmentdata'] = json_decode(json_encode(Patients::getAllPatientOfOneDoctor($id)), true);
		$data['processdata'] = json_decode(json_encode(Patients::getProcessedPatientOfOneDoctor($id)), true); 
		$data['skippdata'] =json_decode(json_encode(Patients::getInQueuePatientOfOneDoctor($id)), true); 
		return view('Doctors.patientappointment')->with('skippdata',$data['skippdata'])->with('processdata',$data['processdata'])->with('appointmentdata',$data['appointmentdata'])->with('doctor_id',$doctor_id);
	} 
	
	/*
	**	@function Name: updateToInqueue
	**	@param: patient id,room_id,request data
	**	@description: Shows all patients of one doctor
	**	@return: Patients under one doctor
	**  Author Name: IDS
	*/
	/* public function updateToInqueue($pid,$room_id,Request $request)
	{
		$checkAccess = $this->checkUserPermissions('doctor_dashboard');
		if($checkAccess == false)
			return redirect('unauthorized-access');
		$matchThesePatient = ['room_id' => $room_id,'queue_status'=>1];
		if(Patients::where($matchThesePatient)->exists()) {
			$request->session()->flash('alert-danger',"One patient is already is in process.");
			return redirect('manage-doctor/patient-appointment');
		}
		$matchThese = ['id' => $pid];
		Patients::where($matchThese)->whereDate('created_at', '=', date('Y-m-d'))->update(['queue_status' => 0]);
		return redirect('manage-doctor/patient-appointment');

	} */
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
class Patients extends Model
{

	protected $table = 'patients';
	public $fillable = ['name','department_id','age','address','hall_id','phone','phone','email','crno','token','queue_status','device_id'];
	
    public static function getAllPatients()
	{
		$data = DB::table('patients as a')
					->leftJoin('departments as b','a.department_id','=','b.id')
					->leftJoin('rooms as r','a.room_id','=','r.id')
					->leftJoin('halls as h','r.hall','=','h.id')
					->select('a.id as pid','a.age','a.name as patient_name','a.phone as patient_phone','a.email as patient_email','a.crno','a.token','a.queue_status','a.device_id','a.created_at as patient_register_date','b.add_hall','b.name as department_name', 'b.floor','r.room_name as room_no' ,'h.name as hall')
					->orderBy('a.created_at','desc')
					->paginate(10);
		return $data;
	}
	
	public static function getAllDepartmentDoctors(){
		$data = DB::table('departments as b')
					->select('b.id as department_id' ,'b.name as department_name','b.room_no','b.floor')
					->paginate(10);
		return $data;
	}
	
	public static function getAllPatientOfOneDoctor($user_id)
	{
		$doctordata = Doctors::where('user_id',$user_id)->first();
		if(!empty($doctordata)) {
			$room_id = $doctordata->room_id;
			$data = DB::table('patients as p')->where('d.user_id',$user_id)->whereDate('p.created_at', '=', date('Y-m-d'))->where('p.queue_status',0)->orWhere('p.queue_status',1)->where('p.room_id',$room_id)
			->join('doctors as d','d.room_id','=','p.room_id')
			->join('departments as d1','p.department_id','=','d1.id')->select('p.room_id','p.remarks','p.id as pid','p.queue_status','p.crno','p.name as patient_name','p.token','p.phone','p.age','p.crno','d.id','d1.name as department_name')->get()->toArray();
		} else {
			$data = [];
		} 
		return $data;
	}
	
	public static function getProcessedPatientOfOneDoctor($user_id)
	{
		$doctordata = Doctors::where('user_id',$user_id)->first();
		if(!empty($doctordata))
		{
			$room_id = $doctordata->room_id;
			$data = DB::table('patients as p')->where('d.user_id',$user_id)->whereDate('p.created_at', '=', date('Y-m-d'))->where('p.queue_status',2)->where('p.room_id',$room_id)
		->join('doctors as d','p.room_id','=','d.room_id')
		->join('departments as d1','p.department_id','=','d1.id')->select('p.room_id','p.id as pid','p.queue_status','p.crno','p.name as patient_name','p.remarks','p.token','p.phone','p.age','p.crno','d.id','d1.name as department_name')->get()->toArray();
		} else {
			$data= [];
		}
		return $data;
	}
	
	public static function getInQueuePatientOfOneDoctor($user_id)
	{
		$doctordata = Doctors::where('user_id',$user_id)->first();
		if(!empty($doctordata))
		{
			$room_id = $doctordata->room_id;
			$data = DB::table('patients as p')->where('d.user_id',$user_id)->whereDate('p.created_at', '=', date('Y-m-d'))->where('p.queue_status',3)->where('p.room_id',$room_id)
		->join('doctors as d','p.room_id','=','d.room_id')
		->join('departments as d1','p.department_id','=','d1.id')->select('p.room_id','p.remarks','p.id as pid','p.crno','p.name as patient_name','p.queue_status','p.token','p.phone','p.age','p.crno','d.id','d1.name as department_name')->get()->toArray();
		} else {
			$data= [];
		}

		return $data;
	}
	public static function getInfoOfToken($departmentId, $hallId)
	{
			$data = DB::select(DB::raw('SELECT max( token ) as token FROM patients WHERE department_id ='.$departmentId.' AND hall_id ='.$hallId.' AND date( `created_at` ) = CURDATE( ) '));
		return $data;
	}
	public static function getInfoOfTokenForRoom($departmentId, $roomID)
	{
		$data = DB::select(DB::raw('SELECT max( token ) as token FROM patients WHERE department_id ='.$departmentId.' AND room_id ='.$roomID.' AND date( `created_at` ) = CURDATE( ) '));
		return $data;
	}

    public static function getPatientFromCrno($crno)
	{
        return Patients::where('crno', $crno)->where('created_at', 'like', date('Y-m-d').'%')->orderBy('id', 'asc')->get();  
    }

    public static function updateDeviceId($crno, $device_id)
	{
        return Patients::where('crno', $crno)->update(['device_id' => $device_id]);
    }

    public static function updateCurrentPatient($crno)
	{
        return Patients::where('crno', $crno)->update(['queue_status' => 1]);
    }
    
	public static function isValidCrno($crno)
	{
		$current_date   =  date('Y-m-d');
        return Patients::where('crno', $crno)->where('created_at', 'like', $current_date.'%')->first();
    }

    public static function allTokens($department_id, $room_id)
	{
        $current_date   =  date('Y-m-d');
        return Patients::where('department_id', $department_id)
                            ->where('room_id', $room_id)
                            ->where('created_at', 'like', $current_date.'%')
                            ->count();
    }

    public static function activeTokens($department_id, $room_id)
	{
        $current_date   =  date('Y-m-d');
        return Patients::where('department_id', $department_id)
                            ->where('room_id', $room_id)
                            ->where('queue_status', 1)
                            ->where('created_at', 'like', $current_date.'%')
                            ->count();
    }

    public static function currentTokens($department_id, $doctor_id, $current_token)
	{
        $current_date   =  date('Y-m-d');
        return Patients::where('department_id', $department_id)
                            ->where('doctor_id', $doctor_id)
                            ->where('queue_status', 0)
                            ->where('token', $current_token)
                            ->where('created_at', 'like', $current_date.'%')
                            ->first();
    }

    public static function isAvailable($department_id, $doctor_id, $token)
	{
        $current_date   =  date('Y-m-d');
        return Patients::where('department_id', $department_id)
                        ->where('doctor_id', $doctor_id)
                        ->where('token', $token)
                        ->where('created_at', 'like', $current_date.'%')
                        ->first();
    }

    public static function checkAdharAlreadyExist($adhar)
	{
        $count = Patients::where('adhar_number',$adhar)->count();
        if($count>0) {
            return false;
        }
        return true;
    }
	public static function checkAdharExist($adhar, $id  = NULL)
	{
		if(empty($id))
			$count = Patients::where('adhar_number',$adhar)->count();
		else
			$count = Patients::where('adhar_number',$adhar)->where('id', '!=',$id)->count();
		return $count;
    }
	public static function checkCrnoExist($crno, $id  = NULL)
	{
		if(empty($id))
			$count = Patients::where('crno',$crno)->count();
		else
			$count = Patients::where('crno',$crno)->where('id', '!=',$id)->count();
		return $count;
    }
	public static function checkPhoneAlreadyExist($phone, $id  = NULL)
	{
        if(empty($id))
			$count = Patients::where('phone',$phone)->count();
		else
			$count = Patients::where('phone',$phone)->where('id', '!=',$id)->count();
        return $count;
    }
	
	public static function getLastRoomHallAlloted($department_id)
	{
		return Patients::where('department_id', $department_id)
                        ->where('queue_status', '0')
						->orderBy('created_at', 'desc')
                        ->first();
		
    }
	public static function getLastRoomAlloted($department_id)
	{
		return DB::select(DB::raw('SELECT patients.id FROM patients where department_id='.$department_id.' and queue_status = "0" and created_at like "%'.date('Y-m-d').'%" order by created_at DESC'));
	}
	public static function getRoomCountByDepartment($department_id, $hallId)
	{
		return DB::select(DB::raw('SELECT count(*) as patient_count, room_id FROM patients join rooms as r on patients.room_id = r.id where patients.department_id='.$department_id.' AND patients.hall_id = '.$hallId.' AND patients.queue_status = "0" AND DATE(`patients`.`created_at`) = CURDATE() and r.status = 1 group by `patients`.`room_id`')); 
	}
	public static function getAllPatientsByDepart($departmentId)
	{
		$data = DB::table('patients as a')
					->join('departments as b','a.department_id','=','b.id')
					->leftJoin('rooms as r','a.room_id','=','r.id')
					->leftJoin('halls as h','r.hall','=','h.id')
					 ->where('department_id', $departmentId)
					->select('a.id as pid','a.age','a.name as patient_name','a.phone as patient_phone','a.email as patient_email','a.crno','a.token','a.queue_status','a.device_id','a.created_at as patient_register_date','b.name as department_name', 'b.floor','r.room_name as room_no' ,'h.name as hall')
					->orderBy('a.created_at','desc')
					->paginate(10);
		return $data;
	}
	public static function getAllPatientsDashboard($departmentId = null)
	{
		if($departmentId != null) {
			$data = DB::table('patients as a')
					->leftJoin('departments as b','a.department_id','=','b.id')
					->leftJoin('rooms as r','a.room_id','=','r.id')
					->leftJoin('halls as h','r.hall','=','h.id')
					->where('a.department_id', $departmentId)
					->whereDate('a.created_at', DB::raw('CURDATE()'))
					->select('a.id','a.room_id','a.room_id','a.department_id','a.hall_id','a.id as pid','a.age','a.name as patient_name','a.phone as patient_phone','a.email as patient_email','a.crno','a.token','a.queue_status','a.device_id','a.created_at as patient_register_date','b.name as department_name', 'b.floor','r.room_name as room_no' ,'h.name as hall')
					->orderBy('a.created_at','desc')
					->get();
		} else {
			$data = DB::table('patients as a')
					->leftJoin('departments as b','a.department_id','=','b.id')
					->leftJoin('rooms as r','a.room_id','=','r.id')
					->leftJoin('halls as h','r.hall','=','h.id')
					->whereDate('a.created_at', DB::raw('CURDATE()'))
					->select('a.id','a.room_id','a.room_id','a.department_id','a.hall_id','a.id as pid','a.age','a.name as patient_name','a.phone as patient_phone','a.email as patient_email','a.crno','a.token','a.queue_status','a.device_id','a.created_at as patient_register_date','b.name as department_name', 'b.floor','r.room_name as room_no' ,'h.name as hall')
					->orderBy('a.created_at','desc')
					->get();
		}
		return $data;
	}
	public function getPatientDataById($id) {
		$data = DB::table('patients as a')
					->leftJoin('departments as b','a.department_id','=','b.id')
					->leftJoin('rooms as r','a.room_id','=','r.id')
					->leftJoin('halls as h','r.hall','=','h.id')
					->where('a.id', $id)
					->select('a.id','a.room_id','a.room_id','a.department_id','a.hall_id','a.id as pid','a.age','a.name as patient_name','a.phone as patient_phone','a.email as patient_email','a.remarks','a.crno','a.token','a.queue_status','a.device_id','a.created_at as patient_register_date','b.name as department_name', 'b.floor' ,'r.room_name as room_no' ,'h.name as hall','h.capacity as hall_capacity', 'h.Status as hall_status')
					->first();
		return $data;
	}
	
	public function getAllHallPatient($hall_id)
	{
		$data = DB::table('patients as p')
					->where('p.hall_id', $hall_id)
					->where('p.queue_status', 0)
					->whereDate('p.created_at', '=', date('Y-m-d'))
					->count();
		return $data;
	}
	public function getPatientNextRecForHall($limit,$hallId) 
	{
		return DB::select(DB::raw('SELECT patients.id, patients.name, patients.department_id, patients.age,patients.room_id,patients.gender, patients.address, patients.hall_id, patients.phone, patients.email, patients.crno, patients.token, patients.queue_status, patients.device_id, patients.adhar_number, patients.created_at, patients.updated_at, r.room_name as room_name, h.name as hall_name FROM patients join rooms as r on patients.room_id = r.id join halls as h on patients.hall_id = h.id where patients.hall_id='.$hallId.' AND patients.queue_status = "0" AND DATE(`patients`.`created_at`) = CURDATE() limit '.($limit-1).',1')); 
	}
	
}

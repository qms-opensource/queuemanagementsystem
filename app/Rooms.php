<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Rooms extends Model
{
    protected $fillable = array('department', 'room_name', 'hall', 'room_prefix');
	
	public static function getAllRoomsAndDepartment($departId = null)
	{ 
		if($departId != null) {
			$data = DB::table('rooms as r')
					->leftJoin('departments as d','r.department','=','d.id')
					->leftJoin('halls as h','r.hall','=','h.id')
					->where('d.id', $departId)
                    ->select('r.room_name','r.hall as hall_id','r.id', 'r.status', 'd.name as department', 'h.name as hall','h.Status as hall_status')
                    ->orderBy('r.id','DESC')
                    ->get();
		} else {
			$data = DB::table('rooms as r')
					->leftJoin('departments as d','r.department','=','d.id')
					->leftJoin('halls as h','r.hall','=','h.id')
                    ->select('r.room_name','r.id','r.hall as hall_id', 'r.status', 'd.name as department', 'h.name as hall','h.Status as hall_status')
                    ->orderBy('r.id','DESC')
                    ->paginate(10);
		}
		return $data;
	}
	public static function getAllRoomsAndDepartmentDashboard($departId = null)
	{ 
		if($departId != null) {
			$data = DB::table('rooms as r')
					->leftJoin('departments as d','r.department','=','d.id')
					->leftJoin('halls as h','r.hall','=','h.id')
					->where('d.id', $departId)
                    ->select('r.room_name','r.id', 'r.status', 'd.name as department', 'h.name as hall', 'h.id as hall_id', 'd.id as department_id', 'h.status')
                    ->get();
		} else {
			$data = DB::table('rooms as r')
					->leftJoin('departments as d','r.department','=','d.id')
					->leftJoin('halls as h','r.hall','=','h.id')
				    ->select('r.room_name','r.id', 'r.status', 'd.name as department', 'h.name as hall', 'h.id as hall_id', 'd.id as department_id' ,'h.status')
                    ->get();
		}
		return $data;
	}
	public function getDoctor()
	{
		return $this->hasOne('App\Doctors','room_id');
	}
	public static function getRoomInfo($roomId)
	{ 
		$data = DB::table('rooms as r')
					->join('departments as d','r.department','=','d.id')
					->join('halls as h','r.hall','=','h.id')
					->where('h.Status',1)
					->where('r.id', $roomId)
                    ->select('r.room_name','r.id', 'r.status', 'd.name as department', 'h.name as hall', 'h.id as hall_id', 'd.id as depart_id')
                    ->first();
		return $data;
	}
	public static function getRoomInfoAdd($roomId)
	{ 
		$data = DB::table('rooms as r')
					->join('departments as d','r.department','=','d.id')
					->join('halls as h','r.hall','=','h.id')
					->where('r.id', $roomId)
                    ->select('r.room_name','r.id', 'r.status', 'd.name as department', 'h.name as hall', 'h.id as hall_id', 'd.id as depart_id')
                    ->first();
		return $data;
	}
	
	public static function getAllRoomsByDepartment($departID = null, $hallId = null)
	{ 
		$data = DB::table('rooms as r')
					->join('halls as h','r.hall','=','h.id')
					->join('doctors as d','r.id','=','d.room_id')
					->where('r.department', $departID)
					->where('h.id', $hallId)
					->where('h.Status',1)
					->where('r.status', 1)
					->groupBy('r.id')
					->orderBy('r.id', 'asc')
					->get();
		return $data;
	}
	public static function getAllRoomInfoById($roomID)
	{ 
		$data = DB::table('rooms as r')
					->join('halls as h','r.hall','=','h.id')
					->join('doctors as d','r.id','=','d.room_id')
					->where('r.id', $roomID)
					->where('r.status', 1)
					//->groupBy('r.id')
                    ->select('r.id')
					->orderBy('r.created_at', 'asc')
					->get();
		return $data;
	}
	
	public static function getAllAvailableRooms()
	{ 
		$data = DB::table('rooms as r')
					->join('halls as h','r.hall','=','h.id')
					->join('doctors as d','r.id','=','d.room_id')
					->where('r.status', 1)
					->where('h.Status',1)
					->groupBy('h.name','h.id')
                    ->select('h.name','h.id')
					->orderBy('h.id', 'asc')
					->get();
		return $data;
	}
	public static function getRoomsByDepart($departID)
	{ 
		$data = DB::table('rooms as r')
					->join('doctors as d','r.id','=','d.room_id')
					->where('r.status', 1)
					->where('r.department',$departID)
					->select('r.room_name','r.id')
					->orderBy('r.created_at', 'asc')
					->get();
		return $data;
	}

	public static function getRoomsNotAllotedToDoctor($departID)
	{ 
		$data = DB::select(DB::raw('SELECT r.id, r.room_name FROM rooms as r inner join halls as h on r.hall = h.id  where r.department = '.$departID.' AND r.status = 1 AND r.id NOT IN (select room_id from doctors where department_id = '.$departID.') order by r.id DESC'));
		return $data;
	}
	public static function getRoomData($room_id)
	{ 
		$data = DB::select(DB::raw('SELECT r.id, r.room_name FROM rooms as r where r.id = '.$room_id));
		return $data;
	}
	public static function getRoomWithoutPatient($departID = null, $hallId = null)
	{ 
		$data = DB::select(DB::raw('SELECT r.id FROM rooms AS r JOIN halls AS h ON r.hall = h.id JOIN doctors AS d ON r.id = d.room_id JOIN patients AS p ON r.id = p.room_id WHERE r.department = '.$departID.' AND h.id = '.$hallId.' AND r.status = 1 AND r.id NOT IN (SELECT room_id FROM patients WHERE department_id = '.$departID.' AND hall_id = '.$hallId.') GROUP BY r.id'));
		return $data;
	}
}
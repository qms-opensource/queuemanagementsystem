<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;


class Halls extends Model
{
    protected $table = 'halls';
	public $fillable = ['name','capacity', 'department_id','Status'];
	
	public static function getAllHalls()
	{
		$halls = DB::table('halls as h')
					->leftjoin('departments as d','h.department_id','=','d.id')
					->where('h.Status',1)
					->select('h.name','h.capacity', 'h.id', 'd.name as department')
					->orderBy('h.id','DESC')
					->paginate(10);
		return $halls;
	}
	public static function getRoomInfo($roomId)
	{ 
		$data = DB::table('rooms as r')
					->join('departments as d','r.department','=','d.id')
					->join('halls as h','r.hall','=','h.id')
					->where('r.id', $roomId)
                    ->select('r.room_name','r.id', 'r.status', 'd.name as department', 'h.name as hall')
                    ->first();
		return $data;
	}
	public static function getHallInfo($hallID)
	{ 
		$data = DB::table('halls as h')
					->where('h.id', $hallID)
                    ->select('h.id','h.department_id')
                    ->first();
		return $data;
	}
	public static function getAllHallsForDashBoard($departId = null)
	{ 
		
		$halls = DB::table('halls as h')
					->leftjoin('departments as d','h.department_id','=','d.id')
					->select('h.id','d.name as department', 'h.name as hall_name', 'h.capacity', 'h.department_id', 'h.Status')
					->orderBy('d.id')	
					->get();
		if($departId != null) {
			$halls = DB::table('halls as h')
					->leftjoin('departments as d','h.department_id','=','d.id')
					->where('d.id', $departId)
					->select('h.id','d.name as department', 'h.name as hall_name', 'h.capacity', 'h.department_id', 'h.Status')
					->orderBy('d.id')	
					->get();
		}		
		return $halls;
	}
}

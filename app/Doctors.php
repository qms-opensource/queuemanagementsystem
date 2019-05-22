<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Rooms;

class Doctors extends Model
{
	protected $table = 'doctors';
	public $fillable = ['name','user_id','room_id','department_id','phone'];
	
    public static function isDoctorValid($phone)
	{
        return Doctors::where('phone', $phone)->first();  
    }

	public static function getAllDoctorsAndDepartment()
	{
		$data = DB::table('doctors as d')
					->leftJoin('departments as dp','d.department_id','=','dp.id')
					->leftJoin('rooms as r','d.room_id','=','r.id')
					->leftJoin('users as u','d.user_id','=','u.id')
					->select('dp.id as department_id','d.user_id as doctor_id','dp.name as department_name','dp.room_no','dp.floor','d.name as doctors_name','d.phone as doctor_phone', 'r.room_name')
					->orderBy('d.id', 'DESC')
					->paginate(10);
		return $data;
	}

	public static function getDoctorInfo($room_id)
	{
		$data = DB::table('doctors as d')
					->where('d.room_id',$room_id)
					->first();
		return $data;
	}

	public static function getDoctorInfoById($id)
	{
		$data = DB::table('doctors as d')
		            ->leftJoin('departments as dp','d.department_id','=','dp.id')
					->join('users as u','d.user_id','=','u.id')
					->where('u.id',$id)
					->select('d.id','d.name','d.phone','d.department_id','d.user_id','d.room_id','d.created_at','d.updated_at', 'u.email','dp.id as d_id','dp.name as dname')
					->first();
		return $data;
	}
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class Departments extends Model
{
    protected $fillable = array('name','add_hall');
	public static function getAllDepartmentWithRoom()
	{ 
		$data = DB::table('departments as d')
					->leftJoin('rooms as r','d.id','=','r.department')
					->select('d.id','d.name','d.add_hall')
                    ->distinct()
                    ->orderBy('d.id','DESC')
                    ->get();
		return $data;
	}
}

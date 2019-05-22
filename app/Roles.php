<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Roles extends Model
{
	protected $fillable = array('role', 'role_privilege');		
	
	public static function getAllRoles()
	{
		$data = DB::table('roles as r')
					->Join('role_types as rt','r.role_type_id','=','rt.id')
					->select( 'r.id','r.role_type_id','r.status','r.role_privilege','r.created_at' ,'r.updated_at' ,'rt.role_name as role')
					->paginate(10);
		return $data;
	}
	public static function getRoleInfo($roleId)
	{
		$data = DB::table('roles as r')
					->where('r.role_id',$roleId)
					->select('r.id')
					->first();
		return $data;
	}
	public static function getRoleInfoById($roleId)
	{
		$data = DB::table('roles as r')
					->Join('role_types as rt','r.role_type_id','=','rt.id')
					->where('r.id',$roleId)
					->select( 'r.id','r.role_type_id','r.status','r.role_privilege','r.created_at' ,'r.updated_at', 'rt.role_name as name')
					->first();
		return $data;
	}
	public static function getAllRolesData()
	{
		$data = DB::table('roles as r')->where('rt.id' ,2)
					->Join('role_types as rt','r.role_type_id','=','rt.id')
					->select( 'r.id','r.role_type_id','r.status','r.role_privilege','r.created_at' ,'r.updated_at', 'rt.role_name as role')
					->get();
		return $data;
	}
}

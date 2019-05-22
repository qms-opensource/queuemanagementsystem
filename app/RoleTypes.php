<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class RoleTypes extends Model
{
    protected $fillable = array('role_name');
	public function getRoleTypes()
	{
		$data = DB::table('role_types as r')
					->where('r.id','!=','1')// SuperAdmin
					->select( 'r.id','r.role_name','r.created_at' ,'r.updated_at' )
					->get();
		return $data;
	
	}
}

<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
       'name', 'type', 'email', 'password','role_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
	
	public static function getAllUserData()
	{ 
		$data = DB::table('users as u')->where('u.type',0)
					->join('roles as r','u.role_id','=','r.id')
					->join('role_types as rt','r.role_type_id','=','rt.id')
				    ->select('u.id','u.name', 'u.email', 'rt.role_name', 'u.role_id')
				    ->orderBy('u.id','DESC')
                    ->paginate(10);
		return $data;
	}
	
	public static function getUserRoleData($userId)
	{ 
		$data = DB::table('users as u')
					->join('roles as r','u.role_id','=','r.id')
					->where('u.id',$userId)
                    ->select('u.id','r.id', 'r.id', 'r.role_type_id', 'r.role_privilege')
                    ->first();
		return $data;
	}
	
	public static function checkRoleWithUser($roleId)
	{ 
		$data = DB::table('users as u')
					->join('roles as r','u.role_id','=','r.id')
					->where('u.role_id',$roleId)
                    ->select('u.id','r.id', 'r.id', 'r.role_type_id', 'r.role_privilege')
                    ->count();
		return $data;
	}
	
	public static function checkEmailExists($email, $id = NULL)
	{ 
		if(!empty($id)){
			$data = DB::table('users as u')
					->where('u.email',$email)
					->where('u.id', '!=',$id)
                    ->select('u.id')
                    ->count();
		}else{
			$data = DB::table('users as u')
					->where('u.email',$email)
                    ->select('u.id')
                    ->count();
		}
		
		return $data;
	}

}

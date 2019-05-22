<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ReservedRoomToken extends Model
{
	public static function getReservedTokenForDepart1($departID)
	{ 
		$data = DB::table('reserved_room_tokens as r')
					->where('department_id',$departID)
					->select('r.id', 'r.token')
					->orderBy('r.created_at', 'asc')
                    ->first();
		return $data;
	}
	public static function getReservedTokenForDepart($departID, $hallId)
	{
		return DB::select(DB::raw('SELECT id,token FROM reserved_room_tokens where department_id='.$departID.' and hall_id='.$hallId.' and created_at like "%'.date('Y-m-d').'%" order by created_at ASC'));
		
    }
}

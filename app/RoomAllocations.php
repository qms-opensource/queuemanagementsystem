<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class RoomAllocations extends Model
{
	protected $fillable = array('department_id', 'hall_id', 'room_id');
	
	public static function getNextRoomAllocation($department_id, $hallId)
	{
		$data = DB::select(DB::raw('SELECT room_id,id FROM room_allocations where department_id='.$department_id.' AND hall_id = '.$hallId.' AND DATE(`created_at`) = CURDATE() ORDER BY created_at DESC'));
		if(!empty($data))
		{
			$finaldata = $data;
		} else {
			$finaldata = [];
		}
		return $finaldata;
	
	}
}

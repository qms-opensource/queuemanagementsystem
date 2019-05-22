<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Departments;
use App\Doctors;
use App\Halls;
use App\Patients;
use App\User;
class TokenStatusController extends Controller
{
    public function index()
	{
		$checkAccess = $this->checkUserPermissions('manage_room');
		if($checkAccess == false)
			return redirect('unauthorized-access');
		$patientsData = Patients::getAllDepartmentDoctors();
		$i = 0;
		$tokensArr = [];
		foreach($patientsData as $patients) {
			$departmentId = $patients->department_id;
			$doctorId = $patients->doctor_id;
			$tokensInfo = Patients::activeTokens($doctorId,$departmentId);
			$total = Patients::allTokens($doctorId,$departmentId);
			$tokensArr[$i]['doctorName'] = $patients->doctors_name;
			$tokensArr[$i]['departmentName'] = $patients->department_name;
			if(!empty($tokensInfo)):
				$tokensArr[$i]['tokenStatus'] = $tokensInfo;
			else:
				$tokensArr[$i]['tokenStatus'] = 0;
			endif;
			$tokensArr[$i]['total'] = $total;
			$i++;
		}
		$allPatients = $this->getAllPatients();
		return view('TokenStatus.list')->with('tokens',$tokensArr)->with('visitedCount',$allPatients);
	}

	public function getAllPatients()
	{
		$patients = Patients::where('created_at','like','2017-12-04%')->count();
		return $patients;
	}

}

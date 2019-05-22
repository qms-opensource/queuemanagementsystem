<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Halls;
use App\Rooms;
use App\Departments;
use App\Patients;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class ManageHallController extends Controller
{
	public function __construct()
	{
		$this->middleware('auth');
	}
	
	/*
	**	@function Name: index
	**	@param: NA
	**	@description: hall listing
	**	@return: hall list 
	**  Author Name: IDS
	*/		
	
    public function index()
	{
		$checkAccess = $this->checkUserPermissions('manage_hall');
		if($checkAccess == false)
			return redirect('unauthorized-access');
		$halls = Halls::getAllHalls();
		return view('ManageHalls.list')->with('halls',$halls);
	}
	
	/*
	**	@function Name: add
	**	@param: NA.
	**	@return: void 
	**  Author Name: IDS
	*/
		
	public function add()
	{
		$checkAccess = $this->checkUserPermissions('manage_hall');
		if($checkAccess == false)
			return redirect('unauthorized-access');
		$departments = Departments::where('add_hall',1)->orderBy('id','DESC')->get();	
		return view('ManageHalls.addHall',['departments'=>$departments]);
	}

	/*
	**	@function Name: save
	**	@param: $request : form data.
	**	@description: save hall data to DB
	**	@return: void 
	**  Author Name: IDS
	*/
	
	public function save(Request $request)
	{
		$validator = Validator::make($request->all(), [
            'name' => 'required|unique:halls|max:30',
            'department' => 'required',
			'capacity' => 'required|numeric|min:1',
        ]);

        if ($validator->fails()) {
            return redirect('manage-hall/add')
                        ->withInput()->withErrors($validator,'addHall');
        }
		$halls = new Halls;
		$halls->name = $request->input('name');
		$halls->capacity = $request->input('capacity');
		$halls->department_id = $request->input('department');
		$insert = $halls->save();
		if($insert){
			$request->session()->flash('alert-success',"Hall Saved Successfully");
			return redirect('/manage-block');
		}else{
			$request->session()->flash('alert-danger',"Something going wrong. Please try again later.");
			return redirect('/manage-block');
		}		
	}

	/*
	**	@function Name: edit
	**	@param: NA
	**	@description: edit form for hall
	**	@return: void 
	**  Author Name: IDS
	*/

	public function edit($id)
	{
		$hall = Halls::find($id);
		if(empty($hall)){
			return redirect('/pagenotfound');
		}
		if(!empty($hall)){
			$departments = Departments::where('add_hall',1)->orderBy('id','DESC')->get();	
			return view('ManageHalls.editHall')->with(['hall'=>$hall,'departments'=>$departments] );
		} else {
			return redirect('/pagenotfound');
		}
			
	}
	/*
	**	@function Name: update
	**	@param: $request : form data.
	**	@description: upate hall data to DB
	**	@return: void 
	**  Author Name: IDS
	*/
	
	public function update(Request $request)
	{
		$checkAccess = $this->checkUserPermissions('manage_hall');
		if($checkAccess == false)
			return redirect('unauthorized-access');
		$id = $request->input('id');
		$validator = Validator::make($request->all(), [
            'name' => 'required|max:30|unique:halls,name,'.$id,
			'capacity' => 'required|numeric|min:1',
        ]);

        if ($validator->fails()) {
            return redirect('manage-block/edit/'.$id)
                        ->withInput()->withErrors($validator,'addHall');
        }
		
		$halls = new Halls;
		$name = $request->input('name');
		$capacity = $request->input('capacity');
		$department = $request->input('department');
		
		$halls_exist = Halls::find($id);
		$patient = Patients::where('hall_id',$id)->exists();
		if($patient == 1)
		{
			$request->session()->flash('alert-danger',"We can not change hall untill patient be empty.");
			return redirect('/manage-block');
		}
		if(!empty($halls_exist))
		{
			$update = Halls::where('id',$id)
	          ->update(['name' => $name,'capacity'=>$capacity,'department_id'=>$department,'updated_at'=>date('Y-m-d h:i:s')]);
			 
			if($update){
				$request->session()->flash('alert-success',"Hall Updated Successfully");
				return redirect('/manage-block');
			}else{
				$request->session()->flash('alert-danger',"Something going wrong. Please try again later.");
				return redirect('/manage-block');
			}
		}else {
			return redirect('/pagenotfound');
		}
	}
	/*
	**	@function Name: deleteHall
	**	@param: $request : form data and delete hall ID
	**	@description: delete hall
	**	@return: void 
	**  Author Name: IDS
	*/
	public function deleteHall(Request $request,$id)
	{
		$checkAccess = $this->checkUserPermissions('manage_hall');
		if($checkAccess == false)
			return redirect('unauthorized-access');
		/* check if the hall exits */
		$hall = Halls::find($id);
		$patientdata = Patients::where('hall_id',$id)->whereDate('created_at', '=', date('Y-m-d'))->exists();

		if($patientdata == 1) {
				$request->session()->flash('alert-danger',"There are some patients associated with this hall, so you are not allowed to delete until patients associated to that hall.");
					return redirect('/manage-block');
		}
		if(Rooms::where('hall',$hall->id)->exists()){ 
				Rooms::where('hall',$id)->delete();
		}
		if(!empty($hall))
		{
			$delete = $hall->delete();
			if($delete){
				$request->session()->flash('alert-success',"Hall Deleted Successfully");
				return redirect('/manage-block');
			}else{
				$request->session()->flash('alert-danger',"Something going wrong. Please try again later.");
				return redirect('/manage-block');
			}
		} else{
			return redirect('/pagenotfound');
		}
			
	}
}	
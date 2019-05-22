<?php 
    namespace App\Http\Controllers;
    use Illuminate\Http\Request;
    use App\Departments;
    use App\Patients;
	use Illuminate\Support\Facades\Validator;
	use Illuminate\Support\Facades\Auth;
	use Config;

    class DepartmentController extends Controller
	{

        public function __construct()
		{
            $this->middleware('auth');
        }
		
		/*
		**	@function Name: index
		**	@param: $request : form data.
		**	@description: department listing
		**	@return: department list 
		**  Author Name: IDS
		*/	
        public function view()
		{
			$checkAccess = $this->checkUserPermissions('manage_department');
			if($checkAccess == false)
				return redirect('unauthorized-access');
			$departments = Departments::orderBy('id','DESC')->paginate(10);
            return view('Department.list')->with('departments',$departments);
        }

		/*
		**	@function Name: index
		**	@param: NA
		**	@description: department listing
		**	@return: void 
		**  Author Name: IDS
		*/	
        public function index()
		{
            return $this->view();
        }
		
		/*
		**	@function Name: create
		**	@param: NA.
		**	@return: void 
		**  Author Name: IDS
		*/	
        public function create()
		{
            $checkAccess = $this->checkUserPermissions('manage_department');
			if($checkAccess == false)
				return redirect('unauthorized-access');
			return view('Department.add');
        }

        /*
		**	@function Name: store
		**	@param: $request : form data.
		**	@description: save department data to DB
		**	@return: void 
		**  Author Name: IDS
		*/	
		
		public function store(Request $request)
		{
			$validator = Validator::make($request->all(), [
            'name' => 'required|max:30|unique:departments|max:30',
            ]);
	        if ($validator->fails()) {
	            return redirect('department/create')
	                        ->withInput()->withErrors($validator,'add');
	        }
            $data = $request->all();
            $saveData = [
                'name' => $data['name'],
				'add_hall' => $data['linkedtype'] == 'block'  ? 1 : 0
                ];
            $check = Departments::create($saveData);     
			if($check){
				$request->session()->flash('alert-success',"Department Saved Successfully");
				return redirect('/department');			
			}
        }
		
		/*
		**	@function Name: edit
		**	@param: NA
		**	@description: edit form for department
		**	@return: void 
		**  Author Name: IDS
		*/	
		
		public function edit($id)
		{
			$checkAccess = $this->checkUserPermissions('manage_department');
			if($checkAccess == false)
				return redirect('unauthorized-access');
		
			$title = "Edit department";
			$departments = Departments::find($id);
			if(empty($departments))
			{
				return redirect('/pagenotfound');
			}
			if(!empty($departments)){
				return view('Department.edit',['departmentData'=>$departments,'title'=>$title]);
			}else{
				return redirect('/pagenotfound');
			}
		}
		
		/*
		**	@function Name: update
		**	@param: $request : form data.
		**	@description: update department 
		**	@return: void 
		**  Author Name: IDS
		*/
		
		public function update(Request $request)
		{
			$id = $request->input('id');
			$validator = Validator::make($request->all(), [
				'name' => 'required|max:30|unique:departments,name,'.$id,
			]);

			if ($validator->fails()) {
				return redirect('department/edit/'.$id)
							->withInput()->withErrors($validator,'edit');
			}
			
			$department = new Departments;
			$name = $request->input('name');
			$hallvalue = $request->input('linkedtype');
			$addhall = ($hallvalue == 'block') ? 1 : 0;
			$departmentInfo = Departments::find($id);
			if(!empty($departmentInfo)){
				$update = Departments::where('id',$id)
			  ->update(['name'=>$name,'add_hall'=>$addhall,'updated_at'=>date('Y-m-d h:i:s')]);
			 
				if($update){
					$request->session()->flash('alert-success',"Record Updated Successfully");
					return redirect('/department');
				}
			} else{
				return redirect('/pagenotfound');
			}
		}
		
		/*
		**	@function Name: deleteDepartment
		**	@param: $request : form data and delete department ID
		**	@description: delete department
		**	@return: void 
		**  Author Name: IDS
		*/
		
		public function deleteDepartment(Request $request,$id)
		{
			$checkAccess = $this->checkUserPermissions('manage_department');
			if($checkAccess == false)
				return redirect('unauthorized-access');
			$department = Departments::find($id);
			$patientdata = Patients::where('department_id',$id)->whereDate('created_at', '=', date('Y-m-d'))->exists();
			if(!empty($department) && $patientdata == 0){
				$delete = $department->delete();
				if($delete){
					$request->session()->flash('alert-success',"Record Deleted Successfully");
					return redirect('/department');
				}
			}elseif(!empty($department) && $patientdata == 1){
			    $request->session()->flash('alert-danger',"There are some patients associated with this department, so you are not allowed to delete until patients associated to that department.");
					return redirect('/department');
			}else{
				return redirect('/pagenotfound');
			}
		}
		
		/*
		**	@function Name: checkDepartStatus
		**	@param: department ID
		**	@description: check if department linked to hall first or directly with a room(as in case of radiology.)
		**	@return: status: true|false 
		**  Author Name: IDS
		*/ 
		
		public function checkDepartStatus($id)
		{
			$departInfo = Departments::find($id);
			if($departInfo->add_hall == 1)
				echo json_encode(['status' => true]);
			else 
				echo json_encode(['status' => false]);
		}
    }
?>
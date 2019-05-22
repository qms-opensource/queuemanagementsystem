<?php		
use App\User;
use App\Departments;;
use Illuminate\Support\Facades\DB;
use App\Setting;
$app_info = Setting::select(['logo_path','display_name','display_system'])->first();
$logo_file =  $app_info->logo_path;
$app_name = $app_info->display_name;
$app_display = $app_info->display_system;
?>
<!doctype html>
<html lang="en">
<!-- include header here -->
@include('includes.header')
<body>
    <div class="wrapper">
        <!-- include Sidebar Here -->
		<div class="col-md-12 header-data">
			<div class="col-md-2">
				<!--a href="{{url('/manage-patient/dashboardData/all')}}" class="simple-text">
					<img class="logo1" style="width: 99px;
					   height: 99px;
					   position: relative!important;
					   margin-top: 10px;" src="{{ asset('assets/img/Banner_sm_update.png') }}">
				</a
				<a href="{{url('/manage-patient/dashboardData/all')}}" class="simple-text">
					<img class="logo1" style="position: relative!important;
					   margin-top: 10px;" src="{{ asset('uploads/'.$logo_file) }}">
				</a>-->
			</div>
			<div class="col-md-8">
				<!--h3 style="color:#fff;text-align: center;font-weight:bold;">QUEUE MANAGEMENT SYSTEM </br> Postgraduate Institute of Medical Education & Research, Chandigarh</h3-->
				<h3 style="color:#fff;text-align: center;font-weight:bold;">{{ $app_name }}</h3>
			</div>
		</div>
		<div class="col-md-12 header-line" style="background:#5cb85c;color:#fff;height:54px;"><?php
			$id = \Auth::user()->id;
						$user= User::where('id',$id)->first();
						?>
		@if($user->role_id == 1 || $user->role_id == 2)
			<div class="sm-logo"><h6 style="text-align:right;margin-top:18px;font-weight:bold; padding-right: 50px;">Welcome &nbsp; {{ $user->name  }}</h6></div>
		@else
		<?php
			$doctor= DB::table('doctors as d')->join('departments as d1','d.department_id','=','d1.id')->join('rooms as r','d.room_id','=','r.id' )->join('halls as h', 'h.id','=', 'r.hall')->where('d.user_id',$id)->select(['d.id','d.department_id','d.name as doctor_name','d1.name as depart_name', 'r.room_name', 'h.name as hall_name'])->first(); 
			$doctor_with_no_resource = DB::table('doctors as d')->where('d.user_id',$id)->select(['d.id','d.department_id','d.name as doctor_name'])->first();   
			if(!empty($doctor_with_no_resource))
			{
				$departmentdata =Departments::where('id',$doctor_with_no_resource->department_id)->first();
				if(!empty($departmentdata))
				{
					$add_hall = $departmentdata->add_hall;
					$dep_name = $departmentdata->name;
				}
			}

		?>
		<div class="sm-logo">
		<h6  style="text-align:right;margin-top:18px; padding-right: 50px;">
		<b>Welcome &nbsp; </b>&nbsp;&nbsp;
			<i>
				<span class="head-info">  
					@if(!empty($doctor) && isset($add_hall) && $add_hall == 1 && !empty($dep_name)) 
						{{ $doctor->doctor_name  }}( {{ $doctor->depart_name }}, {{$doctor->hall_name}},
						<?php 
						/* if(strpos($doctor->room_name, '-') !== false && $dep_name == 'Pediatrics' || $dep_name == 'Pediatrics Surgeon' ) {
						    $roominfo = explode('-',$doctor->room_name);
						    echo $roominfo[0];
						} else { */
							echo $doctor->room_name;
						//	} 
						?>)
					@elseif(!empty($doctor) && isset($add_hall) && $add_hall == 0) 
						{{ $doctor->doctor_name  }}( {{ $doctor->depart_name }},{{$doctor->room_name}} )
					@elseif(!empty($doctor_with_no_resource)) 
						{{ $doctor_with_no_resource->doctor_name  }} 
					@endif
				</span>
			</i>
		</h6>
		</div>
		@endif</div>
		@include('includes.sidebar')
        <div class="main-panel">
            <!-- Include nav Here -->
			@include('includes.nav')
            <div class="content">
                <div class="container-fluid">
                    <div class="row">
                        @yield('content')
                    </div>
                </div>
            </div>
			@include('includes.footer')

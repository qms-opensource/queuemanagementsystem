
@extends('layouts.qmfront')
   <link href="{{ asset('assets/css/custom.css') }}" rel="stylesheet" />
@section('content')
<div class="container">
<div class="bg-logo-login"><div class="col-md-2"><img class="ogo" src="{{ asset('assets/img/Banner_sm_update.png') }}"></div><div class="col-md-10"><h3 style="color:#fff;text-align: center">QUEUE MANAGEMENT SYSTEM </br> Postgraduate Institute of Medical Education &amp; Research, Chandigarh</h3></div></div>
   <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading" data-background-color="purple" style="text-align:center;"></div>
                    <div class="panel-body">
                         <h2 style="color:red;text-align:center;">404 Page not found</h1>
                             <?php 
                            $role_type_id = Session::get('user.role_type_id');  ?>
                            @if($role_type_id == 1)
                                <a href='{{ url("manage-patient/dashboardData/all") }}'><h5 style="text-align:center;">Go Back</a></h5>
                            @elseif($role_type_id == 2) 
                                <a href='{{ url("manage-patient/dashboardData/all") }}'><h5 style="text-align:center;">Go Back</a></h5>
                            @elseif($role_type_id == 3) 
                                <a href='{{ url("manage-doctor/patient-appointment") }}'><h5 style="text-align:center;">Go Back</a></h5>
                            @else
                                <h5 style="text-align:center;"><a href='{{ url("/home") }}' >Go Back</a></h5>
                            @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

<!--div class="container"-->
<!--/div-->


@extends('layouts.qmfront')
   @section('style_page')
        <link href="{{ asset('public/assets/css/custom.css') }}" rel="stylesheet" />
   @endsection
@section('content')
<div class="container">
<div class="bg-logo-login"><div class="col-md-2"><img class="ogo" src="{{ asset('public/assets/img/Banner_sm_update.png') }}"></div><div class="col-md-8"><h3 style="color:#fff;text-align: center;font-weight:bold;">QUEUE MANAGEMENT SYSTEM </br> Postgraduate Institute of Medical Education &amp; Research, Chandigarh</h3></div></div>
   <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading" data-background-color="purple" style="text-align:center;"></div>
                    <div class="panel-body">
                         <h2 style="color:red;text-align:center;">You are using bad method</h1>
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

<!--   Core JS Files   -->
<script src="{{ asset('public/assets/js/jquery-3.2.1.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('public/assets/js/bootstrap.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('public/assets/js/maskedinput.js') }}" type="text/javascript"></script>
<script src="{{ asset('public/assets/js/material.min.js') }}" type="text/javascript"></script>
<!--  Charts Plugin -->
<script src="{{ asset('public/assets/js/chartist.min.js') }}"></script>
<!--  Dynamic Elements plugin -->
<script src="{{ asset('public/assets/js/arrive.min.js') }}"></script>
<!--  PerfectScrollbar Library -->
<!--  Notifications Plugin    -->
<script src="{{ asset('public/assets/js/bootstrap-notify.js') }}"></script>
<!-- Material Dashboard javascript methods -->
<script src="{{ asset('public/assets/js/material-dashboard.js?v=1.2.0') }}"></script>
<script src="{{ asset('public/assets/js/jquery.validate.min.js') }}"></script>
<script src="{{ asset('public/assets/js/validate.additional_methods.js') }}"></script>


<!-- Material Dashboard DEMO methods, don't include it in your project! -->
<script src="{{ asset('public/assets/js/demo.css') }}"></script>
<!--div class="container"-->
<!--div class="container"-->
<!--/div-->
</html>

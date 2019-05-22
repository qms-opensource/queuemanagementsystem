<?php
$dbName = \DB::getDatabaseName();
if($dbName == 'fordertIDS'){
	echo '<script>window.location.href = "install";</script>';exit();
}
use App\Setting;
$app_info = Setting::select(['logo_path','display_name','display_system'])->first();
$logo_file =  $app_info->logo_path;
$app_name = $app_info->display_name;
$app_display = $app_info->display_system;
?>
@extends('layouts.qmfront')
@section('style_page')
   <link href="{{ asset('public/assets/css/custom.css') }}" rel="stylesheet" />
@endsection
@section('content')
<div class="container">
<div class="bg-logo-login"><div class="col-md-2">
<!--<img class="ogo" src="{{ asset('uploads/'.$logo_file) }}">--></div><div class="col-md-8"><h3 style="color:#fff;text-align: center;font-weight:bold;">{{ $app_name }}</br> {{ $app_display}}</h3></div></div>
   <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading" data-background-color="purple" style="text-align:center;"></div>

                <div class="panel-body">
				  <div class="col-sm-12 col-md-6 col-md-offset-3">
                    <form class="form-horizontal" method="POST"  action="{{ route('login') }}" class="formValidate" id="formValidate" novalidate="novalidate">
                        {{ csrf_field() }}
                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <label for="email" class="col-md-3 control-label"></label>

                            <div class="col-md-6">
                               <input  id="email" id="email" type="email" autocomplete="false" readonly onfocus="this.removeAttribute('readonly');" class="form-control login-page" name="email" value="{{ old('email') }}"  placeholder="Username" required>

                                @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                            <label for="password" class="col-md-3 control-label"></label>

                            <div class="col-md-6">
                                <input id="password" id="password" type="password" autocomplete="false" readonly onfocus="this.removeAttribute('readonly');" class="form-control login-page" name="password" placeholder="Password" required>

                                @if ($errors->has('password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-3">
                                <button type="submit" class="btn btn-primary test">
                                    Login
                                </button>
                            </div>

                        </div>
                    </form>
					</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('script')
<script>
	$(document).ready(function(){

	
		$("#formValidate").validate({
			rules: {
				email: {
					required: true,
					email: true
				},
				password: {
					required: true,
				}
			},
			messages: {
				email: {
					required: "Please enter email",
					email: "Please enter a valid email"
				},
				password: {
					required: "Please enter password",
				}
				
			}
			, function(errors, event) {
			if (errors.length > 0) {
				event.preventDefault();
				alert('submit--');
			}
			}
		});
	});
</script>
@endsection
        </div>
    </div>
</body>
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
        
<script>
$(document).ready(function(){
    $( "body" ).on("click",'.manageBtn',function() {
        console.log( "Handler for .click() called." );
        if($('.t-material').html() == 'indeterminate_check_box')
            $('.t-material').html('add_box');
        else
            $('.t-material').html('indeterminate_check_box');
    });
   $( "#email" ).keyup(function() {
        var value1 = $("#email").val();
        var value2 = $("#password").val();
        if ( value1.length == 0 || value1.length > 0 )
        {
            $('.form-group .help-block').css('display','none');
        }
    });
});
</script>

@yield('script')
</html>
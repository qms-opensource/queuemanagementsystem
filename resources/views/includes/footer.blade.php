            <footer class="footer">
                <div class="container-fluid">
                   
                    <p class="copyright pull-right">
                    </p>
                </div>
            </footer>
        </div>
    </div>
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
<script src="{{ asset('public/assets/js/perfect-scrollbar.jquery.min.js') }}"></script>
<!--  Notifications Plugin    -->
<script src="{{ asset('public/assets/js/bootstrap-notify.js') }}"></script>
<!-- Material Dashboard javascript methods -->
<script src="{{ asset('public/assets/js/material-dashboard.js?v=1.2.0') }}"></script>
<script src="{{ asset('public/assets/js/jquery.validate.min.js') }}"></script>
<script src="{{ asset('public/assets/js/validate.additional_methods.js') }}"></script>
<script src="{{ asset('public/assets/js/jquery.js') }}"></script>


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
	var divHeight = $('.main-panel').height(); 
	$('.sidebar').css('min-height', divHeight+'px');
});
</script>

@yield('script')
</body>
</html>
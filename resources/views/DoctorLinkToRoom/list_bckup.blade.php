
@extends('layouts.qmlayout')
@section('content')
<style>
	.addnew{float:right;color:#fff;font-size:25px;}
	.text{float:right;font-size:20px;}
</style>
<div class="col-md-12">
	<div class="card">
		@if(Session::has('alert-success'))
			<div class="alert alert-success">
				{{Session::get('alert-success')}}
			</div>
		@endif
		
		@if(Session::has('alert-danger'))
			<div class="alert alert-danger">
				{{Session::get('alert-danger')}}
			</div>
		@endif
		<div class="card-content">
			<div class="card-header" data-background-color="purple">
				<div class="row">
					<div class="col-md-12">
						<h4 class="title">Manage Doctor Linking</h4>
					</div>
				</div>
			</div>
			<div class="card-content table-responsive">
			 <form name="doctorLinkForm" method="post" action="{{url('/manage-doctors/link')}}" class="formValidate" id="formValidate" novalidate="novalidate">
			   {{ csrf_field() }}
				@if(!empty($doctorswithroom) && count($doctorswithroom) >= 1)
				<table class="table">
					<thead class="text-primary">
						<th>Room Name</th>
						<th width="30%">Doctors</th>
					</thead>
					<tbody>
						@foreach($doctorswithroom as $key=>$doctorswithroomdata)
							<tr>
								<td>{{ $doctorswithroomdata->room_name}}<input type="hidden" name="myroomdata" value="{{ $doctorswithroomdata->room_name}}"></td>
								<td><div class="form-group label-floating">
									<label class="control-label">Select</label>
									<select data-room = "{{$key}}" name="doctor[{{$key}}]" id="doctor" value="{{ old('doctor') }}" class="form-control doctor">
										<option value="">None</option>
										@foreach($doctors as $doctor)
											<option value="{{$doctor['get_doctor']['id']}}"  @if(old('doctor') == $doctor['get_doctor']['id'] || $doctorswithroomdata['getDoctor']['name'] == $doctor['get_doctor']['name']) selected="selected"   @endif>{{ $doctor['get_doctor']['name'] }}</option>
										@endforeach
									</select>
									<span class="has-error">{{ $errors->doctor->first('doctor') }}</span>
								</div>
								</td>
							</tr>
						@endforeach
					</tbody>
				</table>
				<!--<input type="hidden" id="room_data" name="room_data[]" >
				<input type="hidden" id="doctor_data" name="doctor_data[]" >-->
				<button type="submit" id="LinkDoctor" class="btn btn-primary pull-right">Link Doctor</button>
				</form>
				@else <h4 class="title" style="color:#34659d!important;padding-left:12px;">No room exists.</h4>
				@endif
				<div class="clearfix"></div>
			</div>
		</div>
	</div>
</div>
@endsection
@section('script')
<script>
	$(document).ready(function(){
		/* $('.doctor').change(function(){
			var room = $('option:selected', this).attr('related-room');
			var doctor = $('option:selected', this).attr('related-doctor');
            var doctorname = $('option:selected', this).text();  
            $.ajax({
				type:'GET',
				url:'{{url("manage-doctor/changedoctorlink/")}}/'+room+'/'+doctor+'/'+doctorname,
				success:function(data){
				}
			});
		}); */
		
		//console.log(selected);
		$('.doctor').on('change',function () {
			var selected = [];
			var $this = $(this);
	        //console.log($(this));
	        var roomId = $(this).data('room');
	        var val = $this.val();
	        //console.log(roomId);
		$('.doctor option:selected').each( function() {
			var newVal = $(this);
	        //if(newVal.val()!=""){
        		selected.push(newVal.val());
        	//}
		});
			//console.log(selected);

			

			var inArray = $.inArray(val,selected);
			//console.log(inArray);

			//$('.doctor').each( function() {
		        
			if(inArray > -1){
				var mydata = $(this).data('room');
				console.log(mydata);
		        	//$('.doctor')[inArray].val("");
		        	$('select[name="doctor['+inArray+']"]').val("");
		        	//selected[inArray] = "";
		        	//console.log(selected[inArray]);
		        }
			//});
			


			/*var txt = [];
		    var txtdata = [];
    		var id = $(this).closest("tr").find('td:eq(0)').text();
    		var myroomdata = $('option:selected', this).attr('related-room');
    		var mydoctordata = $('option:selected', this).val();
    		txt.push(myroomdata);
    		txtdata.push(mydoctordata);
    		jQuery('#doctor_data').val(JSON.stringify(txt));
            jQuery('#room_data').val(JSON.stringify(txtdata));*/
		});
		 /*  var txt = [];
		   var txtdata = [];
            $('.doctor').each(function () {  
            var mydata = $('option:selected', this).attr('related-room');
            var mydatatext = $('option:selected', this).text();  
            	if(mydata != null)
            	{
            		
					txt.push(mydata);
                }
                if(mydatatext.length > 0)
                {
                	txtdata.push(mydatatext );
                }
            });
             jQuery('#doctor_data').val(JSON.stringify(txtdata));
            jQuery('#room_data').val(JSON.stringify(txt));
         
        */
		});
</script>
@endsection
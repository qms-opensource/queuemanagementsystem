@extends('layouts.qmlayout')
@section('content')
<div class="col-md-12">
	<div class="card">
		<div class="card-header" data-background-color="purple">
			<h4 class="title">Room</h4>
			<p class="category">Edit Room</p>
		</div>
		<div class="card-content">
			<form name="manageroom" method="post" action="{{url('/manage-rooms/update')}}" class="formValidate" id="formValidate" novalidate="novalidate">
				<div class="row">
					{{ csrf_field() }}
					<div class="col-md-4">
					<div class="col-md-1 reqStar">*</div>
					<div class="col-md-11">
						<div class="form-group label-floating">
							<label class="control-label">Room Name</label>
							<input type="text" name="room_name" maxlength="30" value="{{ old('room_name', $roomData->room_name) }}" class="form-control">
							<span class="has-error">{{ $errors->edit->first('room_name') }}</span>
							<input type="hidden" name="id" value="{{$roomData->id}}" />
							<input type="hidden" name="hall_id" value="@if(isset($hallData->id)){{$hallData->id}} @endif" />
						</div>
					</div>
					</div>
					<div class="col-md-4">
					<div class="col-md-1 reqStar">*</div>
					<div class="col-md-11">
						<div class="form-group label-floating">
							<label class="control-label">Department</label>
							<select name="department" value="{{ old('department', $roomData->department ) }}" class="department form-control">
								<option value=""></option>
								@foreach($departments as $department)
									<option value="{{$department->id}}"
									@if ($department->id == old('department', $roomData->department))
										selected="selected"
									@endif
									>{{$department->name}}</option>
								@endforeach
							</select>
							<span class="has-error">{{ $errors->edit->first('department') }}</span>
						</div>
					</div>
					</div>
					<div class="col-md-4">
					<div class="col-md-1 reqStar">*</div>
					<div class="col-md-11">
						<div class="form-group label-floating">
							<label class="control-label">Whether you want to associate hall</label>
							<select name="hall_select" id="hall_select" value="{{ old('hall_select') }}" class="form-control">
							<option value=''></option>
							<option value='{{ $roomData->department }}'@if(!empty($hallData)) @if( $hallData->Status == 1) selected="selected" @endif @endif>yes</option>
							<option value='no'@if(!empty($hallData)) @if( $hallData->Status == 0) selected="selected" @endif @endif >No</option>
							</select>
							<span class="has-error">{{ $errors->add->first('hall_select') }}</span>
						</div>
					</div>
					</div>
					</div>
					<div class="row">
					@if(!empty($hallData))
					@if($hallData->Status != 0)
					<div class="col-md-4 hallshowdata">
					<div class="col-md-1 reqStar">*</div>
					<div class="col-md-11">
						<div class="form-group label-floating">
							<label class="control-label">Hall</label>
							<select name="hall"  id="hall1" value="{{ old('hall') }}" class="form-control">
								<option value=""></option>
								@foreach($halls as $hall)
									<option value="{{ $hall->id }}"
									@if($hall->id == old('hall', $roomData->hall))
										selected="selected"
									@endif
									>{{ $hall->name }}</option>
								@endforeach
							</select>
							<span class="has-error">{{ $errors->edit->first('hall') }}</span>
						</div>
					</div>
					</div>
					@endif
					@endif
					<div class="col-md-4 hallshow" style="display:none;">
					<div class="col-md-1 reqStar">*</div>
					<div class="col-md-11">
						<div class="form-group label-floating">
							<label class="control-label">Hall</label>
							<select name="hall"  id="hall" value="{{ old('hall') }}" class="form-control">
								<option value=""></option>
								@foreach($halls as $hall)
									<option value="{{ $hall->id }}"
									@if($hall->id == old('hall', $roomData->hall))
										selected="selected"
									@endif
									>{{ $hall->name }}</option>
								@endforeach
							</select>
							<input type="hidden" id="halldata" name="halldata" />
							<span class="has-error">{{ $errors->edit->first('hall') }}</span>
						</div>
					</div>
					</div>
					@if(!empty($hallData))
					@if($hallData->Status == 0)
					<div class="col-md-4 capacityshow" style="display:block;">
					<div class="col-md-1 reqStar">*</div>
					<div class="col-md-11">
						<div class="form-group label-floating">
							<label class="control-label">Capacity</label>
							<input type="text" id="capacity" name="capacity" maxlength="5" value="{{ old('capacity',$hallData->capacity) }}" class="form-control">
							<span class="has-error">{{ $errors->edit->first('capacity') }}</span>
						</div>
					</div>
					</div>
					</div>
					@endif
					@endif
					@if(!empty($hallData))
					<div class="col-md-4 capacity" style="display:none;">
					<div class="col-md-1 reqStar">*</div>
					<div class="col-md-11">
						<div class="form-group label-floating">
							<label class="control-label">Capacity</label>
							<input type="text" id="capacity" name="capacity" maxlength="5" value="{{ old('capacity',$hallData->capacity) }}" class="form-control">
							<span class="has-error">{{ $errors->edit->first('capacity') }}</span>
							<input type="hidden" id="capacitydata" name="capacitydata"/>
						</div>
					</div>
					</div>
					</div>
					@endif
				<button type="submit" class="btn btn-primary pull-right">Update Room</button>
				<div class="clearfix"></div>
			</form>
		</div>
	</div>
</div>
@endsection
@section('script')
<script>
	$(document).ready(function(){
		$("#formValidate").validate({
			rules: {
				room_name: {
					required: true,
					minlength: 2,
					maxlength: 30,
					characterSet: true
				},
				hall: {
					required: true,
				},
				department: {
					required: true,
				},
				hall_select: {
					required: true,
				},capacity: {
					required: true,
				},
			},
			messages: {
				room_name: {
					required: "Please enter room name",
					minlength: "Room name must consist of at least 2 characters",
					maxlength: "Max of 30 characters allowed",
					characterSet: "Letters, numbers, space and underscores only please"
				},
				hall: {
					required: "Please select a hall",
				},capacity: {
					required: "Please add capacity",
				},
				department: {
					required: "Please select a department",
				},
				hall_select: {
					required: "Please select option whether a hall have to be select or not"
				}
			}
		});
		$('.hallshow').css('display','none');
	});  
	
	$('.department').on('change',function(){
			var id = $(this).val();
			if(id != '')
			$("#hall_select").html("<option value=''></option><option value='"+id+"'>yes</option><option value='no'>no</option>");
		    else
			$("#hall_select").html("<option value=''></option><option value='yes'>yes</option><option value='no'>no</option>");
	});

	$('#hall_select').on('change',function(){
			var id = $(this).val();
			if(id != 'no' || id == 'no'){
			  $('.hallshowdata').css('display','none')
			  $('.capacityshow').css('display','none')
			}
			getHallsByDepartment(id);
	});
	$('#hall').on('change',function(){
			var halldata = $(this).val();
			$("#halldata").val(halldata);

	});
	$('#addroom').on('click',function(){

			var roomname = $('#room_name').val();
			var hall = $('#hall').val();
			var department = $('#department').val();
			if($('#room_name').val().length >  0   &&
        $('#hall').val().length >  0   &&
       $('#department').val().length  >  0 )
			{
			  $('#addroom').prop('disabled', true);
			  $('#formValidate').submit();
			}
	});
	function getHallsByDepartment(id){
		console.log(id);
		if(id == 'no')
		{
			$('.hallshow').css('display','none')
			$("#halldata").val(""); 
			$(".capacity").removeAttr('style');
			$(".capacity").attr("display",'block');
			var capacityval = $("#capacity").val();
			$("#capacitydata").val(capacityval);
			
		} else{
			$(".hallshow").removeAttr('style');
			$(".hallshow").attr("display",'block');
			$(".capacity").css("display","none");
			$("#capacitydata").val(""); 
			if(id!=""){
				$.ajax({
					type:'GET',
					url:'{{url("manage-rooms/get-hall/")}}/'+id,
					success:function(data){
						var obj = jQuery.parseJSON(data);
						$("#hall").html("<option value=''></option>");
						if(obj.status==true){
							console.log(obj.data);
							$.each(obj.data,function(i,item){
								console.log(item);
								$("#hall").append($("<option></option>").val(item.id).html(item.name));
							});
						}
					}
				});
			}
		}
	}
</script>
@endsection
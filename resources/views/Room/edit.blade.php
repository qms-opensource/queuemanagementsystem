@extends('layouts.qmlayout')
@section('content')
<div class="col-md-12">
	<div class="card">
		<div class="card-content">
		   <div class="card-header" data-background-color="purple">
				<h4 class="title">Manage Rooms</h4>
				<p class="category">Edit Room</p>
			</div>
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
							<input type="hidden"id="hall_id" name="hall_id" value="@if(isset($hallData->id)){{$hallData->id}} @endif" />
						</div>
					</div>
					</div>
					<div class="col-md-4">
					<div class="col-md-1 reqStar">*</div>
					<div class="col-md-11">
						<div class="form-group label-floating">
							<label class="control-label">Department</label>
							<select name="department" id="department" value="{{ old('department', $roomData->department ) }}" class="department form-control">
								<option value=""></option>
								@foreach($departments as $department)
									<option id="{{$department->add_hall}}" value="{{$department->id}}"
									@if ($department->id == old('department', $roomData->department))
										selected="selected"
									@endif
									>{{$department->name}}</option>
								@endforeach
							</select>
							<input type="hidden" name="hall_status" id="hall_status" value="">
							<span class="has-error">{{ $errors->edit->first('department') }}</span>
						</div>
					</div>
					</div>
					@if(!empty($hallData) || empty($hallData))				
					<div class="col-md-4 hallshowdata"@if(!empty($hallData)) @if($hallData->Status == 0) style="display:none;" @elseif(!empty($halls)) style="display:block;" @endif @endif @if(!empty($halls)) style="display:block;"  @endif >
					<div class="col-md-1 reqStar">*</div>
					<div class="col-md-11">
						<div class="form-group label-floating">
							<label class="control-label">Block</label>
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
							<span class="has-error">{{ $errors->edit->first('hall') }}</span>
						</div>
					</div>
					</div>
					@endif
					@if(!empty($hallData))	
						<div class="col-md-4 capacityshow" @if($hallData->Status == 0) style="display:block;" @else style="display:none;" @endif >
						<div class="col-md-1 reqStar">*</div>
						<div class="col-md-11">
							<div class="form-group label-floating">
								<label class="control-label">Capacity</label>
								<input type="text" id="capacity1" name="capacity1" maxlength="5" value="{{ old('capacity1',$hallData->capacity) }}" class="form-control">
								<span class="has-error">{{ $errors->edit->first('capacity') }}</span>
							</div>
						</div>
						</div>
					@else
					<div class="col-md-4 capacityshow" @if(!empty($departmentData))  @if($departmentData->add_hall == 1) style="display:none;" @else style="display:block;" @endif  @endif >
						<div class="col-md-1 reqStar">*</div>
						<div class="col-md-11">
							<div class="form-group label-floating">
								<label class="control-label">Capacity</label>
								<input type="text" id="capacity1" name="capacity1" maxlength="5" value="{{ old('capacity1') }}" class="form-control">
								<span class="has-error">{{ $errors->edit->first('capacity') }}</span>
							</div>
						</div>
						</div>
					@endif
					</div>
				<button type="submit" id="editroom" class="btn btn-primary pull-right">Update</button>
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
				},capacity1: {
					required: true,
					digits: true,
					min:2
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
					required: "Please select a block",
				},capacity1: {
					required: "Please add capacity",
					digits:"Only numbers are allowed",
					min:"Capacity should have at least 2 value"
				},
				department: {
					required: "Please select a department",
				}
			},submitHandler:function(form){
				 $('#editroom').prop('disabled', true);
			      $('#formValidate')[0].submit();
			}

		});
		var add_hall = $("#department option:selected").attr("id");
		if(add_hall == 0){
			$('.hallshowdata').css('display','none')
		} else {
		} 
	});  
	
	$('.department').on('change',function(){		
			var id = $(this).val();
			var add_hall = $("#department option:selected").attr("id");
			var hall_current_status = $('#hall_status').val(add_hall);
			if(add_hall == 0){
				$("#hall").empty();
			   $('.hallshowdata').css('display','none')
              	$('.capacityshow').css('display','block');		  	
			} else {
				 $(".hallshowdata").removeAttr('style');
				 $('.hallshowdata').css('display','block')
			     $('.capacityshow').css('display','none')
			     $('input#capacity1').removeAttr('value');
			}
			getHallsByDepartment(id,add_hall);
	});

	$('#hall').on('change',function(){
		var halldata = $(this).val();
		$("#halldata").val(halldata);

	});

	function getHallsByDepartment(id,add_hall){
		console.log(id);
		if(add_hall == 0) {
			$('.hallshow').css('display','none')
			$("#halldata").val(""); 
			//$(".capacity").removeAttr('style');
			//$(".capacity").attr("display",'block');
			 $('.capacityshow').css('display','block');
			//var capacityval = $("#capacity").val();
			//$("#capacitydata").val(capacityval);
			
		} else{
			$(".hallshow").removeAttr('style');
			$(".hallshow").attr("display",'block');
			 $('.capacityshow').css('display','none');
		//	$(".capacity").css("display","none");
			//$("#capacitydata").val(""); 
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
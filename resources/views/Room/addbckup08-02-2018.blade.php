@extends('layouts.qmlayout')
@section('content')
<div class="col-md-12">
	<div class="card">
		<div class="card-header" data-background-color="purple">
			<h4 class="title">Room</h4>
			<p class="category">Add Room</p>
		</div>
		<div class="card-content">
			<form name="addPatientForm" method="post" action="{{ route('manage-rooms.store') }}" class="formValidate" id="formValidate" novalidate="novalidate">
				<div class="row">
					{{ csrf_field() }}
					<div class="col-md-4">
					<div class="col-md-1 reqStar">*</div>
					<div class="col-md-11">
						<div class="form-group label-floating">
							<label class="control-label">Room Name</label>
							<input type="text" id="room_name" name="room_name" maxlength="30" value="{{ old('room_name') }}" class="form-control">
							<span class="has-error">{{ $errors->add->first('room_name') }}</span>
						</div>
					</div>
					</div>
					<div class="col-md-4">
					<div class="col-md-1 reqStar">*</div>
					<div class="col-md-11">
						<div class="form-group label-floating">
							<label class="control-label">Department</label>
							<select name="department" id="department" value="{{ old('department') }}" class="department form-control">
								<option value=""></option>
								@foreach($departments as $department)
									<option value="{{$department->id}}" @if(old('department') == $department->id) selected="selected" @endif>{{$department->name}}</option>
								@endforeach
							</select>
							<span class="has-error">{{ $errors->add->first('department') }}</span>
						</div>
					</div>
					</div>
					<div class="col-md-4">
					<div class="col-md-1 reqStar">*</div>
					<div class="col-md-11">
						<div class="form-group label-floating">
							<label class="control-label">Whether you want to associate hall</label>
							<select name="hall_select" id="hall_select" value="{{ old('hall_select') }}" class="form-control">
							</select>
							<span class="has-error">{{ $errors->add->first('hall_select') }}</span>
						</div>
					</div>
					</div>
					</div>
					<div class="row">
					<div class="col-md-4">
					<div class="col-md-1 reqStar">*</div>
					<div class="col-md-11">
						<div class="form-group label-floating">
							<label class="control-label">Hall</label>
							<select name="hall" id="hall" value="{{ old('hall') }}" class="form-control">
								<option value=""></option>
								@foreach($halls as $hall)
									<option @if(old('hall') == $hall->id) selected="selected" @endif value="{{ $hall->id }}">{{ $hall->name }}</option>
								@endforeach
							</select>
							<span class="has-error">{{ $errors->add->first('hall') }}</span>
						</div>
					</div>
					</div>
					<div class="col-md-4 capacity" style="display:none;">
					<div class="col-md-1 reqStar">*</div>
					<div class="col-md-11">
						<div class="form-group label-floating">
							<label class="control-label">Capacity</label>
							<input type="text" name="capacity" id="capacity" maxlength="5" value="{{ old('capacity') }}" class="form-control">
							<span class="has-error">{{ $errors->addHall->first('capacity') }}</span>
						</div>
					</div>
					</div>
					</div>
				<button type="submit" id="addroom" class="btn btn-primary pull-right">Add Room</button>
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
		
	}); 
	$('.department').on('change',function(){
			var id = $(this).val();
			$("#hall_select").html("<option value=''></option><option value='"+id+"'>Yes</option><option value='no'>No</option>");
	});

	$('#hall_select').on('change',function(){
			var id = $(this).val();
			getHallsByDepartment(id);
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
			$("#hall").attr("disabled",true);
			$(".capacity").css("display","block");
		//	$("#hall").css("display","none");
			
		} else{
			$("#hall").attr("disabled",false);
			$(".capacity").css("display","none");
			
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
@extends('layouts.qmlayout')
@section('content')
<div class="col-md-12">
	<div class="card">
		<div class="card-content">
			<div class="card-header" data-background-color="purple">
				<h4 class="title">Manage Rooms</h4>
				<p class="category">Add Room</p>
			</div>
			<form name="addPatientForm" method="post" action="{{ route('manage-rooms.store') }}" class="formValidate" id="formValidate" novalidate="novalidate">
				<div class="row">
					{{ csrf_field() }}
					<div class="col-md-4">
					<div class="col-md-1 reqStar">*</div>
					<div class="col-md-11">
						<div class="form-group label-floating">
							<label class="control-label">Room Name</label>
							<input type="text" id="room_name" name="room_name" minlength="2" maxlength="30" value="{{ old('room_name') }}" class="form-control">
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
									<option id="{{$department->add_hall}}" value="{{$department->id}}" @if(old('department') == $department->id) selected="selected" @endif>{{$department->name}}</option>
								@endforeach
							</select>
							<span class="has-error">{{ $errors->add->first('department') }}</span>
						</div>
					</div>
					</div>
					<div class="col-md-4  halldata">
					<div class="col-md-1 reqStar">*</div>
					<div class="col-md-11">
						<div class="form-group label-floating">
							<label class="control-label">Block</label>
							<select name="hall" id="hall" value="{{ old('hall') }}" class="form-control">
								<option value=""></option>
								@foreach($halls as $hall)
									<option @if(old('hall') == $hall->id) selected="selected" @endif value="{{ $hall->id }}">{{ $hall->name }}</option>
								@endforeach
							</select>
							<span class="has-error">{{ $errors->add->first('hall') }}</span>
							<input type="hidden" id="halldata" name="halldata" />
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
				block: {
					required: true,
				},
				department: {
					required: true,
				},capacity: {
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
				block: {
					required: "Please select a block",
				},capacity: {
					required: "Please add capacity",
					digits:"Only numbers are allowed",
					min:"Capacity should have at least 2 value"
				},
				department: {
					required: "Please select a department",
				}
			},submitHandler: function (form) {
            		$("#addroom").prop("disabled", true); //disable to prevent multiple submits
            		$('#formValidate')[0].submit(); 
       		 } 
		});
		
	}); 
	$('.department').on('change',function(s){
			var id = $(this).val();
			var add_hall = $("#department option:selected").attr("id");
			getHallsByDepartment(id,add_hall);
	});

	function getHallsByDepartment(id,add_hall){
		console.log(id);
		if(add_hall == 0)
		{
			$("#hall").attr("disabled",true);
			$("#hall").html("");
			$(".halldata").css("display","none");
			$(".capacity").css("display","block");
			
		} else{
			$("#hall").attr("disabled",false);
			$(".halldata").css("display","block");
			$("#capacity").val("");
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
								$("#halldata").val(item.Status);
							});
						}
					}
				});
			}
		}
	}
</script>
@endsection
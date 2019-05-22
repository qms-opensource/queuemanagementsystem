@extends('layouts.qmlayout')
@section('content')
<style>
	.addnew{float:right;color:#fff;font-size:25px;}
	.text{float:right;font-size:20px;}
</style>
<div class="col-md-12">
	<div class="card">		
		
		<div class="card-content">
		<div class="card-header" data-background-color="purple">
			<div class="row">
				<div class="col-md-8">
					<h4 class="title">Manage Blocks</h4>
					<p class="category">Add Block</p>
				</div>
				<div class="col-md-4">
				</div>
			</div>
		</div>
			<form name="managehall" method="post" action="{{url('/manage-block/save')}}" class="formValidate" id="formValidate" novalidate="novalidate">
				{{csrf_field()}}
				<div class="row">
					<div class="col-md-4">
						<div class="col-md-1 reqStar">*</div>
						<div class="col-md-11">
							<div class="form-group label-floating">
								<label class="control-label">Block Name</label>
								<input type="text" id="hall" name="name" maxlength="30" value="{{ old('name') }}" class="form-control" />
								<span class="has-error errorhall">{{ $errors->addHall->first('name') }}</span>
							</div>
						</div>
					</div>
					<div class="col-md-4">
						<div class="col-md-1 reqStar">*</div>
						<div class="col-md-11">
							<div class="form-group label-floating">
								<label class="control-label">Capacity</label>
								<input type="text" name="capacity" maxlength="5" value="{{ old('capacity') }}" class="form-control">
								<span class="has-error">{{ $errors->addHall->first('capacity') }}</span>
							</div>
						</div>
					</div>
					<div class="col-md-4">
						<div class="col-md-1 reqStar">*</div>
						<div class="col-md-11">
							<div class="form-group label-floating">
								<label class="control-label">Department</label>
								<select name="department" value="{{ old('department') }}" class="department form-control">
									<option value=""></option>
									@foreach($departments as $department)
										@if($department->add_hall == 1)
										<option value="{{$department->id}}"  @if(old('department') == $department->id) selected="selected" @endif>{{$department->name}}</option>
										@endif
									@endforeach
								</select>
								<span class="has-error">{{ $errors->patient->first('department') }}</span>
							</div>
						</div>
					</div>
				</div>
				<button type="submit" class="btn btn-primary pull-right">Add Block</button>
				<div class="clearfix"></div>
			</form>
		</div>
	</div>
</div>
@endsection
@section('script')
<script>
	$(document).ready(function(){
		$('#hall').mouseout(function(){
			$('.errorhall').hide();
		})
		$("#formValidate").validate({
			rules: {
				name: {
					required: true,
					minlength: 2,
					maxlength: 30,
					characterSet: true
				},
				capacity: {
					required: true,
					number: true,
					maxlength: 5,
					min:2
				},
				department: {
					required: true,
				}
			},
			messages: {
				name: {
					required: "Please enter block name",
					minlength: "Block name must consist of at least 2 characters",
					maxlength: "Max of 30 characters allowed",
					characterSet: "Letters, numbers, space and underscores only please"
				},
				capacity: {
					required: "Please enter capacity",
					number: "Please enter numeric value",
					maxlength: "Please enter valid capacity",
					min:"Capacity should have at least 2 value"
				},
				department: {
					required: "Please select department",
				}
			}
		});
		
	}); 
</script>
@endsection
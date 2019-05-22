<?php                ?>
@extends('layouts.qmlayout')
@section('content')
<div class="col-md-12">
	<div class="card">
		@if(Session::has('alert-success'))
			<div class="alert alert-success">
				{{Session::get('alert-success')}}
			</div>
		@endif
		
		@if(Session::has('alert-failure'))
			<div class="alert alert-danger">
				{{Session::get('alert-failure')}}
			</div>
		@endif
		<div class="card-content">
			<div class="card-header" data-background-color="purple">
				<h4 class="title">Dashboard</h4>
			</div>
			<div class="row">
			<div class="col-md-12">
				
				<div class="col-md-3 t-left">
					<div class="form-group label-floating count-all"><?php if($id == 'all'){?>Total Tokens: {{count($patientData)}}<?php }?></div>
				</div>
				
				<div class="col-md-6">
			    </div>
				<div class="col-md-3 dep_select " >
					<div class="form-group label-floating">
						<select name="department" value="{{ old('department') }}" class="department form-control">
							<option value="all">All Departments</option>
							@foreach($departments as $department)
								<option value="{{$department->id}}"  @if($id == $department->id) selected="selected" @endif>{{$department->name}}</option>
							@endforeach
						</select>
						<span class="has-error">{{ $errors->patient->first('department') }}</span>
					</div>
				</div>
			</div>
			
			<?php 
			if(count($departments)>0)
			{
				foreach($departments as $department)
				{
					if($id == 'all' || $id == $department['id']){
				?>
					<table class="dashboard-tbl" border="1px" style="width: 96%; margin:0px auto;float: none;">
						<tr>
							<?php if(isset($departCount[$department['id']])){?>
								<td class="depart-title"  colspan="5"><h4>Department : {{$department['name']}} </h4></td>
							<td class="depart-info">
								<h4>Total Tokens: 				
								@if(isset($departCount[$department['id']]))
									{{$departCount[$department['id']]}} 
								@else
									0
								@endif</h4>
							</td>
							<?php }else{?>
								
							<td class="depart-title"  colspan="6" style="border:1px solid black; padding: 5px;">
								<div style="width: 100%">
									<div style="float:left; width: 50%;">Department : {{$department['name']}}
									</div>
									<div style="float:right; width: 50%; text-align: right;">
										Total Tokens: 	0
									</div>
								</div>
							</td>
							<?php 
							}?>
							
						</tr>
						<?php 
					if(isset($allHalls[$department['id']]))
					{
						$a = 0;
						foreach($allHalls[$department['id']] as $hall)
						{ 
							if($hall->Status == 0 && $a == 0){ 
								$a = $a+1;?>
								<tr class="room">
									<td colspan="2" width="20%">Room Name</td>
									<td>Token Count</td>
									<td>Current Token</td>
									<td>Processed Token</td>
									<td>Skipped Token</td>
								</tr>
							<?php }
							if(count($allHalls[$department['id']]) >0)
							{ 
								if(isset($allRooms[$hall->id]))
								{
									if($hall->Status == 1){ ?>
										<tr class="room">
											<td class="room-title"  width="15%" rowspan="<?php echo count($allRooms[$hall->id])+1;?>">
											<?php  if($hall->Status == 1){
											?>
											<h5 style="margin: 0px;">{{$hall->hall_name}}<br>
												(@if(isset($hallCount[$hall->id]))
													{{$hallCount[$hall->id]}} 
												@else
													0
												@endif
												)
											</h5>
											<?php }?>
											</td>
								<?php
									if(isset($allRooms[$hall->id]))
									{ 		
								?>
											<td width="20%">Room Name</td>
											<td>Token Count</td>
											<td>Current Token</td>
											<td>Processed Token</td>
											<td>Skipped Token</td>
										</tr>
										<?php 
										foreach($allRooms[$hall->id] as $key =>  $room)
										{ 
											$doctor_name = \App\Doctors::where("room_id",$room->id)->select('name')->pluck('name')->first();

											?>
											<tr class="room-info" >
												<td width="10%"> 
												{{-- @if($department['name'] == 'Pediatrics Surgeon' && strpos("$room->room_name","-") !== 'false' || $department['name'] == 'Pediatrics' && strpos("$room->room_name","-") !== 'false')
												@if(!empty($doctor_name))   --}}
												 	<?php
													 /*	$room_data = explode('-',$room->room_name); 
													 	$room_doctor = str_replace($room_data[1],$doctor_name,$room_data[1]) ; 
													 	echo $room_data[0]."-".$room_doctor; */
													 ?>
													{{-- @else
													   {{ $room->room_name }}
												 	@endif 
													 --}}
												{{-- @else --}}
													<?php echo $room->room_name; ?> 
												{{-- @endif  --}}
													
												</td>
												<td>
													@if(isset($patients[$room->id]))
														{{count($patients[$room->id])}} 
													@else
														0
													@endif
												</td>
												<td> @if(isset($currentToken[$room->id]))
														{{$currentToken[$room->id]}} 
													@else
														-
													@endif
												
												</td>
												<td>@if(isset($roomProcessedToken[$room->id]))
														{{count($roomProcessedToken[$room->id])}} 
													@else
														0
													@endif
												</td>
												<td>@if(isset($roomSkippedToken[$room->id]))
														{{count($roomSkippedToken[$room->id])}} 
													@else
														0
													@endif
												</td>
											</tr>
										<?php	
										}
									}
									 }else{
										  ?>

										  
										
								<?php
									if(isset($allRooms[$hall->id]))
									{ 
									$a = $a+1;
								?>
										
										<?php 
										foreach($allRooms[$hall->id] as $key =>  $room)
										{ ?>
											<tr class="room-info" >
												<td width="10%" colspan="2"><?php echo $room->room_name; ?></td>
												<td>
													@if(isset($patients[$room->id]))
														{{count($patients[$room->id])}} 
													@else
														0
													@endif
												</td>
												<td> @if(isset($currentToken[$room->id]))
														{{$currentToken[$room->id]}} 
													@else
														-
													@endif
												
												</td>
												<td>@if(isset($roomProcessedToken[$room->id]))
														{{count($roomProcessedToken[$room->id])}} 
													@else
														0
													@endif
												</td>
												<td>@if(isset($roomSkippedToken[$room->id]))
														{{count($roomSkippedToken[$room->id])}} 
													@else
														0
													@endif
												</td>
											</tr>
										<?php	
										}
									}
									 }
								
									
								}else
								{
									if($hall->Status){
										
									
									?>
									<tr class="room">
										<td class="room-title"  width="15%">
											<span class="t-font-normal"></span>
											<h5>{{$hall->hall_name}}<br>
												(@if(isset($hallCount[$hall->id]))
													{{$hallCount[$hall->id]}} 
												@else
													0
												@endif
												)
											</h5>
										</td>
										<td class="room-info" colspan="5"> No Rooms</td>
									</tr>
							<?php 
								}
							}
								
							} 
						}
					
					}
					else{
						?>
						<tr class="room">
							<td class="room-title"  width="15%">
								No Halls
								</h5>
							</td>
							<td class="room-info" colspan="5"> </td>
						</tr>
							<?php 
					}?>
					</table><br>
				<?php }?>
					
					<?php 
				}
			}
		?>	
		</div>
	</div>
</div>
@endsection
@section('script')
<script>
	$(document).ready(function(){
		$('.department').on('change',function(){
			var id = $(this).val();
			window.location.href = '{{url("manage-patient/dashboardData/")}}/'+id;
		});
	});
</script>
@endsection
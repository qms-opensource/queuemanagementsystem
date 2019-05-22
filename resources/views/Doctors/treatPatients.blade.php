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
		
		@if(Session::has('alert-failure'))
			<div class="alert alert-danger">
				{{Session::get('alert-failure')}}
			</div>
		@endif
		<div class="card-content">
			<div class="card-header" data-background-color="purple">
				<div class="row">
					<div class="col-md-8">
						<h4 class="title">Patients</h4>
						<p class="category">Patients</p>
					</div>
				</div>
			</div>
			<div class="card-content table-responsive">
				<div class="row doctor_start">
                    <div class="col-md-12">
                        <h4>Hi! Doctor Summeet Verma</h4><br/>
                        <div class="form-group row">
                            <label class="col-sm-2"><strong>Department</strong></label>
                            <div class="col-sm-10">
                                Internal Medicine
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="staticEmail" class="col-sm-2 col-form-label"><strong>Mobile No</strong></label>
                            <div class="col-sm-10">
                                +91-9569516378
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="staticEmail" class="col-sm-2 col-form-label"><strong>Floor No</strong></label>
                            <div class="col-sm-10">
                                1
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="staticEmail" class="col-sm-2 col-form-label"><strong>Room No</strong></label>
                            <div class="col-sm-10">
                                10
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="staticEmail" class="col-sm-2 col-form-label"><strong>Total Appointments</strong></label>
                            <div class="col-sm-10">
                                10
                            </div>
                        </div>
                        <div class="form-group row">
                            <button type="submit" class="btn btn-primary start">Start</button>
                            <button type="submit" class="btn btn-primary">Exit</button>
                        </div>
                    </div>
                </div>
				<div class="row appointment_start" style="display:none;">
                    <div class="col-md-12">
                        <h4>Appointment Status: 3/10</h4><br/>
                        <h4>Patient Detail:</h4><br/>
                        <div class="form-group row">
                            <label class="col-sm-2"><strong>Patient Name</strong></label>
                            <div class="col-sm-10">
                                Patient 1
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="staticEmail" class="col-sm-2 col-form-label"><strong>Department Name</strong></label>
                            <div class="col-sm-10">
                                Internal Medicine
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="staticEmail" class="col-sm-2 col-form-label"><strong>Room No</strong></label>
                            <div class="col-sm-10">
                                1
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="staticEmail" class="col-sm-2 col-form-label"><strong>Floor No</strong></label>
                            <div class="col-sm-10">
                                1
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="staticEmail" class="col-sm-2 col-form-label"><strong>Mobile No</strong></label>
                            <div class="col-sm-10">
                                +91-9696969696
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="staticEmail" class="col-sm-2 col-form-label"><strong>Email</strong></label>
                            <div class="col-sm-10">
                                patient1@gmail.com
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="staticEmail" class="col-sm-2 col-form-label"><strong>Patient Record</strong></label>
                            <div class="col-sm-6">
                                <textarea name="patient_record" class="col-sm-6 form-control"></textarea>
                            </div>
                        </div>
                        <div class="form-group row">
                            <button type="submit" class="btn btn-primary">Skip</button>
                            <button type="submit" class="btn btn-primary">Next</button>
                        </div>
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
		$('.start').on('click',function(){
			$('.doctor_start').hide();
            $('.appointment_start').css('display','block');
		});
	});
</script>
@endsection
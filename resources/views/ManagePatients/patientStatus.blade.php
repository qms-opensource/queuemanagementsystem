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
				<h4 class="title">Patient Status</h4>
				<p class="category">Status</p>
			</div>
			<div class="row">
                <div class="col-md-12">
                    <form method="post" action="javascript:void(0);" id="formValidate" novalidate="novalidate">              
                        <div class="col-md-4">
                         <div class="col-md-1 reqStar">*</div>
                          <div class="col-md-11">
                            <div class="form-group label-floating">
                                <label class="control-label">CR No</label>
                                <input type="text" name="name" id="name" value="" maxlength="12" class="crno form-control">
                                <span class="has-error">{{ $errors->add->first('name') }}</span>
                                <span class="invalid-error" style="display:none;color:red;">No record found for the given CR No</span>
                            </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                          <div class="col-md-11">
                            <button type="submit" id="getStatus" class="btn btn-primary pull-left">Submit</button>
                           </div>
                         </div>
				        <div class="clearfix"></div>
                    </form>
                </div>
                <div class="col-md-12 response" style="display:none;">
                    <h4 style="margin-left:15px;">Patient Status</h4>
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
                name: {
                    required: true,
                    minlength: 12,
                    maxlength: 12,
                    number: true
                }
            },
            messages: {
                name: {
                    required: "Please enter CR No",
                    minlength: "CR No must consist of at least 12 characters",
                    maxlength: "Max of 12 characters allowed",
                    number: "Only numbers are allowed"
                }
            },submitHandler: function (form) {
                    form.submit(); 
             } 
        });
		$("#getStatus").on('click',function(){
            var crno = $(".crno").val();
            getPatientByCrno(crno);
        });
		

        $("#name").on('mouseout',function(){
            $('.invalid-error').css('display','none');
        });

	});

    function getPatientByCrno(crno){
        if(crno!=""){
			$('#getStatus').attr('disabled', 'disabled');
			 $('.response').html('');
            $.ajax({
				type:'GET',
				url:'{{url("/get-patient/")}}/'+crno+'/null',
				success:function(response){
					var crnoStatus = response.status;
                    if(crnoStatus==0){
                        $('.invalid_crno').css('display','block');
                        $('.invalid-error').css('display','block');
                        $('.col-md-12.response').css("display","none");
                        $('#getStatus').attr('disabled', false); //disable to prevent multiple submits
                    }else{
                        $('.invalid_crno').css('display','none');
                        if(response.status!=""){
							
                            $('.col-md-12.response').css("display","block");
							var mydata = '';
							console.log(response);
							 $.each(response.data, function(item2, value1){
								 mydata += '<div style=" border: 1px solid #34659d; margin-bottom: 10px" class="col-md-12">';
								 $.each(value1, function(item, value){
                                var itemsVal = item.split('_');
                                if(itemsVal.length>1){
                                    item1 = itemsVal[0]+" "+itemsVal[1];
                                    console.log(item1);
                                }else{
                                    item1 = itemsVal[0];
                                }
                                mydata += '<div class="col-md-4"><div class="form-group label-floating is-focused">\
                                <label class="control-label">'+item1+'</label>\
                                <input type="text" name="name" value="'+value+'" class="crno form-control" readonly>\
                                 </div></div>';
                                
                            });
								 mydata += '</div>';
							 });
							 $('.col-md-12.response').append(mydata);
                         
                            $('#getStatus').attr('disabled', false); //disable to prevent multiple submits
                        }
                    }
				}
			});
        }
    }
</script>
@endsection
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
                    <form id="#formValidate" method="post" action="javascript:void(0);">
                        <div class="col-md-4">
                            <div class="form-group label-floating">
                                <label class="control-label">CR No</label>
                                <input type="text" name="name" value="" maxlength="30" class="crno form-control">
                                <span class="has-error invalid_crno" style="display:none;">Invalid CR No</span>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <button type="submit" id="getStatus" class="btn btn-primary pull-left">Submit</button>
                        </div>
				        <div class="clearfix"></div>
                    </form>
                </div>
                <div class="response" style="display:none;">
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
		$("#getStatus").on('click',function(){
            var crno = $(".crno").val();
            getPatientByCrno(crno);
        });
	});

    function getPatientByCrno(crno){
        if(crno!=""){
            $.ajax({
				type:'GET',
				url:'{{url("/get-patient/")}}/'+crno+'/null',
				success:function(response){
					var crnoStatus = response.status;
                    if(crnoStatus==0){
                        $('.invalid_crno').css('display','block');
                        $('.response').css("display","none");
                    }else{
                        $('.invalid_crno').css('display','none');
                        if(response.status!=""){
                            $('.response').css("display","block");
                            $.each(response.data, function(item, value){
                                var itemsVal = item.split('_');
                                if(itemsVal.length>1){
                                    item1 = itemsVal[0]+" "+itemsVal[1];
                                    console.log(item1);
                                }else{
                                    item1 = itemsVal[0];
                                }
                                $('.response').append('<div class="col-md-4"><div class="form-group label-floating is-focused">\
                                <label class="control-label">'+item1+'</label>\
                                <input type="text" name="name" value="'+value+'" class="crno form-control" readonly>\
                            </div></div>');
                            });
                            $('#getStatus').attr('disabled', 'disabled');
                        }
                    }
				}
			});
        }
    }
</script>
@endsection
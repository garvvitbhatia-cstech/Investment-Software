@extends('layout.admin.dashboard')
@php
$siteUrl = env('APP_URL');
@endphp
@section('content')

<style>
	.required:after {
		content:" *";
		color: red;
	  }
</style>

<div class="container-xxl flex-grow-1 container-p-y">
  <div class="row">
    <div class="col">
      <h6 class="mt-6">Edit Ally Type</h6>
      <div class="row">
        <div class="col-12 col-md-9">
          <div class="card mb-6">
            <form id="pageForm" enctype="multipart/form-data" method="post">
            	<input type="hidden" name="row_id" value="{{$record->id}}"/>
              <div class="card-header px-0 pt-0">
                <div class="nav-align-top">
                  <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item">
                      <button type="button" class="nav-link active" data-bs-toggle="tab" data-bs-target="#form-tabs-personal" aria-controls="form-tabs-personal"
role="tab" aria-selected="true"> <span class="icon-base ti tabler-user icon-lg d-sm-none"></span><span class="d-none d-sm-block">General Info</span> </button>
                    </li>
                  </ul>
                </div>
              </div>
              <div class="card-body">
                <div class="tab-content p-0">
                  <div class="tab-pane fade active show" id="form-tabs-personal" role="tabpanel">
                    <div class="row g-2">
                      <div class="col-md-4">
                        <label class="form-label required" for="title">Ally Type</label>
                        <input type="text" id="ally_type" name="ally_type" value="{{$record->ally_type}}" class="form-control" />
                      </div>
                      <div class="col-md-4">
                        <label class="form-label required" for="title">Ally CODE Initials</label>
                        <input type="text" id="ally_code" name="ally_code" class="form-control" value="{{$record->ally_code}}" />
                      </div>
                      <div class="col-md-4">
                        <label class="form-label required" for="title">Ally Registration Fee</label>
                        <input type="text" id="ally_fees" name="ally_fees" class="form-control numberonly" value="{{$record->ally_fees}}" maxlength="10" />
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </form>
          </div>
        </div>
        <div class="col-12 col-md-3">
          <div class="card mb-6">
            <div class="card-body">
              <div class="pt-6">
                <button  type="button" id="submitBtn" class="btn btn-primary me-4">Submit</button>
                <button type="reset" onclick="window.location.href='{{route('admin.ally-types')}}'" class="btn btn-label-secondary" >Cancel</button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript">
$(document).ready(function () {
	$('.numberonly').keypress(function(e){
		var charCode = (e.which) ? e.which : event.keyCode
		if(String.fromCharCode(charCode).match(/[^0-9+]/g))
		return false;
	});
});
var addUrl = "{{url('/panel/edit-ally-type/')}}/{{$record->id}}";
$(document).ready(function(){
	$('#submitBtn').click(function(e) {
		$('#submitBtn').html('Processing...');
		var form = $('#pageForm')[0];
		var formData = new FormData(form);
       $.ajax({
			headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
			type: 'POST',
			data:formData,
			url: addUrl,
			processData: false,
            contentType: false,
			success: function(response){
				$('#submitBtn').html('Submit');
				var obj = JSON.parse(response);
				 if(obj['heading'] == "Success"){
					swal("", obj['msg'], "success").then((value) => {
						window.location.href = "";
					});					
				}else{
					swal("Error!", obj['msg'], "error");
					return false;
				}
			},error: function(ts) {
				$('#submitBtn').html('Submit');
				swal("Error!", 'Something went wrong, please try after sometime.', "error");
				return false;
			}
		}); 
    });
});
</script> 
@endsection
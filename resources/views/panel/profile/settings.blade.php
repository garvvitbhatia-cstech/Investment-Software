@extends('layout.admin.dashboard')
@section('content')

@php
$siteUrl = env('APP_URL');
@endphp 

<!-- Content -->
<div class="container-xxl flex-grow-1 container-p-y">
  <div class="row g-6"> 
    <!-- Form controls -->
    <div class="col-md-8">
      <div class="card">
        <h5 class="card-header">Settings</h5>
        <div class="card-body">
          <form id="pageForm" class="row" method="post" enctype="multipart/form-data">
            <input type="hidden" name="old_banner" id="old_banner" value="{{$userData->photo}}" />
            <div class="col-md-6">
              <div class="mb-4">
                <label for="" class="form-label">Admin Email</label>
                <input type="text" class="form-control" id="admin_email" name="admin_email" value="{{$userData->admin_email}}" />
              </div>
            </div>
            <div class="col-md-6">
              <div class="mb-4">
                <label for="" class="form-label">Company Name</label>
                <input type="text" class="form-control" id="company_name" name="company_name" value="{{$userData->company_name}}" />
              </div>
            </div>
            <div class="col-md-6">
              <div class="mb-4">
                <label for="" class="form-label">Company Contact</label>
                <input type="text" class="form-control" id="mobile" name="mobile" value="{{$userData->mobile}}" />
              </div>
            </div>
            <div class="col-md-6">
              <div class="mb-4">
                <label for="city" class="form-label">Whatsapp</label>
                <input type="text" class="form-control" id="whatsapp" name="whatsapp" value="{{$userData->whatsapp}}" />
              </div>
            </div>
            <div class="col-md-12">
              <div class="mb-4">
                <label for="address" class="form-label">Address</label>
                <input type="text" class="form-control" id="business_address" name="business_address" value="{{$userData->business_address}}" />
              </div>
            </div>
            <div class="col-md-12">
              <div class="mb-4">
                <label for="address" class="form-label">Footer Content </label>
                <input type="text" class="form-control" id="footer_content" name="footer_content" value="{{$userData->footer_content}}" />
              </div>
            </div>
            <div class="col-md-12">
              <div class="mb-4">
                <label for="address" class="form-label">Footer Info</label>
                <textarea rows="4" class="form-control" id="footer_info" name="footer_info">{{$userData->footer_info}}</textarea>
              </div>
            </div>
            @if($userData->type == 'Admin')
            <div class="col-md-6">
              <div class="mb-4">
                <label for="banner" class="form-label">Profile Pic</label>
                <input type="file" class="form-control" id="banner" name="banner" />
              </div>
            </div>
            @endif
            <div class="mb-4">
              <button type="button" id="submitBtn" class="btn btn-primary me-3">Submit</button>
            </div>
          </form>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      @if($userData->type == 'Admin')
      <div class="card">
        <div class="card-body"> 
        @if($userData->photo != '')
        <div class="mb-4">
        <img src="{{$siteUrl}}public/admin/images/profile/{{$userData->photo}}" width="100%" />
        </div>
        @endif 
        </div>
      </div>
      @endif            
    </div>
  </div>
</div>
<script type="text/javascript">
var addUrl = "{{route('admin.save-setting')}}";
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
				swal("Error!", 'Some thing want to wrong, please try after sometime.', "error");
				return false;
			}
		}); 
    });
});
</script> 
@endsection
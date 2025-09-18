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
        <h5 class="card-header">Profile</h5>
        <div class="card-body">
          <form id="pageForm" class="row" method="post" enctype="multipart/form-data">
            <input type="hidden" name="old_banner" id="old_banner" value="{{$userData->photo}}" />
            <div class="col-md-6">
              <div class="mb-4">
                <label for="name" class="form-label">Name</label>
                <input type="text" class="form-control" name="name" id="name" value="{{$userData->name}}" />
              </div>
            </div>
            <div class="col-md-6">
              <div class="mb-4">
                <label for="" class="form-label">Email</label>
                <input type="text" class="form-control" readonly="readonly"  id="" value="{{$userData->email}}" />
              </div>
            </div>
            <div class="col-md-6">
              <div class="mb-4">
                <label for="" class="form-label">Mobile</label>
                <input type="text" class="form-control" id="" readonly="readonly" value="{{$userData->mobile}}" />
              </div>
            </div>
            <div class="col-md-6">
              <div class="mb-4">
                <label for="address" class="form-label">Address</label>
                <input type="text" class="form-control" id="address" name="address" value="{{$userData->address}}" />
              </div>
            </div>
            <div class="col-md-6">
              <div class="mb-4">
                <label for="city" class="form-label">City</label>
                <input type="text" class="form-control" id="city" name="city" value="{{$userData->city}}" />
              </div>
            </div>
            <div class="col-md-6">
              <div class="mb-4">
                <label for="state" class="form-label">State</label>
                <input type="text" class="form-control" id="state" name="state" value="{{$userData->state}}" />
              </div>
            </div>
            <div class="col-md-6">
              <div class="mb-4">
                <label for="country" class="form-label">Country</label>
                <input type="text" class="form-control" id="country" name="country" value="{{$userData->country}}" />
              </div>
            </div>
            <div class="col-md-6">
              <div class="mb-4">
                <label for="zipcode" class="form-label">Zipcode</label>
                <input type="text" class="form-control" id="zipcode" name="zipcode" maxlength="6" value="{{$userData->zipcode}}" />
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
var addUrl = "{{route('admin.saveProfile')}}";
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
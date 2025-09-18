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
        <h5 class="card-header">Change Password</h5>
        <div class="card-body">
          <form id="pageForm" class="row" method="post" enctype="multipart/form-data">
            <div class="col-md-4">
              <div class="mb-4">
                <label for="name" class="form-label">Current Password</label>
                <input type="password" class="form-control" name="current_password" id="current_password">
              </div>
            </div>
            <div class="col-md-4">
              <div class="mb-4">
                <label for="" class="form-label">New Password</label>
                <input type="password" class="form-control" name="password" id="password">
              </div>
            </div>
            <div class="col-md-4">
              <div class="mb-4">
                <label for="" class="form-label">Confirm Password</label>
                <input type="password" class="form-control" name="confirm_password" id="confirm_password">
              </div>
            </div>
            <div class="mb-4">
              <button type="button" id="submitBtn" class="btn btn-primary me-3">Submit</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript">

var addUrl = "{{route('admin.update-password')}}";

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
@extends('layout.admin.dashboard')

@php

$siteUrl = env('APP_URL');

$admin_type = Session::get('admin_type');
    
$PREV = Session::get('PREV');

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
      <h6 class="mt-6">Edit Customer</h6>
      <div class="row">
        <div class="col-12 col-md-12">
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
                        <label class="form-label required" for="title">UIDAI</label>
                        <input type="text" id="udaid" name="udaid" value="{{$record->udaid}}" class="form-control numberonly"/>
                      </div>
                      <div class="col-md-4">
                        <label class="form-label required" for="title">Employee Code</label>
                        <input type="text" id="emp_code" name="emp_code" value="{{$record->emp_code}}" class="form-control"/>
                      </div>
                      <div class="col-md-4">
                        <label class="form-label required" for="title">Customer Type</label>
                        <select name="customer_type" id="customer_type" class="form-select">  
                        	<option value="">Select</option>                       
                        	@if(isset($cust_types) && $cust_types->count()>0)
                            	@foreach($cust_types as $key => $type)
                                	<option {{$key == $record->customer_type?'selected':''}} value="{{$key}}">{{$type}}</option>
                                @endforeach
                            @endif                          
                        </select>
                      </div>
                      <div class="col-md-4">
                        <label class="form-label required" for="title">Customer Name</label>
                        <input type="text" id="cust_name" name="cust_name" value="{{$record->cust_name}}" class="form-control"/>
                      </div>
                      <div class="col-md-4">
                        <label class="form-label" for="title">PAN</label>
                        <input type="text" id="pan_no" name="pan_no" maxlength="10" value="{{$record->pan_no}}" class="form-control"/>
                      </div>
                      <div class="col-md-4">
                        <label class="form-label required" for="title">Gender</label>
                        <select name="gender" id="gender" class="form-select">                          
                          <option value="">Select</option>    
                          @if(isset($genders) && $genders->count()>0)
                            	@foreach($genders as $key => $gender)
                                	<option {{$key == $record->gender?'selected':''}} value="{{$key}}">{{$gender}}</option>
                                @endforeach
                            @endif                    
                        </select>
                      </div>
                      <div class="col-md-4">
                        <label class="form-label required" for="title">Guardain Name</label>
                        <input type="text" id="gurdain_name" name="gurdain_name" value="{{$record->gurdain_name}}" class="form-control"/>
                      </div>
                      <div class="col-md-4">
                        <label class="form-label required" for="title">Relationship</label>
                        <select name="relationship" id="relationship" class="form-select">                          
                          <option value="">Select</option>                       
                        	@if(isset($relations) && $relations->count()>0)
                            	@foreach($relations as $key => $relation)
                                	<option {{$key == $record->relationship?'selected':''}} value="{{$key}}">{{$relation}}</option>
                                @endforeach
                            @endif             
                        </select>
                      </div>
                      <div class="col-md-4">
                        <label class="form-label" for="title">Occupation</label>
                        <select name="occupation" id="occupation" class="form-select">                          
                          <option value="">Select</option>                       
                        	@if(isset($occupations) && $occupations->count()>0)
                            	@foreach($occupations as $key => $occupation)
                                	<option {{$key == $record->occupation?'selected':''}} value="{{$key}}">{{$occupation}}</option>
                                @endforeach
                            @endif                
                        </select>
                      </div>
                      <div class="col-md-4">
                        <label class="form-label required" for="title">Contact Number</label>
                        <input type="text" id="contact_no" name="contact_no" maxlength="10" value="{{$record->contact_no}}" class="form-control numberonly"/>
                      </div>
                      <div class="col-md-4">
                        <label class="form-label" for="title">Contact No.(Alter)</label>
                        <input type="text" id="alt_contact_no" maxlength="10" name="alt_contact_no" value="{{$record->alt_contact_no}}" class="form-control numberonly"/>
                      </div>
                      <div class="col-md-4">
                        <label class="form-label" for="title">Email</label>
                        <input type="text" id="email" name="email" value="{{$record->email}}" class="form-control"/>
                      </div>
                      <div class="col-md-4">
                        <label class="form-label required" for="title">Date of Birth</label>
                        <input type="date" id="dob" name="dob" value="{{$record->dob}}" class="form-control"/>
                      </div>
                      <div class="col-md-4">
                        <label class="form-label" for="title">Date of Anniversary</label>
                        <input type="date" id="doa" name="doa" value="{{$record->doa}}" class="form-control"/>
                      </div>
                      @if($PREV == 1 || $PREV == 3)
                      @if($record->branch_id != '')
                      	<input type="hidden" name="branch_id" value="{{$record->branch_id}}"/>
                      @endif
                      <div class="col-md-4">
                        <label class="form-label required" for="title">Branch</label>
                        <select name="branch_id" id="branch_id" class="form-select" {{$record->branch_id != ''?'disabled':''}}>                      
                          <option value="">Select</option>                       
                        	@if(isset($branches) && $branches->count()>0)
                            	@foreach($branches as $key => $branch)
                                	<option {{$key == $record->branch_id?'selected':''}} value="{{$key}}">{{$branch}}</option>
                                @endforeach
                            @endif  
                        </select>
                      </div>
                      @endif
                      <div class="col-md-12">
                        <label class="form-label required" for="title">Address</label>
                        <input type="text" id="address" name="address" value="{{$record->address}}" class="form-control"/>
                      </div>
                      <div class="col-md-4">
                        <label class="form-label required" for="title">State</label>
                        <select name="state" id="state" class="form-select">                          
                          <option value="">Select</option>                       
                        	@if(isset($states) && $states->count()>0)
                            	@foreach($states as $key => $state)
                                	<option {{$key == $record->state?'selected':''}} value="{{$key}}">{{$state}}</option>
                                @endforeach
                            @endif                       
                        </select>
                      </div>
                      <div class="col-md-4">
                        <label class="form-label required" for="title">City</label>
                        <input type="text" id="city" name="city" value="{{$record->city}}" class="form-control"/>
                      </div>
                      <div class="col-md-4">
                        <label class="form-label required" for="title">Pin Code</label>
                        <input type="text" id="zip_code" maxlength="6" name="zip_code" value="{{$record->zip_code}}" class="form-control numberonly"/>
                      </div>
                    </div>
                  </div>  
                  <div class="pt-6">
                    <button type="button" id="submitBtn" class="btn btn-primary me-4">Submit</button>
                    <button type="reset" onclick="window.location.href='{{route('admin.customers')}}'" class="btn btn-label-secondary" >Cancel</button>
                  </div>                 
                </div>
              </div>
            </form>
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

var addUrl = "{{url('/panel/edit-customer/')}}/{{$record->id}}";

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
@extends('layout.admin.dashboard')

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

      <h6 class="mt-6">Add Product</h6>

      <div class="row">

        <div class="col-12 col-md-9">

          <div class="card mb-6">

            <form id="pageForm" enctype="multipart/form-data" method="post">

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

                      <div class="col-md-6">

                        <label class="form-label required" for="title">Product Name*</label>

                        <input type="text" id="product_name" name="product_name" class="form-control" />

                      </div>

                      <div class="col-md-3">

                        <label class="form-label required" for="title">Unit Price*</label>

                        <input type="text" id="unit_price" name="unit_price" maxlength="15" class="form-control numberonly" />

                      </div>

                      <div class="col-md-3">

                        <label class="form-label required" for="title">Minimum Unit*</label>

                        <input type="text" id="min_unit" name="min_unit" maxlength="4" class="form-control numberonly" />

                      </div>
                      
                      <div class="col-md-3">

                        <label class="form-label required" for="title">Maximum Unit*</label>

                        <input type="text" id="max_unit" name="max_unit" maxlength="4" class="form-control numberonly" />

                      </div>

                      <div class="col-md-3">

                        <label class="form-label required" for="title">Registration Fees Per Unit*</label>

                        <input type="text" id="per_unit_register_fees" name="per_unit_register_fees" maxlength="15" class="form-control numberonly" />

                      </div>

                      <div class="col-md-3">

                        <label class="form-label required" for="title">Return in days*</label>

                        <input type="text" id="return_in_days" name="return_in_days" maxlength="4" class="form-control numberonly" />

                      </div>

                      <div class="col-md-3">

                        <label class="form-label required" for="title">Rate of Interest*</label>

                        <input type="text" id="interest_rate" name="interest_rate" maxlength="6" class="form-control numberonly" />

                      </div>
                      
                      <div class="col-md-3">
                      
                      	<div class="form-check">
                        	<label class="form-check-label" for="is_saturday_off">
                                Is Saturday Off
                              </label>
                          	<input class="form-check-input" type="checkbox" value="1" name="is_saturday_off" id="is_saturday_off">                          
						</div>


                      </div>
                      
                      <div class="col-md-3">
                      
                      <div class="form-check">
                      		<label class="form-check-label" for="is_sunday_off">
                                Is Sunday Off
                              </label>                              
                          	<input class="form-check-input" type="checkbox" value="1" name="is_sunday_off" id="is_sunday_off">                          
						</div>

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

                <button type="reset" onclick="window.location.href='{{route('admin.products')}}'" class="btn btn-label-secondary" >Cancel</button>

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

		if(String.fromCharCode(charCode).match(/[^0-9.]/g))

		return false;

	});

});

var addUrl = "{{route('admin.add-product')}}";

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

						window.location.href = "{{url('/panel/edit-product/')}}/"+obj['row_id'];

					});

				}else{

					swal("Error!", obj['msg'], "error");

					return false;

				}

			},error: function(ts) {

				$('#submitBtn').html('Submit');

				swal("Error!", 'Something went to wrong, please try after sometime.', "error");

				return false;

			}

		}); 

    });

});

</script> 

@endsection
@extends('layout.admin.dashboard')

@section('content')

<div class="container-xxl flex-grow-1 container-p-y">
  <div class="card mb-2 p-3">
    <form id="searchForm" name="searchForm">
      <div class="row">
        <div class="col-md-2">
          <input type="text" class="form-control" name="search_keywords" placeholder="Search by keywords" id="defaultFormControlInput" />
        </div>
        <div class="col-md-2">
          <select name="search_status" class="form-select">
            <option value="">Status</option>
            <option value="1">Active</option>
            <option value="2">In-Active</option>
          </select>
        </div>
        <div class="col-md-1"> <a style="color:#FFF" id="searchbuttons" onclick="filterData('search');" class="btn btn-primary waves-effect waves-light">Search</a> </div>
        <div class="col-md-1"> <a style="color:#FFF" onclick="resetFilterForm();" class="btn btn-danger waves-effect waves-light">Reset</a> </div>
      </div>
    </form>
  </div>
  
  <!-- Basic Bootstrap Table -->
  
  <div class="card">
    <h5 class="card-header">Customers <a href="{{route('admin.add-customer')}}" style="float:right; color:#FFF" class="btn btn-success waves-effect waves-light">Add</a></h5>
    <div class="table-responsive text-nowrap">
      <table class="table">
        <thead>
          <tr>
            <th>#</th>
            <th>Customer Details</th>
            <th>Customer ID</th>
            <th>Customer Type</th>
            <th>Status</th>
            <th>Investment</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody class="table-border-bottom-0" id="replaceHtml">
          <tr>
          	<input type="hidden" id="page_no" value="1"/>
            <td colspan="10" class="text-center"><img src="{{ asset('public/admin/images/svg/oval.svg') }}" class="me-4" style="width: 3rem" alt="audio"></td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
  
  <!--/ Basic Bootstrap Table --> 
  
</div>
<script type="text/javascript">
function changeCustomerStatus(table,rowID){
	var status = $('#status_value_'+rowID).val();
	$.ajax({
			type: 'POST',
			url: "{{ url('/panel/change-status') }}",
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
			data: {table:table,rowID:rowID,status:status},
			success: function(response){
				if(response == 'Success'){
					if(status == 1){
						$('#investment_btn_'+rowID).hide();
						$('#status_value_'+rowID).val(2);
						$('#status_'+rowID).removeClass('bg-label-success').addClass('bg-label-danger').html('In-Active');
					}else{						
						$('#investment_btn_'+rowID).show();
						$('#status_value_'+rowID).val(1);
						$('#status_'+rowID).removeClass('bg-label-danger').addClass('bg-label-success').html('Active');
					}
					filterData('simple');
				}else if(response == 'SessionExpire'){
					alert('Unauthorized User.'); return false;
				}else if(response == 'InvalidData'){
                    swal({
                        title: "Oops!",
                        html: 'Invalid Data.',
                        type: "error",
                        timer: 3000
                    });
				}else {
                    swal({
                        title: "Oops!",
                        text: response,
                        type: "warning",
                        timer: 3000
                    });
                }
			}
		});
}


    $(document).ready(function(){

        filterData('simple');

    });

    function filterData(type = null){

        if(type =='search'){$('#searchbuttons').html('Searching..');}
		
		var page_no = $('#page_no').val();

        $.ajax({

            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},

            type: 'POST',

            data: $('#searchForm').serialize(),

            url: "{{ url('/panel/customers_paginate') }}?page="+page_no,

            success: function(response){

                $('#replaceHtml').html(response);

                $('#searchbuttons').html('Search');

            }

        });

    }
</script> 
@endsection
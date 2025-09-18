@extends('layout.admin.dashboard')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
  <div class="card mb-2 p-3">
    <form id="searchForm" name="searchForm">
      <div class="row">
        <div class="col-md-2">
          <input type="text" class="form-control" name="search_name" placeholder="Enter Name" id="defaultFormControlInput" />
        </div>
        <div class="col-md-2">
          <input type="text" class="form-control" name="search_email" placeholder="Enter Email" id="defaultFormControlInput" />
        </div>
        <div class="col-md-2">
          <input type="text" class="form-control" name="search_contact" placeholder="Enter Contact" id="defaultFormControlInput" />
        </div>
        <div class="col-md-2">
          <select name="search_status" class="form-select">
            <option value="">Status</option>
            <option value="1">Read</option>
            <option value="2">Unread</option>
          </select>
        </div>
        <div class="col-md-1"> <a style="color:#FFF" id="searchbuttons" onclick="filterData('search');" class="btn btn-primary waves-effect waves-light">Search</a> </div>
        <div class="col-md-1"> <a style="color:#FFF" onclick="resetFilterForm();" class="btn btn-danger waves-effect waves-light">Reset</a> </div>
      </div>
    </form>
  </div>
  <!-- Basic Bootstrap Table -->
  <div class="card">
     
    <div class="table-responsive text-nowrap">
      <table class="table">
        <thead>
          <tr>
            <th>#</th>
            <th>Name</th>
            <th>Email</th>
            <th>Contact</th>
            <th>Created</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody class="table-border-bottom-0" id="replaceHtml">
          <tr>
            <td colspan="10" class="text-center"><img src="{{ asset('public/admin/images/svg/oval.svg') }}" class="me-4" style="width: 3rem" alt="audio"></td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
  <!--/ Basic Bootstrap Table --> 
</div>

<div class="modal fade" id="viewDetails" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered1 modal-simple modal-add-new-cc">
      <div class="modal-content">
        <div class="modal-body">
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          <div class="text-center mb-6">
            <h4 class="mb-2">View Details</h4>
            <!--<p>Create a brand new category from here.</p>-->
          </div>
            <div id="user_details_html"></div>  
        </div>
      </div>
    </div>
  </div>

<script type="text/javascript">
	function getDetails(rowId){
		$('#user_details_html').html('Loading...');
		$.ajax({
			headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
			type: 'POST',
			data:{rowId:rowId},
			url: "{{route('admin.get-enquiry')}}",
			success: function(response){
				$('#user_details_html').html(response);
			}
		});
	}
	
    $(document).ready(function(){
        filterData('simple');
    }); 
	       
    function filterData(type = null){
        if(type =='search'){$('#searchbuttons').html('Searching..');}
        $.ajax({
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            type: 'POST',
            data: $('#searchForm').serialize(),
            url: "{{ url('/panel/enquiries_paginate') }}",
            success: function(response){
                $('#replaceHtml').html(response);
                $('#searchbuttons').html('Search');
            }
        });
    }
</script> 
@endsection
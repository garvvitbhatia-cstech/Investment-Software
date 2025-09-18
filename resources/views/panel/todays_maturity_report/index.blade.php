@extends('layout.admin.dashboard')

@section('content')

@php
    $action =  Route::getCurrentRoute()->getName();
    $admin_type = Session::get('admin_type');
@endphp

<div class="container-xxl flex-grow-1 container-p-y">
  <div class="card mb-2 p-3">
    <form id="searchForm" name="searchForm">
      <div class="row">
      	<div class="col-md-2">
          <input type="date" class="form-control" id="from_date" name="from_date" placeholder="dd-mm-yyyy" value="{{date('Y-m-d')}}">
        </div>
        <div class="col-md-2">
          <input type="date" class="form-control" id="to_date" name="to_date" placeholder="dd-mm-yyyy" value="{{date('Y-m-d')}}">
        </div>
        <div class="col-md-2">
          <select class="form-select" name="myprod">
            <option value="">Select Scheme</option>
            @if(isset($products) && $products->count()>0)
            	@foreach($products as $key => $product)
                	<option value="{{$product->id}}">{{$product->product_name}}</option>
                @endforeach
            @endif
          </select>
        </div>
        <div class="col-md-2">
          <input type="text" class="form-control" name="search_keywords" placeholder="Search by keywords" id="defaultFormControlInput" />
        </div>
        <div class="col-md-1">
        	<a style="color:#FFF" id="searchbuttons" onclick="filterData('search');" class="btn btn-primary waves-effect waves-light">Search</a>
        </div>
        <div class="col-md-1">
        	<a style="color:#FFF" onclick="resetFilterForm();" class="btn btn-danger waves-effect waves-light">Reset</a>
        </div>
      </div>
    </form>
  </div>

  <!-- Basic Bootstrap Table -->

  <div class="card">
    <h5 class="card-header">Maturity Report</h5>
    <div class="table-responsive text-nowrap">
      <table class="table">
        <thead>
          <tr>
            <th>Invoice No</th>
            <th>Investment Date</th>
            <th>Scheme Name</th>
            <th>Customer Name (UIDAI)</th>
            @if($PREV == 1 || $PREV == 3)
            	<th>Branch</th>
            @endif
            <th>Due Amount</th>
            <th>Due Date</th>
            <th>Status</th>
          </tr>
        </thead>
        <tbody class="table-border-bottom-0" id="replaceHtml">
        	<form id="brsupdate" name="brsupdate" method="post" action="#">
          <tr>
            <td colspan="20" class="text-center"><img src="{{ asset('public/admin/images/svg/oval.svg') }}" class="me-4" style="width: 3rem" alt="audio"></td>
          </tr>
          </form>
        </tbody>
      </table>      
    </div>    
  </div>  
  <!--/ Basic Bootstrap Table --> 
</div>
 

<script type="text/javascript">	
	$(document).ready(function(){
		filterData('simple');
	});

	function filterData(type = null){
		if(type =='search'){$('#searchbuttons').html('Searching..');}
		$.ajax({
			headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
			type: 'POST',
			data: $('#searchForm').serialize(),
			url: "{{ url('/panel/todays_maturity_report_paginate') }}",
			success: function(response){
				$('#replaceHtml').html(response);
				$('#searchbuttons').html('Search');
			}
		});
	}
</script>
@endsection
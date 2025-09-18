@extends('layout.admin.dashboard')

@section('content')

@php
    $action =  Route::getCurrentRoute()->getName();
    $admin_type = Session::get('admin_type');
    $PREV = Session::get('PREV');
@endphp

<div class="container-xxl flex-grow-1 container-p-y">
  <div class="card mb-2 p-3">
    <form id="downloadForm" name="downloadForm">
      <div class="row">
        <div class="col-md-3">
          <label class="col-form-label">From Date:</label>
          <br />
          <input type="date" class="form-control" id="from_date" name="from_date" placeholder="dd-mm-yyyy" value="">
        </div>
        <div class="col-md-3">
          <label class="col-form-label">To Date:</label>
          <br />
          <input type="date" class="form-control" id="to_date" name="to_date" placeholder="dd-mm-yyyy" value="">
        </div>
        <div class="col-md-3">
          <label class="col-form-label">Status</label>
          <br />
          <select class="form-select" name="myprod">
            <option value="">All</option>
            <option value="1">Cleared</option>
            <option value="2">Bounced</option>
          </select>
        </div>
        <div class="col-md-2">
          <label class="col-form-label">&nbsp;</label>
          <br />
          <a style="color:#FFF" onclick="exportData();" id="exportCsvBtn" class="btn btn-primary waves-effect waves-light"><i class="icon-base ti tabler-search me-3 icon-md"></i>Download Report</a> </div>
      </div>
    </form>
  </div>
  <div class="card mb-2 p-3">
    <form id="searchForm" name="searchForm">
      <div class="row">
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
    <h5 class="card-header">BRS</h5>
    <div class="table-responsive text-nowrap">
      <table class="table">
        <thead>
          <tr>
            <th>&nbsp;</th>
            <th>Serial No</th>
            <th>Ref. No</th>
            <th>Name</th>
            @if($PREV == 1 || $PREV == 3)
            <th>Branch</th>
            @endif
            <th>Payment Mode</th>
            <th>Receipt No</th>
            <th>Investment Date</th>
            <th>Amount</th>
            <th>Deposit Date</th>
            <th>Current Status</th>
            <th>Clearence Date</th>
            <th>Start Date</th>
            <th>Payment Details</th>
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
    <div class="card-footer text-center">
        <button type="button" id="brssubmitbtn" class="btn btn-primary update-process"><i class="fa fa-retweet" aria-hidden="true"></i> Update</button>
      </div>
  </div>  
  <!--/ Basic Bootstrap Table --> 
</div>

<script type="text/javascript">
	function exportData(){	
		$('#exportCsvBtn').html('......');	
		$.ajax({	
			headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},	
			type: "POST",	
			url: "{{route('exports.export-brs-report')}}",	
			data: $('#downloadForm').serialize(),	
			success: function(msg){
				$('#exportCsvBtn').html('Download Report');
				window.location.href = msg;	
			},error: function(ts){	
				$('#error500').modal('show');	
			}	
		});	
		return false;	
	}

	$(document).on('click', '#brssubmitbtn', function(){
		var temp = 0;	
		var len = $("input[name='myrealselect']:checked").length;
		if(len==0){
			swal("Error", 'Please check atleast one checkbox', "error");
			temp = 1;
		}else{
			var row_id = $("input[name='myrealselect']:checked").val();
			if($('#mystat_'+row_id).val() == ''){
				temp = 1;
				swal("Error", 'Please Select Current Status', "error");
				return false;
			}
			if($('#myclrdate_'+row_id).val() == ''){
				temp = 1;
				swal("Error", 'Please Select Clearence Date', "error");
				return false;
			}
			if($('#mystartdate_'+row_id).val() == ''){
				temp = 1;
				swal("Error", 'Please Select Start Date', "error");
				return false;
			}
			if($('#mypaymentdetail_'+row_id).val() == ''){
				temp = 1;
				swal("Error", 'Please Enter Payment Details', "error");
				return false;
			}
			if(temp == 0){
				var mystat = $('#mystat_'+row_id).val();
				var myclrdate = $('#myclrdate_'+row_id).val();
				var mystartdate = $('#mystartdate_'+row_id).val();
				var mypaymentdetail = $('#mypaymentdetail_'+row_id).val();
				$.ajax({
					headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
					type: 'POST',
					data: {row_id:row_id,mystat:mystat,myclrdate:myclrdate,mystartdate:mystartdate,mypaymentdetail:mypaymentdetail},
					url: "{{ url('/panel/brs-update') }}",
					success: function(response){
						console.log(response);
						swal("Success", 'BRS updated Successfully', "success").then((value) => {	
							window.location.href = "";	
						});
					}
				});
				return false;
			}
		}		
	});
		
	$(document).on('click', '.myselect', function(){		
		var myselid=$(this).attr('data-id');		
		var myselectid='mysel_'+myselid;		
		//alert(myselectid);		
		var mystatid='mystat_'+myselid;		
		var myclrid='myclrdate_'+myselid;		
		var mystartid='mystartdate_'+myselid;		
		var mypaydetail='mypaymentdetail_'+myselid;		
		$('#'+mystartid+'').val('');		
		//alert(mystatid);
		
		var mystatval=$('#'+mystatid+'').val();
		$('.myerr').removeClass('error');
		$('.myerr').removeClass('required');
		if($('#'+myselectid+'').is(":checked")==false){
			$('#'+myclrid+'').removeClass('required');
			$('#'+mystartid+'').removeClass('required');
			$('#'+mystatid+'').removeClass('required');
			$('#'+mypaydetail+'').removeClass('required');			
		}
		
		if($('#'+myselectid+'').is(":checked")==true){
			//alert('hi');
			$('#'+myclrid+'').addClass('required');
			$('#'+mystartid+'').addClass('required');
			$('#'+mystatid+'').addClass('required');
			$('#'+mypaydetail+'').addClass('required');			
			if(mystatval==1){
				$('#'+myclrid+'').addClass('required');
				//$('#'+mystartid+'').addClass('required');				
			}			
			if(mystatval==2){
				$('#'+myclrid+'').val('');
				$('#'+mystartid+'').val('');
				$('#'+myclrid+'').removeClass('required');
				$('#'+mystartid+'').removeClass('required');				
				//$('#'+myclrid+'').attr('required');
				//$('#'+mystartid+'').removeClass('required');				
			}
		}		
	});	
	$(".mydddt").min = "2022-01-18";
	$(".mydddt").max = "2022-01-20";

	$(document).ready(function(){
		filterData('simple');
	});

	function filterData(type = null){
		if(type =='search'){$('#searchbuttons').html('Searching..');}
		$.ajax({
			headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
			type: 'POST',
			data: $('#searchForm').serialize(),
			url: "{{ url('/panel/brs_paginate') }}",
			success: function(response){
				$('#replaceHtml').html(response);
				$('#searchbuttons').html('Search');
			}
		});
	}
</script>
@endsection
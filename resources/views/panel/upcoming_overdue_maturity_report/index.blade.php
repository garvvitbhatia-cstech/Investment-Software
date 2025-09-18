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
          <input type="date" class="form-control" id="from_date" name="from_date" placeholder="dd-mm-yyyy" value="">
        </div>
        <div class="col-md-2">
          <input type="date" class="form-control" id="to_date" name="to_date" placeholder="dd-mm-yyyy" value="">
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

<div class="modal fade" id="pay-modal" data-backdrop="static" data-keyboard="false">
<div class="modal-dialog modal-xl">
	<form class="form-horizontal" id="pay_now_form" name="pay_now_form" autocomplete="off" method="post" action="maturity_hwnd.php">
		<div class="modal-content">
			<input type="hidden" id="mybrsid" name="mybrsid">
			   <div class="modal-body" style="border-bottom: 1px solid #e9ecef;">
				<div class="form-group row" style="margin-bottom:0;">
					<div class="col-sm-7">
						<h4 class="modal-title"><i class="fas fa-rupee-sign"></i> Pay Now</h4>
					</div>
					<label for="payment_date" class="col-sm-2 col-form-label text-right" style="padding-top: calc(0.175rem + 1px);">Payment Date:</label>
					<div class="col-sm-2">                            
						<input type="date" class="form-control form-control-sm" id="payment_date" name="payment_date" value="{{ date('Y-m-d') }}" required>
					</div>
					<div class="col-sm-1 text-right">
						<button type="button" class="btn btn-sm btn-danger" data-dismiss="modal" >Close</button>
					</div>
				</div>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-sm-12">
						<h5>Party Bank Details</h5>
						<hr style="margin:0;margin-bottom: 5px;">
						<div class="row" id="cst_bank_details_party">
						 
						</div>
						<br>
					</div>
					<div class="col-sm-12">
						<div class="form-group row" style="margin-bottom:0;">
							<label class="col-md-3 col-form-label" style="font-size: 21px;font-weight: 500;padding-top: calc(-0.825rem + 1px);">Payee Bank Details</label>
							<div class="col-md-9">
								<select name="payee_banks" id="payee_banks" class="form-control form-control-sm" style="width:100%;" required>
                                	<option value="">Select Payee Bank</option>
                                    @if(isset($payee_bank) && $payee_bank->count()>0)
                                        @foreach($payee_bank as $key => $bank)
                                            <option value="{{$bank->id}}">{{$bank->bank_name}} | {{$bank->accnt_no}}</option>
                                        @endforeach
                                    @endif                                    
								</select>
							</div>
						</div>
						<hr style="margin:0;margin-bottom: 5px;">
						<div class="row" id="cst_bank_details_payee">
							
						</div>
						<hr style="margin:0;margin-bottom: 25px;">
					</div>
					<div class="col-sm-3">
						<div class="form-group">
							<label>Amount to be paid</label>
							<input type="text" class="form-control form-control-sm text-red text-right" id="payment_amount" name="payment_amount"  required disabled>
							<input type="hidden" id="payment_due_amount" name="payment_due_amount">
						</div>
					</div>
					<div class="col-sm-3">
						<div class="form-group">
							<label for="#payment_modes">Payment Mode</label>
							<select name="payment_mode" id="payment_mode" class="form-control form-control-sm" style="width:100%;" required>
								<option value="">Select Payment Mode</option>
                                @if(isset($payment_methods) && $payment_methods->count()>0)
                                    @foreach($payment_methods as $key => $payment)
                                        <option value="{{$payment->id}}">{{$payment->payment_methods}}</option>
                                    @endforeach
                                @endif
							</select>
						</div>
					</div>
					<div class="col-sm-3">
						<div class="form-group">
							<label>Instrument Date</label>
							<input type="date" class="form-control form-control-sm" id="instrument_date" name="instrument_date" required>
						</div>
					</div>
					<div class="col-sm-3">
						<div class="form-group">
							<label>Reference No.</label>
							<input type="text" class="form-control form-control-sm" id="reference_no" name="reference_no" placeholder="Reference No." required>
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="submit" id="pay_now_btn" class="btn btn-primary btn-sm">Save changes</button>
				<button type="button" class="btn btn-default btn-sm" onclick="reset_form()">Reset</button>
			</div>
		</div>
	</form>
	<!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->
</div>

<script type="text/javascript">
	$(document).on('click', '.mypaynow', function(){
		var myid=$(this).attr('data-id');
		$("#pay-modal").modal("show");
		$.ajax({
			headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
			type: "POST",
			dataType:"JSON",
			url: "{{ url('/panel/fetchbank3') }}",
			cache: false,
			data: {accntid:myid},
			success: onSuccessbankfetch2,
			error: onError
		});
	});

	function onSuccessbankfetch2(objcustomize,status){
		 $('#cst_bank_details_party').html('');
		 $('#cst_bank_details_party').html('<div class="col-md-6"><p><strong>Bank Name:</strong>  '+objcustomize.bank_name+'</p><p><strong>Account Holder Name:</strong>  '+objcustomize.accnt_holder_name+'</p><p><strong>Branch Name:</strong>  '+objcustomize.branch_name+'</p></div><div class="col-md-6"><p><strong>Account Number:</strong>  '+objcustomize.back_ac_no+'</p><p><strong>IFSC Code:</strong>  '+objcustomize.accnt_ifsc_code+'</p><p><strong>Branch Address:</strong>   '+objcustomize.branch_addr+'</p></div>');

		 $('#payment_amount').val(objcustomize.matval);
		 $('#mybrsid').val(objcustomize.brsid);	 
	}
	 
	$(document).on('change', '#payee_banks', function(){
		var accntid=$('#payee_banks').val();
		$.ajax({
			headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
			type: "POST",
			dataType:"JSON",
			url: "{{ url('/panel/fetchbank2') }}",
			cache: false,
			data: {accntid:accntid},
			success: function(response){
				$('#cst_bank_details_payee').html('<div class="col-md-6"><p><strong>Bank Name:</strong>'+response.bank_name+'</p><p><strong>Account Holder Name:</strong>'+response.accnt_holder_name+'</p><p><strong>Branch Name:</strong>'+response.branch_name+'</p></div><div class="col-md-6"><p><strong>Account Number:</strong>'+response.back_ac_no+'</p><p><strong>IFSC Code:</strong>'+response.accnt_ifsc_code+'</p><p><strong>Branch Address:</strong>'+response.branch_addr+'</p></div>');
			},
			error: onError
		});
	});

	function onError(data,status){
		 alert('Some network error occured.');		 
	}

	function onSuccessbankfetch(objcustomize,status){
		 $('#cst_bank_details_payee').html('');
		 //var objcustomize = $.parseJSON(data);
		 $('#cst_bank_details_payee').html('<div class="col-md-6"><p><strong>Bank Name:</strong>  '+objcustomize.bank_name+'</p><p><strong>Account Holder Name:</strong>  '+objcustomize.accnt_holder_name+'</p><p><strong>Branch Name:</strong>  '+objcustomize.branch_name+'</p></div><div class="col-md-6"><p><strong>Account Number:</strong>  '+objcustomize.back_ac_no+'</p><p><strong>IFSC Code:</strong>  '+objcustomize.accnt_ifsc_code+'</p><p><strong>Branch Address:</strong>   '+objcustomize.branch_addr+'</p></div>');		 
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
			url: "{{ url('/panel/upcoming_maturity_overdue_paginate') }}",
			success: function(response){
				$('#replaceHtml').html(response);
				$('#searchbuttons').html('Search');
			}
		});
	}
</script>
@endsection
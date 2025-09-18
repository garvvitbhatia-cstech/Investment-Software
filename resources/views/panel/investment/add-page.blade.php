@extends('layout.admin.dashboard')

@section('content')
<style>
	/*.required:after {
		content:" *";
		color: red;
	  }*/
</style>
@php
	$findcustomer_exists_res = $findinvoice_no[0];
@endphp

<div class="container-xxl flex-grow-1 container-p-y">
  <div class="row">
    <div class="col">
      <h6 class="mt-6">Investment</h6>
      <div class="row">
        <div class="col-12 col-md-12">
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
                    <div class="row text-right">
                      <div class="col-12 text-right">
                        <h5>Date: {{ date('d/m/Y') }}</h5>
                      </div>
                    </div>
                    <div class="row g-2">
                      <div class="col-md-4 col-12">
                        <address>
                        <strong>{{ stripslashes($findcustomer_exists_res->cust_name) }}</strong><br>
                        <b>Address:</b> {!! stripslashes($findcustomer_exists_res->address) . ', ' . stripslashes($findcustomer_exists_res->state_name) . ', ' . stripslashes($findcustomer_exists_res->city) . ', ' . stripslashes($findcustomer_exists_res->zip_code); !!}<br>
                        <b>Phone:</b> {{ stripslashes($findcustomer_exists_res->contact_no) }}<br>
                        <b>Email:</b> {{ stripslashes($findcustomer_exists_res->email) }}
                        </address>
                      </div>
                      <div class="col-md-4 col-12">
                        <address>
                        <strong>Customer Type: </strong>{{ stripslashes($findcustomer_exists_res->cust_type) }} <br>
                        <b>UIDAI:</b> {{ stripslashes($findcustomer_exists_res->udaid) }}<br>
                        <b>PAN:</b> {{ stripslashes($findcustomer_exists_res->pan_no); }} <br>
                        <b>Age as on today:</b> {{ date_diff(date_create($findcustomer_exists_res->dob), date_create('today'))->y . ' years'; }}<br>
                        </address>
                      </div>
                      <div class="col-md-4 col-12">
                        <select name="account_id" id="account_id" class="form-select">
                          <option value="">Select Account</option>                          
                            @foreach($cust_accounts as $key => $account)                                	
                                <option value="{{$account->id}}">{{$account->bank_name}} | {{$account->back_ac_no}}</option>                          
                            @endforeach                            
                        </select>
                        <br />
                        <select name="ally_id" id="ally_id" class="form-select">
                          <option value="">Select Ally</option>                          
                            @foreach($allys as $key => $ally)                                	
                                <option data-alcode="{{$ally->ally_code}}" value="{{$ally->id}}">{{$ally->ally_code}} | {{$ally->ally_person_name}}</option>       
                            @endforeach                            
                        </select>
                      </div>
                    </div>
                    <div class="row g-2">
                      <div class="col-md-12 col-12">
                        <table class="table">
                          <thead>
                            <tr>
                              <th width="15%">Product Code</th>
                              <th>Unit Price</th>
                              <th>No of Unit</th>
                              <th>Investment Amount</th>
                              <th>Registration Fees</th>
                              <th>Total Amount</th>
                            </tr>
                            <tr>
                              <td>
                                <select name="myproduct" id="myproduct" class="form-select">
                                    <option value="">Select</option>                            
                                    @foreach($products as $key => $product)                                        	
                                        <option value="{{$product->id}}">{{$product->product_name}}</option>                                  
                                    @endforeach                                    
                                </select>
                              </td>
                              <td><input type="text" id="myunitprice" name="myunitprice" class="form-control numberonly" disabled></td>
                              <td><input type="number" id="myunit" name="myunit" class="form-control" disabled></td>
                              <td><input type="text" id="myinvamnt" name="myinvamnt" class="form-control" disabled></td>
                              <td><input type="text" id="myregfee" name="myregfee" class="form-control" disabled></td>
                              <td><input type="text" id="mytotamnt" name="mytotamnt" class="form-control" disabled></td>
                            </tr>
                          </thead>
                          <tfoot id="show_schemes_footer">
                          </tfoot>
                        </table>
                      </div>
                    </div>
                    <div class="row g-2">
                      <div class="col-md-12 col-12">
                        <table class="table">
                          <thead>
                            <tr>
                              <th>Payment Method</th>
                              <th>Date</th>
                              <th>Amount</th>
                              <th width="5%">&nbsp;</th>
                            </tr>
                            <tr>
                              <td>
                              	<select name="mypay_method" id="mypay_method" class="form-select">
                                  	<option value="">Select</option>                                  
                                    @foreach($payment_methods as $key => $payment)                                                
                                        <option value="{{$payment->id}}">{{$payment->payment_methods}}</option>                                  
                                    @endforeach                                        
                                </select>
                              </td>
                              <td><input type="date" id="payment_date" name="payment_date" class="form-control" disabled></td>
                              <td><input type="text" id="payment_amount" name="payment_amount" class="form-control numberonly" disabled></td>
                              <th> <button type="button" class="btn btn-label-secondary waves-effect btn-sm mymodalopen" style="display:none;"><i class="icon-base ti tabler-plus icon-md" title="Add Payment"></i></button>
                              </th>
                              <input type="hidden" name="myunitregfee" id="myunitregfee">
                              <input type="hidden" name="custid" id="custid" value="{{ base64_encode($findcustomer_exists_res->id); }}">
                              <input type="hidden" name="cashright" id="cashright" value="0">
                            </tr>
                          </thead>
                          <tfoot id="payment_scheme_footer">
                          </tfoot>
                        </table>
                        <table class="table cretinvest" style="display:none;" id="checck_dlts">
                          <thead>
                            <tr>
                              <th width="25%">Cheque / Draft No</th>
                              <!--<th width="30%">Ref. Number</th>-->
                              <th width="25%">Cheque / Draft Date</th>
                              <th width="25%">Bank</th>
                              <th width="25%">Branch</th>
                            </tr>
                            <tr>
                              <th> <input type="text" class="form-control text-right mycaddl" id="cheque_no" name="cheque_no" placeholder="Cheque No">
                              </th>
                              <!--<th>
                                <input type="text" class="form-control form-control-sm text-right required" id="payment_ref_no" name="payment_ref_no" placeholder="Reference Number" disabled>
                            </th>-->
                              <th> <input type="date" class="form-control mycaddl" id="cheque_date" name="cheque_date" placeholder="dd-mm-yyyy">
                              </th>
                              <th> <input type="text" class="form-control text-right mycaddl" id="cheque_bank" name="cheque_bank" placeholder="Bank">
                              </th>
                              <th> <input type="text" class="form-control text-right mycaddl" id="cheque_branch" name="cheque_branch" placeholder="Branch">
                              </th>
                            </tr>
                          </thead>
                          <tfoot id="payment_scheme_footer">
                          </tfoot>
                        </table>
                      </div>
                    </div>
                  </div>
                  <div class="pt-6">
                    <button  type="button" id="invst-btn" id="invst-btn" class="btn btn-primary me-4">Create Investment</button>
                    <button type="reset" onclick="window.location.href='{{route('admin.customers')}}'" class="btn btn-label-secondary" >Cancel</button>
                  </div>
                </div>
              </div>
              <div class="modal fade" id="denomination-modal2" data-bs-keyboard="false" data-bs-backdrop="static">
                <div class="modal-dialog modal-lg modal-dialog-centered1 modal-simple modal-add-new-cc">
                  <div class="modal-content">                    
                    <div class="modal-body">
                    	<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        <div class="text-center mb-6">
                            <h4 class="mb-2">Add Denomination</h4>
                            <!--<p>Create a brand new category from here.</p>-->
                          </div>
                      <div class="row">
                        <div class="col-sm-12">
                          <table class="table table-condensed table-bordered" id="denom_table">
                            <thead>
                              <tr>
                                <th>Denomination</th>
                                <th>Multiplied by</th>
                                <th>Quantity</th>
                                <th>Amount</th>
                              </tr>
                            </thead>
                            <tbody>
                              <tr>
                                <td class="text-right"> Rs. 2000 </td>
                                <td class="text-center"><label style="font-size: 26px;margin-bottom: 0;margin-top: 0;font-weight: normal;">×</label></td>
                                <td><input type="number" class="form-control form-control-sm text-right denom-qnt" value="0" id="denom_qnt_2000" name="denom_qnt_2000" min="1"></td>
                                <td class="text-right"><input type="text" class="form-control form-control-sm text-right" id="denom_amount_2000" name="denom_amount_2000" placeholder="Amount" readonly></td>
                              </tr>
                              <tr>
                                <td class="text-right"> Rs. 500 </td>
                                <td class="text-center"><label style="font-size: 26px;margin-bottom: 0;margin-top: 0;font-weight: normal;">×</label></td>
                                <td><input type="number" class="form-control form-control-sm text-right denom-qnt" value="0" id="denom_qnt_500" name="denom_qnt_500" min="1"></td>
                                <td class="text-right"><input type="text" class="form-control form-control-sm text-right" id="denom_amount_500" name="denom_amount_500" placeholder="Amount" readonly></td>
                              </tr>
                              <tr>
                                <td class="text-right"> Rs. 200 </td>
                                <td class="text-center"><label style="font-size: 26px;margin-bottom: 0;margin-top: 0;font-weight: normal;">×</label></td>
                                <td><input type="number" class="form-control form-control-sm text-right denom-qnt" value="0" id="denom_qnt_200" name="denom_qnt_200" min="1"></td>
                                <td class="text-right"><input type="text" class="form-control form-control-sm text-right" id="denom_amount_200" name="denom_amount_200" placeholder="Amount" readonly></td>
                              </tr>
                              <tr>
                                <td class="text-right"> Rs. 100 </td>
                                <td class="text-center"><label style="font-size: 26px;margin-bottom: 0;margin-top: 0;font-weight: normal;">×</label></td>
                                <td><input type="number" class="form-control form-control-sm text-right denom-qnt" value="0" id="denom_qnt_100" name="denom_qnt_100" min="1"></td>
                                <td class="text-right"><input type="text" class="form-control form-control-sm text-right" id="denom_amount_100" name="denom_amount_100" placeholder="Amount" readonly></td>
                              </tr>
                              <tr>
                                <td class="text-right"> Rs. 50 </td>
                                <td class="text-center"><label style="font-size: 26px;margin-bottom: 0;margin-top: 0;font-weight: normal;">×</label></td>
                                <td><input type="number" class="form-control form-control-sm text-right denom-qnt" value="0" id="denom_qnt_50" name="denom_qnt_50" min="1"></td>
                                <td class="text-right"><input type="text" class="form-control form-control-sm text-right" id="denom_amount_50" name="denom_amount_50" placeholder="Amount" readonly></td>
                              </tr>
                              <tr>
                                <td class="text-right"> Rs. 20 </td>
                                <td class="text-center"><label style="font-size: 26px;margin-bottom: 0;margin-top: 0;font-weight: normal;">×</label></td>
                                <td><input type="number" class="form-control form-control-sm text-right denom-qnt" value="0" id="denom_qnt_20" name="denom_qnt_20" min="1"></td>
                                <td class="text-right"><input type="text" class="form-control form-control-sm text-right" id="denom_amount_20" name="denom_amount_20" placeholder="Amount" readonly></td>
                              </tr>
                              <tr>
                                <td class="text-right"> Rs. 10 </td>
                                <td class="text-center"><label style="font-size: 26px;margin-bottom: 0;margin-top: 0;font-weight: normal;">×</label></td>
                                <td><input type="number" class="form-control form-control-sm text-right denom-qnt" value="0" id="denom_qnt_10" name="denom_qnt_10" min="1"></td>
                                <td class="text-right"><input type="text" class="form-control form-control-sm text-right" id="denom_amount_10" name="denom_amount_10" placeholder="Amount" readonly></td>
                              </tr>
                              <tr>
                                <td class="text-right"> Rs. 5 </td>
                                <td class="text-center"><label style="font-size: 26px;margin-bottom: 0;margin-top: 0;font-weight: normal;">×</label></td>
                                <td><input type="number" class="form-control form-control-sm text-right denom-qnt" value="0" id="denom_qnt_5" name="denom_qnt_5" min="1"></td>
                                <td class="text-right"><input type="text" class="form-control form-control-sm text-right" id="denom_amount_5" name="denom_amount_5" placeholder="Amount" readonly></td>
                              </tr>
                              <tr>
                                <td class="text-right"> Rs. 2 </td>
                                <td class="text-center"><label style="font-size: 26px;margin-bottom: 0;margin-top: 0;font-weight: normal;">×</label></td>
                                <td><input type="number" class="form-control form-control-sm text-right denom-qnt" value="0" id="denom_qnt_2" name="denom_qnt_2" min="1"></td>
                                <td class="text-right"><input type="text" class="form-control form-control-sm text-right" id="denom_amount_2" name="denom_amount_2" placeholder="Amount" readonly></td>
                              </tr>
                              <tr>
                                <td class="text-right"> Rs. 1 </td>
                                <td class="text-center"><label style="font-size: 26px;margin-bottom: 0;margin-top: 0;font-weight: normal;">×</label></td>
                                <td><input type="number" class="form-control form-control-sm text-right denom-qnt" value="0" id="denom_qnt_1" name="denom_qnt_1" min="1"></td>
                                <td class="text-right"><input type="text" class="form-control form-control-sm text-right" id="denom_amount_1" name="denom_amount_1" placeholder="Amount" readonly></td>
                              </tr>
                            </tbody>
                            <tfoot>
                            </tfoot>
                          </table>
                        </div>
                      </div>
                    </div>
                    <div class="modal-footer"> 
                      <!--<button type="reset" class="btn btn-default btn-sm" onclick="reset_denom_modal()">Reset</button>-->
                      <button type="button" id="add-denom" class="btn btn-primary add-denom-btn">Add</button>
                    </div>
                  </div>
                  <!-- /.modal-content --> 
                </div>
                <!-- /.modal-dialog --> 
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">
	$(document).on('change', '#myunit', function(){
        var movunit = $('#myunit').val();
        if (!isNaN(movunit) && movunit > 0) {
            $('#myinvamnt').val(($('#myunitprice').val()) * movunit);
            $('#myregfee').val(($('#myunitregfee').val()) * movunit);
            $('#mytotamnt').val(parseFloat((parseFloat($('#myunitregfee').val()) + parseFloat($('#myunitprice').val())) * movunit));
            $('#payment_amount').val(parseFloat((parseFloat($('#myunitregfee').val()) + parseFloat($('#myunitprice').val())) * movunit));
        } else {
            $('#myunit').val(1);
        }
    });
    $(document).on('click', '.mymodalopen', function(){
        $("#denomination-modal2").modal('show');
    });

    $(document).on('click keyup blur', '#denom_qnt_2000', function() {
        var denomval = $('#denom_qnt_2000').val();
        var myval = parseInt(parseInt(denomval) * 2000);
        if (denomval >= 1) {
            $('#denom_amount_2000').val(myval);
        } else {
            $('#denom_amount_2000').val('');
        }
    });

    $(document).on('click keyup blur', '#denom_qnt_500', function() {
        var denomval = $('#denom_qnt_500').val();
        var myval = parseInt(parseInt(denomval) * 500);
        if (denomval >= 1) {
            $('#denom_amount_500').val(myval);
        } else {
            $('#denom_amount_500').val('');
        }
    });

    $(document).on('click keyup blur', '#denom_qnt_200', function() {
        var denomval = $('#denom_qnt_200').val();
        var myval = parseInt(parseInt(denomval) * 200);
        if (denomval >= 1) {
            $('#denom_amount_200').val(myval);
        } else {
            $('#denom_amount_200').val('');
        }
    });

    $(document).on('click keyup blur', '#denom_qnt_100', function() {
        var denomval = $('#denom_qnt_100').val();
        var myval = parseInt(parseInt(denomval) * 100);
        if (denomval >= 1) {
            $('#denom_amount_100').val(myval);
        } else {
            $('#denom_amount_100').val('');
        }
    });

    $(document).on('click keyup blur', '#denom_qnt_50', function() {
        var denomval = $('#denom_qnt_50').val();
        var myval = parseInt(parseInt(denomval) * 50);
        if (denomval >= 1) {
            $('#denom_amount_50').val(myval);
        } else {
            $('#denom_amount_50').val('');
        }
    });

    $(document).on('click keyup blur', '#denom_qnt_20', function() {
        var denomval = $('#denom_qnt_20').val();
        var myval = parseInt(parseInt(denomval) * 20);
        if (denomval >= 1) {
            $('#denom_amount_20').val(myval);
        } else {
            $('#denom_amount_20').val('');
        }
    });

    $(document).on('click keyup blur', '#denom_qnt_10', function() {
        var denomval = $('#denom_qnt_10').val();
        var myval = parseInt(parseInt(denomval) * 10);
        if (denomval >= 1) {
            $('#denom_amount_10').val(myval);
        } else {

            $('#denom_amount_10').val('');
        }
    });

    $(document).on('click keyup blur', '#denom_qnt_5', function() {
        var denomval = $('#denom_qnt_5').val();
        var myval = parseInt(parseInt(denomval) * 5);
        if (denomval >= 1) {
            $('#denom_amount_5').val(myval);
        } else {
            $('#denom_amount_5').val('');
        }
    });

    $(document).on('click keyup blur', '#denom_qnt_2', function() {
        var denomval = $('#denom_qnt_2').val();
        var myval = parseInt(parseInt(denomval) * 2);
        if (denomval >= 1) {
            $('#denom_amount_2').val(myval);
        } else {

            $('#denom_amount_2').val('');
        }
    });

    $(document).on('click keyup blur', '#denom_qnt_1', function() {
        var denomval = $('#denom_qnt_1').val();
        var myval = parseInt(parseInt(denomval) * 1);
        if (denomval >= 1) {
            $('#denom_amount_1').val(myval);
        } else {
            $('#denom_amount_1').val('');
        }
    });
    $(document).on('click', '#add-denom', function() {
        /*var mytotal=parseInt($('#denom_amount_2000').val())+parseInt($('#denom_amount_500').val())+parseInt($('#denom_amount_200').val())+parseInt($('#denom_amount_100').val())+parseInt($('#denom_amount_50').val())+parseInt($('#denom_amount_20').val())+parseInt($('#denom_amount_10').val())+parseInt($('#denom_amount_5').val())+parseInt($('#denom_amount_2').val())+parseInt($('#denom_amount_1').val());*/
        var mytotal = 0;
        if ($('#denom_amount_2000').val() != "") {
            mytotal += parseInt($('#denom_amount_2000').val());
        }
        if ($('#denom_amount_500').val() != "") {
            mytotal += parseInt($('#denom_amount_500').val());
        }
        if ($('#denom_amount_200').val() != "") {
            mytotal += parseInt($('#denom_amount_200').val());
        }
        if ($('#denom_amount_100').val() != "") {
            mytotal += parseInt($('#denom_amount_100').val());
        }
        if ($('#denom_amount_50').val() != "") {
            mytotal += parseInt($('#denom_amount_50').val());
        }
        if ($('#denom_amount_20').val() != "") {
            mytotal += parseInt($('#denom_amount_20').val());
        }
        if ($('#denom_amount_10').val() != "") {
            mytotal += parseInt($('#denom_amount_10').val());
        }
        if ($('#denom_amount_5').val() != "") {
            mytotal += parseInt($('#denom_amount_5').val());
        }
        if ($('#denom_amount_2').val() != "") {
            mytotal += parseInt($('#denom_amount_2').val());
        }
        if ($('#denom_amount_1').val() != "") {
            mytotal += parseInt($('#denom_amount_1').val());
        }
        var actval = parseInt($('#mytotamnt').val());
        //alert(mytotal);
        //alert(actval);
        if (mytotal != actval) {
            $('#cashright').val(0);
            $('#invst-btn').hide();
            alert('Wrong denomination');
        } else {
            $('#cashright').val(1);
            $('#invst-btn').show();
            $('#denomination-modal2').modal('hide');

        }
    });
    $(document).on('click', '#close-denom-modal', function() {
        var mytotal = 0;
        if ($('#denom_amount_2000').val() != "") {
            mytotal += parseInt($('#denom_amount_2000').val());
        }
        if ($('#denom_amount_500').val() != "") {
            mytotal += parseInt($('#denom_amount_500').val());
        }
        if ($('#denom_amount_200').val() != "") {
            mytotal += parseInt($('#denom_amount_200').val());
        }
        if ($('#denom_amount_100').val() != "") {
            mytotal += parseInt($('#denom_amount_100').val());
        }
        if ($('#denom_amount_50').val() != "") {
            mytotal += parseInt($('#denom_amount_50').val());
        }
        if ($('#denom_amount_20').val() != "") {
            mytotal += parseInt($('#denom_amount_20').val());
        }
        if ($('#denom_amount_10').val() != "") {
            mytotal += parseInt($('#denom_amount_10').val());
        }
        if ($('#denom_amount_5').val() != "") {
            mytotal += parseInt($('#denom_amount_5').val());
        }
        if ($('#denom_amount_2').val() != "") {
            mytotal += parseInt($('#denom_amount_2').val());
        }
        if ($('#denom_amount_1').val() != "") {
            mytotal += parseInt($('#denom_amount_1').val());
        }

        var actval = parseInt($('#mytotamnt').val());
        //alert(mytotal);
        //alert(actval);
        if (mytotal != actval) {
            $('#cashright').val(0);
            $('#invst-btn').hide();
            alert('Wrong denomination');
        } else {
            $('#cashright').val(1);
            $('#invst-btn').show();
        }
    });

    $("#ally_id").on('change', function() {
        var alcode = $(this).find(":selected").data('alcode');
        $("#ally_code").val(alcode);
    });
</script> 

<script type="text/javascript">
$(document).on('change', '#mypay_method', function() {
	if ($('#mypay_method').val() == 1) {
		$('#payment_date').removeClass('required');
		$('.mycaddl').removeClass('required');
		$('.mycaddl').val('');
		$('#checck_dlts').hide();
		$('#invst-btn').hide();
		if ($('#mytotamnt').val() != "") {
			$('.mymodalopen').show();
		}
		$('#payment_date').val('');
		$('#payment_date').attr("disabled", "disabled");

	} else if ($('#mypay_method').val() == 3 || $('#mypay_method').val() == 4) {
		//alert('hi');
		$('.mycaddl').addClass('required');
		$('.mycaddl').val('');
		$('#checck_dlts').show();
		$('#payment_date').addClass('required');
		$('#invst-btn').show();

		$('.mymodalopen').hide();
		$('#payment_date').removeAttr("disabled");

	} else {
		$('.mycaddl').removeClass('required');
		$('.mycaddl').val('');
		$('#payment_date').addClass('required');
		$('#invst-btn').show();
		$('#checck_dlts').hide();
		$('.mymodalopen').hide();
		$('#payment_date').removeAttr("disabled");

	}
});

$(document).on('change', '#myproduct', function() {
	var prodid = $('#myproduct').val();
	var myunit = $('#myunit').val();
	if (prodid == '') {
		$('#myunitprice').val('');
		$('#myunit').val('');
		$('#myunit').attr("disabled", "disabled");
		$('#myinvamnt').val('');
		$('#myregfee').val('');
		$('#mytotamnt').val('');
		$('#myunitregfee').val('');

	} else {
		var fetchUrl = "{{route('admin.get-product-details')}}";
		$.ajax({
			headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
			type: 'POST',
			dataType:"JSON",
			data:{prodid:prodid,myunit:myunit},
			url: fetchUrl,
			success: function(response){
				$('#myunitprice').val(response.product.unit_price);
				$('#myunit').val(1);
				$('#myunit').removeAttr("disabled");
				if(response.product.min_unit != ''){
					$('#myunit').val(response.product.min_unit);
				}
				//$('#myunit').attr("readonly",true);
				//$('#myunit').setAttribute("max",objcustomize.max_unit);
				$('#myunit').attr("min", response.product.min_unit);
				$('#myunit').attr("max", response.product.max_unit);
				$('#myinvamnt').val(response.product.unit_price);
				$('#myregfee').val(response.product.fee_per_unit);
				$('#myunitregfee').val(response.product.fee_per_unit);
				$('#mytotamnt').val(parseFloat(parseFloat(response.product.fee_per_unit) + parseFloat(response.product.unit_price)));
				$('#payment_amount').val(parseFloat(parseFloat(response.product.fee_per_unit) + parseFloat(response.product.unit_price)));
				
			},error: function(ts) {
				swal("Error!", 'Something went to wrong, please try after sometime.', "error");
				return false;
			}
		});
		return false;
	}
});
$(document).ready(function () {
	$('.numberonly').keypress(function(e){
		var charCode = (e.which) ? e.which : event.keyCode
		if(String.fromCharCode(charCode).match(/[^0-9+]/g))
		return false;
	});
});

var addUrl = "{{route('admin.add-investment',base64_encode($findcustomer_exists_res->id))}}";
$(document).ready(function(){
	$('#invst-btn').click(function(e) {
		$('#invst-btn').html('Processing...');
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
				$('#invst-btn').html('Submit');
				var obj = JSON.parse(response);
				 if(obj['heading'] == "Success"){
					swal("", obj['msg'], "success").then((value) => {
						window.location.href = "{{url('/panel/brs')}}";
					});
				}else{
					swal("Error!", obj['msg'], "error");
					return false;
				}
			},error: function(ts) {
				$('#invst-btn').html('Create Investment');
				swal("Error!", 'Something went to wrong, please try after sometime.', "error");
				return false;
			}
		});
		return false;
    });
});
</script> 
@endsection
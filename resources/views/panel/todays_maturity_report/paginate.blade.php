@if(count($records) > 0)
	@php
    	$findtolinv=0;
		$findtotreg=0;
    @endphp

    @foreach($records as $key => $row)
	@php    	
        $findinvoice_no = getCustomQuery("Select invoiceno from met_invoice where brsid='".$row->myid."'");
	    $findinvoice_no_res=$findinvoice_no[0];
    	$findtolinv += $row->investment_amount;
	    $findtotreg += $row->reg_fee;
   	@endphp

    <tr>								
         <td>{{ stripslashes($findinvoice_no_res->invoiceno) }}</td>
          <td>{{ stripslashes($row->start_date) }}</td>
          <td>{{ stripslashes($row->prod_name) }}</td>
          <td>{{ stripslashes($row->cust_name).' ('.$row->udaid.')' }}</td>
          @if($PREV == 1)
          	<td> {{ stripslashes($row->branch_name) }}</td>
          @else
          	<td>{$row->branch_name}</td>
          @endif
    
          <td>{{ stripslashes($row->maturity_val) }}
          	@if($row->mydiff > 0)
            	&nbsp;&nbsp;<small class="badge badge-primary"><i class="fa fa-clock-o" aria-hidden="true"></i> {{ $row->mydiff }}</small>
            @endif
            
            @if($row->mydiff == 0)
            	&nbsp;&nbsp;<small class="badge badge-success"><i class="fa fa-clock-o" aria-hidden="true"></i></small>	
            @endif
            
            @if($row->mydiff < 0)
            	&nbsp;&nbsp;<small class="badge badge-danger"><i class="fa fa-clock-o" aria-hidden="true"></i>&nbsp; {{ abs($row['mydiff']) }}</small>
            @endif
            </td>
          <td>{{ stripslashes($row->maturity_date) }}</td>
          <td>
            <!-- <button type="button" class="d-none btn btn-sm btn-success pay-now" onclick="payDueamount(event,'WUtNY25RUGJEWUdKSzFpOXE5Q25Jdz09','REN1aWY2RmtodTU1NUhtdWNvdjUvdz09','UVFoVjNLNU1pU0c0OE50cERhV3JlZz09','a2RHRVh5RGVDRFVoQ2VUOTN6VXNKQT09','Yk9HZ29LaGk0MzhTa2daUFdZVDc4UT09','35000.00')">Pay now</button> -->
            @if($row->mydiff <= 0)
            	<button type="button" class="btn btn-sm btn-success pay-now mypaynow" data-bankId="{{ $row->credit_bank_account }}" data-id="{{ $row->myid }}">Pay now</button>Not Paid
            @endif              
          </td>
    </tr>
    @endforeach

    <tr>
    <th colspan="9" style="text-align:right">Total Registration Fees :</th> 
    <th colspan="1" style="text-align:right">{{ number_format($findtotreg,2) }} </th>
    </tr>
    <tr>
    <th colspan="9" style="text-align:right">Total Booking Amount :</th>
    <th colspan="1" style="text-align:right">{{ number_format($findtolinv,2) }}</th>
    </tr>

    @else

    <tr>
        <td align="center" colspan="20">Record not found</td>
    </tr>

    @endif

    <tr>
        <td align="center" colspan="20">
           <div id="pagination">{{ $records->appends(request()->except('page'))->links('pagination.front') }}</div>
        </td>
    </tr>
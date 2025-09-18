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
        <td>{{ stripslashes($row->product_name) }}</td>
        <td>{{ stripslashes($row->cust_name).' ('.$row->udaid.')' }}</td>
        @if($PREV == 1)
        	<td> {{ stripslashes($row->branch_name) }}</td>
        @else
        	<td>{{$row->branch_name}}</td>
        @endif
    
        <td>{{ stripslashes($row->maturity_val) }}
        	@if($row->mydiff > 0)
            	<span class="badge bg-label-primary" style="--bs-badge-padding-y:0.12em"><i style="height:15px;" class="icon-base ti tabler-clock"></i> {{ $row->mydiff }}</span>                
            @endif
            
            @if($row->mydiff == 0)
            	<span class="badge bg-label-success" style="--bs-badge-padding-y:0.12em"><i style="height:15px;" class="icon-base ti tabler-clock"></i></span>
            @endif
            
            @if($row->mydiff < 0)
            	<span class="badge bg-label-danger" style="--bs-badge-padding-y:0.12em"><i style="height:15px;" class="icon-base ti tabler-clock"></i> {{ abs($row->mydiff) }}</span>
            @endif
        </td>
        <td>{{ stripslashes($row->maturity_date) }}</td>
        <td>
            <!-- <button type="button" class="d-none btn btn-sm btn-success pay-now" onclick="payDueamount(event,'WUtNY25RUGJEWUdKSzFpOXE5Q25Jdz09','REN1aWY2RmtodTU1NUhtdWNvdjUvdz09','UVFoVjNLNU1pU0c0OE50cERhV3JlZz09','a2RHRVh5RGVDRFVoQ2VUOTN6VXNKQT09','Yk9HZ29LaGk0MzhTa2daUFdZVDc4UT09','35000.00')">Pay now</button> -->
        <?php /*?>@if($row->mydiff > 0)
           	<button type="button" class="btn btn-sm btn-success pay-now mypaynow" data-bankId="{{ $row->credit_bank_account }}" data-id="{{ $row->myid }}">Pay now</button>
            @endif  <?php */?>            
            Not Paid
        </td>
    </tr>
    @endforeach

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
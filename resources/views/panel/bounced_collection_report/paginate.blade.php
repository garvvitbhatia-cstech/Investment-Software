@if(count($records) > 0)
	@php
    	$findtolinv=0;
		$findtotreg=0;
    @endphp
    @foreach($records as $key => $row)
	@php 
    	$findtolinv += $row->investment_amount;
	    $findtotreg += $row->reg_fee;
   	@endphp

    <tr>								
        <td>{{ stripslashes($row->cust_name) }}</td>
		@if($PREV==1 || $PREV==3)
			<td>{{ stripslashes($row->branch_name) }}</td>
        @endif
        <td>{{ stripslashes($row->receipt_id) }}</td>
        <td>{{ stripslashes($row->paid_on) }}</td>
        <td>{{ stripslashes($row->payment_methods) }}</td>
        <td>{{ stripslashes($row->ref_no) }}</td>
        <td>{{ stripslashes($row->paid_on) }}</td>
        <td>
		@if($row->status==0)
        	{{"Pending"}}
        @endif
        @if($row->status==1)
        	{{"Cleared"}}
        @endif
        @if($row->status==2)
        	{{"Bounced"}}
        @endif
        </td>
        <td>
        	@if($row->status==0)
            	{{ "N/A" }}
            @endif
            
            @if($row->status==1)
            	{{ stripslashes($row->cleared_on) }}
            @endif
            
            @if($row->status==2)
            	{{ stripslashes($row->bounced_date) }}
            @endif
        </td>
        <td>{{ stripslashes($row->investment_amount) }}</td>
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
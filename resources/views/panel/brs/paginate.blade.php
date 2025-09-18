@if(count($records) > 0)

    @foreach($records as $key => $row)
   
    <tr>
    <td><input type="radio" class="myselect" name="myrealselect" id="mysel_{{ $row->id }}" data-id="{{ $row->id }}" value="{{ $row->id }}" ></td>
    <td>{{ stripslashes($row->serial_no) }}</td>
    <td>{{ stripslashes($row->ref_no) }}</td>
    <td>{{ stripslashes($row->cust_name) }}</td>
    @if($PREV == 1 || $PREV == 3)
    <td>{{ stripslashes($row->branch_name) }}</td>
    @endif
    <td>{{ stripslashes($row->payment_methods) }}</td>
    <td>{{ stripslashes($row->receipt_id) }}</td>
    <td>
    	@if($row->paid_on != '')
    	{{ date('d-m-Y',strtotime($row->paid_on)) }}
        @endif
    </td>
    <td>{{ stripslashes($row->tot_paid) }}</td>
    <td>
    	@if($row->paid_on != '')
    	{{ date('d-m-Y',strtotime($row->paid_on)) }}
        @endif
    </td>
    <td>
    	<select class="select2 form-select myerr" name="myrealstatus_{{ $row->id }}" id="mystat_{{ $row->id }}">
    		<option value="">Select</option>
            <option value="1">Cleared</option>
            <option value="2">Bounced</option>
        </select>
    </td>
    <td><input type="date" class="form-control myerr" name="myclrdate_{{ $row->id }}" id="myclrdate_{{ $row->id }}" ></td>
    <td><input type="date" class="form-control myerr mydddt" name="mystartdate_{{ $row->id }}" id="mystartdate_{{ $row->id }}" ></td>
    <td><input type="text" class="form-control myerr" name="mypaymentdetail_{{ $row->id }}" id="mypaymentdetail_{{ $row->id }}" ></td>
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
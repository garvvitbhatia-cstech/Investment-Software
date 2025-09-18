@if($records->count()>0)

    @foreach($records as $key => $row)
    
    @php
    	$count = $records->count();
    	$last = $records->lastItem();
        $page = $records->currentPage();
        $sr = $key+1;
        if($page > 1){
        	$sr = ($last-$count)+$key+1;
        }
    @endphp    
<tr>
  <td>{!! $sr !!}</td>
  <input type="hidden" id="page_no" value="{{$page}}"/>
  <td>
  	<b>Name: </b>{{$row->cust_name}}<br />
    <b>Contact: </b>{{$row->contact_no}}<br />
    <b>PAN: </b>{{$row->pan_no}}
  </td>
  <td>
  	<b>Customer ID: </b>{{$row->customer_id}}<br />
    <b>UIDAI: </b>{{$row->udaid}}<br />
    <b>Code: </b>{{$row->customer_code}}
  </td>
  <td>
  	<b>Type: </b>{{getDetails('master_cust_type',$row->customer_type,'cust_type')}}<br />
    <b>Branch: </b>{{getDetails('master_branch',$row->customer_type,'branch_name')}}
  </td>
  <td>
        @php
            if($row->status == 1){$class = 'bg-label-success'; $label = 'Active';}else{$class = 'bg-label-danger'; $label = 'In-Active';}
        @endphp
        <a style="cursor:pointer" onclick="changeCustomerStatus('master_customer','{!!$row->id!!}');" id="status_{{$row->id}}" class="badge {{$class}} me-1">{{$label}}</a>
        <input type="hidden" id="status_value_{{$row->id}}" value="{!!$row->status!!}" />
   </td>
  <td>
  	@if($row->status == 1)
    <a id="investment_btn_{{$row->id}}" class="btn btn-outline-secondary" href="{{url('panel/add-investment',base64_encode($row->id))}}"><i class="icon-base ti tabler-currency-rupee me-1"></i> Create Investment</a>
    @endif
   </td>
  <td>
  	<div class="dropdown">
      <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown"> <i class="icon-base ti tabler-dots-vertical"></i> </button>
      
      <div class="dropdown-menu"> <a class="dropdown-item" style="cursor:pointer" href="{{url('panel/edit-customer',base64_encode($row->id))}}"><i class="icon-base ti tabler-pencil me-1"></i> Edit</a>
      
      <a class="dropdown-item" href="{{url('panel/bank-accounts',base64_encode($row->id))}}"><i class="icon-base ti tabler-building-bank me-1"></i> Bank Details</a>
      	@if(Session::get('admin_type') == 'Admin') 
      		<?php /*?><a class="dropdown-item" onclick="deleteData('master_customer','{{ $row->id }}');" href="javascript:void(0);"><i class="icon-base ti tabler-trash me-1"></i> Delete</a><?php */?> 
       	@endif            
      </div>
    </div>
  </td>
</tr>
@endforeach

@else
    
<tr>
  <td align="center" colspan="15">Record not found</td>
</tr>
@endif
<tr>
  <td align="center" colspan="10"><div id="pagination">{{ $records->appends(request()->except('page'))->links('pagination.front') }}</div></td>
</tr>
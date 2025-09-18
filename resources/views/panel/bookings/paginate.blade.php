@if(count($records) > 0)
	@php
    	$findtolinv=0;
		$findtotreg=0;
    @endphp
    @foreach($records as $key => $row)
	@php    	
        $findinvoice_no = getCustomQuery("Select invoiceno from met_invoice where brsid='".$row->myid."'");
	    $findinvoice_no_res=$findinvoice_no;
    	$findtolinv += $row->investment_amount;
	    $findtotreg += $row->reg_fee;
   	@endphp

    <tr>								
        <td>
        	@if(isset($findinvoice_no_res[0]->invoiceno))
        		{{ stripslashes($findinvoice_no_res[0]->invoiceno) }}
            @endif
        </td>
        <td>{{ stripslashes($row->product_name) }}</td>
        <td>{{ stripslashes($row->cust_name) }}</td>
        @if($PREV == 1 || $PREV == 3)
			<td>{{ stripslashes($row->branch_name) }}</td>
        @endif
        <td>{{ stripslashes($row->udaid) }}</td>
        <td>{{ stripslashes($row->paid_on) }}</td>
        <td>{{ stripslashes($row->reg_fee) }}</td>
        <td>{{ stripslashes($row->investment_amount) }}</td>

        <td>
        @if($row->status==1)
        <button type="button" class="btn btn-block btn-outline-primary" onclick="javascript:window.open('{{ $row->mypdf_agreement }}','_blank');">Print</button>
        @endif
        @if($row->status==2)
        <p style="color:red;">Bounced</p>
        @endif
        @if($row->status==0)
        <button type="button" class="btn btn-block btn-outline-danger" role="button" data-tooltip="tooltip" data-placement="bottom" title="BRS not completed!"><i class="icon-base ti tabler-lock icon-md"></i></button>
        @endif
        </td>        
        <td>
        @if($row->status==1)        
        <button type="button" class="btn btn-block btn-outline-info" onclick="javascript:window.open('{{ $row->mypdf_invoice }}','_blank');">Print</button>
        @endif
        
        @if($row->status==0)
        <button type="button" class="btn btn-block btn-outline-danger" role="button" data-tooltip="tooltip" data-placement="bottom" title="BRS not completed!"><i class="icon-base ti tabler-lock icon-md"></i></button>
        @endif
        </td>
        <td>
		@php
            if($row->status==1){                
                $curdate=date('Y').'-'.date('m').'-'.date('d');
                if($row->is_renewed == 0){
                    $findrevinv_res = getOrgBrsid($row->myid);
                    $mycntt=0;
                    if(isset($findrevinv_res->org_brsid)){
                        $findrevinv = getRenewalBrsid($findrevinv_res->org_brsid);
                   	}
                    if(isset($findrevinv_res->org_brsid) && $findrevinv_res->org_brsid != ""){
                        $mycntt=$findrevinv->count();
                    }
                    if($mycntt < 2){
                    	if($row->myddiff>=1){
            @endphp
            <button type="button" class="btn btn-block btn-outline-info" onclick="reInvestment('{{ base64_encode($row->myid) }}')"><i class="fa fa-repeat" aria-hidden="true"></i>Reinvest<?php //echo $mycntt;?></button>
            @php } } } } @endphp
    	</td>
    </tr>    
    @endforeach
    
    <tr>
    <th colspan="8" style="text-align:right">Total Registration Fees :</th> 
    <th colspan="1" style="text-align:right">{{ number_format($sum_reg_fee,2) }}</th>
    <th></th>
    </tr>
	<tr>
    <th colspan="8" style="text-align:right">Total Booking Amount :</th>
    <th colspan="1" style="text-align:right">{{ number_format($sum_investment_amount,2) }}</th>
    <th></th>
    <th></th>
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
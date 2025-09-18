<?php
function getDetails($table,$id,$field){
	$query = DB::table($table);
	$query->where('status',1);	
	$query->where('id',$id);
	$data = $query->first();	
	if(isset($data->id)){		
		return $data->$field;		
	}else{		
		return '';		
	}
}

function getTotal($table,$status = NULL){
	$query = DB::table($table);
	if($status == NULL){
		$query->where('status','!=',3);
	}else{
		$query->where('status',$status);
	}
	return $query->count();
}

function getCustomQuery($query){
	return DB::select($query);
}

function getOrgBrsid($id){
	$query = DB::table('renenwal_brs');
	$query->where('renewed_brsid',$id);
	return $query->first();
}

function getRenewalBrsid($id){
	$query = DB::table('renenwal_brs');
	$query->where('org_brsid',$id);
	return $query->get();
}

function getTotalCustomer($table, $branch = NULL){
	$query = DB::table($table);
	if($branch == NULL){
		$query->where('status','!=',3);
	}else{
		$query->where('status','!=',3);
		$query->where('branch_id',$branch);
	}
	return $query->count();
}

function getTotalInvestment($table, $branch = NULL){
	$query = DB::table($table)->join('master_customer','master_customer.id','=','mel_investment.cust_id')
	->join('master_payment_method','mel_investment.payment_method','=','master_payment_method.id')
	->join('master_product','mel_investment.product_id','=','master_product.id')
	->join('master_branch','mel_investment.branch_id','=','master_branch.id');

	$query->select(['mel_investment.investment_amount as investment_amount']);
		
	if($branch != NULL){		
		$query->where('mel_investment.branch_id',$branch);
	}
	return $query->sum('investment_amount');
}

function getTotalInvestmentByDate($table, $date, $branch = NULL){
	$query = DB::table($table);
	$query->where('paid_on',$date);
	$query->where('status','!=',3);
	if($branch != NULL){
		$query->where('branch_id',$branch);
	}
	return $query->sum('investment_amount');
}

function getTotalOldInvestment($table, $branch = NULL){
	$query = DB::table($table);
	$query->where('status','!=',3);
	if($branch != NULL){
		$query->where('branch_id',$branch);
	}
	$query->where('is_paid',0);
	$query->where('maturity_date','<=','NOW()');
	return $query->sum('investment_amount');
}

function getTodaysCashCollection($table, $branch = NULL){
	$date = date('Y-m-d');
	$query = DB::table($table);
	$query->where('paid_on',$date);
	$query->where('status','!=',3);
	$query->where('payment_method',1);	
	if($branch != NULL){
		$query->where('branch_id',$branch);
	}
	return $query->sum('investment_amount');
}

function getTodaysBankCollection($table,$branch = NULL){
	$date = date('Y-m-d');
	$query = DB::table($table);
	$query->where('paid_on',$date);
	$query->where('payment_method','!=',1);
	$query->where('status','!=',3);
	if($branch != NULL){
		$query->where('branch_id',$branch);
	}
	return $query->sum('investment_amount');
}

function getTodaysClearedCollection($table,$branch = NULL){
	$date = date('Y-m-d');
	$query = DB::table($table);
	$query->where('cleared_on',$date);
	$query->where('payment_method','!=',1);
	$query->where('status',1);
	if($branch != NULL){
		$query->where('branch_id',$branch);
	}
	return $query->sum('investment_amount');
}

function getTodaysBouncedCollection($table,$branch = NULL){
	$date = date('Y-m-d');
	$query = DB::table($table);
	$query->where('bounced_date',$date);
	$query->where('status',2);
	if($branch != NULL){
		$query->where('branch_id',$branch);
	}
	return $query->sum('investment_amount');
}
function getTodaysMaturity($table,$branch = NULL){
	$sdate = date('Y-m-d');
	$edate = date('Y-m-d',strtotime('+1 week'));	
	$query = DB::table($table);
	$query->where('is_paid',0);
	$query->where('status',1);
	$query->where('maturity_date',$sdate);
	if($branch != NULL){
		$query->where('branch_id',$branch);
	}
	return $query->sum('maturity_val');
}

function getMaturityOverdue($table,$branch = NULL){	
	$sdate = date('Y-m-d');
	$edate = date('Y-m-d',strtotime('+1 week'));	
	$query = DB::table($table);
	$query->where('maturity_date', '<=', $sdate);
	$query->where('is_paid',0);
	$query->where('status',1);
	if($branch != NULL){
		$query->where('branch_id',$branch);
	}
	return $query->sum('maturity_val');
}

function getUpcoming7DayMaturity($table,$branch = NULL){	
	$sdate = date('Y-m-d');
	$edate = date('Y-m-d',strtotime('+1 week'));	
	$query = DB::table($table);
	$query->where('maturity_date' , '>=',$sdate);
	$query->where('maturity_date' , '<=', $edate);	
	$query->where('is_paid',0);
	$query->where('status',1);
	if($branch != NULL){
		$query->where('branch_id',$branch);
	}
	return $query->sum('maturity_val');
}


function getTotalMaturityInvestment(){

	$query = DB::table('mel_investment')->join('master_customer','master_customer.id','=','mel_investment.cust_id')
	->join('master_payment_method','mel_investment.payment_method','=','master_payment_method.id')
	->join('master_product','mel_investment.product_id','=','master_product.id')
	->join('master_branch','mel_investment.branch_id','=','master_branch.id');

	$query->select(['mel_investment.*',
	'mel_investment.id as myid',
	'master_customer.cust_name',
	'master_customer.udaid',
	'master_customer.id as customr_id',
	'master_payment_method.payment_methods',
	'master_product.product_name',
	'master_product.return_in_days',
	'master_product.id',
	'master_branch.branch_name']);
	
	$query->where('mel_investment.status', 1);	
	$query->where('mel_investment.is_paid', 0);	
	$query->orderBy('mel_investment.id','desc');
	$data = $query->get();
		
	$sum_maturity = 0;
	$sum_investment = 0;	
	foreach($data as $key => $maturity){
		$sum_investment += $maturity->investment_amount;
		$sum_maturity += $maturity->maturity_val;
	}
	
	return array('sum_maturity' => $sum_maturity, 'sum_investment' => $sum_investment);
}

?>
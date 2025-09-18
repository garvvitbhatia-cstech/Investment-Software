<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Customers;
use App\Models\CustomerAccounts;
use App\Models\Holidays;
use App\Models\MetInvoice;
use App\Models\InvestDenomination;
use App\Models\MelInvestment;
use App\RouteHelper;
use App\Models\TokenHelper;
use App\Models\Responses;
use ReallySimpleJWT\Token;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Session;
use Validator;
use Mail;
use URL;
use Illuminate\Validation\Rule;


class BrsController extends Controller {
	
    private static $CustomerAccounts;
	private static $Customers;
	private static $Holidays;
	private static $MetInvoice;
	private static $InvestDenomination;
	private static $MelInvestment;
    private static $TokenHelper;	

    public function __construct(){
        self::$CustomerAccounts = new CustomerAccounts();
		self::$Customers = new Customers();
		self::$Holidays = new Holidays();
		self::$MetInvoice = new MetInvoice();
		self::$InvestDenomination = new InvestDenomination();
		self::$MelInvestment = new MelInvestment();
        self::$TokenHelper = new TokenHelper();
    }

    #admin dashboard page
    public function getList(Request $request){
        if(!$request->session()->has('admin_id')){
            return redirect('/panel/');
        }
		$PREV = $request->session()->get('PREV');
		return view('/panel/brs/index',compact('PREV'));	
    }

    public function listPaginate(Request $request){
        if(!$request->session()->has('admin_id')){
            return redirect('/panel/');
        }
		$PREV = $request->session()->get('PREV');
		$BRID = $request->session()->get('BRID');
			
		if($PREV == 2){
			//$query = DB::select("Select a.*,b.cust_name,c.payment_methods,d.branch_name from mel_investment a inner join master_customer b on a.cust_id=b.id inner join master_payment_method c on a.payment_method=c.id inner join master_branch d on a.branch_id=d.id where a.status=0 and a.branch_id='".$BRID."' order by a.id desc");
			$query = self::$MelInvestment->join('master_customer','master_customer.id','=','mel_investment.cust_id')
			->join('master_payment_method','mel_investment.payment_method','=','master_payment_method.id')
			->join('master_branch','mel_investment.branch_id','=','master_branch.id');	
					
			$query->select(['mel_investment.*',
			'master_customer.cust_name',
			'master_payment_method.payment_methods',
			'master_branch.branch_name']);
						
			$query->where('mel_investment.status', 0);
			$query->where('mel_investment.branch_id', $BRID);
			$query->orderBy('mel_investment.id','desc');
		}
		if($PREV == 1 || $PREV == 3){
			//$query = DB::select("Select a.*,b.cust_name,c.payment_methods,d.branch_name from mel_investment a inner join master_customer b on a.cust_id=b.id inner join master_payment_method c on a.payment_method=c.id inner join master_branch d on a.branch_id=d.id where a.status=0 order by a.id desc");			
			
			$query = self::$MelInvestment->join('master_customer','master_customer.id','=','mel_investment.cust_id')
			->join('master_payment_method','mel_investment.payment_method','=','master_payment_method.id')
			->join('master_branch','mel_investment.branch_id','=','master_branch.id');	
					
			$query->select(['mel_investment.*',
			'master_customer.cust_name',
			'master_payment_method.payment_methods',
			'master_branch.branch_name']);
						
			$query->where('mel_investment.status', 0);
			$query->orderBy('mel_investment.id','desc');
		}
		if($request->input('search_keywords') && $request->input('search_keywords') != ""){

			$SearchKeyword = $request->input('search_keywords');
            $query->where(function($query) use ($SearchKeyword){
                if(!empty($SearchKeyword)){
                    $query->where('mel_investment.serial_no', 'like', '%'.$SearchKeyword.'%') 
                    ->orWhere('mel_investment.ref_no', 'like', '%'.$SearchKeyword.'%')
					->orWhere('master_customer.cust_name', 'like', '%'.$SearchKeyword.'%')
					->orWhere('master_branch.branch_name', 'like', '%'.$SearchKeyword.'%')
					->orWhere('master_payment_method.payment_methods', 'like', '%'.$SearchKeyword.'%')
					->orWhere('mel_investment.receipt_id', 'like', '%'.$SearchKeyword.'%')
					->orWhere('mel_investment.paid_on', 'like', '%'.$SearchKeyword.'%')
					->orWhere('mel_investment.tot_paid', 'like', '%'.$SearchKeyword.'%');
                }
             });

        }
		//$records = $query;
		$records = $query->paginate(20);
        return view('/panel/brs/paginate', compact('records','PREV'));
    }
	
	public function editPage(Request $request){
		if(!$request->session()->has('admin_id')){
            return redirect('/panel/');
        }
		$PREV = $request->session()->get('PREV');
		$BRID = $request->session()->get('BRID');
		$CRXUSERID = $request->session()->get('CRXUSERID');
		$siteurl = env('APP_URL');
		
		if($request->input()){
			$myrealselect = $request->input('row_id');
			$mystatfldval = $request->input('mystat');
			$myclrfldval = $request->input('myclrdate');
			$mystartfldval = $request->input('mystartdate');
			$mytxnfldval = $request->input('mypaymentdetail');

			$findproddetails = DB::select("Select b.return_in_days,b.is_saturday_off,b.is_sunday_off,a.cust_id,a.payment_method,a.cheque_no,a.cheque_date,a.cheque_bank,a.cheque_branch from mel_investment a inner join master_product b on a.product_id=b.id where a.id='" . $myrealselect . "'");
			$findproddetails_res = $findproddetails[0];
			
			$findnewcustomerdetails = DB::select("Select a.customer_id,b.branch_code,a.customer_code,emp_code from master_customer a inner join master_branch b on a.branch_id=b.id where a.id='" . $findproddetails_res->cust_id . "'");	
			$findnewcustomerdetails_res = $findnewcustomerdetails[0];

			if(isset($findnewcustomerdetails_res->customer_id) && $findnewcustomerdetails_res->customer_id != ""){
				$mycustomerid = $findnewcustomerdetails_res->customer_id;
			}else{
				$mycustomerid = 'MT' . $findnewcustomerdetails_res->branch_code . date('dmy', strtotime($mystartfldval)) . '-1';
				DB::table('master_customer')->where('id', $findproddetails_res->ust_id)->update(array('customer_id' => $mycustomerid));
			}
			
			$mycustomercode = $findnewcustomerdetails_res->customer_code;
			$myempcode = stripslashes($findnewcustomerdetails_res->emp_code);
			$mypaymentnote = '';
			if(isset($findproddetails_res->payment_method)){
				if($findproddetails_res->payment_method != 1 && $findproddetails_res->payment_method != 3 && $findproddetails_res->payment_method != 4){
					$mypaymentnote = stripslashes($mytxnfldval);
				}
				if($findproddetails_res->payment_method == 1){	
					$mypaymentnote = '';
				}
				if($findproddetails_res->payment_method == 3){	
					$mypaymentnote = 'Cheque No : ' . stripslashes($findproddetails_res->cheque_no) . ', Date : ' . date('d-m-Y', strtotime($findproddetails_res->cheque_date)) . ', Bank : ' . stripslashes($findproddetails_res->cheque_bank) . ', Branch : ' . stripslashes($findproddetails_res->cheque_branch);
				}
				if($findproddetails_res->payment_method == 4){
					$mypaymentnote = 'Draft No : ' . stripslashes($findproddetails_res->cheque_no) . ', Date : ' . date('d-m-Y', strtotime($findproddetails_res->cheque_date)) . ', Bank : ' . stripslashes($findproddetails_res->cheque_bank) . ', Branch : ' . stripslashes($findproddetails_res->cheque_branch);
				}
			}
						
			$return_in_days = $findproddetails_res->return_in_days;
			
			$future_date1 = strtotime($myclrfldval . "+" . $findproddetails_res->return_in_days . " days");
			$future_date1 = date("Y-m-d", $future_date1);
			
			$future_date = strtotime($mystartfldval . "+" . ($findproddetails_res->return_in_days - 1) . " days");
			$future_date = date("Y-m-d", $future_date);
			$future_date_year = date('Y',strtotime($future_date));

			########### include holiday ################
			$starth_date = date('Y-m-d');
			$endh_date = date('Y-m-d', strtotime('+1 year'));

			$holidays = self::$Holidays->where('status',1)->where('holiday_date','>=',$starth_date)->where('holiday_date','<=',$endh_date)->orderBy('holiday_date')->get();			
			$satoff = $sunoff = 0;
			$is_sat_off = $findproddetails_res->is_saturday_off;
			$is_sun_off = $findproddetails_res->is_sunday_off;

			$hdate = '';
			$hdate = array();
			foreach($holidays as $key => $holiday){
				$hdate[] = $holiday->holiday_date;
			}
			if($is_sat_off == 1){
				$startDates = Carbon::today();
    			$endDates= $startDates->copy()->addYear();	
				$weekendsat = collect();
				while ($startDates->lte($endDates)) {
					if ($startDates->isSaturday()) {
						$weekendsat->push($startDates->toDateString());
					}			
					$startDates->addDay();
				}
			}
			
			if($is_sun_off == 1){
				$startDates1 = Carbon::today();
    			$endDates1 = $startDates1->copy()->addYear();
				$weekendsun = collect();
				while($startDates1->lte($endDates1)){
					if($startDates1->isSunday()){
						$weekendsun->push($startDates1->toDateString());
					}
					$startDates1->addDay();
				}
			}

			$all_holiday_dates= array_unique(array_merge(json_decode($weekendsat),json_decode($weekendsun),$hdate));

			$startFutureDate = Carbon::parse($future_date);
    		$endfutureDate = $startFutureDate->copy()->addYear();
			$period = CarbonPeriod::create($startFutureDate, $endfutureDate);
			
			$all_dates = '';
			$all_dates = array();
			foreach($period as $date){
				if(!in_array($date->toDateString(), $all_holiday_dates)) {
					$all_dates[] = $date->toDateString();
				}
			}
			if(isset($all_dates[0]) && $all_dates[0] != ''){
				$future_date = $all_dates[0];
			}
			
			############################################

			$mod_matdate = $future_date;
			$startdate = $mystartfldval;
			$startvalauto = 1;
			
			$myinsertdate_with_time = date('Y'). '-' .date('m'). '-' .date('d'). '-' .date('H'). '-' .date('i'). '-' .date('s');
			if($mystatfldval == 1){
				/*if($startvalauto==0){
			dbQuery($dbConn,"Update mel_investment set status='".$mystatfldval."',cleared_on='".$myclrfldval."',start_date='".$startdate."',start_date_act='".$myclrfldval."',maturity_date='".$mod_matdate."',real_mat_date='".$future_date1."',manual_start_date='".$startvalauto."',brs_updated_by='".$_SESSION['CRXUSERID']."',brs_updated_on='".$myinsertdate_with_time."' where id='".$myrealselect."'");
			}*/
	
				//if($startvalauto==1) 
				//{
				DB::table('mel_investment')->where('id', $myrealselect)->update(array('status' => $mystatfldval, 'cleared_on' => $myclrfldval, 'start_date' => $startdate, 'start_date_act' => $startdate, 'maturity_date' => $mod_matdate, 'real_mat_date' => $mod_matdate, 'manual_start_date' => $startvalauto, 'brs_updated_by' => $CRXUSERID, 'brs_updated_on' => $myinsertdate_with_time, 'mypaydetail' => $mytxnfldval));
				//}  
				
				$findallmydenom = DB::select("Select * from invest_denomination where investment_id='" . $myrealselect . "'");
				if(isset($findallmydenom[0])){
				$findallmydenom_res = $findallmydenom[0];	
	
				$denom_qnt_2000_amnt = '';
				$denom_qnt_2000 = $findallmydenom_res->denom_2000;	
				if($denom_qnt_2000 > 0){
					$denom_qnt_2000_amnt = 2000 * $denom_qnt_2000;
				}

				if($denom_qnt_2000 == 0){
					$denom_qnt_2000 = '';
				}

				$denom_qnt_500 = $findallmydenom_res->denom_500;	
				$denom_qnt_500_amnt = '';
				if($denom_qnt_500 > 0){
					$denom_qnt_500_amnt = 500 * $denom_qnt_500;
				}
	
				if($denom_qnt_500 == 0){
					$denom_qnt_500 = '';
				}
	
				$denom_qnt_200 = $findallmydenom_res->denom_200;	
				$denom_qnt_200_amnt = '';
				if($denom_qnt_200 > 0){
					$denom_qnt_200_amnt = 200 * $denom_qnt_200;
				}
	
				if($denom_qnt_200 == 0){
					$denom_qnt_200 = '';
				}	
	
				$denom_qnt_100 = $findallmydenom_res->denom_100;	
				$denom_qnt_100_amnt = '';
				if($denom_qnt_100 > 0){
					$denom_qnt_100_amnt = 100 * $denom_qnt_100;
				}
	
				if($denom_qnt_100 == 0){
					$denom_qnt_100 = '';
				}
	
				$denom_qnt_50 = $findallmydenom_res->denom_50;	
				$denom_qnt_50_amnt = '';
				if($denom_qnt_50 > 0){
					$denom_qnt_50_amnt = 50 * $denom_qnt_50;
				}
	
				if($denom_qnt_50 == 0){
					$denom_qnt_50 = '';
				}
	
				$denom_qnt_20 = $findallmydenom_res->denom_20;	
				$denom_qnt_20_amnt = '';
				if($denom_qnt_20 > 0){
					$denom_qnt_20_amnt = 20 * $denom_qnt_20;
				}
	
				if($denom_qnt_20 == 0){
					$denom_qnt_20 = '';
				}	
	
				$denom_qnt_10 = $findallmydenom_res->denom_10;	
				$denom_qnt_10_amnt = '';
				if($denom_qnt_10 > 0){
					$denom_qnt_10_amnt = 10 * $denom_qnt_10;
				}
	
				if($denom_qnt_10 == 0){
					$denom_qnt_10 = '';
				}	
	
				$denom_qnt_5 = $findallmydenom_res->denom_5;	
				$denom_qnt_5_amnt = '';
				if($denom_qnt_5 > 0){
					$denom_qnt_5_amnt = 5 * $denom_qnt_5;
				}
	
				if($denom_qnt_5 == 0){
					$denom_qnt_5 = '';
				}
	
				$denom_qnt_2 = $findallmydenom_res->denom_2;	
				$denom_qnt_2_amnt = '';
				if($denom_qnt_2 > 0){
					$denom_qnt_2_amnt = 2 * $denom_qnt_2;
				}
	
				if($denom_qnt_2 == 0){
					$denom_qnt_2 = '';
				}
	
				$denom_qnt_1 = $findallmydenom_res->denom_1;	
				$denom_qnt_1_amnt = '';
				if($denom_qnt_1 > 0){
					$denom_qnt_1_amnt = 1 * $denom_qnt_1;
				}
				if($denom_qnt_1 == 0){
					$denom_qnt_1 = '';
				}	
				}
	
				$invdetails = DB::select("Select a.*, a.ally_code as alcode,a.id as actid,b.*,c.payment_methods,d.product_name,f.state_name from mel_investment a inner join master_customer b on a.cust_id=b.id inner join master_payment_method c on a.payment_method=c.id inner join master_product d on a.product_id=d.id inner join master_states f on b.state=f.id  where a.id='" . $myrealselect . "'");	
				$invdetails_res = $invdetails[0];	
	
				// echo "<pre>";
				// print_r($invdetails_res);
				// echo "</pre>";
				// die();
						
				$findinvoice_no = DB::select("Select invoiceno from met_invoice where brsid='" . $invdetails_res->actid . "'");
				$findinvoice_no_res = $findinvoice_no[0];
	
				$mydaydate = date('d-m-Y');
				$mystartdate = date('d-m-Y', strtotime($invdetails_res->start_date));
				$mobno = $invdetails_res->contact_no;
	
				$altno = $invdetails_res->alt_contact_no;
				$gurdain_name = $invdetails_res->gurdain_name;
	
				$cust_addr = stripslashes($invdetails_res->address). ' ' .stripslashes($invdetails_res->city). ' ' .stripslashes($invdetails_res->state_name). ' ' .stripslashes($invdetails_res->zip_code);
	
				$cust_name = $invdetails_res->cust_name;
				$paydate = date('d-m-Y', strtotime($invdetails_res->maturity_date));
	
				$panno = $invdetails_res->pan_no;	
				$adhno = $invdetails_res->udaid;	
				$allycode = $invdetails_res->alcode;	
				$empcode = $CRXUSERID;	
				$invamnt = stripslashes($invdetails_res->investment_amount);
	
				$regamnt = stripslashes($invdetails_res->reg_fee);
				$paymethod = stripslashes($invdetails_res->payment_methods);
	
				if($findproddetails_res->payment_method != 1 && $findproddetails_res->payment_method != 3 && $findproddetails_res->payment_method != 4){
					$mypaymentnote = $paymethod . ' : ' . stripslashes($mytxnfldval);
				}
	
				$mytotamnt = stripslashes($invdetails_res->tot_paid);	
				$payment_ref_no = stripslashes($invdetails_res->ref_no);	
				$myunit = $invdetails_res->tot_unit;	
				$custid_dec = $invdetails_res->cust_id;	
				$insertid = $invdetails_res->actid;
				$order = time() . '_' . $insertid;
				$tickname = $order . ".pdf";	
				$matval = stripslashes($invdetails_res->maturity_val);	
				$myinvid = stripslashes($findinvoice_no_res->invoiceno);	
				$account_id = stripslashes($invdetails_res->credit_bank_account);

				$findaccounttype = DB::select("Select * from customer_account where id='" . $account_id . "'");
				$findaccounttype_res = $findaccounttype[0];
	
				$accntholder = stripslashes($findaccounttype_res->accnt_holder_name);	
				$ifsc = stripslashes($findaccounttype_res->accnt_ifsc_code);
				$bankname = stripslashes($findaccounttype_res->bank_name);
				$accntno = stripslashes($findaccounttype_res->back_ac_no);
				$branch_name = stripslashes($findaccounttype_res->branch_name);
				$branch_addr = stripslashes($findaccounttype_res->branch_addr);
	
				//include_once('pdf_invoice_new_not_reinv.php');
	
				$invdetails = DB::select("Select a.*,b.*,c.payment_methods,d.product_name,d.return_in_days,d.interest_rate,e.relation_name,f.occ_name,g.state_name,h.* from mel_investment a inner join master_customer b on a.cust_id=b.id inner join master_payment_method c on a.payment_method=c.id inner join master_product d on a.product_id=d.id inner join master_relation e on b.relationship=e.id inner join master_occupation f on b.occupation=f.id inner join master_states g on b.state=g.id inner join customer_account h on a.credit_bank_account=h.id where a.id='" . $myrealselect . "'");
				$invdetails_res = $invdetails[0];
				
				$findinvoice_no = DB::select("Select invoiceno from met_invoice where brsid='" . $myrealselect . "'");
				$findinvoice_no_res = $findinvoice_no[0];
				
				$mystartdate1 = date('d');
				$mystartdate2 = date('S');
				$mystartdate3 = date('F');
				$mystartdate4 = date('Y');
	
				$myagreementdate1 = date('d', strtotime($invdetails_res->start_date));
				$myagreementdate2 = date('S', strtotime($invdetails_res->start_date));
				$myagreementdate3 = date('F', strtotime($invdetails_res->start_date));
				$myagreementdate4 = date('Y', strtotime($invdetails_res->start_date));
	
				$myagreementdate_paid1 = date('d', strtotime($invdetails_res->start_date));
				$myagreementdate_paid2 = date('S', strtotime($invdetails_res->start_date));
				$myagreementdate_paid3 = date('F', strtotime($invdetails_res->start_date));
				$myagreementdate_paid4 = date('Y', strtotime($invdetails_res->start_date));
	
				$myagreementdate_mat1 = date('d', strtotime($invdetails_res->maturity_date));
				$myagreementdate_mat2 = date('S', strtotime($invdetails_res->maturity_date));
				$myagreementdate_mat3 = date('F', strtotime($invdetails_res->maturity_date));
				$myagreementdate_mat4 = date('Y', strtotime($invdetails_res->maturity_date));
				
				//$class_obj = new numbertowordconvertsconver();
				$order = time() . '_agreement_' . $insertid;
				$tickname1 = $order . ".pdf";	
	
				//include_once('pdf_agreement.php');					
				
				$url = 'https://api-ssl.bitly.com/v4/bitlinks';
				$ch = curl_init($url);
				curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(['long_url' => $siteurl . 'invoice/' . $tickname]));
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
				curl_setopt($ch, CURLOPT_HTTPHEADER, [
					"Authorization: Bearer 1af80b7d1145db256c9f5b04cd37c6527f565293",
					"Content-Type: application/json"
				]);
	
				$arr_result = json_decode(curl_exec($ch));
				$bitlink = "";
				if(isset($arr_result->link)){
					$bitlink = $arr_result->link;
				}
	
				$url1 = 'https://api-ssl.bitly.com/v4/bitlinks';
				$ch1 = curl_init($url1);
				curl_setopt($ch1, CURLOPT_POSTFIELDS, json_encode(['long_url' => $siteurl . 'agreement/' . $tickname1]));
				curl_setopt($ch1, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($ch1, CURLOPT_SSL_VERIFYPEER, false);
				curl_setopt($ch1, CURLOPT_HTTPHEADER, [
					"Authorization: Bearer 1af80b7d1145db256c9f5b04cd37c6527f565293",
					"Content-Type: application/json"
				]);
				
				$bitlink1 = '';
				$arr_result1 = json_decode(curl_exec($ch1));
				if(isset($arr_result1->link)){
					$bitlink1 = $arr_result1->link;
				}
	
				if($bitlink != "" && $bitlink1 != ""){
					DB::table('mel_investment')->where('id', $myrealselect)->update(array('mypdf_invoice' => $bitlink, 'mypdf_agreement' => $bitlink1));
					if($invdetails_res->contact_no != ""){
						$apiKey = urlencode('NzU2ZjY5NTYzODM0NmI3NjUwNmU1NzU0NzE0YzZkMzU=');
	
						$l_mob_dup = '91' . $invdetails_res->contact_no;
	
						$numbers = array();
						$numbers[] = $l_mob_dup;
						$sender = urlencode('JAYBIS');
						$message = rawurlencode('Payment received. Click for details ' . $bitlink . ', ' . $bitlink1 . ' - Jayant Biswas');
	
						$numbers = implode(',', $numbers);	
						$data = array('apikey' => $apiKey, 'numbers' => $numbers, "sender" => $sender, "message" => $message);
		
						$ch = curl_init('https://api.textlocal.in/send/');
						curl_setopt($ch, CURLOPT_POST, true);
						curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
						curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
						$response = curl_exec($ch);
						curl_close($ch);
						DB::table('mel_investment')->where('id', $myrealselect)->update(array('mymsg_pdfresponse' => $response));
					}
	
					if($invdetails_res->email != ""){
						/*$mail = new PHPMailer();
	
						$mail->From = "noreply@jayantbiswas.co.in";
						$mail->FromName = "Jayantbiswas";
						$mail->Subject = 'Jayantbiswas - investment #' . $myinvid . ' created!!';
						$mail->isHTML(true);
						$mail->AddAddress($invdetails_res['email']);
						//$mail->AddAddress('crs.info@metalloids.tech');
	
						$message = '<!DOCTYPE html>   
							<html lang="en">
							<head>
							<meta charset="UTF-8" />
							<meta name="viewport" content="width=device-width, initial-scale=1.0" />
							<meta http-equiv="X-UA-Compatible" content="ie=edge" />
							<link href="https://fonts.googleapis.com/css2?family=Karla:wght@200;300;400;500;600;700;800&display=swap" rel="stylesheet"> 
							</head>
							<body>
							<table cellpadding="0" cellspacing="0" width="600px" style="margin:0 auto; font-family: \'Karla\', sans-serif; border:1px #f74847 solid; border-top:3px #f74847 solid; text-align:center;">
							<tr>
							<td style="padding:5px 0; font-size:20px;">Hello ' . stripslashes($findcustomer_branch_res->cust_name) . ',</td>
							</tr>
							<tr>
							<td style="padding:5px; font-size:18px; color:green">Your investment #' . $myinvid . ' has been created. </td>
							</tr>
							<tr>
							<td style="padding-top:20px;">Download Receipt: <a href="' . $bitlink . '">' . $bitlink . '</a></td>
							</tr>
							<tr>
							<td style="padding-top:20px;">Download Agreement: <a href="' . $bitlink1 . '">' . $bitlink1 . '</a></td>
							</tr>
	
							<tr>
							<td style="padding:10px 0;">Thanks</td>
							</tr>
							<tr>
							<td style="padding-bottom:30px;">Jayant Biswas</td>
							</tr>
							</table></body></html>';
	
						$mail->Body = $message;
						$mail->send();*/
					} else {
	
	
						/*$mail = new PHPMailer();
	
						$mail->From = "noreply@jayantbiswas.co.in";
						$mail->FromName = "Jayantbiswas";
						$mail->Subject = 'Jayantbiswas - investment #' . $myinvid . ' created!!';
						$mail->isHTML(true);
						//$mail->AddAddress('crs.info@metalloids.tech');
	
						$message = '<!DOCTYPE html>
							<html lang="en">
							<head>
							<meta charset="UTF-8" />
							<meta name="viewport" content="width=device-width, initial-scale=1.0" />
							<meta http-equiv="X-UA-Compatible" content="ie=edge" />
							<link href="https://fonts.googleapis.com/css2?family=Karla:wght@200;300;400;500;600;700;800&display=swap" rel="stylesheet"> 
							</head>
							<body>
							<table cellpadding="0" cellspacing="0" width="600px" style="margin:0 auto; font-family: \'Karla\', sans-serif; border:1px #f74847 solid; border-top:3px #f74847 solid; text-align:center;">
							<tr>
							<td style="padding:5px 0; font-size:20px;">Hello ' . stripslashes($findcustomer_branch_res->cust_name) . ',</td>
							</tr>
							<tr>
							<td style="padding:5px; font-size:18px; color:green">Your investment #' . $myinvid . ' has been created. </td>
							</tr>
							<tr>
							<td style="padding-top:20px;">Download Receipt: <a href="' . $bitlink . '">' . $bitlink . '</a></td>
							</tr>
							<tr>
							<td style="padding-top:20px;">Download Agreement: <a href="' . $bitlink1 . '">' . $bitlink1 . '</a></td>
							</tr>
	
							<tr>
							<td style="padding:10px 0;">Thanks</td>
							</tr>
							<tr>
							<td style="padding-bottom:30px;">Jayant Biswas</td>
							</tr>
							</table></body></html>';
	
						$mail->Body = $message;
						$mail->send();*/
					}
				}
			}
			if($mystatfldval == 2){
				DB::table('mel_investment')->where('id', $myrealselect)->update(array('status' => $mystatfldval, 'cleared_on' => $myclrfldval, 'start_date' => $mystartfldval, 'future_date' => $future_date, 'bounced_date' => time(), 'brs_updated_by' => $CRXUSERID, 'brs_updated_on' => $myinsertdate_with_time));
			}
			echo "Success";
		}
		exit;
	}

	function convert_number($number){
		if(($number < 0) || ($number > 999999999)){
			throw new Exception("Number is out of range");
		}
		$giga = floor($number / 1000000);
		// Millions (giga)
		$number -= $giga * 1000000;
		$kilo = floor($number / 1000);
		// Thousands (kilo)
		$number -= $kilo * 1000;
		$hecto = floor($number / 100);
		// Hundreds (hecto)
		$number -= $hecto * 100;
		$deca = floor($number / 10);
		// Tens (deca)
		$n = $number % 10;
		// Ones
		$result = "";
		if($giga){
			$result .= $this->convert_number($giga) .  "Million";
		}
		if($kilo){
			$result .= (empty($result) ? "" : " ") . $this->convert_number($kilo) . " Thousand";
		}
		if($hecto){
			$result .= (empty($result) ? "" : " ") . $this->convert_number($hecto) . " Hundred";
		}
		$ones = array("", "One", "Two", "Three", "Four", "Five", "Six", "Seven", "Eight", "Nine", "Ten", "Eleven", "Twelve", "Thirteen", "Fourteen", "Fifteen", "Sixteen", "Seventeen", "Eightteen", "Nineteen");
		$tens = array("", "", "Twenty", "Thirty", "Fourty", "Fifty", "Sixty", "Seventy", "Eigthy", "Ninety");
		if($deca || $n){
			if(!empty($result)){
				$result .= " and ";
			}
			if($deca < 2){
				$result .= $ones[$deca * 10 + $n];
			} else {
				$result .= $tens[$deca];
				if($n){
					$result .= "-" . $ones[$n];
				}
			}
		}
		if(empty($result)){
			$result = "zero";
		}
		return $result;
	}

}
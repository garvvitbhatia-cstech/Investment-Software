<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Customers;
use App\Models\CustomerAccounts;
use App\Models\MetInvoice;
use App\Models\RenenwalBrs;
use App\Models\Products;
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
use Session;
use Validator;
use Mail;
use URL;
use Illuminate\Validation\Rule;


class SchemeBookingsController extends Controller {
	
    private static $CustomerAccounts;
	private static $Customers;
	private static $RenenwalBrs;
	private static $Products;
	private static $MetInvoice;
	private static $InvestDenomination;
	private static $MelInvestment;
    private static $TokenHelper;	

    public function __construct(){
        self::$CustomerAccounts = new CustomerAccounts();
		self::$Customers = new Customers();
		self::$Products = new Products();
		self::$MetInvoice = new MetInvoice();
		self::$RenenwalBrs = new RenenwalBrs();
		self::$InvestDenomination = new InvestDenomination();
		self::$MelInvestment = new MelInvestment();
        self::$TokenHelper = new TokenHelper();
    }

    #admin dashboard page
    public function getList(Request $request){
        if(!$request->session()->has('admin_id')){
            return redirect('/panel/');
        }
		$products = $this->getProducts();
		$PREV = $request->session()->get('PREV');
		return view('/panel/bookings/index',compact('PREV','products'));
    }

    public function listPaginate(Request $request){
        if(!$request->session()->has('admin_id')){
            return redirect('/panel/');
        }
		$PREV = $request->session()->get('PREV');
		$BRID = $request->session()->get('BRID');

		$query = self::$MelInvestment->join('master_customer','master_customer.id','=','mel_investment.cust_id')
		->join('master_payment_method','mel_investment.payment_method','=','master_payment_method.id')
		->join('master_product','mel_investment.product_id','=','master_product.id')
		->join('master_branch','mel_investment.branch_id','=','master_branch.id');

		$query->select(['mel_investment.*',
		'mel_investment.id as myid',
		'master_customer.cust_name',
		'master_customer.udaid',
		'master_payment_method.payment_methods',
		'master_product.product_name',
		'master_product.id',
		'master_branch.branch_name',
		'mel_investment.maturity_date as myddiff']);
		
		if(!empty($request->input('myprod'))){
			$query->where('master_product.id', $request->input('myprod'));
		}			
		if(!empty($request->input('from_date')) || !empty($request->input('to_date'))){
			$from_date = $request->input('from_date');
			$to_date = $request->input('to_date');
			if(!empty($from_date) && empty($to_date)){
				$from_date = $request->input('from_date');
			}else if(empty($from_date) && !empty($to_date)){
				$to_date = $request->input('to_date');
			}else{
				$from_date = $request->input('from_date');
				$to_date = $request->input('to_date');
			}
			$query->where('mel_investment.paid_on' , '>=', $from_date);
			$query->where('mel_investment.paid_on' , '<=', $to_date);
		}
		
		if($PREV == 2){			
			//$findallcustomer=dbQuery($dbConn,"Select a.id as myid,a.*,b.cust_name,b.udaid,c.payment_methods,d.prod_name,d.id,e.branch_name,datediff(a.maturity_date,NOW()) as myddiff from mel_investment a inner join master_customer b on a.cust_id=b.id inner join master_payment_method c on a.payment_method=c.id inner join master_product d on a.product_id=d.id inner join master_branch e on a.branch_id=e.id where 1 $mystr $mystr2 and a.branch_id='".$_SESSION['BRID']."' order by a.id desc");			

			$query->where('mel_investment.branch_id', $BRID);	
		}
		if($PREV == 1 || $PREV == 3){
			//$findallcustomer=dbQuery($dbConn,"Select a.id as myid,a.*,b.cust_name,b.udaid,c.payment_methods,d.prod_name,d.id,e.branch_name from mel_investment a inner join master_customer b on a.cust_id=b.id inner join master_payment_method c on a.payment_method=c.id inner join master_product d on a.product_id=d.id inner join master_branch e on a.branch_id=e.id where 1 $mystr $mystr2  order by a.id desc");
			
		}
		if($request->input('search_keywords') && $request->input('search_keywords') != ""){

			$SearchKeyword = $request->input('search_keywords');
            $query->where(function($query) use ($SearchKeyword){
                if(!empty($SearchKeyword)){
                    $query->where('master_product.product_name', 'like', '%'.$SearchKeyword.'%') 
                    ->orWhere('master_customer.cust_name', 'like', '%'.$SearchKeyword.'%')
					->orWhere('master_branch.branch_name', 'like', '%'.$SearchKeyword.'%')
					->orWhere('master_customer.udaid', 'like', '%'.$SearchKeyword.'%')
					->orWhere('mel_investment.paid_on', 'like', '%'.$SearchKeyword.'%')
					->orWhere('mel_investment.reg_fee', 'like', '%'.$SearchKeyword.'%')				
					->orWhere('mel_investment.investment_amount', 'like', '%'.$SearchKeyword.'%');
                }
             });

        }
		$query->where('mel_investment.status', '!=',3);
		$query->orderBy('mel_investment.id','desc');		
		$all_record = $query->get();
		$records = $query->paginate(20);
		$sum_investment_amount = $all_record->sum('investment_amount');
		$sum_reg_fee = $all_record->sum('reg_fee');
        return view('/panel/bookings/paginate', compact('records','PREV','sum_investment_amount','sum_reg_fee'));
    }
	
	public function reInvestment(Request $request){
		if(!$request->session()->has('admin_id')){
            return redirect('/panel/');
        }
		if($request->ajax()){
			$PREV = $request->session()->get('PREV');
			$BRID = $request->session()->get('BRID');
			$CRXUSERID = $request->session()->get('CRXUSERID');
			$siteurl = env('APP_URL');
			$investmentid_dec = base64_decode($request->invid);
			if(is_numeric($investmentid_dec)){						
				$orgbrsid='';				
				$findtransaction_res = self::$RenenwalBrs->where('renewed_brsid',$investmentid_dec)->first();
				if(isset($findtransaction_res->org_brsid)){
					$orgbrsid = $findtransaction_res->org_brsid;
				}else{
					$orgbrsid=$investmentid_dec;	
				}

				$findtotrenewal = self::$RenenwalBrs->where('org_brsid',$orgbrsid)->get();
				
				if($findtotrenewal->count() < 2){
					
					$findinvoicenoold_res = self::$MetInvoice->where('brsid',$investmentid_dec)->first();
					$oldinvoiceno=$findinvoicenoold_res->invoiceno;
					
					$findinvdetails_res = self::$MelInvestment->where('id',$investmentid_dec)->first();					
					if($findinvdetails_res->is_renewed==0){
						$account_id=$findinvdetails_res->credit_bank_account;
						$myproduct=$findinvdetails_res->product_id;
						$myunit=$findinvdetails_res->tot_unit;
						$custid_dec=$findinvdetails_res->cust_id;

						$findaccounttype_res = self::$CustomerAccounts->where('id',$account_id)->first();
						$findproddetails_res = self::$Products->where('id',$myproduct)->first();
						
						$matval=$findinvdetails_res->investment_amount+ ($findinvdetails_res->investment_amount*($findproddetails_res->interest_rate/100));
						$modified_mat_val=$findinvdetails_res->maturity_val-$findinvdetails_res->investment_amount-($findproddetails_res->fee_per_unit*$myunit);					
						
						if (date('m') <= 3) {//Upto June 2014-2015
							$financial_year = (date('Y')-1) . '-' . date('y');
						} else {//After June 2015-2016
							$financial_year = date('Y') . '-' . (date('y') + 1);
						}			

						$findcustomer_branch = self::$Customers->join('master_states','master_customer.state','=','master_states.id');				
						$findcustomer_branch->select(['master_customer.*',
						'master_states.state_name']);						
						$findcustomer_branch->where('master_customer.id',$custid_dec);
						$findcustomer_branch_res = $findcustomer_branch->first();
										
						//date_default_timezone_set('Asia/Kolkata');
						$myinsertdate_with_time=date('Y').'-'.date('m').'-'.date('d').'-'.date('H').'-'.date('i').'-'.date('s');
						$myinsertdate=date('Y').'-'.date('m').'-'.date('d');
						
						$myinsertdateint=strtotime($findinvdetails_res->maturity_date);							
						$mystartday=date("w", $myinsertdateint);
						
						
						
						/*if($mystartday==4 || $mystartday==5 || $mystartday==6 || $mystartday==0 || $mystartday==2 || $mystartday==3 || $mystartday==1)  
						{
							
							$startdate=date('Y-m-d', strtotime('next monday', $myinsertdateint));
						}*/
						
						 $startdate=$findinvdetails_res->maturity_date;
						/*if($mystartday==1)  
						{
							
							$startdate=$findinvdetails_res['maturity_date'];
						}
						
						if($mystartday==2 || $mystartday==3)  
						{
							
							$startdate=date('Y-m-d', strtotime('previous monday', $myinsertdateint));
						}*/
						
						//echo $startdate;
						//exit;
						$future_date=strtotime("+".($findproddetails_res->return_in_day-1)." days",strtotime($startdate));						
						$future_date=date("Y-m-d", $future_date);
						
						
						/*$future_date_day=date("w", strtotime($future_date));
						
						if($future_date_day==0 || $future_date_day==1 || $future_date_day==2 || $future_date_day==3 || $future_date_day==5 || $future_date_day==6)
						{
							
							$mod_matdate=date('Y-m-d', strtotime('next thursday', strtotime($future_date)));
						}
						if($future_date_day==4)
						{*/
							
							 $mod_matdate=$future_date;
							
						//}
						
						$setMelInvestmentData['cust_id'] = $custid_dec;
						$setMelInvestmentData['product_id'] = $myproduct;
						$setMelInvestmentData['investment_amount'] = $findinvdetails_res->investment_amount;
						$setMelInvestmentData['reg_fee'] = $findproddetails_res->fee_per_unit*$myunit;
						$setMelInvestmentData['tot_paid'] = $findinvdetails_res->investment_amount+($findproddetails_res->fee_per_unit*$myunit);
						$setMelInvestmentData['paid_on'] = $myinsertdate;
						$setMelInvestmentData['cleared_on'] = $myinsertdate;
						$setMelInvestmentData['start_date'] = $startdate;
						$setMelInvestmentData['start_date_act'] = $myinsertdate;
						$setMelInvestmentData['maturity_val'] = $matval;
						$setMelInvestmentData['credit_bank_account'] = $account_id;
						$setMelInvestmentData['payment_method'] = 9;
						$setMelInvestmentData['status'] = 1;
						$setMelInvestmentData['maturity_date'] = $mod_matdate;
						$setMelInvestmentData['real_mat_date'] = $future_date;
						$setMelInvestmentData['branch_id'] = $findcustomer_branch_res->branch_id;
						$setMelInvestmentData['created_by'] = $CRXUSERID;
						$setMelInvestmentData['created_on'] = $myinsertdate_with_time;
						$setMelInvestmentData['brs_updated_by'] = $CRXUSERID;
						$setMelInvestmentData['brs_updated_on'] = $myinsertdate_with_time;
						$setMelInvestmentData['bank_type'] = $findaccounttype_res->bank_type;
						$setMelInvestmentData['tot_unit'] = $myunit;
						$setMelInvestmentData['is_reinvestment'] = 1;
						$setMelInvestmentData['reinvest_from'] = $findinvoicenoold_res->invoiceno;
						$setMelInvestmentData['roi'] = $findproddetails_res->interest_rate;
						$setMelInvestmentData['reinv_ref_id'] = $investmentid_dec;						
                		$minvsrecord = self::$MelInvestment->CreateRecord($setMelInvestmentData);
						
						$insertid = $minvsrecord->id;
						$payment_ref_no=$insertid;
						
						DB::table('mel_investment')->where('id', $investmentid_dec)->update(array('is_renewed' => 1, 'maturity_val' => $modified_mat_val));
						
						$setBrsData['org_brsid'] = $orgbrsid;
						$setBrsData['renewed_brsid'] = $insertid;
						$setBrsData['reinv_ref_id'] = $investmentid_dec;				
                		self::$RenenwalBrs->CreateRecord($setBrsData);						
						
						$receipt_no="BR/".$financial_year.'/'.$findcustomer_branch_res->branch_id.'/'.$insertid;
											
						DB::table('mel_investment')->where('id', $insertid)->update(array('receipt_id' => $receipt_no, 'ref_no' => $payment_ref_no));
						
						$setMetInvData['brsid'] = $insertid;			
						$setMetInvData['invoiceno'] = 'HTX';		
                		$met_iv_record = self::$MetInvoice->CreateRecord($setMetInvData);						
						$myinsertid = $met_iv_record->id;
						$myinvid='INV/'.$financial_year.'/'.$findcustomer_branch_res->branch_id.'/'.$myinsertid;
						
						DB::table('met_invoice')->where('id', $myinsertid)->update(array('invoiceno' => $myinvid));
						
						$mydaydate=date('d-m-Y');
						$mystartdate=date('d-m-Y',strtotime($startdate));
						$mobno=$findcustomer_branch_res->contact_no;
						
						$altno=$findcustomer_branch_res->alt_contact_no;
						$gurdain_name=$findcustomer_branch_res->gurdain_name;
						
						$cust_addr=stripslashes($findcustomer_branch_res->address).' '.stripslashes($findcustomer_branch_res->city).' '.stripslashes($findcustomer_branch_res->state_name).' '.stripslashes($findcustomer_branch_res->zip_code);
						
						$cust_name=$findcustomer_branch_res->cust_name;						
						$mycustomerid=$findcustomer_branch_res->customer_id;						
						$mycustomercode=$findcustomer_branch_res->customer_code;						
						$myempcode=stripslashes($findcustomer_branch_res->emp_code);						
						$paydate=date('d-m-Y',strtotime($mod_matdate));						
						$invamnt=$findinvdetails_res->investment_amount;						
						$panno=$findcustomer_branch_res->pan_no;						
						$adhno=$findcustomer_branch_res->udaid;						
						$empcode=$CRXUSERID;
						
						$regamnt=($findproddetails_res->fee_per_unit*$myunit);
						$paymethod='Cash';
						$mytotamnt=(($findproddetails_res->fee_per_unit+$findproddetails_res->unit_price)*$myunit);
						$order = time().'_'.$insertid;
						$tickname = $order.".pdf";
						
						$accntholder=stripslashes($findaccounttype_res->accnt_holder_name);						
						$ifsc=stripslashes($findaccounttype_res->accnt_ifsc_code);
						$bankname=stripslashes($findaccounttype_res->bank_name);
						$accntno=stripslashes($findaccounttype_res->back_ac_no);
						$branch_name=stripslashes($findaccounttype_res->branch_name);
						$branch_addr=stripslashes($findaccounttype_res->branch_addr);
						
						//include_once('pdf_invoice_new.php');
						
						$miquery = self::$MelInvestment->join('master_customer','mel_investment.cust_id','=','master_customer.id')
						->join('master_payment_method','mel_investment.payment_method','=','master_payment_method.id')
						->join('master_product','mel_investment.product_id','=','master_product.id')
						->join('master_relation','master_customer.relationship','=','master_relation.id')
						->join('master_occupation','master_customer.occupation','=','master_occupation.id')
						->join('master_states','master_customer.state','=','master_states.id')
						->join('customer_account','mel_investment.credit_bank_account','=','customer_account.id');
				
						$miquery->select(['mel_investment.*',
						'master_customer.*',
						'master_payment_method.payment_methods',
						'master_product.product_name',
						'master_product.return_in_days',
						'master_product.interest_rate',
						'master_relation.relation_name',
						'master_occupation.occ_name',
						'customer_account.*']);
						$miquery->where('mel_investment.id', $insertid);		
						$invdetails_res = $miquery->first();
						
						$findinvoice_no_res = self::$MetInvoice->where('brsid',$insertid)->first();
						
						$mystartdate1=date('d');
						$mystartdate2=date('S');
						$mystartdate3=date('F');
						$mystartdate4=date('Y');
			
						$myagreementdate1=date('d',strtotime($invdetails_res->start_date));
						$myagreementdate2=date('S',strtotime($invdetails_res->start_date));
						$myagreementdate3=date('F',strtotime($invdetails_res->start_date));
						$myagreementdate4=date('Y',strtotime($invdetails_res->start_date));
			
						$myagreementdate_paid1=date('d',strtotime($invdetails_res->start_date));
						$myagreementdate_paid2=date('S',strtotime($invdetails_res->start_date));
						$myagreementdate_paid3=date('F',strtotime($invdetails_res->start_date));
						$myagreementdate_paid4=date('Y',strtotime($invdetails_res->start_date));
			
						$myagreementdate_mat1=date('d',strtotime($invdetails_res->maturity_date));
						$myagreementdate_mat2=date('S',strtotime($invdetails_res->maturity_date));
						$myagreementdate_mat3=date('F',strtotime($invdetails_res->maturity_date));
						$myagreementdate_mat4=date('Y',strtotime($invdetails_res->maturity_date));
						
						$order = time().'_agreement_'.$insertid;
						$tickname1 = $order.".pdf";
						
						//include_once('pdf_agreement.php');
						
						$url = 'https://api-ssl.bitly.com/v4/bitlinks';
						$ch = curl_init($url);
						curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(['long_url' => 'https://jayantbiswas.com/ncrs/invoice/'.$tickname]));
						curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
						curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
						curl_setopt($ch, CURLOPT_HTTPHEADER, [
						"Authorization: Bearer 1af80b7d1145db256c9f5b04cd37c6527f565293",
						"Content-Type: application/json"
						]);
			
						$arr_result = json_decode(curl_exec($ch));
						$bitlink=$arr_result->link;
						
						$url1 = 'https://api-ssl.bitly.com/v4/bitlinks';
						$ch1 = curl_init($url1);
						curl_setopt($ch1, CURLOPT_POSTFIELDS, json_encode(['long_url' => 'https://jayantbiswas.com/ncrs/agreement/'.$tickname1]));
						curl_setopt($ch1, CURLOPT_RETURNTRANSFER, true);
						curl_setopt($ch1, CURLOPT_SSL_VERIFYPEER, false);
						curl_setopt($ch1, CURLOPT_HTTPHEADER, [
						"Authorization: Bearer 1af80b7d1145db256c9f5b04cd37c6527f565293",
						"Content-Type: application/json"
						]);
			
						$arr_result1 = json_decode(curl_exec($ch1));
						$bitlink1=$arr_result1->link;
						
						if($bitlink!="" && $bitlink1!=""){						
							DB::table('mel_investment')->where('id', $insertid)->update(array('mypdf_invoice' => $bitlink, 'mypdf_agreement' => $bitlink1));							
							if($findcustomer_branch_res->contact_no!=""){
							$apiKey = urlencode('NzU2ZjY5NTYzODM0NmI3NjUwNmU1NzU0NzE0YzZkMzU=');
							$l_mob_dup='91'.$findcustomer_branch_res->contact_no;
							
							$numbers = array();
							$numbers[]=$l_mob_dup;
							$sender = urlencode('JAYBIS');
							$message = rawurlencode('Payment received. Click for details '.$bitlink.', '.$bitlink1.' - Jayant Biswas');
			
							$numbers = implode(',', $numbers);		
						
							$data = array('apikey' => $apiKey, 'numbers' => $numbers, "sender" => $sender, "message" => $message);			
							
							$ch = curl_init('https://api.textlocal.in/send/');
							curl_setopt($ch, CURLOPT_POST, true);
							curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
							curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
							$response = curl_exec($ch);
							curl_close($ch);
							DB::table('mel_investment')->where('id', $insertid)->update(array('mymsg_pdfresponse' => $response));
							}
							if($findcustomer_branch_res->email != ""){/*
									$mail = new PHPMailer();
							
									$mail->From = "noreply@jayantbiswas.co.in";
									$mail->FromName = "Jayantbiswas";
									$mail->Subject = 'Jayantbiswas - investment #'.$myinvid.' created!!';
									$mail->isHTML(true);
									$mail->AddAddress($findcustomer_branch_res['email']);
			
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
									<td style="padding:5px 0; font-size:20px;">Hello '.stripslashes($findcustomer_branch_res['cust_name']).',</td>
									</tr>
									<tr>
									<td style="padding:5px; font-size:18px; color:green">Your investment #'.$myinvid.' has been created. </td>
									</tr>
									<tr>
									<td style="padding-top:20px;">Download Receipt: <a href="https://jayantbiswas.com/ncrs/invoice/'.$tickname.'">https://jayantbiswas.com/ncrs/invoice/'.$tickname.'</a></td>
									</tr>
									<tr>
									<td style="padding-top:20px;">Download Agreement: <a href="https://jayantbiswas.com/ncrs/agreement/'.$tickname1.'">https://jayantbiswas.com/ncrs/agreement/'.$tickname1.'</a></td>
									</tr>
			
									<tr>
									<td style="padding:10px 0;">Thanks</td>
									</tr>
									<tr>
									<td style="padding-bottom:30px;">Jayant Biswas</td>
									</tr>
									</table></body></html>';
			
									$mail->Body = $message;
									$mail->send();
							*/}
							
						}			
						
					}
					
				}
				
			}
			echo "Success";
		}
		exit;		
	}
	
	public function maturityDashboard(Request $request){
		if(!$request->session()->has('admin_id')){
            return redirect('/panel/');
        }
		$PREV = $request->session()->get('PREV');
		$BRID = $request->session()->get('BRID');
		return view('/panel/bookings/maturity_dashboard',compact('PREV','BRID'));
	}
	
	public function bookingDashboard(Request $request){
		if(!$request->session()->has('admin_id')){
            return redirect('/panel/');
        }
		$PREV = $request->session()->get('PREV');
		$BRID = $request->session()->get('BRID');
		return view('/panel/bookings/booking_dashboard',compact('PREV','BRID'));
	}

	function getProducts(){
		return self::$Products->where('status',1)->get();
	}

}
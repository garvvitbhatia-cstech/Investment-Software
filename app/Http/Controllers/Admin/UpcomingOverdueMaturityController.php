<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Customers;
use App\Models\CustomerAccounts;
use App\Models\PayeeBankDetails;
use App\Models\MetInvoice;
use App\Models\PaymentMethod;
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


class UpcomingOverdueMaturityController extends Controller {

    private static $PaymentMethod;
	private static $CustomerAccounts;
	private static $PayeeBankDetails;
	private static $Customers;
	private static $RenenwalBrs;
	private static $Products;
	private static $MetInvoice;
	private static $InvestDenomination;
	private static $MelInvestment;
    private static $TokenHelper;

    public function __construct(){
        self::$CustomerAccounts = new CustomerAccounts();
		self::$PaymentMethod = new PaymentMethod();
		self::$Customers = new Customers();
		self::$Products = new Products();
		self::$PayeeBankDetails = new PayeeBankDetails();
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
		$payment_methods = $this->getPaymentMethod();
		$payee_bank = $this->getPayeeBank();
		return view('/panel/upcoming_overdue_maturity_report/index',compact('PREV','products','payment_methods','payee_bank'));
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

		//$query->selectRaw('datediff(mel_investment.maturity_date,NOW()) as myddiff');
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

		//'datediff(mel_investment.maturity_date,NOW()) as myddiff']);

		if(!empty($request->input('mystat'))){
			$query->where('mel_investment.status', $request->input('mystat'));
		}
		if(!empty($request->input('myprod'))){
			$query->where('mel_investment.payment_method', $request->input('myprod'));
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
			$query->where('mel_investment.maturity_date' , '>=', $from_date);
			$query->where('mel_investment.maturity_date' , '<=', $to_date);
		}else{
			$mystr=" and (datediff(a.maturity_date,NOW())<=7 and datediff(a.maturity_date,NOW())>0)";

			$sdate = date('Y-m-d');
			$edate = date('Y-m-d',strtotime('+1 week'));

			$query->where('mel_investment.maturity_date' , '>=', $sdate);
			$query->where('mel_investment.maturity_date' , '<=', $edate);
		}

		if($PREV == 2){
			//$findallcustomer=dbQuery($dbConn,"Select a.id as myid,a.*,b.cust_name,b.udaid,c.payment_methods,d.prod_name,d.id,datediff(a.maturity_date,NOW()) as mydiff,e.branch_name from mel_investment a inner join master_customer b on a.cust_id=b.id inner join master_payment_method c on a.payment_method=c.id inner join master_product d on a.product_id=d.id inner join master_branch e on a.branch_id=e.id where 1   $mystr $mystr2 and a.status='1' and a.is_paid='0' and a.branch_id='".$_SESSION['BRID']."'  order by a.id desc");
			$query->where('mel_investment.branch_id', $BRID);
		}
		if($PREV == 1 || $PREV == 3){
			//$findallcustomer=dbQuery($dbConn,"Select a.id as myid,a.*,b.cust_name,b.udaid,c.payment_methods,d.prod_name,d.id,datediff(a.maturity_date,NOW()) as mydiff,e.branch_name from mel_investment a inner join master_customer b on a.cust_id=b.id inner join master_payment_method c on a.payment_method=c.id inner join master_product d on a.product_id=d.id inner join master_branch e on a.branch_id=e.id where 1   $mystr $mystr2 and a.status='1' and a.is_paid='0' and a.branch_id='".$_SESSION['BRID']."'  order by a.id desc");
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
		$query->where('mel_investment.status', 1);	
		$query->where('mel_investment.is_paid', 0);	
		$query->orderBy('mel_investment.id','desc');
		$records = $query->paginate(20);
		
		foreach($records as $key => $record){
			$date1 = $record->maturity_date;
			$date2 = NOW();
			$diff = abs(strtotime($date2) - strtotime($date1));
			$years = floor($diff / (365*60*60*24));
			$months = floor(($diff - $years * 365*60*60*24) / (30*60*60*24));
			$days = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));
			$record->mydiff = $days;
		}
        return view('/panel/upcoming_overdue_maturity_report/paginate', compact('records','PREV'));
    }
	
	public function fetchbank2(Request $request){
		if($request->ajax()){
			$accntid = $request->accntid;
			$findaccntdetails_res = self::$PayeeBankDetails->where('id',$accntid)->first();
			
			$response['bank_name']=stripslashes($findaccntdetails_res->bank_name);
			$response['back_ac_no']=stripslashes($findaccntdetails_res->accnt_no);
			$response['accnt_holder_name']=stripslashes($findaccntdetails_res->accnt_name);
			$response['accnt_ifsc_code']=stripslashes($findaccntdetails_res->ifsc_code);
			$response['branch_name']=stripslashes($findaccntdetails_res->branch_name);
			$response['branch_addr']=stripslashes($findaccntdetails_res->branch_addr);
			
			echo json_encode($response);
		}
		exit;
	}
	
	public function fetchbank3(Request $request){
		if($request->ajax()){
			$brsid = $request->brsid;
			$accntid = $request->accntid;
			$findaccntdetails_res = self::$PayeeBankDetails->where('id',$accntid)->first();
			
			//$findaccntdetails = DB::select("Select a.maturity_val,a.id as myid,b.* from mel_investment a inner join customer_account b on a.credit_bank_account=b.id where a.id='" . $brsid . "'");			
			
			$query = self::$MelInvestment->join('customer_account','customer_account.id','=','mel_investment.credit_bank_account');
			$query->select(['customer_account.*',
			'mel_investment.id as myid',
			'mel_investment.maturity_val']);
			$query->where('mel_investment.id', $brsid);
			//$findaccntdetails = $query->first();
			
			$findaccntdetails = DB::select("Select a.maturity_val,a.id as myid,b.* from mel_investment a inner join customer_account b on a.credit_bank_account=b.id where a.id='" . $brsid . "'");

			$response['bank_name']=stripslashes($findaccntdetails->bank_name);
			$response['back_ac_no']=stripslashes($findaccntdetails->accnt_no);
			$response['accnt_holder_name']=stripslashes($findaccntdetails->accnt_name);
			$response['accnt_ifsc_code']=stripslashes($findaccntdetails->ifsc_code);
			$response['branch_name']=stripslashes($findaccntdetails->branch_name);
			$response['branch_addr']=stripslashes($findaccntdetails->branch_addr);
			$response['matval'] = stripslashes($findaccntdetails->maturity_val);
			$response['brsid'] = stripslashes($findaccntdetails->myid);			
			echo json_encode($response);
		}
		exit;
	}

	function getProducts(){
		return self::$Products->where('status',1)->get();
	}

	function getPaymentMethod(){
		return self::$PaymentMethod->where('is_active',1)->whereIn('id',[5,6,7])->get();
	}
	
	function getPayeeBank(){
		return self::$PayeeBankDetails->where('isactive',1)->get();
	}

}
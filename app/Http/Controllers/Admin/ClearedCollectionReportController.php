<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Customers;
use App\Models\CustomerAccounts;
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


class ClearedCollectionReportController extends Controller {
	
    private static $PaymentMethod;
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
		self::$PaymentMethod = new PaymentMethod();
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
		$payment_methods = $this->getPaymentMethod();
		return view('/panel/cleared_collection_report/index',compact('PREV','products','payment_methods'));
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

		if(!empty($request->input('mystat'))){
			$query->where('mel_investment.status', $request->input('mystat'));
		}else{
			$query->where('mel_investment.status', 1);
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
			$query->where('mel_investment.cleared_on' , '>=', $from_date);
			$query->where('mel_investment.cleared_on' , '<=', $to_date);
		}
		
		if($PREV == 2){
			//$findallcustomer=dbQuery($dbConn,"Select a.id as myid,a.*,b.cust_name,b.udaid,c.payment_methods,d.prod_name,d.id,e.branch_name from mel_investment a inner join master_customer b on a.cust_id=b.id inner join master_payment_method c on a.payment_method=c.id inner join master_product d on a.product_id=d.id inner join master_branch e on a.branch_id=e.id where 1 $mystr $mystr2 and payment_method='1' and status='1' and a.branch_id='".$_SESSION['BRID']."' order by a.id desc");			
				
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
		$query->orderBy('mel_investment.id','desc');
		$records = $query->paginate(20);
		$sum_investment_amount = $query->sum('investment_amount');
		$sum_reg_fee = $query->sum('reg_fee');
        return view('/panel/cleared_collection_report/paginate', compact('records','PREV','sum_investment_amount','sum_reg_fee'));
    }
	
	function getProducts(){
		return self::$Products->where('status',1)->get();
	}
	
	function getPaymentMethod(){
		return self::$PaymentMethod->where('is_active',1)->get();
	}

}
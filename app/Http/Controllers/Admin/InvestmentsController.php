<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\CustomerAccounts;
use App\Models\Customers;
use App\Models\MetInvoice;
use App\Models\InvestDenomination;
use App\Models\MelInvestment;
use App\Models\Products;
use App\Models\PaymentMethod;
use App\Models\Allys;
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
use Cookie;
use Illuminate\Validation\Rule;


class InvestmentsController extends Controller {	
    private static $InvestDenomination;
	private static $CustomerAccounts;
	private static $MelInvestment;
	private static $MetInvoice;
	private static $Customers;
	private static $Products;
	private static $PaymentMethod;
	private static $Allys;
    private static $TokenHelper;	

    public function __construct(){
        self::$InvestDenomination = new InvestDenomination();
		self::$CustomerAccounts = new CustomerAccounts();
		self::$MelInvestment = new MelInvestment();
		self::$Customers = new Customers();
		self::$MetInvoice = new MetInvoice();
		self::$Products = new Products();
		self::$PaymentMethod = new PaymentMethod();
		self::$Allys = new Allys();
        self::$TokenHelper = new TokenHelper();
    }	

    #add new Service Type
    public function addPage(Request $request,$row_id = NULL){
        if(!$request->session()->has('admin_id')){
            return redirect('/panel/');
        }
		$custid_dec = base64_decode($row_id);
		$findinvoice_no = DB::select("Select a.*,b.state_name,c.cust_type from master_customer a inner join master_states b on a.state=b.id inner join master_cust_type c on a.customer_type=c.id where a.id='" . $custid_dec . "'");
		
		if(count($findinvoice_no) == 0){
			return redirect('/panel/customers');
		}		
		
        if($request->input()){
						
            $validator = Validator::make($request->all(), [
				'account_id' => 'required',
				'ally_id' => 'required',				
				'myproduct' => 'required',
				'mypay_method' => 'required'
			], [
				'account_id.required' => 'Please select account.',
				'ally_id.required' => 'Please select ally.',
				'myproduct.required' => 'Please select product code.',
				'mypay_method.required' => 'Please select payment method.'
			]);
            if($validator->fails()){
                $errors = $validator->errors();
                if($errors->first('account_id')){
                    return json_encode(array('heading' => 'Error', 'msg' => $errors->first('account_id')));
                    die;
                }
				if($errors->first('ally_id')){
                    return json_encode(array('heading' => 'Error', 'msg' => $errors->first('ally_id')));
                    die;
                }
				if($errors->first('myproduct')){
                    return json_encode(array('heading' => 'Error', 'msg' => $errors->first('myproduct')));
                    die;
                }
				if($errors->first('mypay_method')){
                    return json_encode(array('heading' => 'Error', 'msg' => $errors->first('mypay_method')));
                    die;
                }
            }else{
				if($request->mypay_method == 2 || $request->mypay_method == 5 || $request->mypay_method == 6 || $request->mypay_method == 7 || $request->mypay_method == 8){					
					if($request->payment_date == ''){
						return json_encode(array('heading' => 'Error', 'msg' => 'Please select date'));
						die;
					}					
				}
				if($request->mypay_method == 3 || $request->mypay_method == 4){
										
					if($request->cheque_no == ''){
						return json_encode(array('heading' => 'Error', 'msg' => 'Please enter cheque no'));
                    	die;
					}
					if($request->cheque_date == ''){
						return json_encode(array('heading' => 'Error', 'msg' => 'Please enter cheque date'));
                    	die;
					}
					if($request->cheque_bank == ''){
						return json_encode(array('heading' => 'Error', 'msg' => 'Please enter bank name'));
                    	die;
					}
					if($request->cheque_branch == ''){
						return json_encode(array('heading' => 'Error', 'msg' => 'Please enter branch name'));
                    	die;
					}
				}
				
				$custid_dec = base64_decode($request->custid);
				$cust_id = $custid_dec;
				$account_id = $request->input('account_id');
				$myproduct = $request->input('myproduct');				
				$ally_id = $request->input('ally_id');
				$ally_code = $request->input('ally_code');
				$myunit = $request->input('myunit');
				$payment_method = $request->input('mypay_method');
				$payment_date = $request->input('payment_date');
				$cheque_no = $request->input('cheque_no');
				$cheque_date = $request->input('cheque_date');
				$cheque_bank = $request->input('cheque_bank');
				$mypay_method = $request->input('mypay_method');								
				$cheque_branch = $request->input('cheque_branch');
				
				$denom_qnt_2000_amnt = '';
				$denom_qnt_2000 = $request->input('denom_qnt_2000');
				if($request->input('denom_qnt_2000')){
					if($denom_qnt_2000 > 0){
						$denom_qnt_2000_amnt = 2000 * $denom_qnt_2000;
					}	
				}
				$denom_qnt_500_amnt = '';
				$denom_qnt_500 = $request->input('denom_qnt_500');
				if($request->input('denom_qnt_500')){					
					if($denom_qnt_500 > 0){
						$denom_qnt_500_amnt = 500 * $denom_qnt_500;
					}	
				}
				$denom_qnt_200_amnt = '';
				$denom_qnt_200 = $request->input('denom_qnt_200');
				if($request->input('denom_qnt_200')){					
					if($denom_qnt_200 > 0){
						$denom_qnt_200_amnt = 200 * $denom_qnt_200;
					}	
				}
				$denom_qnt_100_amnt = '';
				$denom_qnt_100 = $request->input('denom_qnt_100');
				if($request->input('denom_qnt_100')){					
					if($denom_qnt_100 > 0){
						$denom_qnt_100_amnt = 100 * $denom_qnt_100;
					}	
				}
				$denom_qnt_50_amnt = '';
				$denom_qnt_50 = $request->input('denom_qnt_50');
				if($request->input('denom_qnt_50')){					
					if($denom_qnt_50 > 0){
						$denom_qnt_50_amnt = 50 * $denom_qnt_50;
					}	
				}
				$denom_qnt_20_amnt = '';
				$denom_qnt_20 = $request->input('denom_qnt_20');
				if($request->input('denom_qnt_20')){					
					if($denom_qnt_20 > 0){
						$denom_qnt_20_amnt = 20 * $denom_qnt_20;
					}	
				}
				$denom_qnt_10_amnt = '';
				$denom_qnt_10 = $request->input('denom_qnt_10');
				if($request->input('denom_qnt_10')){					
					if($denom_qnt_10 > 0){
						$denom_qnt_10_amnt = 10 * $denom_qnt_10;
					}	
				}
				$denom_qnt_5_amnt = '';
				$denom_qnt_5 = $request->input('denom_qnt_5');
				if($request->input('denom_qnt_5')){					
					if($denom_qnt_5 > 0){
						$denom_qnt_5_amnt = 5 * $denom_qnt_5;
					}	
				}
				$denom_qnt_2_amnt = '';
				$denom_qnt_2 = $request->input('denom_qnt_2');
				if($request->input('denom_qnt_2')){					
					if($denom_qnt_2 > 0){
						$denom_qnt_2_amnt = 2 * $denom_qnt_2;
					}	
				}
				$denom_qnt_1_amnt = '';
				$denom_qnt_1 = $request->input('denom_qnt_1');
				if($request->input('denom_qnt_1')){
					if($denom_qnt_1 > 0){
						$denom_qnt_1_amnt = 1 * $denom_qnt_1;
					}	
				}
				
				$findaccounttype = DB::select("Select * from customer_account where id='" . $account_id . "'");
				$findaccounttype_res = $findaccounttype[0];
				
				$findproddetails = DB::select("Select * from master_product where id='" . $myproduct . "'");
				$findproddetails_res = $findproddetails[0];
				
				$matval = ($findproddetails_res->unit_price * $myunit) + (($findproddetails_res->unit_price * $myunit) * ($findproddetails_res->interest_rate / 100));
						
				if(date('m') <= 3){
					$financial_year = (date('Y') - 1) . '-' . date('y');
				}else{
					$financial_year = date('Y') . '-' . (date('y') + 1);
				}

				$findcustomer_branch = DB::select("Select a.*,b.state_name from master_customer a inner join master_states b on a.state=b.id where a.id='" . $custid_dec . "'");
				$findcustomer_branch_res = $findcustomer_branch[0];	
				
				$myinsertdate = date('Y'). '-' .date('m'). '-' .date('d');
				$myinsertdate_with_time = date('Y'). '-' .date('m'). '-' .date('d'). '-' .date('H'). '-' .date('i'). '-' .date('s');
				
				$findcusrid = DB::select("Select a.customer_code,a.customer_id,a.udaid,b.branch_code from master_customer a inner join master_branch b on a.branch_id=b.id where a.id='" . $custid_dec . "'");
				$findcusrid_res = $findcusrid[0];				
				$mycustomer_code = '';		
				
				if($findcusrid_res->customer_id != ""){
					$mycustomer_id = $findcusrid_res->customer_id;
				}else{		
					$mycustomer_id = 'MT' . $findcusrid_res->branch_code . date('dmy');					
					DB::table('master_customer')->where('id', $custid_dec)->update(array('customer_id' => $mycustomer_id));					
				}
		
				if($findcusrid_res->customer_code != ""){
					$mycustomer_code = $findcusrid_res->customer_code;
				}else{		
					$mycustomer_code = $findcusrid_res->branch_code . $findcusrid_res->udaid;
					DB::table('master_customer')->where('id', $custid_dec)->update(array('customer_code' => $mycustomer_code));
				}
				
				if($mypay_method == 1){
					
					$setData2['receipt_id'] = 0;
					$setData2['cust_id'] = $custid_dec;
					$setData2['ally_id'] = $ally_id;
					$setData2['ally_code'] = $ally_code;
					$setData2['product_id'] = $myproduct;					
					$setData2['investment_amount'] = $findproddetails_res->unit_price * $request->input('myunit');
					$setData2['reg_fee'] = $findproddetails_res->per_unit_register_fees * $request->input('myunit');
					$setData2['tot_paid'] = $findproddetails_res->per_unit_register_fees+($findproddetails_res->unit_price * $request->input('myunit'));
					$setData2['paid_on'] = $request->input('payment_date');
					$setData2['maturity_val'] = $matval;
					$setData2['credit_bank_account'] = $account_id;
					$setData2['payment_method'] = $mypay_method;
					$setData2['branch_id'] = $findcustomer_branch_res->branch_id;
					$setData2['created_by'] = $request->session()->get('CRXUSERID');
					$setData2['created_on'] = $myinsertdate_with_time;
					$setData2['bank_type'] = $findaccounttype_res->bank_type;
					$setData2['tot_unit'] = $myunit;
					$setData2['roi'] = $findproddetails_res->interest_rate;
					$setData2['brs_updated_by'] = $request->session()->get('CRXUSERID');
					
					$record = self::$MelInvestment->CreateRecord($setData2);
				}else{

					$setData['receipt_id'] = 0;
					$setData['cust_id'] = $custid_dec;
					$setData['ally_id'] = $ally_id;
					$setData['ally_code'] = $ally_code;
					$setData['product_id'] = $myproduct;					
					$setData['investment_amount'] = $findproddetails_res->unit_price * $request->input('myunit');
					$setData['reg_fee'] = $findproddetails_res->per_unit_register_fees * $request->input('myunit');
					$setData['tot_paid'] = $findproddetails_res->per_unit_register_fees+($findproddetails_res->unit_price * $request->input('myunit'));
					$setData['paid_on'] = $request->input('payment_date');
					$setData['maturity_val'] = $matval;
					$setData['credit_bank_account'] = $account_id;
					$setData['payment_method'] = $mypay_method;
					$setData['branch_id'] = $findcustomer_branch_res->branch_id;
					$setData['created_by'] = $request->session()->has('CRXUSERID');
					$setData['created_on'] = $myinsertdate_with_time;
					$setData['bank_type'] = $findaccounttype_res->bank_type;
					$setData['tot_unit'] = $myunit;
					$setData['cheque_no'] = $cheque_no;
					$setData['cheque_date'] = $cheque_date;
					$setData['cheque_bank'] = $cheque_bank;
					$setData['cheque_branch'] = $cheque_branch;
					$setData['roi'] = $findproddetails_res->interest_rate;
					$setData['brs_updated_by'] = $request->session()->get('CRXUSERID');
					$record = self::$MelInvestment->CreateRecord($setData);					
				}
				
				$payment_serial_no = $record->id;
				$payment_ref_no = 'A-' . $record->id;
				$receipt_no = "BR/" . $financial_year . '/' . $findcustomer_branch_res->branch_id . '/' . $record->id;
				
				DB::table('mel_investment')->where('id', $record->id)->update(array('receipt_id' => $receipt_no, 'serial_no' => $payment_serial_no, 'ref_no' => $payment_ref_no));

				$setMetInvData['brsid'] = $record->id;
				$setMetInvData['invoiceno'] = 'HTX';
				$record = self::$MetInvoice->CreateRecord($setMetInvData);
				
				$myinvid = 'INV/' . $financial_year . '/' . $findcustomer_branch_res->branch_id . '/' . $record->id;				
				DB::table('met_invoice')->where('id', $record->id)->update(array('invoiceno' => $myinvid));

				if($mypay_method == 1 && $request->input('cashright') == 1){
					$setInvestData['investment_id'] = $record->id;
					$setInvestData['denom_500'] = $denom_qnt_500;
					$setInvestData['denom_200'] = $denom_qnt_200;
					$setInvestData['denom_100'] = $denom_qnt_100;
					$setInvestData['denom_50'] = $denom_qnt_50;
					$setInvestData['denom_20'] = $denom_qnt_20;
					$setInvestData['denom_10'] = $denom_qnt_10;
					$setInvestData['denom_5'] = $denom_qnt_5;
					$setInvestData['denom_2'] = $denom_qnt_2;
					$setInvestData['denom_1'] = $denom_qnt_1;
					$record = self::$InvestDenomination->CreateRecord($setInvestData);			
				}				
                echo json_encode(array('heading' => 'Success', 'msg' => 'Record added successfully', 'row_id' => base64_encode($record->id)));
                die;
            }
        }
		$allys = $this->getAllys();
		$products = $this->getProducts();
		$payment_methods = $this->getPaymentMethod();
		$cust_accounts = $this->getCustomerAccounts($custid_dec);
        return view('/panel/investment/add-page',compact('findinvoice_no','cust_accounts','allys','products','payment_methods'));
    }

	public function getPage(Request $request){
		if(!$request->session()->has('admin_id')){
            return redirect('/panel/');
        }
		if($request->ajax()){
			$prodid = $request->prodid;
			$product = self::$Products->where('status', 1)->where('id',$prodid)->first();

			$response['unit_price']=stripslashes($product->unit_price);
			$response['max_unit']=stripslashes($product->max_unit);
			$response['min_unit']=stripslashes($product->min_unit);
			$response['fee_per_unit']=stripslashes($product->per_unit_register_fees);
			$response['return_in_day']=stripslashes($product->return_in_days);
			$response['roi']=stripslashes($product->interest_rate);
			$response['branch_addr']=stripslashes($product->branch_addr);

			echo json_encode(array('product'=>$response));
		}
		exit;
	} 
	
	function getCustomerAccounts($cust_id){
		return self::$CustomerAccounts->where('status', 1)->where('cust_id',$cust_id)->get();	
	}
	
	function getAllys(){
		return self::$Allys->where('status', 1)->get();	
	}
	
	function getProducts(){
		return self::$Products->where('status', 1)->get();	
	}
	
	function getPaymentMethod(){
		return self::$PaymentMethod->where('is_active', 1)->where('id','!=',9)->get();	
	}


}
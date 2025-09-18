<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Customers;
use App\Models\CustomerAccounts;
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


class CustomerAccountsController extends Controller {
	
    private static $CustomerAccounts;
	private static $Customers;
    private static $TokenHelper;	

    public function __construct(){
        self::$CustomerAccounts = new CustomerAccounts();
		self::$Customers = new Customers();
        self::$TokenHelper = new TokenHelper();
    }

    #admin dashboard page
    public function getList(Request $request,$row_id = NULL){
        if(!$request->session()->has('admin_id')){
            return redirect('/panel/');
        }
		$rowID = base64_decode($row_id);
		$record = self::$CustomerAccounts->where('id',$rowID)->first();
		if(isset($record->id)){
			return view('/panel/customer_account/index',compact('row_id'));	
		}else{
			return redirect('/panel/customers');	
		}
    }

    public function listPaginate(Request $request){
        if(!$request->session()->has('admin_id')){
            return redirect('/panel/');
        }
        $query = self::$CustomerAccounts->where('status', '!=', 3);
		$row_id = $request->input('row_id');
		$query->where('cust_id', base64_decode($row_id));
		if($request->input('search_keywords') && $request->input('search_keywords') != ""){
		
			$SearchKeyword = $request->input('search_keywords');
            $query->where(function($query) use ($SearchKeyword)  {
                if(!empty($SearchKeyword)) {
                    $query->where('customer_account.bank_name', 'like', '%'.$SearchKeyword.'%') 
                    ->orWhere('customer_account.back_ac_no', 'like', '%'.$SearchKeyword.'%')
                    ->orWhere('customer_account.accnt_holder_name', 'like', '%'.$SearchKeyword.'%');
                }
             });

        }

        $records = $query->orderBy('id', 'DESC')->paginate(20);
        return view('/panel/customer_account/paginate', compact('records'));
    }
	

    #add new Service Type
    public function addPage(Request $request,$cust_id = NULL){
        if(!$request->session()->has('admin_id')){
            return redirect('/panel/');
        }
        if($request->input()){

			$validator = Validator::make($request->all(), [
				'bank_name' => 'required', 
				'bank_type' => 'required', 
				'back_ac_no' => 'required', 
				'accnt_holder_name' => 'required', 
				'accnt_ifsc_code' => 'required', 
			], [
				'bank_name.required' => 'Please enter bank name.', 
				'bank_type.required' => 'Please enter bank type.', 
				'back_ac_no.required' => 'Please enter account number.', 
				'accnt_holder_name.required' => 'Please enter account holder name.', 
				'accnt_ifsc_code.required' => 'Please enter ifsc.',
			]);

            if($validator->fails()){
                $errors = $validator->errors();
                if($errors->first('bank_name')){
                    return json_encode(array('heading' => 'Error', 'msg' => $errors->first('bank_name')));
                    die;
                }
				if($errors->first('bank_type')){
                    return json_encode(array('heading' => 'Error', 'msg' => $errors->first('bank_type')));
                    die;
                }
				if($errors->first('back_ac_no')){
                    return json_encode(array('heading' => 'Error', 'msg' => $errors->first('back_ac_no')));
                    die;
                }
				if($errors->first('accnt_holder_name')){
                    return json_encode(array('heading' => 'Error', 'msg' => $errors->first('accnt_holder_name')));
                    die;
                }
				if($errors->first('accnt_ifsc_code')){
                    return json_encode(array('heading' => 'Error', 'msg' => $errors->first('accnt_ifsc_code')));
                    die;
                }
            }else{
                //$classExist = self::$CustomerAccounts->where('back_ac_no', $request->name)->where('status', '!=',3)->count();
               	//if($classExist > 0){
                    //return json_encode(array('heading' => 'Error', 'msg' => 'Subject already exist'));
                    //die;
				//}
                $setData['cust_id'] = $request->cust_id;
				$setData['bank_name'] = $request->input('bank_name');
				$setData['bank_type'] = $request->input('bank_type');
				$setData['back_ac_no'] = $request->input('back_ac_no');
				$setData['accnt_holder_name'] = $request->input('accnt_holder_name');
				$setData['accnt_ifsc_code'] = $request->input('accnt_ifsc_code');
				$setData['branch_name'] = $request->input('branch_name');
                $setData['branch_addr'] = $request->input('branch_addr');
                $record = self::$CustomerAccounts->CreateRecord($setData);
                echo json_encode(array('heading' => 'Success', 'msg' => 'Record added successfully', 'row_id' => base64_encode($record->id)));
                die;
            }
        }
		$custID = base64_decode($cust_id);
		$record = self::$Customers->where('id',$custID)->first();		
		if(!$record){
			return redirect('/panel/');
		}
        return view('/panel/customer_account/add-page',compact('record'));
    }	

    #editPage
    public function editPage(Request $request, $row_id){
        if(!$request->session()->has('admin_id')){
            return redirect('/panel/');
        }

        $row_id = base64_decode($row_id);
        if($request->input()){
            $validator = Validator::make($request->all(), [
				'bank_name' => 'required', 
				'back_ac_no' => 'required', 
				'accnt_holder_name' => 'required', 
				'accnt_ifsc_code' => 'required', 
			], [
				'bank_name.required' => 'Please enter bank name.', 
				'back_ac_no.required' => 'Please enter account number.', 
				'accnt_holder_name.required' => 'Please enter account holder name.', 
				'accnt_ifsc_code.required' => 'Please enter ifsc.',
			]);

            if($validator->fails()){
                $errors = $validator->errors();
                if($errors->first('bank_name')){
                    return json_encode(array('heading' => 'Error', 'msg' => $errors->first('bank_name')));
                    die;
                }
				if($errors->first('back_ac_no')){
                    return json_encode(array('heading' => 'Error', 'msg' => $errors->first('back_ac_no')));
                    die;
                }
				if($errors->first('accnt_holder_name')){
                    return json_encode(array('heading' => 'Error', 'msg' => $errors->first('accnt_holder_name')));
                    die;
                }
				if($errors->first('accnt_ifsc_code')){
                    return json_encode(array('heading' => 'Error', 'msg' => $errors->first('accnt_ifsc_code')));
                    die;
                }
            }else{
                //$classExist = self::$CustomerAccounts->where('name', $request->name)->where('id', '!=', $request->row_id)->where('status', '!=',3)->count();
                //if($classExist > 0){
                   // return json_encode(array('heading' => 'Error', 'msg' => 'Subject already exist'));
                    //die;
                //}
                $setData['bank_name'] = $request->input('bank_name');
				$setData['back_ac_no'] = $request->input('back_ac_no');
				$setData['accnt_holder_name'] = $request->input('accnt_holder_name');
				$setData['accnt_ifsc_code'] = $request->input('accnt_ifsc_code');
				$setData['branch_name'] = $request->input('branch_name');
                $setData['branch_addr'] = $request->input('branch_addr');
                $record = self::$CustomerAccounts->where('id', $request->row_id)->update($setData);
                echo json_encode(array('heading' => 'Success', 'msg' => 'Record updated successfully'));
                die;
            }
        }
        if($row_id > 0){
            $record = self::$CustomerAccounts->where('id', $row_id)->first();
            return view('/panel/customer_account/edit-page', ['record' => $record]);
        }else{
            return redirect('/panel/customer_account');
        }
    }


}
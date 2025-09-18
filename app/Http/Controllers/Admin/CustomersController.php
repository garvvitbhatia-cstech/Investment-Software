<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\Customers;

use App\Models\CustomerTypes;

use App\Models\Gender;

use App\Models\Relations;

use App\Models\Occupations;

use App\Models\Branches;

use App\Models\States;

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



class CustomersController extends Controller {	

    private static $CustomerTypes;
	
	private static $Gender;
	
	private static $Relations;
	
	private static $Occupations;
	
	private static $Branches;
	
	private static $States;
	
	private static $Customers;

    private static $TokenHelper;

    public function __construct(){

        self::$CustomerTypes = new CustomerTypes();
		
		self::$Gender = new Gender();
		
		self::$Relations = new Relations();
		
		self::$Occupations = new Occupations();
		
		self::$Branches = new Branches();
		
		self::$States = new States();
		
		self::$Customers = new Customers();

        self::$TokenHelper = new TokenHelper();

    }

	

    #admin dashboard page

    public function getList(Request $request){

        if(!$request->session()->has('admin_id')){

            return redirect('/panel/');

        }

        return view('/panel/customers/index');

    }

	

    public function listPaginate(Request $request){

        if(!$request->session()->has('admin_id')){

            return redirect('/panel/');

        }
		
		$page = $request->page;
		
        $query = self::$Customers->where('status', '!=', 3);
		
		$branch_id = $request->session()->get('BRID');
		
		if($request->session()->get('admin_type') == 'BranchAdmin'){
			$query->where('master_customer.branch_id', $branch_id);
		}

         if($request->input('search_keywords') && $request->input('search_keywords') != ""){
		
			$SearchKeyword = $request->input('search_keywords');
            $query->where(function($query) use ($SearchKeyword)  {
                if(!empty($SearchKeyword)) {
                    $query->where('master_customer.customer_id', 'like', '%'.$SearchKeyword.'%') 
                    ->orWhere('master_customer.udaid', 'like', '%'.$SearchKeyword.'%')
                    ->orWhere('master_customer.customer_code', 'like', '%'.$SearchKeyword.'%')
					->orWhere('master_customer.cust_name', 'like', '%'.$SearchKeyword.'%')
					->orWhere('master_customer.contact_no', 'like', '%'.$SearchKeyword.'%')
					->orWhere('master_customer.pan_no', 'like', '%'.$SearchKeyword.'%');
                }
             });

        }
		
		if($request->input('ally_code') && $request->input('ally_code') != ""){
			
			$all_ally_code = self::$AllyTypes->where('status', '!=', 3)->where('ally_types.ally_type', 'like', '%'.$request->input('ally_code').'%')->get();
			
			$setDatas = '';
			$setDatas = array();
			foreach($all_ally_code as $key => $code){
				$setDatas[] = $code->id;
			}						
			$implode = implode(',',array_unique($setDatas));
			$query->whereRaw('FIND_IN_SET(ally_type, ?)', [$implode]);	
		}

        if($request->input('search_status') && $request->input('search_status') != ""){

            $query->where('status', $request->input('search_status'));

        }

        $records = $query->orderBy('id', 'DESC')->paginate(20);

        return view('/panel/customers/paginate', compact('records','page'));

    }

	

    #add new Service Type

    public function addPage(Request $request){

        if(!$request->session()->has('admin_id')){

            return redirect('/panel/');

        }

        if($request->input()){
			
             $validator = Validator::make($request->all(), [

				'udaid' => 'required',
				'emp_code' => 'required',
				'customer_type' => 'required',
				'cust_name' => 'required',
				'gender' => 'required',
				'gurdain_name' => 'required',
				'relationship' => 'required',
				'contact_no' => 'required|digits:10',
				'email' => 'nullable|email',
				'dob' => 'required',
				'branch_id' => 'required',
				'address' => 'required',
				'state' => 'required',
				'city' => 'required',
				'zip_code' => 'required|digits:6'
				
			], [

				'udaid.required' => 'Please enter UIDAI.',
				'emp_code.required' => 'Please enter employee code.',
				'customer_type.required' => 'Please enter customer type.',
				'cust_name.required' => 'Please enter customer name.',
				'gender.required' => 'Please select gender.',
				'gurdain_name.required' => 'Please enter guardain name.',
				'relationship.required' => 'Please enter relationship.',
				'contact_no.required' => 'Please enter contact no.',
				'contact_no.digits' => 'Please enter valid contact no.',
				'email.email' => 'Please enter valid email.',
				'dob.required' => 'Please enter date of birth.',
				'branch_id.required' => 'Please enter branch.',
				'address.required' => 'Please enter address.',
				'state.required' => 'Please enter state.',
				'city.required' => 'Please enter city.',
				'zip_code.required' => 'Please enter zipcode.',
				'zip_code.digits' => 'Please enter valid zipcode.',

			]);

            if($validator->fails()){

                $errors = $validator->errors();
                if($errors->first('udaid')){
                    return json_encode(array('heading' => 'Error', 'msg' => $errors->first('udaid')));
                    die;
                }
				if($errors->first('emp_code')){
                    return json_encode(array('heading' => 'Error', 'msg' => $errors->first('emp_code')));
                    die;
                }				
				if($errors->first('customer_type')){
                    return json_encode(array('heading' => 'Error', 'msg' => $errors->first('customer_type')));
                    die;
                }				
				if($errors->first('cust_name')){
                    return json_encode(array('heading' => 'Error', 'msg' => $errors->first('cust_name')));
                    die;
                }				
				if($errors->first('gender')){
                    return json_encode(array('heading' => 'Error', 'msg' => $errors->first('gender')));
                    die;
                }				
				if($errors->first('gurdain_name')){
                    return json_encode(array('heading' => 'Error', 'msg' => $errors->first('gurdain_name')));
                    die;
                }				
				if($errors->first('relationship')){
                    return json_encode(array('heading' => 'Error', 'msg' => $errors->first('relationship')));
                    die;
                }				
				if($errors->first('contact_no')){
                    return json_encode(array('heading' => 'Error', 'msg' => $errors->first('contact_no')));
                    die;
                }				
				if($errors->first('email')){
                    return json_encode(array('heading' => 'Error', 'msg' => $errors->first('email')));
                    die;
                }				
				if($errors->first('dob')){
                    return json_encode(array('heading' => 'Error', 'msg' => $errors->first('dob')));
                    die;
                }			
				if($errors->first('address')){
                    return json_encode(array('heading' => 'Error', 'msg' => $errors->first('address')));
                    die;
                }				
				if($errors->first('state')){
                    return json_encode(array('heading' => 'Error', 'msg' => $errors->first('state')));
                    die;
                }				
				if($errors->first('city')){
                    return json_encode(array('heading' => 'Error', 'msg' => $errors->first('city')));
                    die;
                }				
				if($errors->first('zip_code')){
                    return json_encode(array('heading' => 'Error', 'msg' => $errors->first('zip_code')));
                    die;
                }
            }else{

                $setData['udaid'] = $request->input('udaid');
                $setData['emp_code'] = $request->input('emp_code');
                $setData['customer_type'] = $request->input('customer_type');
                $setData['cust_name'] = $request->input('cust_name');
                $setData['pan_no'] = $request->input('pan_no');
                $setData['gender'] = $request->input('gender');
                $setData['gurdain_name'] = $request->input('gurdain_name');
                $setData['relationship'] = $request->input('relationship');
                $setData['occupation'] = $request->input('occupation');
                $setData['contact_no'] = $request->input('contact_no');
                $setData['alt_contact_no'] = $request->input('alt_contact_no');
                $setData['email'] = $request->input('email');
				$setData['dob'] = $request->input('dob');
				$setData['doa'] = $request->input('doa');
				$setData['branch_id'] = $request->input('branch_id');
				$setData['address'] = $request->input('address');
				$setData['state'] = $request->input('state');
				$setData['city'] = $request->input('city');
				$setData['zip_code'] = $request->input('zip_code');
                $record = self::$Customers->CreateRecord($setData);

                echo json_encode(array('heading' => 'Success', 'msg' => 'Record added successfully', 'row_id' => base64_encode($record->id)));

                die;

            }

        }
		$cust_types = $this->getCustomerTypes();
		
		$genders = $this->getGender();
		
		$relations = $this->getRelations();
		
		$occupations = $this->getOccupations();
		
		$branches = $this->getBranches();
		
		$states = $this->getStates();
        return view('/panel/customers/add-page', ['cust_types' => $cust_types, 'genders' => $genders, 'relations' => $relations, 'occupations' => $occupations, 'branches' => $branches, 'states' => $states]);

    }

	
    #editPage

    public function editPage(Request $request, $row_id){
		
        if(!$request->session()->has('admin_id')){

            return redirect('/panel/');

        }

        $row_id = base64_decode($row_id);

        if($request->input()){
			
            $validator = Validator::make($request->all(), [

				'udaid' => 'required',
				'emp_code' => 'required',
				'customer_type' => 'required',
				'cust_name' => 'required',
				'gender' => 'required',
				'gurdain_name' => 'required',
				'relationship' => 'required',
				'contact_no' => 'required|digits:10',
				'email' => 'nullable|email',
				'dob' => 'required',
				'branch_id' => 'required',
				'address' => 'required',
				'state' => 'required',
				'city' => 'required',
				'zip_code' => 'required|digits:6'
				
			], [

				'udaid.required' => 'Please enter UIDAI.',
				'emp_code.required' => 'Please enter employee code.',
				'customer_type.required' => 'Please enter customer type.',
				'cust_name.required' => 'Please enter customer name.',
				'gender.required' => 'Please select gender.',
				'gurdain_name.required' => 'Please enter guardain name.',
				'relationship.required' => 'Please enter relationship.',
				'contact_no.required' => 'Please enter contact no.',
				'contact_no.digits' => 'Please enter valid contact no.',
				'email.email' => 'Please enter valid email.',
				'dob.required' => 'Please enter date of birth.',
				'branch_id.required' => 'Please enter branch.',
				'address.required' => 'Please enter address.',
				'state.required' => 'Please enter state.',
				'city.required' => 'Please enter city.',
				'zip_code.required' => 'Please enter zipcode.',
				'zip_code.digits' => 'Please enter valid zipcode.',

			]);

            if($validator->fails()){

                $errors = $validator->errors();
                if($errors->first('udaid')){
                    return json_encode(array('heading' => 'Error', 'msg' => $errors->first('udaid')));
                    die;
                }
				if($errors->first('emp_code')){
                    return json_encode(array('heading' => 'Error', 'msg' => $errors->first('emp_code')));
                    die;
                }				
				if($errors->first('customer_type')){
                    return json_encode(array('heading' => 'Error', 'msg' => $errors->first('customer_type')));
                    die;
                }				
				if($errors->first('cust_name')){
                    return json_encode(array('heading' => 'Error', 'msg' => $errors->first('cust_name')));
                    die;
                }				
				if($errors->first('gender')){
                    return json_encode(array('heading' => 'Error', 'msg' => $errors->first('gender')));
                    die;
                }				
				if($errors->first('gurdain_name')){
                    return json_encode(array('heading' => 'Error', 'msg' => $errors->first('gurdain_name')));
                    die;
                }				
				if($errors->first('relationship')){
                    return json_encode(array('heading' => 'Error', 'msg' => $errors->first('relationship')));
                    die;
                }				
				if($errors->first('contact_no')){
                    return json_encode(array('heading' => 'Error', 'msg' => $errors->first('contact_no')));
                    die;
                }				
				if($errors->first('email')){
                    return json_encode(array('heading' => 'Error', 'msg' => $errors->first('email')));
                    die;
                }				
				if($errors->first('dob')){
                    return json_encode(array('heading' => 'Error', 'msg' => $errors->first('dob')));
                    die;
                }			
				if($errors->first('address')){
                    return json_encode(array('heading' => 'Error', 'msg' => $errors->first('address')));
                    die;
                }				
				if($errors->first('state')){
                    return json_encode(array('heading' => 'Error', 'msg' => $errors->first('state')));
                    die;
                }				
				if($errors->first('city')){
                    return json_encode(array('heading' => 'Error', 'msg' => $errors->first('city')));
                    die;
                }				
				if($errors->first('zip_code')){
                    return json_encode(array('heading' => 'Error', 'msg' => $errors->first('zip_code')));
                    die;
                }				
           }else{
               	$setData['udaid'] = $request->input('udaid');
                $setData['emp_code'] = $request->input('emp_code');
                $setData['customer_type'] = $request->input('customer_type');
                $setData['cust_name'] = $request->input('cust_name');
                $setData['pan_no'] = $request->input('pan_no');
                $setData['gender'] = $request->input('gender');
                $setData['gurdain_name'] = $request->input('gurdain_name');
                $setData['relationship'] = $request->input('relationship');
                $setData['occupation'] = $request->input('occupation');
                $setData['contact_no'] = $request->input('contact_no');
                $setData['alt_contact_no'] = $request->input('alt_contact_no');
                $setData['email'] = $request->input('email');
				$setData['dob'] = $request->input('dob');
				$setData['doa'] = $request->input('doa');
				$setData['branch_id'] = $request->input('branch_id');
				$setData['address'] = $request->input('address');
				$setData['state'] = $request->input('state');
				$setData['city'] = $request->input('city');
				$setData['zip_code'] = $request->input('zip_code');
                $record = self::$Customers->where('id', $request->row_id)->update($setData);
                echo json_encode(array('heading' => 'Success', 'msg' => 'Record updated successfully'));
                die;
            }
        }

        if($row_id > 0){

            $cust_types = $this->getCustomerTypes();
			
			$genders = $this->getGender();
			
			$relations = $this->getRelations();
			
			$occupations = $this->getOccupations();
			
			$branches = $this->getBranches();
			
			$states = $this->getStates();

            $record = self::$Customers->where('id', $row_id)->first();

            return view('/panel/customers/edit-page', ['record' => $record, 'cust_types' => $cust_types, 'genders' => $genders, 'relations' => $relations, 'occupations' => $occupations, 'branches' => $branches, 'states' => $states]);

        }else{

            return redirect('/panel/customers');

        }

    }

	
    public function getCustomerTypes(){

        return self::$CustomerTypes->where('status', 1)->pluck('cust_type', 'id');

    }


    public function getGender(){

        return self::$Gender->where('status', 1)->pluck('gender_name', 'id');

    }


    public function getRelations(){

        return self::$Relations->where('status', 1)->pluck('relation_name', 'id');

    }


    public function getOccupations(){

        return self::$Occupations->where('status', 1)->pluck('occ_name', 'id');

    }


    public function getBranches(){

        return self::$Branches->where('status', 1)->pluck('branch_name', 'id');

    }


    public function getStates(){

        return self::$States->where('status', 1)->pluck('state_name', 'id');

    }


}
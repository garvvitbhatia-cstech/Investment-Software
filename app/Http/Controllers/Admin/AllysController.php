<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\Allys;

use App\Models\AllyTypes;

use App\Models\Branches;

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


class AllysController extends Controller{


    private static $Ally;
	
	private static $AllyTypes;
	
	private static $Branches;

    private static $TokenHelper;

	
    public function __construct(){

        self::$Ally = new Allys();
		
		self::$AllyTypes = new AllyTypes();
		
		self::$Branches = new Branches();

		self::$TokenHelper = new TokenHelper();

    }

    #admin dashboard page

    public function getList(Request $request){

        if(!$request->session()->has('admin_id')){return redirect('/panel/');}
		
		$ally_types = $this->getAllyTypes();
			
		$branches = $this->getBranches();
		
        return view('/panel/allys/index',compact('ally_types','branches'));

    }

    public function listPaginate(Request $request){

        if(!$request->session()->has('admin_id')){return redirect('/panel/');}

        $query = self::$Ally->where('status', '!=', 3);		
				
		if($request->input('search_payment_status') != ""){
		
			$query->where('allys.payment_status', $request->input('search_payment_status'));
			
		}
		
		if($request->input('search_status') && $request->input('search_status') != ""){
		
			$query->where('allys.status', 'like', '%'.$request->input('search_status').'%');
			
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

        if($request->input('search_keywords') && $request->input('search_keywords') != ""){
		
			$SearchKeyword = $request->input('search_keywords');
            $query->where(function($query) use ($SearchKeyword)  {
                if(!empty($SearchKeyword)) {
                    $query->where('allys.ally_person_name', 'like', '%'.$SearchKeyword.'%');
                }
             });

        }

        $records = $query->orderBy('id', 'DESC')->paginate(20);

        return view('/panel/allys/paginate', compact('records'));

    }


    #add new Service Type

    public function addPage(Request $request){

        if(!$request->session()->has('admin_id')){return redirect('/panel/');}

        if($request->input()){

            $validator = Validator::make($request->all(), [

				'ally_person_name' => 'required', 

				'branch' => 'required', 

				'ally_type' => 'required', 

				'registration_fees' => 'required', 

				'payment_status' => 'required', 

			], [

				'ally_person_name.required' => 'Please enter ally person name.',

				'branch.required' => 'Please select branch.',

				'ally_type.required' => 'Please select ally type.',

				'registration_fees.required' => 'Please enter registration fees.',

				'payment_status.required' => 'Please select payment status.',

			]);

            if($validator->fails()){

                $errors = $validator->errors();

                if($errors->first('ally_person_name')){

                    return json_encode(array('heading' => 'Error', 'msg' => $errors->first('ally_person_name')));die;

                }

				if($errors->first('branch')){

                    return json_encode(array('heading' => 'Error', 'msg' => $errors->first('branch')));die;

                }

				if($errors->first('ally_type')){

                    return json_encode(array('heading' => 'Error', 'msg' => $errors->first('ally_type')));die;

                }

				if($errors->first('registration_fees')){

                    return json_encode(array('heading' => 'Error', 'msg' => $errors->first('registration_fees')));die;

                }

				if($errors->first('payment_status')){

                    return json_encode(array('heading' => 'Error', 'msg' => $errors->first('payment_status')));die;

                }

            } else {
					$branch_code = $request->input('branch_code');
					
					$ally_code_init = $request->input('ally_code_init');
										 
					$ally_person_name_arr = explode(" ", $request->input('ally_person_name'));

					$ally_name_fs = substr($ally_person_name_arr[0], 0, 1);
					$ally_name_ls = substr(end($ally_person_name_arr), 0, 1);
				
					$ally_code = $ally_code_init . strtoupper($ally_name_fs) . strtoupper($ally_name_ls) . $branch_code;
					
					$count = self::$Ally->count()+1;
								
					$ally_code = $ally_code . '-' . $count;
					
					$cuserid = $request->session()->get('CRXUSERID');

					$setData['ally_person_name'] = $request->input('ally_person_name');

					$setData['branch'] = $request->input('branch');

					$setData['ally_type'] = $request->input('ally_type');
					
					$setData['ally_code'] = $request->input('ally_code');

					$setData['registration_fees'] = $request->input('registration_fees');

					$setData['payment_status'] = $request->input('payment_status');

					$record = self::$Ally->CreateRecord($setData);					

					echo json_encode(array('heading' => 'Success', 'msg' => 'Record added successfully', 'row_id' => base64_encode($record->id)));die;

            }

        }
		
		$ally_types = $this->getAllyTypes();
			
		$branches = $this->getBranches();

        return view('/panel/allys/add-page',compact('ally_types','branches'));

    }

	

	#edit Service Type

    public function editPage(Request $request, $row_id){

        $RowID = base64_decode($row_id);

        if(!$request->session()->has('admin_id')){

            return redirect('/panel/');

        }

        $record = self::$Ally->where(array('id' => $RowID))->first();

        if($request->input()){

            $validator = Validator::make($request->all(), [

				'ally_person_name' => 'required', 

				'branch' => 'required', 

				'ally_type' => 'required', 

				'registration_fees' => 'required', 

				'payment_status' => 'required', 

			], [

				'ally_person_name.required' => 'Please enter ally person name.',

				'branch.required' => 'Please select branch.',

				'ally_type.required' => 'Please select ally type.',

				'registration_fees.required' => 'Please enter registration fees.',

				'payment_status.required' => 'Please select payment status.',

			]);

            if($validator->fails()){

                $errors = $validator->errors();

                if($errors->first('ally_person_name')){

                    return json_encode(array('heading' => 'Error', 'msg' => $errors->first('ally_person_name')));die;

                }

				if($errors->first('branch')){

                    return json_encode(array('heading' => 'Error', 'msg' => $errors->first('branch')));die;

                }

				if($errors->first('ally_type')){

                    return json_encode(array('heading' => 'Error', 'msg' => $errors->first('ally_type')));die;

                }

				if($errors->first('registration_fees')){

                    return json_encode(array('heading' => 'Error', 'msg' => $errors->first('registration_fees')));die;

                }

				if($errors->first('payment_status')){

                    return json_encode(array('heading' => 'Error', 'msg' => $errors->first('payment_status')));die;

                }

            }else{
                    $setData['id'] = $request->row_id; 

                    //$setData['ally_person_name'] = $request->input('ally_person_name');

					//$setData['branch'] = $request->input('branch');

					//$setData['ally_type'] = $request->input('ally_type');

					//$setData['registration_fees'] = $request->input('registration_fees');

					$setData['payment_status'] = $request->input('payment_status');

                    self::$Ally->UpdateRecord($setData);

                    echo json_encode(array('heading' => 'Success', 'msg' => 'Record updated successfully'));

                    die;

            }

        }

        if(isset($record->id)){
			
			$ally_types = $this->getAllyTypes();
			
			$branches = $this->getBranches();

            return view('/panel/allys/edit-page', compact('record', 'row_id','ally_types','branches'));

        }else{

            return redirect('/panel/allys');

        }

    }
	
	public function getBranches(){			

		return self::$Branches->where('status', 1)->latest()->get();

	}
	
	public function getAllyTypes(){			

		return self::$AllyTypes->where('status', 1)->latest()->get();

	}


}
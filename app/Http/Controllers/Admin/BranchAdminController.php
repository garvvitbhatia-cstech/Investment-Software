<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\Branches;

use App\Models\AdminUser;

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



class BranchAdminController extends Controller {

    private static $AdminUser;

	private static $Branches;

    private static $TokenHelper;

	

    public function __construct(){

        self::$AdminUser = new AdminUser();

		self::$Branches = new Branches();

        self::$TokenHelper = new TokenHelper();

    }

	

    #admin dashboard page

    public function getList(Request $request){

        if(!$request->session()->has('admin_email')){

            return redirect('/panel/');

        }

		$branches = $this->getBranches();

        return view('/panel/branch_admin/index',compact('branches'));

    }



    public function listPaginate(Request $request){

        if(!$request->session()->has('admin_email')){

            return redirect('/panel/');

        }

        $query = self::$AdminUser->where('status', '!=', 3)->where('type', 'BranchAdmin');
		
		if($request->input('search_status') && $request->input('search_status') != ""){
		
			$query->where('status', 'like', '%'.$request->input('search_status').'%');
			
		}

        if($request->input('search_keywords') && $request->input('search_keywords') != ""){
		
			$SearchKeyword = $request->input('search_keywords');
            $query->where(function($query) use ($SearchKeyword)  {
                if(!empty($SearchKeyword)) {
                    $query->where('users.name', 'like', '%'.$SearchKeyword.'%') 
                    ->orWhere('users.mobile', 'like', '%'.$SearchKeyword.'%');
                }
             });

        }

        $records = $query->orderBy('id', 'DESC')->paginate(20);

        return view('/panel/branch_admin/paginate', compact('records'));

    }



    public function addPage(Request $request){

        if(!$request->session()->has('admin_email')){

            return redirect('/panel/');

        }

        if($request->input()){

            $validator = Validator::make($request->all(), [				 				

				'mobile' => 'required|digits:10',

				'name' => 'required',

				'branch_id' => 'required',

				'password' => 'required|min:5|confirmed'

			 ], [			 	

				'mobile.required' => 'Please enter phone number.', 

				'mobile.digits' => 'Please enter valid phone no.',

				'name.required' => 'Please enter name.', 

				'branch_id.required' => 'Please select branch name.', 

				'password.required' => 'Please enter password.',

				'password.min' => 'The password must be at least 5 characters.',

				'password_confirmation.required' => 'Please enter confirm password.',

				'password.confirmed' => 'The password and confirm password does not match.',

			]);

            if($validator->fails()){

                $errors = $validator->errors();

                if($errors->first('mobile')){

                    return json_encode(array('heading' => 'Error', 'msg' => $errors->first('mobile')));

                    die;

                }

				if($errors->first('name')){

                    return json_encode(array('heading' => 'Error', 'msg' => $errors->first('name')));

                    die;

                }

				if($errors->first('branch_id')){

                    return json_encode(array('heading' => 'Error', 'msg' => $errors->first('branch_id')));

                    die;

                }

                if($errors->first('password')){

                    return json_encode(array('heading' => 'Error', 'msg' => $errors->first('password')));

                    die;

                }  

				if($errors->first('password_confirmation')){

                    return json_encode(array('heading' => 'Error', 'msg' => $errors->first('password_confirmation')));

                    die;

                }                

            }else{

                $mobileExist = self::$AdminUser->where('mobile', $request->mobile)->where('status', '!=',3)->count();

                if($mobileExist > 0){

                    return json_encode(array('heading' => 'Error', 'msg' => 'Mobile no already exist'));

                    die;

                }

                $setData['type'] = 'BranchAdmin';
								
				$setData['user_prev'] = 2;

                $setData['mobile'] = $request->input('mobile');

                $setData['name'] = $request->input('name');

				$setData['branch_id'] = $request->input('branch_id');

                if($request->input('password') != ''){

                    $setData['password'] = password_hash($request->input('password'), PASSWORD_BCRYPT);

                }

               	$record = self::$AdminUser->CreateRecord($setData);

                echo json_encode(array('heading' => 'Success', 'msg' => 'Record added successfully', 'row_id' => base64_encode($record->id)));

                die;

            }

        }

		$branches = $this->getBranches();

        return view('/panel/branch_admin/add-page',compact('branches'));

    }



    #editPage

    public function editPage(Request $request, $row_id){

        if(!$request->session()->has('admin_email')){

            return redirect('/panel/');

        }

        $row_id = base64_decode($row_id);

        if($request->input()){

            $validator = Validator::make($request->all(), [				 				

				'mobile' => 'required|digits:10',

				'name' => 'required',

				'branch_id' => 'required',

			 ], [			 	

				'mobile.required' => 'Please enter phone number.', 

				'mobile.digits' => 'Please enter valid phone no.',

				'name.required' => 'Please enter name.', 

				'branch_id.required' => 'Please select branch name.', 

			]);

            if($validator->fails()){

                $errors = $validator->errors();

                if($errors->first('mobile')){

                    return json_encode(array('heading' => 'Error', 'msg' => $errors->first('mobile')));

                    die;

                }

				if($errors->first('name')){

                    return json_encode(array('heading' => 'Error', 'msg' => $errors->first('name')));

                    die;

                }

				if($errors->first('branch_id')){

                    return json_encode(array('heading' => 'Error', 'msg' => $errors->first('branch_id')));

                    die;

                }
  
            }else{

                $mobileExist = self::$AdminUser->where('mobile', $request->mobile)->where('id', '!=', $request->row_id)->where('status', '!=',3)->count();

                if($mobileExist > 0){

                    return json_encode(array('heading' => 'Error', 'msg' => 'Mobile no already exist'));

                    die;

                }

				$setData['mobile'] = $request->input('mobile');

                $setData['name'] = $request->input('name');

				$setData['branch_id'] = $request->input('branch_id');

                if($request->input('password') != ''){

                    $setData['password'] = password_hash($request->input('password'), PASSWORD_BCRYPT);

                }

                $record = self::$AdminUser->where('id', $request->row_id)->update($setData);

                echo json_encode(array('heading' => 'Success', 'msg' => 'Record updated successfully'));

                die;

            }

        }

        if($row_id > 0){

			$branches = $this->getBranches();

            $record = self::$AdminUser->where('id', $row_id)->first();

            return view('/panel/branch_admin/edit-page', ['record' => $record, 'branches' => $branches]);

        }else{

            return redirect('/panel/branch-admin');

        }

    }

	

	public function getBranches(){			

		return self::$Branches->where('status', 1)->latest()->get();

	}



}
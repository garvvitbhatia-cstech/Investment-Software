<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\AdminUser;

use App\RouteHelper;

use App\Models\TokenHelper;

use App\Models\Responses;

use ReallySimpleJWT\Token;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Str;

use App\Models\Languages;

use Session;

use Validator;

use Mail;

use URL;

use Cookie;

use Illuminate\Validation\Rule;



class AccountsController extends Controller {

    private static $AdminUser;

    private static $TokenHelper;
	

    public function __construct(){

        self::$AdminUser = new AdminUser();

        self::$TokenHelper = new TokenHelper();

    }

	

    #admin dashboard page

    public function getList(Request $request){

        if(!$request->session()->has('admin_email')){

            return redirect('/panel/');

        }

        return view('/panel/accounts/index');

    }



    public function listPaginate(Request $request){

        if(!$request->session()->has('admin_email')){

            return redirect('/panel/');

        }

        $query = self::$AdminUser->where('status', '!=', 3)->where('type', 'Account');
		
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

        return view('/panel/accounts/paginate', compact('records'));

    }



    public function addPage(Request $request){

        if(!$request->session()->has('admin_email')){

            return redirect('/panel/');

        }

        if($request->input()){

            $validator = Validator::make($request->all(), [

				'name' => 'required', 				

				'mobile' => 'required|digits:10',

				'password' => 'required|min:5|confirmed'

			 ], [

			 	'name.required' => 'Please enter name.', 

				'mobile.required' => 'Please enter phone number.', 

				'mobile.digits' => 'Please enter valid phone no.',

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

                $setData['type'] = 'Account';
				
				$setData['user_prev'] = 1;

                $setData['mobile'] = $request->input('mobile');

                $setData['name'] = $request->input('name');

                if($request->input('password') != ''){

                    $setData['password'] = password_hash($request->input('password'), PASSWORD_BCRYPT);

                }

               	$record = self::$AdminUser->CreateRecord($setData);

                echo json_encode(array('heading' => 'Success', 'msg' => 'Record added successfully', 'row_id' => base64_encode($record->id)));

                die;

            }

        }

        return view('/panel/accounts/add-page');

    }



    #editPage

    public function editPage(Request $request, $row_id){

        if(!$request->session()->has('admin_email')){

            return redirect('/panel/');

        }

        $row_id = base64_decode($row_id);

        if($request->input()){

            $validator = Validator::make($request->all(), [

				'name' => 'required', 				

				'mobile' => 'required|digits:10',

			 ], [

			 	'name.required' => 'Please enter name.', 

				'mobile.required' => 'Please enter phone number.', 

				'mobile.digits' => 'Please enter valid phone no.',

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

            }else{

                $mobileExist = self::$AdminUser->where('mobile', $request->mobile)->where('id', '!=', $request->row_id)->where('status', '!=',3)->count();

                if($mobileExist > 0){

                    return json_encode(array('heading' => 'Error', 'msg' => 'Mobile no already exist'));

                    die;

                }

                $setData['mobile'] = $request->input('mobile');

                $setData['name'] = $request->input('name');
				
                if($request->input('password') != ''){

                    $setData['password'] = password_hash($request->input('password'), PASSWORD_BCRYPT);

                }

                $record = self::$AdminUser->where('id', $request->row_id)->update($setData);

                echo json_encode(array('heading' => 'Success', 'msg' => 'Record updated successfully'));

                die;

            }

        }

        if($row_id > 0){

            $record = self::$AdminUser->where('id', $row_id)->first();

            return view('/panel/accounts/edit-page', ['record' => $record]);

        }else{

            return redirect('/panel/admins');

        }

    }

	

}
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



class UsersController extends Controller{



    private static $AdminUser;

    private static $TokenHelper;



    public function __construct(){

        self::$AdminUser = new AdminUser();

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



        $query = self::$AdminUser->where('status', '!=', 3)->where('type', 'User');

        if($request->input('search_title') && $request->input('search_title') != ""){

            $query->where('name', 'like', '%' . $request->input('search_title') . '%');

        }

		 if($request->input('search_email') && $request->input('search_email') != ""){

            $query->where('email', 'like', '%' . $request->input('search_email') . '%');

        }

		 if($request->input('search_mobile') && $request->input('search_mobile') != ""){

            $query->where('mobile', 'like', '%' . $request->input('search_mobile') . '%');

        }

		if($request->input('search_status') && $request->input('search_status') != ""){

            $query->where('status', $request->input('search_status'));

        }

        $records = $query->orderBy('id', 'DESC')->paginate(20);

        return view('/panel/customers/paginate', compact('records'));



    }



    #add new Service Type

    public function addPage(Request $request){



        if(!$request->session()->has('admin_id')){

            return redirect('/panel/');

        }

        if($request->input()){



            $validator = Validator::make($request->all(), [

				'name' => 'required', 

				'email' => 'required', 

				'mobile' => 'required|numeric', 

			],[

				'name.required' => 'Please enter name.',

				'email.required' => 'Please enter email.',

				'mobile.required' => 'Please enter mobile.'

			]);



            if($validator->fails()){

                $errors = $validator->errors();

                if($errors->first('name')){return json_encode(array('heading' => 'Error', 'msg' => $errors->first('name'))); die;}

				if($errors->first('email')){return json_encode(array('heading' => 'Error', 'msg' => $errors->first('email'))); die;}

				if($errors->first('mobile')){return json_encode(array('heading' => 'Error', 'msg' => $errors->first('mobile'))); die;}

            }else{



				$setData['type'] = 'User';

				$setData['name'] = $request->input('name');

				$setData['email'] = $request->input('email');

				$setData['mobile'] = $request->input('mobile');

				if($request->input('password') != ''){

					$setData['password'] = password_hash($request->input('password'),PASSWORD_BCRYPT);

				}

				if($request->input('row_id') > 0){

					

					$mobileExist = self::$AdminUser->where('mobile',$request->mobile)->where('id','!=',$request->row_id)->count();

					if($mobileExist > 0){

						return json_encode(array('heading' => 'Error', 'msg' => 'Mobile no already exist')); die;

					}

					$emailExist = self::$AdminUser->where('email',$request->email)->where('id','!=',$request->row_id)->count();

					if($emailExist > 0){

						return json_encode(array('heading' => 'Error', 'msg' => 'Email address already exist')); die;

					}

					

					self::$AdminUser->where('id',$request->row_id)->update($setData);

					echo json_encode(array('heading' => 'Success', 'msg' => 'Record updated successfully'));die;

				}else{

					

					$mobileExist = self::$AdminUser->where('mobile',$request->mobile)->count();

					if($mobileExist > 0){

						return json_encode(array('heading' => 'Error', 'msg' => 'Mobile no already exist')); die;

					}

					$emailExist = self::$AdminUser->where('email',$request->email)->count();

					if($emailExist > 0){

						return json_encode(array('heading' => 'Error', 'msg' => 'Email address already exist')); die;

					}

					

					$record = self::$AdminUser->CreateRecord($setData);

					echo json_encode(array('heading' => 'Success', 'msg' => 'Record added successfully'));die;

				}

				

            }



        }



        return view('/panel/customers/add-page');



    }



    #getPage

    public function getPage(Request $request){

		$validator = Validator::make($request->all(), [

			'rowId' => 'required|numeric', 

		], [

			'rowId.required' => 'Please enter rowId.'

		]);

		if($validator->fails()){

			$errors = $validator->errors();

			if($errors->first('rowId')){

				return json_encode(array('heading' => 'Error', 'msg' => $errors->first('rowId')));

				die;

			}

		}else{

			$record = self::$AdminUser->where('id',$request->rowId)->first();

			echo json_encode(array('heading' => 'Success', 'record' => $record));die;

		}

	}

}
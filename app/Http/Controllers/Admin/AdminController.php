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

use Session;

use Validator;

use Mail;

use URL;

use Cookie;

use Illuminate\Validation\Rule;



class AdminController extends Controller{
	

	private static $UserModel;

    private static $TokenHelper;

	

	public function __construct(){

		self::$UserModel = new AdminUser();

        self::$TokenHelper = new TokenHelper();

	}

	

    # admin login page

    public function login(Request $request){

        if($request->session()->has('admin_id')){return redirect('/panel/dashboard/');}

        $cookieUsername = Cookie::get('cookieUsername');

        $cookiePassword = Cookie::get('cookiePassword');

        return view('/panel/login',compact('cookieUsername','cookiePassword'));

    }



    # admin dashboard page

    public function admin_login(Request $request){

		$validator = Validator::make($request->all(), [

			'email' => 'required',

			'password' => 'required',

		],[

			'email.required' => 'Please enter your email address.',

			'password.required' => 'Please enter your password.'

		]);



		if($validator->fails()){

			 $errors = $validator->errors();

			if($errors->first('email')){

				echo json_encode(array('heading'=>'Error Email','msg'=>$errors->first('email')));

			}else if($errors->first('password')){

				echo json_encode(array('heading'=>'Error Password','msg'=>$errors->first('password')));

			}

		}else{

			if(isset($request->reminderMe) && $request->reminderMe == 1){

                Cookie::queue('cookieUsername', $request->email, 5000);

                Cookie::queue('cookiePassword', $request->password, 5000);

            }else{

                Cookie::queue('cookieUsername', '', 5000);

                Cookie::queue('cookiePassword', '', 5000);

            }

			$User = self::$UserModel->where('email', $request->email)->orWhere('mobile',$request->email)->first();
			
			if(isset($User->id)){
				
				if($User->type == 'Admin' || $User->type == 'Account' || $User->type == 'BranchAdmin'){
				
				$branch_name = $branch_code = NULL;
				
				if($User->status == 1){
					
					$PasswordMatch = password_verify($request->password, $User->password);
					
					if(!$PasswordMatch){

						echo json_encode(array('heading'=>'Error Account','msg'=>'Username or password incorrect'));

					}else{
						
						if($User->branch_id > 0){
							$findbranch_res = DB::table('master_branch')->where('status',1)->where('id', $User->branch_id)->first();
							if(isset($findbranch_res->id) && $findbranch_res->status == 1){
								$branch_name=stripslashes($findbranch_res->branch_name);
								$branch_code =stripslashes($findbranch_res->branch_code);						
							}else{
								echo json_encode(array('heading'=>'Error Account','msg'=>'Username or password incorrect'));	
							}
						}						
						session(['admin_unique_id' => $User->unique_id, 'admin_id' => $User->id, 'admin_email' => $User->email, 'admin_profile' => $User, 'admin_type' => $User->type, 'admin_name' => $User->name, 'BRID' => $User->branch_id, 'PREV' => $User->user_prev, 'CRXUSERID' => $User->id, 'BRNM' => $branch_name, 'BRCD' => $branch_code]);
						
						echo json_encode(array('heading'=>'Success','msg'=>''));

					}

				}else{

					echo json_encode(array('heading'=>'Error Account','msg'=>'Account is inactive. Please contact to system administrator'));

				}
				
			}else{
				echo json_encode(array('heading'=>'Error Account','msg'=>'Username or password incorrect'));	
			}

			}else{

				echo json_encode(array('heading'=>'Error Account','msg'=>'Username or password incorrect'));

			}

		}

    }



    # admin dashboard page

    public function dashboard(Request $request){

		if(!$request->session()->has('admin_id')){return redirect('/panel/');}

		$manufacturers = self::$UserModel->where(array('type' => 'Manufacturer'))->where('status',1)->count();

		$distributers = self::$UserModel->where(array('type' => 'Distributer'))->where('status',1)->count();

		$modifiers = self::$UserModel->where(array('type' => 'Modifier'))->where('status',1)->count();

		$users = self::$UserModel->where(array('type' => 'User'))->where('status',1)->count();

        return view('/panel/dashboard',['manufacturers' => $manufacturers,'distributers' => $distributers,'modifiers' => $modifiers,'users' => $users]);

    }



    # admin dashboard page

    public function logout(Request $request){

        $request->session()->flush();

        return redirect('/panel/');

   }



}
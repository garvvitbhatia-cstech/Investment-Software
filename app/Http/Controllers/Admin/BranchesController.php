<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

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



class BranchesController extends Controller {

    private static $Branches;

    private static $TokenHelper;

	

    public function __construct(){

        self::$Branches = new Branches();

        self::$TokenHelper = new TokenHelper();

    }

	

    #admin dashboard page

    public function getList(Request $request){

        if(!$request->session()->has('admin_id')){

            return redirect('/panel/');

        }

        return view('/panel/branches/index');

    }

	

    public function listPaginate(Request $request){

        if(!$request->session()->has('admin_id')){

            return redirect('/panel/');

        }

        $query = self::$Branches->where('status', '!=', 3);
		
		if($request->input('search_status') && $request->input('search_status') != ""){
		
			$query->where('status', 'like', '%'.$request->input('search_status').'%');
			
		}

        if($request->input('search_keywords') && $request->input('search_keywords') != ""){
		
			$SearchKeyword = $request->input('search_keywords');
            $query->where(function($query) use ($SearchKeyword)  {
                if(!empty($SearchKeyword)) {
                    $query->where('master_branch.branch_name', 'like', '%'.$SearchKeyword.'%') 
                    ->orWhere('master_branch.branch_code', 'like', '%'.$SearchKeyword.'%');
                }
             });

        }


        $records = $query->orderBy('id', 'DESC')->paginate(20);

        return view('/panel/branches/paginate', compact('records'));

    }

	

    #add new Service Type

    public function addPage(Request $request){

        if(!$request->session()->has('admin_id')){

            return redirect('/panel/');

        }

        if($request->input()){

            $validator = Validator::make($request->all(), [

				'branch_name' => 'required', 

				'branch_code' => 'required'

			], [

				'branch_name.required' => 'Please enter branch name.', 

				'branch_code.required' => 'Please enter branch code.'

			]);

            if($validator->fails()){

                $errors = $validator->errors();

                if($errors->first('branch_name')){

                    return json_encode(array('heading' => 'Error', 'msg' => $errors->first('branch_name')));

                    die;

                }

                if($errors->first('branch_code')){

                    return json_encode(array('heading' => 'Error', 'msg' => $errors->first('branch_code')));

                    die;

                }

            }else{

                $mobileExist = self::$Branches->where('branch_name', $request->branch_name)->where('status', '!=',3)->count();

                if($mobileExist > 0){

                    return json_encode(array('heading' => 'Error', 'msg' => 'branch name already exist'));

                    die;

                }

                $emailExist = self::$Branches->where('branch_code', $request->branch_code)->where('status', '!=',3)->count();

                if($emailExist > 0){

                    return json_encode(array('heading' => 'Error', 'msg' => 'Branch code already exist'));

                    die;

                }

                $setData['branch_name'] = $request->input('branch_name');

				$setData['branch_code'] = ucwords($request->input('branch_code'));

				

                $record = self::$Branches->CreateRecord($setData);

                echo json_encode(array('heading' => 'Success', 'msg' => 'Record added successfully', 'row_id' => base64_encode($record->id)));

                die;

            }

        }

        return view('/panel/branches/add-page');

    }

	

    #editPage

    public function editPage(Request $request, $row_id){

        if(!$request->session()->has('admin_id')){

            return redirect('/panel/');

        }

        $row_id = base64_decode($row_id);

        if($request->input()){

            $validator = Validator::make($request->all(), [

				'branch_name' => 'required', 

				'branch_code' => 'required'

			], [

				'branch_name.required' => 'Please enter branch name.', 

				'branch_code.required' => 'Please enter branch code.'

			]);

            if($validator->fails()){

                $errors = $validator->errors();

                if($errors->first('branch_name')){

                    return json_encode(array('heading' => 'Error', 'msg' => $errors->first('branch_name')));

                    die;

                }

                if($errors->first('branch_code')){

                    return json_encode(array('heading' => 'Error', 'msg' => $errors->first('branch_code')));

                    die;

                }

            }else{

                $mobileExist = self::$Branches->where('branch_name', $request->branch_name)->where('id', '!=', $request->row_id)->where('status', '!=',3)->count();

                if($mobileExist > 0){

                    return json_encode(array('heading' => 'Error', 'msg' => 'Branch name no already exist'));

                    die;

                }

                $emailExist = self::$Branches->where('branch_code', $request->branch_code)->where('id', '!=', $request->row_id)->where('status', '!=',3)->count();

                if($emailExist > 0){

                    return json_encode(array('heading' => 'Error', 'msg' => 'Branch code already exist'));

                    die;

                }

                $setData['branch_name'] = $request->input('branch_name');

				$setData['branch_code'] = ucwords($request->input('branch_code'));



                $record = self::$Branches->where('id', $request->row_id)->update($setData);

                echo json_encode(array('heading' => 'Success', 'msg' => 'Record updated successfully'));

                die;

            }

        }

        if($row_id > 0){

            $record = self::$Branches->where('id', $row_id)->first();

            return view('/panel/branches/edit-page', ['record' => $record]);

        }else{

            return redirect('/panel/branches');

        }

    }



}
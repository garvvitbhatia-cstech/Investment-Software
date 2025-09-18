<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\AllyTypes;

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



class AllyTypesController extends Controller {

    private static $AllyTypes;

    private static $TokenHelper;

	

    public function __construct(){

        self::$AllyTypes = new AllyTypes();

        self::$TokenHelper = new TokenHelper();

    }

	

    #admin dashboard page

    public function getList(Request $request){

        if(!$request->session()->has('admin_id')){

            return redirect('/panel/');

        }

        return view('/panel/ally_types/index');

    }

	

    public function listPaginate(Request $request){

        if(!$request->session()->has('admin_id')){

            return redirect('/panel/');

        }

        $query = self::$AllyTypes->where('status', '!=', 3);
		
		if($request->input('search_keywords') && $request->input('search_keywords') != ""){
		
			$SearchKeyword = $request->input('search_keywords');
            $query->where(function($query) use ($SearchKeyword)  {
                if(!empty($SearchKeyword)) {
                    $query->where('ally_types.ally_type', 'like', '%'.$SearchKeyword.'%') 
                    ->orWhere('ally_types.ally_code', 'like', '%'.$SearchKeyword.'%')
                    ->orWhere('ally_types.ally_fees', 'like', '%'.$SearchKeyword.'%');
                }
             });

        }
		
        $records = $query->orderBy('id', 'DESC')->paginate(20);

        return view('/panel/ally_types/paginate', compact('records'));

    }

	

    #add new Service Type

    public function addPage(Request $request){

        if(!$request->session()->has('admin_id')){

            return redirect('/panel/');

        }

        if($request->input()){

            $validator = Validator::make($request->all(), [

				'ally_type' => 'required', 

				'ally_code' => 'required',

				'ally_fees' => 'required'

			], [

				'ally_type.required' => 'Please enter ally type.', 

				'ally_code.required' => 'Please enter ally code.',

				'ally_fees.required' => 'Please enter ally registration fees.'

			]);

            if($validator->fails()){

                $errors = $validator->errors();

                if($errors->first('ally_type')){

                    return json_encode(array('heading' => 'Error', 'msg' => $errors->first('ally_type')));

                    die;

                }

                if($errors->first('ally_code')){

                    return json_encode(array('heading' => 'Error', 'msg' => $errors->first('ally_code')));

                    die;

                }

				if($errors->first('ally_fees')){

                    return json_encode(array('heading' => 'Error', 'msg' => $errors->first('ally_fees')));

                    die;

                }

            }else{

                $typeExist = self::$AllyTypes->where('ally_type', $request->ally_type)->where('status', '!=',3)->count();

                if($typeExist > 0){

                    return json_encode(array('heading' => 'Error', 'msg' => 'Ally type already exist'));

                    die;

                }

                $setData['ally_type'] = $request->input('ally_type');

				$setData['ally_code'] = $request->input('ally_code');

				$setData['ally_fees'] = $request->input('ally_fees');

				

                $record = self::$AllyTypes->CreateRecord($setData);

                echo json_encode(array('heading' => 'Success', 'msg' => 'Record added successfully', 'row_id' => base64_encode($record->id)));

                die;

            }

        }

        return view('/panel/ally_types/add-page');

    }

	

    #editPage

    public function editPage(Request $request, $row_id){

        if(!$request->session()->has('admin_id')){

            return redirect('/panel/');

        }

        $row_id = base64_decode($row_id);

        if($request->input()){

            $validator = Validator::make($request->all(), [

				'ally_type' => 'required', 

				'ally_code' => 'required',

				'ally_fees' => 'required'

			], [

				'ally_type.required' => 'Please enter ally type.', 

				'ally_code.required' => 'Please enter ally code.',

				'ally_fees.required' => 'Please enter ally registration fees.'

			]);

            if($validator->fails()){

                $errors = $validator->errors();

                if($errors->first('ally_type')){

                    return json_encode(array('heading' => 'Error', 'msg' => $errors->first('ally_type')));

                    die;

                }

                if($errors->first('ally_code')){

                    return json_encode(array('heading' => 'Error', 'msg' => $errors->first('ally_code')));

                    die;

                }

				if($errors->first('ally_fees')){

                    return json_encode(array('heading' => 'Error', 'msg' => $errors->first('ally_fees')));

                    die;

                }

            }else{

                $mobileExist = self::$AllyTypes->where('ally_type', $request->ally_type)->where('id', '!=', $request->row_id)->where('status', '!=',3)->count();

                if($mobileExist > 0){

                    return json_encode(array('heading' => 'Error', 'msg' => 'Ally type already exist'));

                    die;

                }

                $setData['ally_type'] = $request->input('ally_type');

				$setData['ally_code'] = $request->input('ally_code');

				$setData['ally_fees'] = $request->input('ally_fees');

				

                $record = self::$AllyTypes->where('id', $request->row_id)->update($setData);

                echo json_encode(array('heading' => 'Success', 'msg' => 'Record updated successfully'));

                die;

            }

        }

        if($row_id > 0){

            $record = self::$AllyTypes->where('id', $row_id)->first();

            return view('/panel/ally_types/edit-page', ['record' => $record]);

        }else{

            return redirect('/panel/ally-types');

        }

    }



}
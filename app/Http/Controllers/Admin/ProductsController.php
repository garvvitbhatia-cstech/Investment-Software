<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\Products;

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


class ProductsController extends Controller{



    private static $Products;

    private static $TokenHelper;

	

    public function __construct(){

        self::$Products = new Products();

		self::$TokenHelper = new TokenHelper();

    }



    #admin dashboard page

    public function getList(Request $request){

        if(!$request->session()->has('admin_id')){return redirect('/panel/');}

        return view('/panel/products/index');

    }

    public function listPaginate(Request $request){

        if(!$request->session()->has('admin_id')){return redirect('/panel/');}

        $query = self::$Products->where('status', '!=', 3);

        if($request->input('search_keywords') && $request->input('search_keywords') != ""){
		
			$SearchKeyword = $request->input('search_keywords');
            $query->where(function($query) use ($SearchKeyword)  {
                if(!empty($SearchKeyword)) {
                    $query->where('master_product.product_name', 'like', '%'.$SearchKeyword.'%') 
                    ->orWhere('master_product.unit_price', 'like', '%'.$SearchKeyword.'%')
                    ->orWhere('master_product.min_unit', 'like', '%'.$SearchKeyword.'%')
					->orWhere('master_product.max_unit', 'like', '%'.$SearchKeyword.'%')
					->orWhere('master_product.per_unit_register_fees', 'like', '%'.$SearchKeyword.'%')
					->orWhere('master_product.return_in_days', 'like', '%'.$SearchKeyword.'%')
                    ->orWhere('master_product.interest_rate', 'like', '%'.$SearchKeyword.'%');
                }
             });

        }

        $records = $query->orderBy('id', 'DESC')->paginate(20);

        return view('/panel/products/paginate', compact('records'));

    }


    #add new Service Type

    public function addPage(Request $request){

        if(!$request->session()->has('admin_id')){return redirect('/panel/');}

        if($request->input()){

            $validator = Validator::make($request->all(), [

				'product_name' => 'required', 

				'unit_price' => 'required', 

				'min_unit' => 'required', 
				
				'max_unit' => 'required', 

				'per_unit_register_fees' => 'required', 

				'return_in_days' => 'required', 

				'interest_rate' => 'required'

			], [

				'product_name.required' => 'Please enter product name.',

				'unit_price.required' => 'Please enter unit price.',

				'min_unit.required' => 'Please enter minimum unit.',
				
				'max_unit.required' => 'Please enter maximum unit.',

				'per_unit_register_fees.required' => 'Please enter registration fees per unit.',

				'return_in_days.required' => 'Please enter return in days.',

				'interest_rate.required' => 'Please enter rate of interest.'

			]);

            if($validator->fails()){

                $errors = $validator->errors();

                if($errors->first('product_name')){

                    return json_encode(array('heading' => 'Error', 'msg' => $errors->first('product_name')));die;

                }

				if($errors->first('unit_price')){

                    return json_encode(array('heading' => 'Error', 'msg' => $errors->first('unit_price')));die;

                }

				if($errors->first('min_unit')){

                    return json_encode(array('heading' => 'Error', 'msg' => $errors->first('min_unit')));die;

                }
				
				if($errors->first('max_unit')){

                    return json_encode(array('heading' => 'Error', 'msg' => $errors->first('max_unit')));die;

                }

				if($errors->first('per_unit_register_fees')){

                    return json_encode(array('heading' => 'Error', 'msg' => $errors->first('per_unit_register_fees')));die;

                }

				if($errors->first('return_in_days')){

                    return json_encode(array('heading' => 'Error', 'msg' => $errors->first('return_in_days')));die;

                }

				if($errors->first('interest_rate')){

                    return json_encode(array('heading' => 'Error', 'msg' => $errors->first('interest_rate')));die;

                }

            } else {

				if(!self::$Products->ExistingRecord($request->input('product_name'))){
					
					$saturday = $sunday = 2;
					
					if($request->is_saturday_off){
						$saturday = 1;
					}
					
					if($request->is_sunday_off){
						$sunday = 1;
					}
					
					$setData['is_saturday_off'] = $saturday;
					
					$setData['is_sunday_off'] = $sunday;

					$setData['product_name'] = $request->input('product_name');

					$setData['unit_price'] = $request->input('unit_price');

					$setData['min_unit'] = $request->input('min_unit');
					
					$setData['max_unit'] = $request->input('max_unit');

					$setData['per_unit_register_fees'] = $request->input('per_unit_register_fees');

					$setData['return_in_days'] = $request->input('return_in_days');

					$setData['interest_rate'] = $request->input('interest_rate');

					$record = self::$Products->CreateRecord($setData);					

					echo json_encode(array('heading' => 'Success', 'msg' => 'Record added successfully', 'row_id' => base64_encode($record->id)));die;

				}else{

					echo json_encode(array('heading' => 'Error', 'msg' => 'Product already exists.'));

                    die;

				}

            }



        }

        return view('/panel/products/add-page');

    }

	

	#edit Service Type

    public function editPage(Request $request, $row_id){

        $RowID = base64_decode($row_id);

        if(!$request->session()->has('admin_id')){

            return redirect('/panel/');

        }

        $record = self::$Products->where(array('id' => $RowID))->first();

        if($request->input()){

            $validator = Validator::make($request->all(), [

				'product_name' => 'required', 

				'unit_price' => 'required', 

				'min_unit' => 'required', 
				
				'max_unit' => 'required', 

				'per_unit_register_fees' => 'required', 

				'return_in_days' => 'required', 

				'interest_rate' => 'required'

			], [

				'product_name.required' => 'Please enter product name.',

				'unit_price.required' => 'Please enter unit price.',

				'min_unit.required' => 'Please enter minimum unit.',
				
				'max_unit.required' => 'Please enter maximum unit.',

				'per_unit_register_fees.required' => 'Please enter registration fees per unit.',

				'return_in_days.required' => 'Please enter return in days.',

				'interest_rate.required' => 'Please enter rate of interest.'

			]);

            if($validator->fails()){

                $errors = $validator->errors();

                if($errors->first('product_name')){

                    return json_encode(array('heading' => 'Error', 'msg' => $errors->first('product_name')));die;

                }

				if($errors->first('unit_price')){

                    return json_encode(array('heading' => 'Error', 'msg' => $errors->first('unit_price')));die;

                }

				if($errors->first('min_unit')){

                    return json_encode(array('heading' => 'Error', 'msg' => $errors->first('min_unit')));die;

                }
				
				if($errors->first('max_unit')){

                    return json_encode(array('heading' => 'Error', 'msg' => $errors->first('max_unit')));die;

                }

				if($errors->first('per_unit_register_fees')){

                    return json_encode(array('heading' => 'Error', 'msg' => $errors->first('per_unit_register_fees')));die;

                }

				if($errors->first('return_in_days')){

                    return json_encode(array('heading' => 'Error', 'msg' => $errors->first('return_in_days')));die;

                }

				if($errors->first('interest_rate')){

                    return json_encode(array('heading' => 'Error', 'msg' => $errors->first('interest_rate')));die;

                }

            }else{

                if(self::$Products->ExistingRecordUpdate($request->input('product_name'), $request->row_id)){

                    echo json_encode(array('heading' => 'Error', 'msg' => 'Product already exists.'));

                    die;

                }else{
					
					$saturday = $sunday = 2;
					
					if($request->is_saturday_off){
						$saturday = 1;
					}
					
					if($request->is_sunday_off){
						$sunday = 1;
					}
	
                    $setData['id'] = $request->row_id;
					
					$setData['is_saturday_off'] = $saturday;
					
					$setData['is_sunday_off'] = $sunday;

                    $setData['product_name'] = $request->input('product_name');

					$setData['unit_price'] = $request->input('unit_price');

					$setData['max_unit'] = $request->input('max_unit');
					
					$setData['min_unit'] = $request->input('min_unit');

					$setData['per_unit_register_fees'] = $request->input('per_unit_register_fees');

					$setData['return_in_days'] = $request->input('return_in_days');

					$setData['interest_rate'] = $request->input('interest_rate');

                    self::$Products->UpdateRecord($setData);

                    echo json_encode(array('heading' => 'Success', 'msg' => 'Product details updated successfully'));

                    die;

                }

            }

        }

        if(isset($record->id)){

            return view('/panel/products/edit-page', compact('record', 'row_id'));

        }else{

            return redirect('/panel/products');

        }

    }



}
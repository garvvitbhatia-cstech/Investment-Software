<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\Holidays;

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


class HolidaysController extends Controller{

    private static $Holidays;

    private static $TokenHelper;


    public function __construct(){

        self::$Holidays = new Holidays();

		self::$TokenHelper = new TokenHelper();

    }

    #admin dashboard page

    public function getList(Request $request){

        if(!$request->session()->has('admin_id')){return redirect('/panel/');}

        return view('/panel/holidays/index');

    }

    public function listPaginate(Request $request){

        if(!$request->session()->has('admin_id')){return redirect('/panel/');}

        $query = self::$Holidays->where('status', '!=', 3);

        if($request->input('search_status') && $request->input('search_status') != ""){
		
        	$query->where('holidays.status', 'like', '%'.$request->input('search_status').'%');

        }
		
		if(!empty($request->input('from_date')) || !empty($request->input('to_date'))){
            $startdate = $request->input('from_date');
            $to_date = $request->input('to_date');
			if(!empty($startdate) && empty($to_date)){
				$startdate = $request->input('from_date');
				$query->whereDate('holiday_date', '>=', $startdate);
            }else if(empty($startdate) && !empty($to_date)){
				$to_date = $request->input('to_date');
				$query->whereDate('holiday_date', '<=', $to_date);
            }else{
				$startdate = $request->input('from_date');
            	$to_date = $request->input('to_date');
				$query->whereBetween('holiday_date', [$startdate, $to_date]);
            }
        }
		
		if($request->input('search_title') && $request->input('search_title') != ""){
		
			$SearchKeyword = $request->input('search_title');
            $query->where(function($query) use ($SearchKeyword)  {
                if(!empty($SearchKeyword)) {
                    $query->where('holidays.title', 'like', '%'.$SearchKeyword.'%');
                }
             });

        }

        $records = $query->orderBy('id', 'DESC')->paginate(20);

        return view('/panel/holidays/paginate', compact('records'));

    }


    #add new Service Type

    public function addPage(Request $request){

        if(!$request->session()->has('admin_id')){return redirect('/panel/');}

        if($request->input()){

            $validator = Validator::make($request->all(), [

				'title' => 'required', 

				'holiday_date' => 'required',
			], [

				'title.required' => 'Please enter title.',

				'holiday_date.required' => 'Please enter holiday date.',
			]);

            if($validator->fails()){

                $errors = $validator->errors();

                if($errors->first('title')){

                    return json_encode(array('heading' => 'Error', 'msg' => $errors->first('title')));die;

                }

				if($errors->first('holiday_date')){

                    return json_encode(array('heading' => 'Error', 'msg' => $errors->first('holiday_date')));die;

                }

            } else {

				if(!self::$Holidays->ExistingRecord($request->input('holiday_date'))){

					$holiday_date = $request->input('holiday_date');
                     
                    $setData['title'] = $request->input('title');

					$setData['holiday_date'] = $request->input('holiday_date');
					
					$setData['day'] = date('d',strtotime($holiday_date));
					
					$setData['month'] = date('m',strtotime($holiday_date));
					
					$setData['year'] = date('Y',strtotime($holiday_date));

					$record = self::$Holidays->CreateRecord($setData);					

					echo json_encode(array('heading' => 'Success', 'msg' => 'Record added successfully', 'row_id' => base64_encode($record->id)));die;

				}else{

					echo json_encode(array('heading' => 'Error', 'msg' => 'Holiday already exists.'));

                    die;

				}

            }

        }

        return view('/panel/holidays/add-page');
    }


	#edit Service Type

    public function editPage(Request $request, $row_id){

        $RowID = base64_decode($row_id);

        if(!$request->session()->has('admin_id')){

            return redirect('/panel/');

        }

        $record = self::$Holidays->where(array('id' => $RowID))->first();

        if($request->input()){

            $validator = Validator::make($request->all(), [

				'title' => 'required', 

				'holiday_date' => 'required',
			], [

				'title.required' => 'Please enter title.',

				'holiday_date.required' => 'Please enter holiday date.',
			]);

            if($validator->fails()){

                $errors = $validator->errors();

                if($errors->first('title')){

                    return json_encode(array('heading' => 'Error', 'msg' => $errors->first('title')));die;

                }

				if($errors->first('holiday_date')){

                    return json_encode(array('heading' => 'Error', 'msg' => $errors->first('holiday_date')));die;

                }

            }else{

                if(self::$Holidays->ExistingRecordUpdate($request->input('holiday_date'), $request->row_id)){

                    echo json_encode(array('heading' => 'Error', 'msg' => 'Holiday already exists.'));

                    die;

                }else{
					
					$holiday_date = $request->input('holiday_date');
                     
					$setData['id'] = $request->row_id;
					 
                    $setData['title'] = $request->input('title');

					$setData['holiday_date'] = $request->input('holiday_date');
					
					$setData['day'] = date('d',strtotime($holiday_date));
					
					$setData['month'] = date('m',strtotime($holiday_date));
					
					$setData['year'] = date('Y',strtotime($holiday_date));

                    self::$Holidays->UpdateRecord($setData);

                    echo json_encode(array('heading' => 'Success', 'msg' => 'Holiday details updated successfully'));

                    die;

                }

            }

        }

        if(isset($record->id)){

            return view('/panel/holidays/edit-page', compact('record', 'row_id'));

        }else{

            return redirect('/panel/holidays');

        }

    }


}
<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\Contacts;

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



class ContactsController extends Controller{

    private static $Contacts;

    private static $TokenHelper;

    public function __construct(){

        self::$Contacts = new Contacts();

        self::$TokenHelper = new TokenHelper();

    }

	

    #admin dashboard page

    public function getList(Request $request){

        if(!$request->session()->has('admin_id')){

            return redirect('/panel/');

        }

        return view('/panel/enquiries/index');

    }

	

    public function listPaginate(Request $request){

        if(!$request->session()->has('admin_id')){

            return redirect('/panel/');

        }

        $query = self::$Contacts->where('status', '!=', 3);

        if($request->input('search_name') && $request->input('search_name') != ""){

            $query->where('name', 'like', '%' . $request->input('search_name') . '%');

        }

        if($request->input('search_email') && $request->input('search_email') != ""){

            $query->where('email', 'like', '%' . $request->input('search_email') . '%');

        }

        if($request->input('search_contact') && $request->input('search_contact') != ""){

            $query->where('contact', 'like', '%' . $request->input('search_contact') . '%');

        }

        if($request->input('search_status') && $request->input('search_status') != ""){

            $query->where('read_status', $request->input('search_status'));

        }

        $records = $query->orderBy('id', 'DESC')->paginate(20);

        return view('/panel/enquiries/paginate', compact('records'));

    }



}
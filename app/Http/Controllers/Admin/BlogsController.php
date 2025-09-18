<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\Blogs;

use App\Models\Categories;

use App\RouteHelper;

use App\Models\TokenHelper;

use App\Models\Responses;

use ReallySimpleJWT\Token;

use Illuminate\Http\Request;

use Illuminate\Support\Str;

use Illuminate\Support\Facades\DB;

use Illuminate\Database\Eloquent\Model;

use Session;

use Validator;

use Mail;

use URL;

use Cookie;

use Illuminate\Validation\Rule;



class BlogsController extends Controller{

    private static $Blogs;

    private static $Categories;

    public function __construct(){

        self::$Blogs = new Blogs();

        self::$Categories = new Categories();

    }

	

    #admin dashboard page

    public function getList(Request $request){

        if(!$request->session()->has('admin_id')){

            return redirect('/panel/');

        }

        return view('/panel/blogs/index');

    }

	

    public function listPaginate(Request $request){

        if(!$request->session()->has('admin_id')){

            return redirect('/panel/');

        }

        $page = $request->page;

        $query = self::$Blogs->where('status', '!=', 3);

        if($request->input('search_title') && $request->input('search_title') != ""){

            $search_title = $request->input('search_title');

            $query->where('title', 'like', '%' . $search_title . '%');

        }

        if($request->input('search_status') && $request->input('search_status') != ""){

            $query->where('status', $request->input('search_status'));

        }

        $records = $query->orderBy('id', 'DESC')->paginate(20);

        return view('/panel/blogs/paginate', compact('records', 'page'));

    }

	

    #add new Service Type

    public function addPage(Request $request){

        if(!$request->session()->has('admin_id')){

            return redirect('/panel/');

        }

        if($request->input()){

            $validator = Validator::make($request->all(), [

				'title' => 'required'

			], [

				'title.required' => 'Please enter title.'

			]);

            if($validator->fails()){

                $errors = $validator->errors();

                if($errors->first('title')){

                    return json_encode(array('heading' => 'Error', 'msg' => $errors->first('title')));

                    die;

                }

            }else{

                if(!self::$Blogs->ExistingRecord($request->input('title'))){

                    # profile pic upload

                    if(isset($request->banner) && $request->banner->extension() != ""){

                        $validator = Validator::make($request->all(), ['banner' => 'required|image|mimes:jpeg,png,jpg,webp|max:20480']);

                        if($validator->fails()){

                            $errors = $validator->errors();

                            return json_encode(array('heading' => 'Error', 'msg' => $errors->first('banner')));

                            die;

                        }else{

                            $actual_image_name = str_shuffle(time() . rand()) . '.' . $request->banner->extension();

                            $destination = base_path() . '/public/admin/images/blogs/';

                            $request->banner->move($destination, $actual_image_name);

                            $setData['banner'] = $actual_image_name;

                        }

                    }

                    $setData['slug'] = Str::slug($request->title);

                    $setData['category'] = $request->input('category');

                    $setData['title'] = $request->input('title');

                    $setData['short_description'] = $request->input('short_description');

                    $setData['description'] = $request->input('description');

                    $setData['keywords'] = $request->input('keywords');

                    $setData['tags'] = $request->input('tags');

                    $setData['seo_title'] = $request->input('seo_title');

                    $setData['seo_description'] = $request->input('seo_description');

                    $setData['seo_keyword'] = $request->input('seo_keyword');

                    $setData['robot_tags'] = $request->input('robot_tags');

                    $record = self::$Blogs->CreateRecord($setData);

                    echo json_encode(array('heading' => 'Success', 'msg' => 'Record added successfully', 'row_id' => base64_encode($record->id)));

                    die;

                }

            }

        }

        $categories = $this->getCategories();

        return view('/panel/blogs/add-page', compact('categories'));

    }

	

    #edit Service Type

    public function editPage(Request $request, $row_id){

        $RowID = base64_decode($row_id);

        if(!$request->session()->has('admin_id')){

            return redirect('/panel/');

        }

        $record = self::$Blogs->where(array('id' => $RowID))->first();

        if($request->input()){

            $validator = Validator::make($request->all(), [

				'title' => 'required'

			], [

				'title.required' => 'Please enter title.'

			]);

            if($validator->fails()){

                $errors = $validator->errors();

                if($errors->first('title')){

                    return json_encode(array('heading' => 'Error', 'msg' => $errors->first('title')));

                    die;

                }

            }else{

                if(self::$Blogs->ExistingRecordUpdate($request->input('title'), $request->row_id)){

                    echo json_encode(array('heading' => 'Error', 'msg' => 'Blog already exists.'));

                    die;

                }else{

                    #profile pic upload

                    if(isset($request->banner) && $request->banner->extension() != ""){

                        $validator = Validator::make($request->all(), ['banner' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048']);

                        if($validator->fails()){

                            $errors = $validator->errors();

                            return json_encode(array('heading' => 'Error', 'msg' => $errors->first('banner')));

                            die;

                        }else{

                            $actual_image_name = str_shuffle(mt_rand() . time()) . '.' . $request->banner->extension();

                            $destination = base_path() . '/public/admin/images/blogs/';

                            $request->banner->move($destination, $actual_image_name);

                            if($request->input('old_banner') != ""){

                                if(file_exists($destination . $request->old_banner)){

                                    unlink($destination . $request->old_banner);

                                }

                            }

                            $setData['banner'] = $actual_image_name;

                        }

                    }

                    $setData['id'] = $request->row_id;

                    $setData['category'] = $request->input('category');

                    $setData['title'] = $request->input('title');

                    $setData['short_description'] = $request->input('short_description');

                    $setData['description'] = $request->input('description');

                    $setData['keywords'] = $request->input('keywords');

                    $setData['tags'] = $request->input('tags');

                    $setData['seo_title'] = $request->input('seo_title');

                    $setData['seo_description'] = $request->input('seo_description');

                    $setData['seo_keyword'] = $request->input('seo_keyword');

                    $setData['robot_tags'] = $request->input('robot_tags');

                    self::$Blogs->UpdateRecord($setData);

                    echo json_encode(array('heading' => 'Success', 'msg' => 'Blog details updated successfully'));

                    die;

                }

            }

        }

        if(isset($record->id)){

            $categories = $this->getCategories();

            return view('/panel/blogs/edit-page', compact('record', 'row_id', 'categories'));

        }else{

            return redirect('/panel/blogs');

        }

    }

	

    public function getCategories(){

        return self::$Categories->where('status', 1)->where('type', 'Blog')->latest()->get();

    }

	

}


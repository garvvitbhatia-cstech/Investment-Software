<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;

use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Foundation\Auth\User as Authenticatable;

use Illuminate\Notifications\Notifiable;

use Laravel\Sanctum\HasApiTokens;



class AdminUser extends Authenticatable{



    use HasFactory, Notifiable;



    protected $table = 'users';



    /**

     * The attributes that are mass assignable.

     *

     * @var array<int, string>

     */

    protected $fillable = [

		"added_by",

        "type",

		"username",

        'name',

        'email',

        'password',

		'mobile',

		'gender',

		'company',

		'phone',

		'branch_id',

		'address',

		'city',

		'state',

		'country',

		'zipcode',

		'photo',

		'dob',

		'website',

		'referral_code',

		'logo',

		'user_prev',

		'product_manufatured_type',

		'manufacture_unit_address',

		'monthly_output',

		'sell_on_caabaa',

		'service_type',

		'workshop_address',

		'service_pincode_coverage',

		'experience',

		'certificate',

		'service_on_caabaa'

    ];



    /**

     * The attributes that should be hidden for serialization.

     *

     * @var array<int, string>

     */

    protected $hidden = [

        "login_otp",

        'password',

        'remember_token',

    ];



    /**

     * The attributes that should be cast.

     *

     * @var array<string, string>

     */

    protected $casts = [

        'email_verified_at' => 'datetime',

    ];

	

	public function GetRecordById($id){

		return $this::where('id', $id)->first();

	}



	public function UpdateRecord($Details){

		$Record = $this::where('id', $Details['id'])->update($Details);

		return true;

	}



	public function CreateRecord($Details){

		$Record = $this::create($Details);

		return $Record;

	}

	

	public function ExistingRecord($title){

		return $this::where('product_name',$title)->where('status','!=', 3)->exists();

	}



	public function ExistingRecordUpdate($title, $id){

		return $this::where('product_name',$title)->where('id','!=', $id)->where('status','!=', 3)->exists();

	}



}
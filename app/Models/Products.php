<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;

use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Foundation\Auth\User as Authenticatable;

use Illuminate\Notifications\Notifiable;

use Laravel\Sanctum\HasApiTokens;



class Products extends Authenticatable{



  use HasFactory, Notifiable;



	protected $table = 'master_product';

    /**

     * The attributes that are mass assignable.

     *

     * @var array<int, string>

     */

    protected $fillable = [

		'product_name',

		'unit_price',

		'min_unit',
		
		'max_unit',

		'per_unit_register_fees',

		'return_in_days',

		'interest_rate',
		
		'is_saturday_off',
		
		'is_sunday_off',

		'status'

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
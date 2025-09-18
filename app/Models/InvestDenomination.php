<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;

use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Foundation\Auth\User as Authenticatable;

use Illuminate\Notifications\Notifiable;

use Laravel\Sanctum\HasApiTokens;



class InvestDenomination extends Authenticatable{



  use HasFactory, Notifiable;



	protected $table = 'invest_denomination';

    /**

     * The attributes that are mass assignable.

     *

     * @var array<int, string>

     */

    protected $fillable = [

		'investment_id',

		'denom_2000',

		'denom_500',

		'denom_200',

		'denom_100',
		
		'denom_50',
		
		'denom_10',
		
		'denom_5',

		'denom_2',
		
		'denom_1',

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
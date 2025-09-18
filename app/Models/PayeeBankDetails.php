<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;

use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Foundation\Auth\User as Authenticatable;

use Illuminate\Notifications\Notifiable;

use Laravel\Sanctum\HasApiTokens;



class PayeeBankDetails extends Authenticatable{



  use HasFactory, Notifiable;



	protected $table = 'payee_bank_details';

    /**

     * The attributes that are mass assignable.

     *

     * @var array<int, string>

     */

    protected $fillable = [

		'bank_name',

		'accnt_name',
		
		'branch_name',
		
		'branch_addr',
		
		'ifsc_code',
		
		'accnt_no',
		
		'isactive',

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
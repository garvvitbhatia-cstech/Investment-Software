<?php
namespace App\Models;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;


class Customers extends Authenticatable{

  use HasFactory, Notifiable;
	
	protected $table = 'master_customer';

    /**

     * The attributes that are mass assignable.

     *

     * @var array<int, string>

     */

    protected $fillable = [
		'customer_id',
		'customer_code',
		'emp_code',
		'ally_code',
		'udaid',
		'customer_type',
		'cust_name',
		'pan_no',
		'gender',
		'gurdain_name',
		'relationship',
		'occupation',
		'contact_no',
		'alt_contact_no',
		'email',
		'dob',
		'doa',
		'address',
		'state',
		'city',
		'zip_code',
		'prof_image',
		'branch_id',
		'created_by',
		'created_on',
		'last_updated_by',
		'last_updated_on',
		'status'
    ];


    /**


     * The attributes that should be hidden for serialization.


     *


     * @var array<int, string>


     */


    protected $hidden = [

    ];


    /**

     * The attributes that should be cast.

     *

     * @var array<string, string>

     */


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
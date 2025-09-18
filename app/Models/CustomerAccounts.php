<?php
namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;

use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Foundation\Auth\User as Authenticatable;

use Illuminate\Notifications\Notifiable;

use Laravel\Sanctum\HasApiTokens;



class CustomerAccounts extends Authenticatable{

  use HasFactory, Notifiable;

	
	protected $table = 'customer_account';



    /**

     * The attributes that are mass assignable.

     *

     * @var array<int, string>

    */

    protected $fillable = [
		'cust_id',
		'bank_name',
		'back_ac_no',
		'accnt_holder_name',
		'accnt_ifsc_code',
		'branch_name',
		'branch_addr',
		'bank_type',
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


}
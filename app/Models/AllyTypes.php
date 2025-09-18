<?php
namespace App\Models;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;


class AllyTypes extends Authenticatable{

  use HasFactory, Notifiable;
  
  protected $table = 'ally_types';

    /**


     * The attributes that are mass assignable.


     *


     * @var array<int, string>


     */


    protected $fillable = [
	    'ally_type',
        'ally_code',		
		'ally_fees',
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

    public function ExistingRecord($ally_type){
		return $this::where('ally_type',$ally_type)->where('status','!=', 3)->exists();
	}

	public function ExistingRecordUpdate($ally_type, $id){
		return $this::where('ally_type',$ally_type)->where('id','!=', $id)->where('status','!=', 3)->exists();
	}

}
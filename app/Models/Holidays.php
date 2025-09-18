<?php
namespace App\Models;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;


class Holidays extends Authenticatable{

  use HasFactory, Notifiable;

	protected $table = 'holidays';

    /**

     * The attributes that are mass assignable.

     *

     * @var array<int, string>

     */

    protected $fillable = [
		'title',
		'holiday_date',
		'day',		
		'month',
		'year',
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
	
	public function ExistingRecord($holiday_date){
		return $this::where('holiday_date',$holiday_date)->where('status','!=', 3)->exists();
	}

	public function ExistingRecordUpdate($holiday_date, $id){
		return $this::where('holiday_date',$holiday_date)->where('id','!=', $id)->where('status','!=', 3)->exists();
	}

}
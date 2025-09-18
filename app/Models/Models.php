<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;

use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Foundation\Auth\User as Authenticatable;

use Illuminate\Notifications\Notifiable;

use Laravel\Sanctum\HasApiTokens;



class Models extends Authenticatable{

  use HasFactory, Notifiable;

	
	protected $table = 'models';


    /**

     * The attributes that are mass assignable.

     *

     * @var array<int, string>

     */

    protected $fillable = [
		'title',
		'brand_id',
		'vehicle_type_id',
		'slug',
		'description',
		'seo_title',
		'seo_description',
		'seo_keyword',
		'status',
		'ordering'
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

		return $this::where('title',$title)->where('status','!=', 3)->exists();

	}

	public function ExistingRecordUpdate($title, $id){

		return $this::where('title',$title)->where('id','!=', $id)->where('status','!=', 3)->exists();

	}

}
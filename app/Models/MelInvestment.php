<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;

use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Foundation\Auth\User as Authenticatable;

use Illuminate\Notifications\Notifiable;

use Laravel\Sanctum\HasApiTokens;



class MelInvestment extends Authenticatable{



  use HasFactory, Notifiable;



	protected $table = 'mel_investment';

    /**

     * The attributes that are mass assignable.

     *

     * @var array<int, string>

     */

    protected $fillable = [
		'cust_id',
		'ally_id',
		'ally_code',
		'receipt_id',
		'product_id',
		'serial_no',		
		'ref_no',
		'tot_unit',
		'investment_amount',
		'reg_fee',
		'tot_paid',
		'payment_method',
		'status',
		'paid_on',
		'cleared_on',
		'start_date',
		'start_date_act',
		'maturity_val',
		'maturity_date',
		'real_mat_date',
		'credit_bank_account',
		'bank_type',
		'invoice_id',
		'bounced_date',
		'is_paid',
		'branch_id',
		'manual_start_date',
		'created_by',
		'created_on',
		'brs_updated_by',
		'brs_updated_on',
		'mypdf_invoice',
		'mypdf_agreement',
		'mymsg_pdfresponse',
		'renewal_count',
		'auto_renewal_on',
		'is_reinvestment',
		'is_renewed',
		'reinvest_from',		
		'roi',
		'reinv_ref_id',
		'cheque_no',
		'cheque_date',
		'cheque_bank',
		'cheque_branch',
		'mypaydetail'
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
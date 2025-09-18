<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\AdminUser;
use App\RouteHelper;
use App\Models\TokenHelper;
use App\Models\Responses;
use ReallySimpleJWT\Token;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Session;
use Validator;
use Mail;
use URL;
use Cookie;
use Illuminate\Validation\Rule;


class ExportsController extends Controller{

	private static $AdminUser;
    private static $TokenHelper;
	
	public function __construct(){
		self::$AdminUser = new AdminUser();
        self::$TokenHelper = new TokenHelper();
	}

	#exportBrs
    public function exportBrsReport(Request $request){
		if(!$request->session()->has('admin_id')){ echo 'SessionExpired'; die; }

		$PREV = $request->session()->get('PREV');
		$BRID = $request->session()->get('BRID');

		$startdate=date('Y-m-d');
		$to_date=date('Y-m-d');
		if(!empty($request->input('from_date')) || !empty($request->input('to_date'))){
            $startdate = $request->input('from_date');
            $to_date = $request->input('to_date');
			if(!empty($startdate) && empty($to_date)){
				$startdate = $request->input('from_date');
            }else if(empty($startdate) && !empty($to_date)){
				$to_date = $request->input('to_date');
            }else{
				$startdate = $request->input('from_date');
            	$to_date = $request->input('to_date');
            }
        }
		$searchstr=" and (a.cleared_on >='".$startdate."' and a.cleared_on <='".$to_date."')";	
		 
		$myprod = $request->myprod;
		if($myprod!=""){			 
			$searchstr.= " and a.status='".$myprod."'";
		}
		
		if($PREV == 2){
			$records = DB::select("Select a.*,b.cust_name as myname,c.payment_methods,d.branch_name from mel_investment a inner join master_customer b on a.cust_id=b.id inner join master_payment_method c on a.payment_method=c.id inner join master_branch d on a.branch_id=d.id where a.status!='0' $searchstr and a.payment_method!='1' and a.branch_id='".$BRID."' order by a.id desc");
		}
		
		if($PREV == 1 || $PREV == 3){
			$records = DB::select("Select a.*,b.cust_name as myname,c.payment_methods,d.branch_name from mel_investment a inner join master_customer b on a.cust_id=b.id inner join master_payment_method c on a.payment_method=c.id inner join master_branch d on a.branch_id=d.id where a.status!='0' $searchstr and a.payment_method!='1' order by a.id desc");
		}
		
		$delimiter = ",";
		$filename = "brs_" . date('d_F_Y') . ".csv";
		
		$destination = "storage/csv/".$filename;
		
		//create a file pointer
		$f = fopen($destination,"w");
		
		//set column headers				
		$fields = array(
						'S.No',
						'Ref.No', 
						'Name',
						'Branch',
						'Payment Mode',
						'Receipt No',
						'Instrument Date',
						'Deposit Amount',
						'Registration Fee',
						'Deposit Date',
						'Status',
						'Clearence/Bounce Date'
					);
		 
		fputcsv($f, $fields, $delimiter);

		foreach($records as $key => $record):
		
			$mystat='';		
			if($record->status == '2'){				
				$mystat='Bounced';
			}
			if($record->status == '1'){
				$mystat='Cleared';
			}	
			/*$findinvoice_no = DB::select("Select invoiceno from met_invoice where brsid='".$record->myid."'");
			$invoiceno = '';
			if(isset($findinvoice_no[0])){
				$invoiceno = $findinvoice_no[0]->invoiceno;
			}*/

			$lineData = array(
							$key+1,
							$record->ref_no,
							$record->myname,
							$record->branch_name,
							$record->payment_methods,
							$record->receipt_id,
							date('d M Y', strtotime($record->paid_on)),
							$record->investment_amount,
							$record->reg_fee,
							date('d M Y', strtotime($record->paid_on)),
							$mystat,
							date('d M Y', strtotime($record->cleared_on))
						);
			fputcsv($f, $lineData, $delimiter);

		endforeach;
		$lineData2 = array('','');						
		fputcsv($f, $lineData2, $delimiter);                     
		
		fclose ($f);

		echo env('APP_URL').$destination;
		exit;		
	}

	#exportMaturity
    public function exportMaturityReport(Request $request){		
		if(!$request->session()->has('admin_id')){ echo 'SessionExpired'; die; }
		
		$PREV = $request->session()->get('PREV');
		$BRID = $request->session()->get('BRID');
				
		if($PREV == 2){
			$records = DB::select("Select a.id as myid,a.*,b.cust_name,b.udaid,b.contact_no,c.payment_methods,d.product_name,d.id,datediff(a.maturity_date,NOW()) as mydiff,e.branch_name from mel_investment a inner join master_customer b on a.cust_id=b.id inner join master_payment_method c on a.payment_method=c.id inner join master_product d on a.product_id=d.id inner join master_branch e on a.branch_id=e.id where 1 and a.status='1' and a.is_paid='0' and a.branch_id='" . $BRID . "' order by a.id desc");
		}
		
		if($PREV == 1 || $PREV == 3){
			$records = DB::select("Select a.id as myid,a.*,b.cust_name,b.udaid,b.contact_no,c.payment_methods,d.product_name,d.id,datediff(a.maturity_date,NOW()) as mydiff,e.branch_name from mel_investment a inner join master_customer b on a.cust_id=b.id inner join master_payment_method c on a.payment_method=c.id inner join master_product d on a.product_id=d.id inner join master_branch e on a.branch_id=e.id where 1 and a.status='1' and a.is_paid='0' order by a.id desc");
		}

		$delimiter = ",";
		$filename = "report_" . date('d_F_Y') . ".csv";
		
		$destination = "storage/csv/".$filename;
		//create a file pointer
		$f = fopen($destination,"w");
		
		//set column headers
		$fields = array(
						'S.No', 
						'Invoice No',
						'Branch',
						'Investment Date',
						'Scheme Name',
						'Customer Name (UIDAI)',
						'Mobile',
						'Investment Amount',
						'Maturity Amount',
						'Maturity Date'
					);
		 
		fputcsv($f, $fields, $delimiter);

		foreach($records as $key => $record):
		
			$findinvoice_no = DB::select("Select invoiceno from met_invoice where brsid='" . $record->myid . "'");			
			$invoiceno = '';
			if(isset($findinvoice_no[0])){
				$invoiceno = $findinvoice_no[0]->invoiceno;
			}	
			
			$lineData = array(
							$key+1,
							$invoiceno,
							$record->branch_name,
							date('M d Y', strtotime($record->start_date)),
							$record->product_name,
							$record->cust_name.' ('.$record->udaid.')',							
							$record->contact_no,
							$record->investment_amount,
							$record->maturity_val,
							date('M d Y', strtotime($record->maturity_date))                           
						);
			fputcsv($f, $lineData, $delimiter);

		endforeach;
		$lineData2 = array('','');						
		fputcsv($f, $lineData2, $delimiter);                     
		
		fclose ($f);

		$path = env('APP_URL').$destination;	
		
		return redirect($path);
		exit;
    }

}
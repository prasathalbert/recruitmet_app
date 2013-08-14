<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| File and Directory Modes
|--------------------------------------------------------------------------
|
| These prefs are used when checking and setting modes when working
| with the file system.  The defaults are fine on servers with proper
| security, but you may wish (or even need) to change the values in
| certain environments (Apache running a separate process for each
| user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
| always be used to set the mode correctly.
|
*/
define('FILE_READ_MODE', 0644);
define('FILE_WRITE_MODE', 0666);
define('DIR_READ_MODE', 0755);
define('DIR_WRITE_MODE', 0777);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/

define('FOPEN_READ',							'rb');
define('FOPEN_READ_WRITE',						'r+b');
define('FOPEN_WRITE_CREATE_DESTRUCTIVE',		'wb'); // truncates existing file data, use with care
define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE',	'w+b'); // truncates existing file data, use with care
define('FOPEN_WRITE_CREATE',					'ab');
define('FOPEN_READ_WRITE_CREATE',				'a+b');
define('FOPEN_WRITE_CREATE_STRICT',				'xb');
define('FOPEN_READ_WRITE_CREATE_STRICT',		'x+b');

//SMTP INFORMATIONS
define("SMTP_HOST","");
define("SMTP_USER","");
define("SMTP_PASS","");


/*** Site Setup*****/

$env = "dev"; 
define("GLOBAL_ENV",$env."_PAY");
switch(strtolower($env))
{
	case "local":
					define("DBHOST","localhost");
					define("DBNAME","hr_payprocess_db");
					define("DBUSER","root");
					define("DBPWD","");
					define ("FCKEDITORPATH",$_SERVER['DOCUMENT_ROOT']."js/fckeditor/fckeditor.php");					
					break;
					
	case "dev":		//s2p
					define("DBHOST","localhost");
					define("DBNAME","hr_payprocess_db");
					define("DBUSER","root");
					define("DBPWD","");
					define ("FCKEDITORPATH",$_SERVER['DOCUMENT_ROOT']."js/fckeditor/fckeditor.php");					
					break;
	
}


define("USER_SESSION_TIMEOUT",1200);//60 MINS
define("SITE_NAME","PAY");
define("SITE_SHORT_NAME","GKM");
define("PLACE_EXCEL","misc/employee_excels");
define("PLACE_RECRUIT_DOC","misc/recruits_files");
define("PLACE_OFFER_LETTER","misc/offer_letters");
define("PLACE_SALARY_BREAKUP","misc/salary_breakup");
define("PLACE_PAYROLL_DOCS","misc/payroll_docs");
define("PLACE_PAYMENT_DOCS","misc/payment_docs");
define("PLACE_VOUCHER_DOC","misc/voucher_docs");
define("PAYMENT_RECIEPT_DOCS","misc/payment_result_docs");
define("EMP_ATTENDANCE_START","2012/01/01");


// File Type Configs
define("FILE_TYPE_AUTHFORM","doc|DOC|docx|DOCX");
define("FILE_TYPE_OFFERLETTER","doc|DOC|docx|DOCX");
define("FILE_TYPE_SALARYBREAKUP","doc|DOC|docx|DOCX");
define("FILE_TYPE_RECRUITPAYDOCS","doc|DOC|pdf|jpeg|jpg|png|docx|DOCX");
define("FILE_TYPE_PAYMENTDOCS","doc|pdf|DOC|jpeg|jpg|png|csv|xls|docx|DOCX|xlsx|txt");
define("FILE_TYPE_RECIEPTDOCS","doc|pdf|DOC|jpeg|jpg|png|csv|xls|docx|DOCX|xlsx|txt");


// Global Genders List
$gender_list = array(
				"M"=>"Male",
				"F"=>"Female"
				);
$tds_list = array(
                "A"=>"Applicable",
                "D"=>"Deductable"
                  );
$servtax_list = array(
                "1"=>"Yes",
                "0"=>"No"
                );
$payment_type_list = array(
                "C" => "Check Payment",
                "O" => "Online Payment",
                "F" => "NEFT / RTGS Transfer"    
                );
// Global List of Designatios
$designation_list = array();

//Array to load admin rights									
$config_user_rights = array();

// Global AUForm Status
$auform_status = array(
                "0" => "Rejected",
				"1" => "Waiting for Chitra Review", 
                "2" => "Forwarded to HR",
                "3" => "Offer Letter Prepared / Waiting for Branch Admin Aproval", 
                "4" => "Offer Letter Rejected by Branch Admin", 
                "5" => "Offer Ltter Approvrd By Branch Admin", 
                "6" => "Salary Breakup Created", 
                "7" => "Sent to Recruit for Confirmation", 
                "8" => "Confirmed By Recruit",
                "9" => "Upoaded documents for Payroll"
				);

// Global PAyment Status
$payment_status = array(
                "0" => "Rejected",
				"1" => "Waiting for Approval", 
                "2" => "Approved & Forwarded to Chitra",
                "3" => "Approved & Forwarded to Chitra and Accounts", //Modified to Same Status for Skip the Step
                "4" => "Forwarded to Accounts", 
                "5" => "Payment Made", 
                "6" => "Payment Failed"
				);

//Global - GKM Employee Keys

$gkm_employees_array=array(4,5,7,8);
$gkm_employees=implode(",", $gkm_employees_array);
$gkm_employees_attendance_date_start = "2012-01-01";


                
/* End of file constants.php */
/* Location: ./application/config/constants.php */
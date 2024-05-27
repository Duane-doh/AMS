<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class Request_employee_declaration_model extends Soap_Model
{
	
	protected static $table = 'requests_employee_declaration';
	protected static $pk = 'request_sub_id';
	protected static $fk = 'employee_id';
	protected static $fields = array(
		'request_sub_id',
		'employee_declaration_id',
		'employee_id',
		'ctc_no',
		'issued_place',
		'issued_date',
		'accomplished_date',
		'ctc_no_old',
		'issued_place_old',
		'issued_date_old',
		'accomplished_date_old',
	);
	
}
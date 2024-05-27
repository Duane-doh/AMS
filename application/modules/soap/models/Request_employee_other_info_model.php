<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class Request_employee_other_info_model extends Soap_Model
{
	
	protected static $table = 'requests_employee_other_info';
	protected static $pk = 'request_sub_id';
	protected static $fk = 'employee_id';
	protected static $fields = array(
		'request_sub_id',
		'employee_other_info_id',
		'employee_id',
		'other_info_type_id',
		'others_value',
		'other_info_type_id_old',
		'others_value_old',
	);
	
}
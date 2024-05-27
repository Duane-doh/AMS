<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class Employee_other_info_model extends Soap_Model
{
	
	protected static $table = 'employee_other_info';
	protected static $pk = 'employee_other_info_id';
	protected static $fk = 'employee_id';
	protected static $fields = array(
		'employee_other_info_id',
		'employee_id',
		'other_info_type_id',
		'others_value',
	);
	
}
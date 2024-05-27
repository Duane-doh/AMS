<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class Employee_declaration_model extends Soap_Model
{
	
	protected static $table = 'employee_declaration';
	protected static $pk = 'employee_declaration_id';
	protected static $fk = 'employee_id';
	protected static $fields = array(
		'employee_declaration_id',
		'employee_id',
		'ctc_no',
		'issued_place',
		'issued_date',
		'accomplished_date',
	);
	
}
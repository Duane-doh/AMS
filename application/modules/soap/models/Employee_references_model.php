<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class Employee_references_model extends Soap_Model
{
	
	protected static $table = 'employee_references';
	protected static $pk = 'employee_reference_id';
	protected static $fk = 'employee_id';
	protected static $fields = array(
		'employee_reference_id',
		'employee_id',
		'reference_full_name',
		'reference_address',
		'reference_contact_info',
	);
	
}
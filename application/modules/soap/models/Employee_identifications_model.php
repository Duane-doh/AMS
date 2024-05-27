<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class Employee_identifications_model extends Soap_Model
{
	
	protected static $table = 'employee_identifications';
	protected static $pk = 'employee_identification_id';
	protected static $fk = 'employee_id';
	protected static $fields = array(
		'employee_identification_id',
		'employee_id',
		'identification_type_id',
		'identification_value',
	);
	
}
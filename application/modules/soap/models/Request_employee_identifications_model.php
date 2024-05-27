<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class Request_employee_identifications_model extends Soap_Model
{
	
	protected static $table   = 'requests_employee_identifications';
	protected static $pk      = 'request_sub_id';
	protected static $fk      = 'employee_id';
	protected static $fields = array(
		'request_sub_id',
		'employee_identification_id',
		'employee_id',
		'identification_type_id',
		'identification_value',
		'identification_type_id_old',
		'identification_value_old',
	);
	
}
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class Request_employee_references_model extends Soap_Model
{
	
	protected static $table = 'requests_employee_references';
	protected static $pk = 'request_sub_id';
	protected static $fk = 'employee_id';
	protected static $fields = array(
		'request_sub_id',
		'employee_reference_id',
		'employee_id',
		'reference_full_name',
		'reference_address',
		'reference_contact_info',
		'reference_full_name_old',
		'reference_address_old',
		'reference_contact_info_old',
	);
	
}
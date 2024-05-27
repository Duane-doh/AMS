<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class Request_employee_contacts_model extends Soap_Model
{
	
	protected static $table = 'requests_employee_contacts';
	protected static $pk = 'request_sub_id';
	protected static $fk = 'employee_id';
	protected static $fields = array(
		'request_sub_id',
		'employee_contact_id',
		'employee_id',
		'contact_type_id',
		'contact_value',
		'contact_type_id_old',
		'contact_value_old',
	);
	
}
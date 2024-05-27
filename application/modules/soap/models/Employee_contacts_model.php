<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class Employee_contacts_model extends Soap_Model
{
	
	protected static $table = 'employee_contacts';
	protected static $pk = 'employee_contact_id';
	protected static $fk = 'employee_id';
	protected static $fields = array(
		'employee_contact_id',
		'employee_id',
		'contact_type_id',
		'contact_value',
	);
	
}
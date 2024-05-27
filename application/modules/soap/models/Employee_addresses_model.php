<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class Employee_addresses_model extends Soap_Model
{
	
	protected static $table = 'employee_addresses';
	protected static $pk = 'employee_address_id';
	protected static $fk = 'employee_id';
	protected static $fields = array(
		'employee_address_id',
		'employee_id',
		'address_type_id',
		'address_value',
		'barangay_code',
		'municity_code',
		'province_code',
		'region_code',
		'postal_number',
	);
	
}
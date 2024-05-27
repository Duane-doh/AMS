<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class Request_employee_addresses_model extends Soap_Model
{
	
	protected static $table = 'requests_employee_addresses';
	protected static $pk = 'request_sub_id';
	protected static $fk = 'employee_id';
	protected static $fields = array(
		'request_sub_id',
		'employee_address_id',
		'employee_id',
		'address_type_id',
		'address_value',
		'barangay_code',
		'municity_code',
		'province_code',
		'region_code',
		'postal_number',
		'address_type_id_old',
		'address_value_old',
		'barangay_code_old',
		'municity_code_old',
		'province_code_old',
		'region_code_old',
		'postal_number_old'
	);
	
}
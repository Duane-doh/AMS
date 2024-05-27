<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class Employee_personal_info_model extends Soap_Model
{

	protected static $table = 'employee_personal_info';
	protected static $pk = 'employee_id';
	protected static $fk = '';
	protected static $agency_employee_id = 'agency_employee_id';
	protected static $fields = array(
		'employee_id',
		'last_name',
		'first_name',
		'middle_name',
		'ext_name',
		'birth_date',
		'birth_place',
		'gender_code',
		'civil_status_id',
		'citizenship_id',
		'height',
		'weight',
		'blood_type_id',
		'agency_employee_id',
		'biometric_pin',
		'pds_status_id',
	);
	
}
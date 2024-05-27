<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class Request_employee_personal_info_model extends Soap_Model
{

	protected static $table = 'requests_employee_personal_info';
	protected static $pk = 'request_sub_id';
	protected static $fk = 'employee_id';
	protected static $fields = array(
		'request_sub_id',
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
		'last_name_old',
		'first_name_old',
		'middle_name_old',
		'ext_name_old',
		'birth_date_old',
		'birth_place_old',
		'gender_code_old',
		'civil_status_id_old',
		'citizenship_id_old',
		'height_old',
		'weight_old',
		'blood_type_id_old',
		'agency_employee_id_old',
		'biometric_pin_old',
		'pds_status_id_old',
	);
	
}
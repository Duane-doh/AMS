<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class Request_employee_relations_model extends Soap_Model
{
	
	protected static $table = 'requests_employee_relations';
	protected static $pk = 'request_sub_id';
	protected static $fk = 'employee_id';
	protected static $fields = array(
		'request_sub_id',
		'employee_relation_id',
		'employee_id',
		'relation_type_id',
		'relation_first_name',
		'relation_middle_name',
		'relation_last_name',
		'relation_ext_name',
		'relation_gender_code',
		'relation_occupation',
		'relation_company',
		'relation_company_address',
		'relation_contact_num',
		'relation_birth_date',
		'relation_civil_status_id',
		'relation_employment_status_id',
		'pwd_flag',
		'gsis_flag',
		'bir_flag',
		'pagibig_flag',
		'philhealth_flag',
		'philhealth_number',
		'deceased_flag',
		'death_date',
		'relation_type_id_old',
		'relation_first_name_old',
		'relation_middle_name_old',
		'relation_last_name_old',
		'relation_ext_name_old',
		'relation_gender_code_old',
		'relation_occupation_old',
		'relation_company_old',
		'relation_company_address_old',
		'relation_contact_num_old',
		'relation_birth_date_old',
		'relation_civil_status_id_old',
		'relation_employment_status_id_old',
		'pwd_flag_old',
		'gsis_flag_old',
		'bir_flag_old',
		'pagibig_flag_old',
		'philhealth_flag_old',
		'philhealth_number_old',
		'deceased_flag_old',
		'death_date_old',
	);
	
}
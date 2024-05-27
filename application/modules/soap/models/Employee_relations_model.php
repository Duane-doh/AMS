<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class Employee_relations_model extends Soap_Model
{
	
	protected static $table = 'employee_relations';
	protected static $pk = 'employee_relation_id';
	protected static $fk = 'employee_id';
	protected static $fields = array(
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
	);
	
}
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class Employee_work_experiences_model extends Soap_Model
{
	
	protected static $table = 'employee_work_experiences';
	protected static $pk = 'employee_work_experience_id';
	protected static $fk = 'employee_id';
	protected static $fields = array(
		'employee_work_experience_id',
		'employee_id',
		'employ_type_flag',
		'employ_start_date',
		'employ_end_date',
		'employ_plantilla_id',
		'employ_position_id',
		'employ_position_name',
		'employ_office_id',
		'employ_office_name',
		'employ_salary_grade',
		'employ_salary_step',
		'employ_monthly_salary',
		'employ_personnel_movement_id',
		'employment_status_id',
		'separation_mode_id',
		'govt_service_flag',
		'government_branch_id',
		'service_lwop',
		'publication_date',
		'publication_place',
		'prev_employee_id',
		'prev_separation_mode_id',
		'active_flag',
		'step_incr_reason_code',
	);
	
}
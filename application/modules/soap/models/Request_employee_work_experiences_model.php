<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class Request_employee_work_experiences_model extends Soap_Model
{
	
	protected static $table = 'requests_employee_work_experiences';
	protected static $pk = 'request_sub_id';
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
		'employ_type_flag_old',
		'employ_start_date_old',
		'employ_end_date_old',
		'employ_plantilla_id_old',
		'employ_position_id_old',
		'employ_position_name_old',
		'employ_office_id_old',
		'employ_office_name_old',
		'employ_salary_grade_old',
		'employ_salary_step_old',
		'employ_monthly_salary_old',
		'employ_personnel_movement_id_old',
		'employment_status_id_old',
		'separation_mode_id_old',
		'govt_service_flag_old',
		'government_branch_id_old',
		'service_lwop_old',
		'publication_date_old',
		'publication_place_old',
		'prev_employee_id_old',
		'prev_separation_mode_id_old',
		'active_flag_old',
		'step_incr_reason_code_old',
	);
	
}
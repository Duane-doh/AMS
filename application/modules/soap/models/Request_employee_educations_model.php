<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class Request_employee_educations_model extends Soap_Model
{
	
	protected static $table = 'requests_employee_educations';
	protected static $pk = 'request_sub_id';
	protected static $fk = 'employee_id';
	protected static $fields = array(
		'request_sub_id',
		'employee_education_id',
		'employee_id',
		'educational_level_id',
		'school_id',
		'education_degree_id',
		'highest_level',
		'start_year',
		'end_year',
		'academic_honor',
		'year_graduated_flag',
		'educational_level_id_old',
		'school_id_old',
		'education_degree_id_old',
		'highest_level_old',
		'start_year_old',
		'end_year_old',
		'academic_honor_old',
		'year_graduated_flag_old',
	);
	
}
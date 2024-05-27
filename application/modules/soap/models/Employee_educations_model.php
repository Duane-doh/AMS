<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class Employee_educations_model extends Soap_Model
{
	
	protected static $table = 'employee_educations';
	protected static $pk = 'employee_education_id';
	protected static $fk = 'employee_id';
	protected static $fields = array(
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
	);
	
}
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class Employee_eligibility_model extends Soap_Model
{
	
	protected static $table = 'employee_eligibility';
	protected static $pk = 'employee_eligibility_id';
	protected static $fk = 'employee_id';
	protected static $fields = array(
		'employee_eligibility_id',
		'employee_id',
		'eligibility_type_id',
		'rating',
		'exam_date',
		'exam_place',
		'license_no',
		'release_date',
	);
	
}
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class Request_employee_eligibility_model extends Soap_Model
{
	
	protected static $table = 'requests_employee_eligibility';
	protected static $pk = 'request_sub_id';
	protected static $fk = 'employee_id';
	protected static $fields = array(
		'request_sub_id',
		'employee_eligibility_id',
		'employee_id',
		'eligibility_type_id',
		'rating',
		'exam_date',
		'exam_place',
		'license_no',
		'release_date',
		'eligibility_type_id_old',
		'rating_old',
		'exam_date_old',
		'exam_place_old',
		'license_no_old',
		'release_date_old',
	);
	
}
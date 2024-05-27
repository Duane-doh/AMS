<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class Request_employee_trainings_model extends Soap_Model
{
	
	protected static $table = 'requests_employee_trainings';
	protected static $pk = 'request_sub_id';
	protected static $fk = 'employee_id';
	protected static $fields = array(
		'request_sub_id',
		'employee_training_id',
		'employee_id',
		'training_name',
		'training_start_date',
		'training_end_date',
		'training_hour_count',
		'training_conducted_by',
		'training_name_old',
		'training_start_date_old',
		'training_end_date_old',
		'training_hour_count_old',
		'training_conducted_by_old',

	);
	
}
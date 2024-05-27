<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class Employee_trainings_model extends Soap_Model
{
	
	protected static $table = 'employee_trainings';
	protected static $pk = 'employee_training_id';
	protected static $fk = 'employee_id';
	protected static $fields = array(
		'employee_training_id',
		'employee_id',
		'training_name',
		'training_start_date',
		'training_end_date',
		'training_hour_count',
		'training_conducted_by',
	);
	
}
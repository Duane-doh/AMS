<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class Employee_voluntary_works_model extends Soap_Model
{
	
	protected static $table = 'employee_voluntary_works';
	protected static $pk = 'employee_voluntary_work_id';
	protected static $fk = 'employee_id';
	protected static $fields = array(
		'employee_voluntary_work_id',
		'employee_id',
		'volunteer_org_name',
		'volunteer_org_address',
		'volunteer_start_date',
		'volunteer_end_date',
		'volunteer_hour_count',
		'volunteer_position',
	);
	
}
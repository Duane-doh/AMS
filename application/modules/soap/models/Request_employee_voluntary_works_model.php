<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class Request_employee_voluntary_works_model extends Soap_Model
{
	
	protected static $table = 'requests_employee_voluntary_works';
	protected static $pk = 'request_sub_id';
	protected static $fk = 'employee_id';
	protected static $fields = array(
		'request_sub_id',
		'employee_voluntary_work_id',
		'employee_id',
		'volunteer_org_name',
		'volunteer_org_address',
		'volunteer_start_date',
		'volunteer_end_date',
		'volunteer_hour_count',
		'volunteer_position',
		'volunteer_org_name_old',
		'volunteer_org_address_old',
		'volunteer_start_date_old',
		'volunteer_end_date_old',
		'volunteer_hour_count_old',
		'volunteer_position_old',
	);
	
}
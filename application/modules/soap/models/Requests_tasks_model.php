<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class Requests_tasks_model extends Soap_Model
{
	
	protected static $table = 'requests_tasks';
	protected static $pk = 'request_task_id';
	protected static $fk = 'request_id';
	protected static $fields = array(
		'request_task_id',
		'request_id',
		'task_detail',
		'process_id',
		'process_stage_id',
		'process_step_id',
		'task_status_id',
		'assigned_to',
		'assigned_date',
		'processed_date',
		'remarks'
	);
	
}
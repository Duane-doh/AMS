<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class Requests_sub_model extends Soap_Model
{
	
	protected static $table = 'requests_sub';
	protected static $pk = 'request_sub_id';
	protected static $fk = 'employee_id';
	protected static $fields = array(
		'request_sub_id',
		'request_id',
		'request_sub_type_id',
		'action',
		'employee_id',
		'request_sub_status_id',
		'remarks',
	);
	
}
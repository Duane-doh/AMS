<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class Requests_model extends Soap_Model
{
	
	protected static $table = 'requests';
	protected static $pk = 'request_id';
	protected static $fk = 'employee_id';
	protected static $fields = array(
		'request_id',
		'request_code',
		'request_type_id',
		'request_status_id',
		'employee_id',
		'date_requested',
		'date_processed',
		'remarks',
	);
	
}
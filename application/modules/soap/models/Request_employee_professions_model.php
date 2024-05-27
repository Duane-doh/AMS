<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class Request_employee_professions_model extends Soap_Model
{
	
	protected static $table = 'requests_employee_professions';
	protected static $pk = 'request_sub_id';
	protected static $fk = 'employee_id';
	protected static $fields = array(
		'request_sub_id',
		'employee_profession_id',
		'employee_id',
		'profession_id',
		'others_specify',
		'profession_id_old',
		'others_specify_old',
	);
	
}
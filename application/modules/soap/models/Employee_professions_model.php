<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class Employee_professions_model extends Soap_Model
{
	
	protected static $table = 'employee_professions';
	protected static $pk = 'employee_profession_id';
	protected static $fk = 'employee_id';
	protected static $fields = array(
		'employee_profession_id',
		'employee_id',
		'profession_id',
		'others_specify',
	);
	
}
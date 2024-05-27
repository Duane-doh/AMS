<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class Employee_questions_model extends Soap_Model
{
	
	protected static $table = 'employee_questions';
	protected static $pk = 'question_id';
	protected static $fk = 'employee_id';
	protected static $fields = array(
		'question_id',
		'employee_id',
		'question_answer_flag',
		'question_answer_txt',
	);
	
}